<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Post extends Public_Controller
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

    public function detail($slug)
    {
        $data = [];
        $data['oneItem'] = $oneItem = $this->_post->getBySlug($slug);

        //add breadcrumbs
        $this->breadcrumbs->push("Home", base_url());
        $this->breadcrumbs->push($oneItem->title, base_url($oneItem->slug.'.html'));
        $data['breadcrumb'] = $this->breadcrumbs->show();
        
        $data['SEO'] = [
            'meta_title' => !empty($oneItem->meta_title) ? $oneItem->meta_title : $oneItem->title,
            'meta_description' => !empty($oneItem->meta_description) ? $oneItem->meta_description : $oneItem->description,
            'meta_keyword' => !empty($oneItem->meta_title) ? $oneItem->meta_keyword : '',
            'url' => current_url(),
            'image' => getImageThumb($oneItem->thumbnail, 600, 314),
            'meta_robots' => 1
        ];
        
        $data['main_content'] = $this->load->view(TEMPLATE_PATH . 'post/detail', $data, TRUE);
        $this->load->view(TEMPLATE_MAIN, $data);
    }
}
