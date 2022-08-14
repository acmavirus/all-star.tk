<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Page extends Public_Controller
{
    protected $_category;
    protected $_post;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['category_model', 'post_model']);
        $this->_post = new Post_model();
        $this->_category = new Category_model();
    }

    public function music()
    {
        $data = [];
        $params = [
            'page' => 1,
            'limit'=> 18
        ];
        $data['data'] = $this->_post->getData($params);

        //add breadcrumbs
        $this->breadcrumbs->push("Home", base_url());
        $this->breadcrumbs->push("dsaf", base_url());
        $data['breadcrumb'] = $this->breadcrumbs->show();

        $data['main_content'] = $this->load->view(TEMPLATE_PATH . 'page/music', $data, TRUE);
        $this->load->view(TEMPLATE_MAIN, $data);
    }

    public function tool()
    {
        $data = [];
        $params = [
            'page' => 1,
            'limit'=> 18
        ];
        $data['data'] = $this->_post->getData($params);

        //add breadcrumbs
        $this->breadcrumbs->push("Home", base_url());
        $this->breadcrumbs->push("dsaf", base_url());
        $data['breadcrumb'] = $this->breadcrumbs->show();
        
        $data['main_content'] = $this->load->view(TEMPLATE_PATH . 'page/tool', $data, TRUE);
        $this->load->view(TEMPLATE_MAIN, $data);
    }

    public function notfound()
    {
        $data = [];
        $data['main_content'] = $this->load->view(TEMPLATE_PATH . 'home/index', $data, TRUE);
        $this->load->view(TEMPLATE_MAIN, $data);
    }
}
