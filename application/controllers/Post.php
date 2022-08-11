<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Post extends Public_Controller
{
    protected $_post;
    protected $_all_category;
    protected $_category;
    protected $_user;
    protected $_reviews;
    public function __construct()
    {
        parent::__construct();

        $this->load->model(['post_model', 'category_model', 'users_model', 'reviews_model']);
        $this->_post = new Post_model();
        $this->_category = new Category_model();
        $this->_user = new Users_model();
        $this->_reviews = new Reviews_model();
        $this->_all_category = $this->_category->_all_category();
    }

    public function detail($slug, $id)
    {
        $this->setCacheFile(15);
        $oneItem = $this->_post->getByIdCached($id);
        if (empty($oneItem)) show_404();
        $urlNormal = getUrlPost($oneItem);
        if ($oneItem->slug !== $slug) redirect($urlNormal, "location", "301");
        
        switch ($oneItem->type) {
            default:
            $data = $this->detail_post($oneItem);
            $data['main_content'] = $this->load->view(TEMPLATE_PATH . $oneItem->type . '/detail', $data, TRUE);
        }
        $this->load->view(TEMPLATE_MAIN, $data);
    }



    private function detail_post($oneItem)
    {
        if ($oneItem->is_status == 0) show_404();
        $id = $oneItem->id;
        $data['category'] = $oneCategory = $this->_category->getCatByPostId($id);
        $this->_category->_recursive_child_id($this->_all_category, $oneCategory->id);
        $listChildId = $this->_category->_list_category_child_id;
        $data['recent_post'] = $this->_post->getDataFE([
            'is_status' => 1,
            'limit' => 6,
        ]);
        $data['related_post'] = $this->_post->getDataFE([
            'is_status' => 1,
            'category_id' => $listChildId,
            'limit' => 6
        ]);

        $data['reviews'] = $this->_reviews->getRate([
            'url' => getUrlPost($oneItem)
        ]);
        $data['data_tag'] = $this->_category->getTagByPostId($id);
        $data['user'] = $this->_user->getById($oneItem->user_id);

        $this->breadcrumbs->push('Home', base_url());
        $this->breadcrumbs->push($oneCategory->title, getUrlCategory($oneCategory));
        $this->breadcrumbs->push($oneItem->title, getUrlPost($oneItem));
        $data['breadcrumb'] = $this->breadcrumbs->show();

        $data['SEO'] = [
            'meta_title' => !empty($oneItem->meta_title) ? $oneItem->meta_title : $oneItem->title,
            'meta_description' => !empty($oneItem->meta_description) ? $oneItem->meta_description : $oneItem->description,
            'meta_keyword' => !empty($oneItem->meta_keyword) ? $oneItem->meta_keyword : '',
            'url' => getUrlPost($oneItem),
            'is_robot' => !empty($oneItem->is_robot) ? $oneItem->is_robot : '',
            'image' => !empty($oneItem->thumbnail) ? getImageThumb($oneItem->thumbnail, 470, 246) : '',
        ];

        $data['oneItem'] = $oneItem;
        return $data;
    }
}
