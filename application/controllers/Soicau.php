<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Soicau extends Public_Controller
{

    protected $_data;
    protected $_data_category;
    protected $_data_onpage;
    protected $_data_statistic;
    protected $_data_post;
    protected $_lang_code;
    protected $_all_category;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['category_model', 'result_model', 'statistic_model', 'post_model']);
        $this->_data = new Result_model();
        $this->_data_category = new Category_model();
        $this->_data_statistic = new Statistic_model();
        $this->_data_post = new Post_model();
        $this->_all_category = $this->_data_category->_all_category();
        $this->_lang_code = 'vi';
    }

    public function js_data_bach_thu($code){
        header("Content-type: text/javascript");
        echo $this->_data_statistic->getBachThu($code, 'bachthu', 'js');
        exit;
    }
    public function js_data_lat_lien_tuc(){
        header("Content-type: text/javascript");
        echo $this->_data_statistic->getLatLienTuc('js');
        exit;
    }
    public function js_data_ve_nhieu_nhay(){
        header("Content-type: text/javascript");
        echo $this->_data_statistic->getBachThu('xsmb', 'nhieunhay', 'js');
        exit;
    }
    public function js_data_lokep($code){
        header("Content-type: text/javascript");
        echo $this->_data_statistic->getBachThu($code, 'lokep', 'js');
        exit;
    }
}

/* End of file Statistic.php */