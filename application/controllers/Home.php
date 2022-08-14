<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends Public_Controller
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

    public function index()
    {
        $data = [];
        $params = [
            'page' => 1,
            'limit'=> 18,
            'order'=> [
                'id' => 'RANDOM'
            ]
        ];
        $data['data'] = $this->_post->getData($params);
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $data['main_content'] = $this->load->view(TEMPLATE_PATH . 'home/index', $data, TRUE);
        $this->load->view(TEMPLATE_MAIN, $data);
    }
}
