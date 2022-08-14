<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Post extends Admin_Controller
{
    protected $_data;
    protected $_user;
    protected $_reviews;
    protected $_category;

    public function __construct()
    {
        parent::__construct();
        //tải thư viện
        $this->load->model(['post_model', 'users_model', 'reviews_model', 'category_model']);
        $this->_data         = new Post_model();
        $this->_user         = new Users_model();
        $this->_reviews = new Reviews_model();
        $this->_category = new Category_model();
    }

    public function get_list($data)
    {
        $data['main_content'] = $this->load->view(TEMPLATE_PATH . $this->_controller . DIRECTORY_SEPARATOR . 'index', $data, TRUE);
        $this->load->view(TEMPLATE_MAIN, $data);
    }


    public function index()
    {
        // $this->session->set_userdata('type', $this->_controller);
        $data['heading_title'] = "Quản lý bài viết";
        $data['heading_description'] = "Danh sách bài viết";
        $this->get_list($data);
    }


    public function ajax_list()
    {
        $this->checkRequestPostAjax();
        $data = array();
        $pagination = $this->input->post('pagination');
        $page = $pagination['page'];
        $total_page = isset($pagination['pages']) ? $pagination['pages'] : 1;
        $limit = !empty($pagination['perpage']) && $pagination['perpage'] > 0 ? $pagination['perpage'] : 1;

        $queryFilter = $this->input->post('query');

        $params = [
            'category_id'   => !empty($queryFilter['category_id']) ? $queryFilter['category_id'] : '',
            'page'          => $page,
            'limit'         => $limit,
            'type'          => 'post',
            'order'         => array('id', 'desc')
        ];
        if (isset($queryFilter['is_status']) && $queryFilter['is_status'] !== '')
            $params = array_merge($params, ['is_status' => $queryFilter['is_status']]);

        $listData = $this->_data->getData($params);


        if (!empty($listData)) foreach ($listData as $item) {
            $dataUser = $this->_user->getByField('id', $item->user_id);
            $cate = $this->_category->getCatByPostId($item->id);
            $data_match = !empty($item->data_match) ? json_decode($item->data_match) : '';
            $data_match_html = !empty($data_match) ? '<div><p>' . $data_match->name_home . '</p><p class="text-danger">' . date('d/m/Y H:i:s', strtotime($item->start_time)) . '</p><p>' . $data_match->name_away . '</p></div>' : '';
            $row = array();
            $row['checkID']    = $item->id;
            $row['id']         = $item->id;
            $row['category']         = !empty($cate->title) ? $cate->title : '';
            $row['title']        = '<a href="' . getUrlPost($item) . '" target="_blank">' . $item->title . '</a>';
            $row['data_match']  = $data_match_html;
            $row['thumbnail']  = getImageThumb($item->thumbnail);
            $row['username']   = !empty($dataUser->username) ? $dataUser->username : '';
            $row['is_status']  = $item->is_status;
            $row['viewed']     = $item->viewed;
            $row['updated_time'] = $item->updated_time;
            $row['created_time'] = $item->created_time;
            $data[] = $row;
        }

        $output = [
            "meta" => [
                "page"      => $page,
                "pages"     => $total_page,
                "perpage"   => $limit,
                "total"     => $this->_data->getTotal($params),
                "sort"      => "asc",
                "field"     => "id"
            ],
            "data" =>  $data
        ];
        $this->returnJson($output);
    }

    private function save_category($id, $data, $primary_category_id)
    {
        $this->_data->delete([$this->_data->table . '_id' => $id], $this->_data->table_post_category);
        if (!empty($data)) foreach ($data as $category_id) {
            $tmp = [
                "{$this->_data->table}_id" => $id,
                'category_id' => $category_id,
                'is_primary' => $category_id == $primary_category_id ? true : false
            ];
            $data_category[] = $tmp;
        }

        if (!$this->_data->insertMultiple($data_category, $this->_data->table_post_category)) {
            $message['type'] = 'error';
            $message['message'] = "Thêm {$this->_data->table_category} thất bại !";
            log_message('error', $message['message'] . '=>' . json_encode($data_category));
            $this->returnJson($message);
        }
    }
    private function save_tag($id, $data)
    {
        $this->_data->delete(['post_id' => $id], 'post_tag');
        if (!empty($data)) foreach ($data as $tag_id) {
            $tmp = ['post_id' => $id, 'tag_id' => $tag_id];
            $data_tag[] = $tmp;
        }


        if (!$this->_data->insertMultiple($data_tag, 'post_tag')) {
            $message['type'] = 'error';
            $message['message'] = 'Thêm tag thất bại !';
            log_message('error', $message['message'] . '=>' . json_encode($data_tag));
        }
    }

    public function ajax_load()
    {
        $term = $this->input->get("q");
        $id = $this->input->get('id') ? $this->input->get('id') : 0;
        $params = [
            'is_status' => 1,
            'not_in' => ['id' => $id],
            'search' => $term,
            'limit' => 10
        ];
        $data = $this->_data->getData($params);
        $output = [];
        if (!empty($data)) foreach ($data as $item) {
            $output[] = ['id' => $item->id, 'text' => $item->title];
        }
        $this->returnJson($output);
    }
    public function ajax_add()
    {
        $this->checkRequestPostAjax();
        $data = $this->_convertData();
        $data['type'] = 'post';
        $referrer = $this->agent->referrer();
        $type = str_replace(site_admin_url('post/'), '', $referrer);
        $data['user_id'] = (int) $this->session->userdata('user_id');

        $data_category = $data['category_id'];
        unset($data['category_id']);

        if (isset($data['tag_id'])) {
            $data_tag = $data['tag_id'];
            unset($data['tag_id']);
        }

        $primary_category     = $data['category_primary_id'];
        unset($data['category_primary_id']);

        if ($id = $this->_data->save($data)) {
            $this->save_category($id, $data_category, $primary_category);
            $this->_reviews->save([
                'id' => $id,
                'url' => $data['slug'] . '-p' . $id,
                'rate' => 5,
                'ip' => $this->input->ip_address(),
            ]);
            if (!empty($data_tag)) $this->save_tag($id, $data_tag);

            $note   = 'Thêm post có id là : ' . $id;
            $this->addLogaction('post', $data, $id, $note, 'Add');


            $message['type'] = 'success';
            $message['message'] = "Thêm mới thành công !";
        } else {
            $message['type'] = 'error';
            $message['message'] = "Thêm mới thất bại !";
        }
        $this->returnJson($message);
    }

    public function ajax_edit()
    {
        $this->checkRequestPostAjax();
        $id = $this->input->post('id');
        $referrer = $this->agent->referrer();
        $type = str_replace(site_admin_url('post/'), '', $referrer);

        if (!empty($id)) {
            $output['data_info'] = $data_info = $this->_data->single(['id' => $id], $this->_data->table);
            $output['data_category'] = $this->_data->getSelect2Category($id);
            $output['data_tag'] = $this->_data->getSelect2Tag($id);
            $output['data_category_primary'] = $this->_data->getSelect2Category($id, true);
            $this->returnJson($output);
        }
    }

    public function ajax_update()
    {
        $this->checkRequestPostAjax();
        $data = $this->_convertData();
        $data['type'] = 'post';
        $id = $data['id'];
        $data_old = $this->_data->single(['id' => $id], $this->_data->table);

        $data_category = $data['category_id'];
        unset($data['category_id']);

        if (isset($data['tag_id'])) {
            $data_tag = $data['tag_id'];
            unset($data['tag_id']);
        }

        $primary_category = [];
        if (!empty($data['category_primary_id'])) {
            $primary_category     = $data['category_primary_id'];
            unset($data['category_primary_id']);
        }


        if ($this->_data->update(['id' => $id], $data, $this->_data->table)) {
            $this->save_category($id, $data_category, $primary_category);
            if (!empty($data_tag)) $this->save_tag($id, $data_tag);
            $note   = 'Update post có id là : ' . $id;
            $this->addLogaction('post', $data_old, $id, $note, 'Update');
            $this->_data->getByIdCached($id, true);
            $this->delete_cache_file(getUrlPost($data_old));
            $message['type'] = 'success';
            $message['message'] = "Cập nhật thành công !";
        } else {
            $message['type'] = 'error';
            $message['message'] = "Cập nhật thất bại !";
        }
        $this->returnJson($message);
    }

    public function ajax_update_field()
    {
        $this->checkRequestPostAjax();
        $id = $this->input->post('id');
        $field = $this->input->post('field');
        $value = $this->input->post('value');
        $response = $this->_data->update(['id' => $id], [$field => $value]);
        if ($response != false) {
            $message['type'] = 'success';
            $message['message'] = "Cập nhật thành công !";
        } else {
            $message['type'] = 'error';
            $message['message'] = "Cập nhật thất bại !";
        }
        $this->returnJson($message);
    }

    public function ajax_delete()
    {
        $this->checkRequestPostAjax();
        $ids = (int)$this->input->post('id');
        $response = $this->_data->deleteArray('id', $ids);
        $this->_data->deleteArray('post_id', $ids, 'post_tag');
        $this->_data->deleteArray('post_id', $ids, 'post_category');
        if ($response != false) {
            $message['type'] = 'success';
            $message['message'] = "Xóa thành công !";
        } else {
            $message['type'] = 'error';
            $message['message'] = "Xóa thất bại !";
            log_message('error', $response);
        }
        $this->returnJson($message);
    }

    private function _validation()
    {
        $this->checkRequestPostAjax();
        $referrer = $this->agent->referrer();
        $type = str_replace(site_admin_url('post/'), '', $referrer);
        $rules = [
            [
                'field' => "title",
                'label' => "Tiêu đề",
                'rules' => "trim|required"
            ], [
                'field' => "slug",
                'label' => "Đường dẫn",
                'rules' => "trim|required" . (!empty($this->input->post('id')) ? "" : "|is_unique[post.slug]"),
                array(
                    'is_unique'     => 'Trường %s đã tồn tại. Vui lòng đổi %s khác'
                )
            ], [
                'field' => "meta_title",
                'label' => "Tiêu đề SEO",
                'rules' => "trim|required"
            ], [
                'field' => "meta_description",
                'label' => "Mô tả SEO",
                'rules' => "trim|required"
            ], [
                'field' => "thumbnail",
                'label' => "ảnh đại diện",
                'rules' => "trim|required"
            ], [
                'field' => "category_id[]",
                'label' => "danh mục",
                'rules' => "trim|required"
            ]
        ];
        if ($this->input->post('category_id') && count($this->input->post('category_id')) > 1) $rules = array_merge($rules, [
            [
                'field' => "category_primary_id",
                'label' => "Danh mục chính",
                'rules' => "required"
            ]
        ]);
        if ($type === 'video') {
            $rule_video = [
                'field' => 'video',
                'label' => 'video',
                'rules' => 'trim|required'
            ];

            array_push($rules, $rule_video);
        }
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
            $message['type'] = "warning";
            $message['message'] = "Vui lòng kiểm tra lại thông tin vừa nhập.";
            $valid = array();
            if (!empty($rules)) foreach ($rules as $item) {
                if (!empty(form_error($item['field']))) $valid[$item['field']] = form_error($item['field']);
            }
            $message['validation'] = $valid;
            $this->returnJson($message);
        }
    }

    private function _convertData()
    {
        $this->_validation();
        $data = $this->input->post();
        $referrer = $this->agent->referrer();
        $type = str_replace(site_admin_url('post/'), '', $referrer);
        if (in_array($type, ['nhacai', 'gamebai', 'banca']))  $data['data_dealer'] = json_encode($data['data_dealer']);
        if (!empty($type)) $data['type'] = $type;
        else $data['type'] = 'post';
        if (isset($data['is_status'])) $data['is_status'] = 1;
        else $data['is_status'] = 0;
        if (isset($data['is_featured'])) $data['is_featured'] = 1;
        else $data['is_featured'] = 0;
        if (isset($data['is_robot'])) $data['is_robot'] = 1;
        else $data['is_robot'] = 0;
        if (empty($data['displayed_time'])) $data['displayed_time'] = date('Y-m-d H:i:s');
        if (!empty($data['slug'])) $data['slug'] = $this->toSlug($data['slug']);
        return $data;
    }

    // private function getPostCategory(int $id = null)
    // {
    //     if(empty($id)){
    //         return 'Chưa có danh mục';
    //     } else {
    //         $this->db->select('id_category');
    //         $this->db->from('post_category')
    //     }
    // }
}
