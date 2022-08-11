<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends Public_Controller {

    protected $_data_post;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('post_model');
        $this->_data_post = new Post_model();
    }

    public function index(){
        $keyword = $this->input->get('q');
        $page = $this->input->get('page');
        $oneItem = new stdClass();
        $oneItem->keyword = $keyword;
        $oneItem->title = "Tìm kiếm: $keyword";
        $oneItem->layout = '';
        $oneItem->thumbnail = '';
        $oneItem->meta_title = "Tìm kiếm từ khóa: $keyword - Thông tin, hình ảnh, video clip về $keyword";
        $oneItem->meta_description = "Tim kiếm từ khóa: $keyword - Thông tin, hình ảnh, video clip về $keyword";
        $oneItem->slug = $this->toSlug($keyword);
        $data['oneItem'] = $oneItem;
        $limit = 10;
        $params = [
            'keyword' => $keyword,
            'page' => $page,
            'limit' => $limit,
            'is_status' => 1,
            'order' => ['id' => 'DESC']
        ];
        $data['list_post'] = $this->_data_post->getDataFE($params);
        $totalPost = $this->_data_post->getTotalFE($params);
        $data['page'] = (int) round($totalPost / $limit);


        $this->breadcrumbs->push("Trang chủ", base_url());
        $this->breadcrumbs->push($oneItem->title, getUrlSearch($keyword));
        $data['breadcrumb'] = $this->breadcrumbs->show();

        $data['SEO'] = [
            'meta_title' => !empty($oneItem->meta_title) ? $oneItem->meta_title : '',
            'meta_description' => !empty($oneItem->meta_description) ? $oneItem->meta_description : '',
            'meta_keyword' => !empty($oneItem->meta_keyword) ? $oneItem->meta_keyword : '',
            'url' => getUrlSearch($keyword),
        ];

        $data['main_content'] = $this->load->view(TEMPLATE_PATH . "search/index", $data, TRUE);
        $this->load->view(TEMPLATE_MAIN, $data);
    }


}
