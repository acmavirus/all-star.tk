<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Drag extends Admin_Controller
{
    protected $_data;
    protected $_category;
    protected $type;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['drag_model','category_model']);
        $this->_data = new Drag_model();
        $this->_category = new Category_model();
        $this->type = $this->uri->segment(3);
    }

    public function index($data){
        $data['data'] = [];
        $data['main_content'] = $this->load->view(TEMPLATE_PATH . $this->_controller . DIRECTORY_SEPARATOR . 'index', $data, TRUE);
        $this->load->view(TEMPLATE_MAIN, $data);
    }

    public function table_ranking(){
        $data['heading_title'] = "Quản lý bảng xếp hạng";
        $data['heading_type'] = "table_ranking";
        $data['heading_description'] = "Danh sách bảng xếp hạng";
        $data['dragInfo'] = $this->_data->getDataDrag($this->type);
        $this->index($data);
    }

    public function table_schedule(){
        $data['heading_title'] = "Quản lý lịch thi đấu";
        $data['heading_type'] = "table_schedule";
        $data['heading_description'] = "Danh sách lịch thi đấu";
        $data['dragInfo'] = $this->_data->getDataDrag($this->type);
        $this->index($data);
    }

    public function save_drag(){
        $input = $this->input->post()['s'];
        $type  = $this->input->post('type');
        foreach ($input as $k => $value){
            $data[$k]['order'] = $k;
            $data[$k]['id'] = $value['id'];
            $data[$k]['type'] = $type;
        }
        $this->_data->delete(['type' => $type],'drag');
        if ($this->_data->insertMultiple($data,'drag')){
            echo 1;
        } else {
            echo 0;
        };
    }
    /*end drag*/

    public function ajax_load(){
        $term = $this->input->get("q");
        $params = [
            'is_status'=> 1,
            'keyword' => $term,
            'limit'=> 100,
            'type' => 'page'
        ];

        $data = $this->_category->getData($params);
        $output = [];
        if(!empty($data)) foreach ($data as $item) {
            $output[] = ['id'=>$item->id, 'text'=>$item->title];
        }
        $this->returnJson($output);
    }
  
}