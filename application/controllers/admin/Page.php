<?php
/**
 * Created by PhpStorm.
 * User: ducto
 * Date: 9/29/2018
 * Time: 12:38 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Page extends Admin_Controller
{
    protected $_data;

    const STATUS_CANCEL = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DRAFT = 2;
    public function __construct()
    {
        parent::__construct();
        //tải thư viện
        $this->load->model(['page_model']);
        $this->_data = new Page_model();
    }

    public function index(){
        $data['heading_title'] = "Quản lý trang";
        $data['heading_description'] = "Danh sách trang";
        $data['main_content'] = $this->load->view($this->template_path . $this->_controller . DIRECTORY_SEPARATOR . 'index', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function ajax_list(){
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
        if(isset($queryFilter['is_status']) && $queryFilter['is_status'] !== '')
            $params = array_merge($params,['is_status' => $queryFilter['is_status']]);

        $listData = $this->_data->getData($params);
        if(!empty($listData)) foreach ($listData as $item) {
            $row = array();
            $row['checkID'] = $item->id;
            $row['id'] = $item->id;
            $row['title'] = $item->title;
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

    public function ajax_load(){
        $term = $this->input->get("q");
        $id = $this->input->get('id')?$this->input->get('id'):0;
        $params = [
            'is_status'=> 1,
            'not_in' => ['id' => $id],
            'search' => $term,
            'limit'=> 10
        ];
        $data = $this->_data->getData($params);
        $output = [];
        if(!empty($data)) foreach ($data as $item) {
            $output[] = ['id'=>$item->id, 'text'=>$item->title];
        }
        $this->returnJson($output);
    }
    public function ajax_add(){
        $this->checkRequestPostAjax();
        $data = $this->_convertData();
        $data['viewed'] = rand(1000,9999);
        if($id = $this->_data->save($data)){
            $message['type'] = 'success';
            $message['message'] = "Thêm mới thành công !";
        }else{
            $message['type'] = 'error';
            $message['message'] = "Thêm mới thất bại !";
        }
        $this->returnJson($message);
    }

    public function ajax_edit(){
        $this->checkRequestPostAjax();
        $id = $this->input->post('id');
        if(!empty($id)){
            $output['data_info'] = $this->_data->single(['id' => $id],$this->_data->table);
            $this->returnJson($output);
        }
    }

    public function ajax_update(){
        $this->checkRequestPostAjax();
        $data = $this->_convertData();
        $id = $data['id'];
        if($this->_data->update(['id' => $id],$data, $this->_data->table)){
            $message['type'] = 'success';
            $message['message'] = "Cập nhật thành công !";
        }else{
            $message['type'] = 'error';
            $message['message'] = "Cập nhật thất bại !";
        }
        $this->returnJson($message);
    }

    public function ajax_update_field(){
        $this->checkRequestPostAjax();
        $id = $this->input->post('id');
        $field = $this->input->post('field');
        $value = $this->input->post('value');
        $response = $this->_data->update(['id' => $id], [$field => $value]);
        if($response != false){
            $message['type'] = 'success';
            $message['message'] = "Cập nhật thành công !";
        }else{
            $message['type'] = 'error';
            $message['message'] = "Cập nhật thất bại !";
        }
        $this->returnJson($message);
    }

    public function ajax_delete(){
        $this->checkRequestPostAjax();
        $ids = (int)$this->input->post('id');
        $response = $this->_data->deleteArray('id',$ids);
        if($response != false){
            $message['type'] = 'success';
            $message['message'] = "Xóa thành công !";
        }else{
            $message['type'] = 'error';
            $message['message'] = "Xóa thất bại !";
            log_message('error',$response);
        }
        $this->returnJson($message);
    }

    private function _validation(){
        $this->checkRequestPostAjax();
        $rules = [
            [
                'field' => "title",
                'label' => "Tiêu đề",
                'rules' => "trim|required"
            ],[
                'field' => "slug",
                'label' => "Đường dẫn",
                'rules' => "trim|required"
            ],[
                'field' => "meta_title",
                'label' => "Tiêu đề SEO",
                'rules' => "trim|required"
            ],[
                'field' => "meta_description",
                'label' => "Mô tả SEO",
                'rules' => "trim|required"
            ]
        ];
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
            $message['type'] = "warning";
            $message['message'] = "Vui lòng kiểm tra lại thông tin vừa nhập.";
            $valid = array();
            if(!empty($rules)) foreach ($rules as $item){
                if(!empty(form_error($item['field']))) $valid[$item['field']] = form_error($item['field']);
            }
            $message['validation'] = $valid;
            $this->returnJson($message);
        }
    }

    private function _convertData(){
        $this->_validation();
        $data = $this->input->post();
        if(!empty($data['is_status'])) $data['is_status'] = 1;else $data['is_status'] = 0;
        return $data;
    }
}