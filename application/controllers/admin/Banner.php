<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Banner extends Admin_Controller
{
    protected $_data;

    public function __construct()
    {
        parent::__construct();
        //tải thư viện
        $this->config->load('banner');
        $this->load->model('banner_model');
        $this->_data = new Banner_model();
    }

    public function index()
    {
        $data['heading_title'] = "Quản lý banner";
        $data['heading_description'] = "Danh sách banner";
        $data['main_content'] = $this->load->view(TEMPLATE_PATH . $this->_controller . DIRECTORY_SEPARATOR . 'index', $data, TRUE);
        $this->load->view(TEMPLATE_MAIN, $data);
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
            'page'          => $page,
            'limit'         => $limit
        ];
        if (isset($queryFilter['is_status']) && $queryFilter['is_status'] !== '')
            $params = array_merge($params, ['is_status' => $queryFilter['is_status']]);

        $listData = $this->_data->getData($params);
        if (!empty($listData)) foreach ($listData as $item) {
            $row = array();
            $row['checkID'] = $item->id;
            $row['id'] = $item->id;
            $row['title'] = $item->title;
            $row['order'] = $item->order;
            $row['thumbnail'] = $item->thumbnail;
            $row['location']  = $this->config->item('cms_banner')[$item->location];
            $row['is_status'] = $item->is_status;
            $row['updated_time'] = $item->updated_time;
            $row['created_time'] = $item->created_time;
            $data[] = $row;
        }

        $output = [
            "meta" => [
                "page"      => $page,
                "pages"     => $total_page,
                "perpage"   => $limit,
                "total"     => $this->_data->getTotal(),
                "sort"      => "asc",
                "field"     => "id"
            ],
            "data" =>  $data
        ];

        $this->returnJson($output);
    }

    public function ajax_add()
    {
        $this->checkRequestPostAjax();
        $data = $this->_convertData();
        $data['is_status'] = 1;
        if ($id = $this->_data->save($data)) {
            $note   = 'Thêm banner có id là : ' . $id;
            $this->addLogaction('banner', $data, $id, $note, 'Add');
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
            $output['data_info'] = $oneItem = $this->_data->single(['id' => $id], $this->_data->table);
            $output['data_banner'] = json_decode($oneItem->data_info);
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
            $note   = 'Update location có id là : ' . $id;
            $this->addLogaction('location', $data_old, $id, $note, 'Update');
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
                'field' => "thumbnail",
                'label' => "Hình ảnh",
                'rules' => "trim|required"
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
        $data['data_info'] = json_encode($data['data_info']);
        return $data;
    }
}
