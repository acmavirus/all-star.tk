<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category extends Admin_Controller
{
    protected $_data;
    protected $category_tree;
    protected $_reviews;

    public function __construct()
    {
        parent::__construct();
        //tải thư viện
        $this->load->model(['category_model', 'reviews_model']);
        $this->_data = new Category_model();
        $this->_reviews = new Reviews_model();
    }

    public function get_list($data)
    {
        //        $this->session->set_userdata('type',$this->_method);
        $data['main_content'] = $this->load->view(TEMPLATE_PATH . $this->_controller . DIRECTORY_SEPARATOR . 'index', $data, TRUE);
        $this->load->view(TEMPLATE_MAIN, $data);
    }

    public function page()
    {
        $data['heading_title'] = "Quản lý page tĩnh";
        $data['heading_description'] = "Danh sách page";
        $this->get_list($data);
    }

    public function tag()
    {
        $data['heading_title'] = "Quản lý thẻ tag";
        $data['heading_description'] = "Danh sách thẻ tag";
        $this->get_list($data);
    }

    public function post()
    {
        $data['heading_title'] = "Quản lý danh mục";
        $data['heading_description'] = "Danh sách danh mục";
        $this->get_list($data);
    }
    public function _queue($categories, $parent_id = 0, $char = '')
    {
        if (!empty($categories)) foreach ($categories as $key => $item) {
            if ($item->parent_id == $parent_id) {
                $tmp['title'] = $char . $item->title;
                $tmp['value'] = $item;
                $this->category_tree[] = $tmp;
                unset($categories[$key]);
                $this->_queue($categories, $item->id, $char . '  ' . $item->title . ' <i class="fa fa-fw fa-caret-right"></i> ');
            }
        }
    }
    public function _queue_select($categories, $parent_id = 0, $char = '')
    {
        if (!empty($categories)) foreach ($categories as $key => $item) {
            if ($item->parent_id == $parent_id) {
                $tmp['title'] = $parent_id ? '  |--' . $char . $item->title : $char . $item->title;
                $tmp['id'] = $item->id;
                $tmp['thumbnail'] = $item->thumbnail;
                $this->category_tree[] = $tmp;
                unset($categories[$key]);
                $this->_queue_select($categories, $item->id, $char . '--');
            }
        }
    }


    public function origami(){
        $data['heading_title'] = "Quản lý origami";
        $data['heading_description'] = "Danh mục origami";
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
        $referrer = $this->agent->referrer();
        $type = str_replace(site_admin_url('category/'), '', $referrer);

        $params = [
            'type'      => $type,
            'limit'     => 2000,
            'order'     => ['id' => 'desc']
        ];


        if (isset($queryFilter['is_status']) && $queryFilter['is_status'] !== '')
            $params = array_merge($params, ['is_status' => $queryFilter['is_status']]);

        if (!empty($queryFilter['category_id'])) $params['parent_id'] = $queryFilter['category_id'];

        $listAll = $this->_data->getData($params);


        if (empty($queryFilter) && $type !== 'weekday') {
            $this->_queue($listAll);
            $listData = $this->category_tree;
            $offset = ($page - 1) * $limit;
            if (!empty($listData))
                $listData = array_slice($listData, $offset, $limit);
        } else {
            $listData = $listAll;
        }

        if (!empty($listData)) foreach ($listData as $category) {
            if (empty($queryFilter) && $type !== 'weekday') {
                $item = $category['value'];
                $title = $category['title'];
            } else {
                $item = $category;
                $title = $category->title;
            }


            switch ($type):
                default:
                    $link = getUrlCategory($item);
            endswitch;
            $row = array();
            $row['checkID'] = $item->id;
            $row['id'] = $item->id;
            $row['title']        = '<a href="' . $link . '" target="_blank">' . $title . '</a>';
            $row['is_featured']  = $item->is_featured;
            $row['is_robot'] = $item->is_robot;
            $row['is_status']    = $item->is_status;
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

    public function ajax_load($type = '')
    {
        $term = $this->input->get("q");
        $id = $this->input->get('id') ? $this->input->get('id') : 0;
        $type = $type === 'index' ? 'post' : $type;
        if($type === 'weekday'){
            $type = 'lottery';
            $parent_id = 0;
        }
        $params = [
            'type' => $type,
            'parent_id' => $parent_id ?? '',
            'is_status' => 1,
            'limit' => 2000
        ];

        $list = $this->_data->getData($params);
        $this->_queue_select($list);
        $listTree = $this->category_tree;
        if (!empty($term)) {
            $searchword = $term;
            $matches = array();
            foreach ($listTree as $k => $v) {
                if (preg_match("/\b$searchword\b/i", $v['title'])) {
                    $matches[$k] = $v;
                }
            }
            $listTree = $matches;
        }
        $output = [];
        if (!empty($listTree)) foreach ($listTree as $item) {
            $item = (object) $item;
            $output[] = ['id' => $item->id, 'text' => $item->title];
        }
        $this->returnJson($output);
    }

    public function ajax_add()
    {
        $this->checkRequestPostAjax();
        $data = $this->_convertData();
        $referrer = $this->agent->referrer();
        $type = str_replace(site_admin_url('category/'), '', $referrer);

        if ($id = $this->_data->save($data)) {
            if ($type == 'page') {
                $this->_reviews->save([
                    'post_id' => $id,
                    'slug' => $data['slug'],
                    'rate' => 5,
                    'ip' => $this->input->ip_address(),
                    'type' => $type,
                ]);
            }

            $note   = 'Thêm category có id là : ' . $id;
            $this->addLogaction('category', $data, $id, $note, 'Add');
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
        if (!empty($id)) {
            $output['data_info'] = $this->_data->single(['id' => $id], $this->_data->table);
            $output['data_category'] = $this->_data->getSelect2($output['data_info']->parent_id);
            // $output['data_tournament'] = $this->_data_tournament->getSelect2Tourament($output['data_info']->tournament_id);
            $this->returnJson($output);
        }
    }

    public function ajax_update()
    {
        $this->checkRequestPostAjax();
        $data = $this->_convertData();
        $id = $data['id'];
        $data_old = $this->_data->single(['id' => $id], $this->_data->table);
        if ($this->_data->update(['id' => $id], $data, $this->_data->table)) {
            $note   = 'Update category có id là : ' . $id;
            $this->addLogaction('category', $data_old, $id, $note, 'Update');
            $this->_data->getBySlugCached($data['slug'], true);
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
        if ($field == 'order') {
            $check_order = $this->_data->check_order($value);
            if (!empty($check_order)) {
                $message['type'] = 'error';
                $message['message'] = "Thứ tự đã tồn tại, vui lòng thử thứ tự khác";
                $this->returnJson($message);
            }
        }

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
        $rules = [
            [
                'field' => "title",
                'label' => "Tiêu đề",
                'rules' => "trim|required"
            ], [
                'field' => "slug",
                'label' => "Đường dẫn",
                'rules' => "trim|required" . (!empty($this->input->post('id')) ? "" : "|is_unique[category.slug]"),
                array(
                    'is_unique'     => 'Trường %s đã tồn tại. Vui lòng đổi %s khác'
                )
            ]
        ];
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
        $type = str_replace(site_admin_url('category/'), '', $referrer);
        if (!empty($type)) $data['type'] = $type;
        else $data['type'] = 'post';
        if (!empty($data['is_status'])) $data['is_status'] = 1;
        else $data['is_status'] = 0;
        if (isset($data['is_robot'])) $data['is_robot'] = 1;
        else $data['is_robot'] = 0;
        return $data;
    }
}
