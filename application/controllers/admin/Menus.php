<?php
/**
 * Created by PhpStorm.
 * User: ducto
 * Date: 18/12/2017
 * Time: 5:25 CH
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Menus extends Admin_Controller {

    protected $_data;
    protected $_pageModel;
    protected $_postModel;
    protected $_productModel;
    protected $_categoryModel;
    protected $_listMenu;
    public function __construct()
    {
        parent::__construct();
        //tải file ngôn ngữ
        //$this->lang->load('menu');
        $this->config->load('menus');
        $this->load->model(['Menus_model','post_model','category_model','page_model']);
        $this->_data = new Menus_model();
        $this->_postModel = new Post_model();
        $this->_pageModel = new Page_model();
        $this->_categoryModel = new Category_model();
    }

    public function index()
    {
        $data['heading_title'] = "Cấu hình Menu";
        $data['heading_description'] = "Danh sách menu";

        $data['main_content'] = $this->load->view($this->template_path . $this->_controller . '/index', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }
    public function ajax_load(){
        $this->checkRequestPostAjax();
        $lang_code = $this->input->post('lang_code');
        $data['lang_code'] = !empty($lang_code)?$lang_code:$this->session->admin_lang;
        $data['list_category_type'] = $groupCategory = $this->_categoryModel->getDataGroupBy();
        $data['list_category'] = $allCategory = $this->_categoryModel->getAll($lang_code);
        if(!empty($groupCategory)) foreach ($groupCategory as $key) {
            $tmp[$key['type']] = $this->getCategoryByType($allCategory, $key['type']);
            $data['list'] =  $tmp;
        }
        $data['list_pages'] = $this->_pageModel->getAll($lang_code);
        //$data['list_posts'] = $this->_postModel->getAll($lang_code);
        echo $this->load->view($this->template_path . 'menus/_ajax_load_data', $data, TRUE);
        exit;
    }

    public function ajax_load_menu(){
        $this->checkRequestPostAjax();
        $locationId = $this->input->post('location_id');
        $lang_code = $this->input->post('lang_code');
        $data = $this->_data->search(['location_id' => $locationId, 'language_code' => $lang_code]);
        $this->listMenu($data, 0, $locationId, $lang_code);
        die(json_encode($this->_listMenu));
    }

    public function ajax_save_menu(){
        $this->checkRequestPostAjax();
        $menuLocation = $this->input->post('loc');
        $response = $this->input->post('s');
        $menuLanguage = $this->input->post('lang');

        $this->_data->delete(['location_id'=>$menuLocation,'language_code'=>$menuLanguage]);
        if (is_array($response)) {
            $topmenusorder = 1;
            if(!empty($response)) foreach ($response as $key => $block) {
                $tmp['title'] = trim($block['label']);
                $tmp['class'] = trim($block['cls']);
                //$tmp['type'] = trim($block['type']);
                $tmp['link'] = trim($block['link']);
                $tmp['order'] = $topmenusorder;
                $tmp['parent_id'] = 0;
                $tmp['location_id'] = $menuLocation;
                $tmp['language_code'] = $menuLanguage;
                $menuid = $this->_data->saveMenu($tmp);
                if (!empty($block['children'])) {
                    $this->childsubmenus($menuid, $block['children'], $menuLocation, $menuLanguage);
                }
                $topmenusorder++;
            }
        }
        echo 1;
        exit;
    }

    //-----------------------------------
    private function childsubmenus($menuid, $e, $menuLocation, $menuLanguage)
    {
        $topmenusorder = 1;
        foreach ($e as $key => $block) {
            $tmp['title'] = trim($block['label']);
            $tmp['class'] = trim($block['cls']);
            //$tmp['type'] = trim($block['type']);
            $tmp['link'] = trim($block['link']);
            $tmp['order'] = $topmenusorder;
            $tmp['parent_id'] = $menuid;
            $tmp['location_id'] = $menuLocation;
            $tmp['language_code'] = $menuLanguage;
            $menu = $this->_data->saveMenu($tmp);
            if (!empty($block['children'])) {
                $this->childsubmenus($menu, $block['children'], $menuLocation, $menuLanguage);
            }
            $topmenusorder++;
        }
    }

    private function listMenu($menu, $parent = 0,$locationId, $lang_code)
    {
        if(!empty($menu)) foreach ($menu as $row) {
            $row = (array) $row;
            if ($row['parent_id'] == $parent)
            {
                $this->_listMenu[] = array(
                    'id' => $row['id'],
                    'name' => $row['title'],
                    'class' => $row['class'],
                    //'type' => $row['type'],
                    'link' => $row['link'],
                    'level' => $row['parent_id']);
                $this->listMenu($menu, $row['id'],$locationId, $lang_code);
            }
        }
    }
    private function getCategoryByType($all, $type){
        $data = [];
        if(!empty($all)) foreach ($all as $item){
            if($item->type === $type) $data[] = $item;
        }
        return $data;
    }

    /*public function saveMenuBrand(){
        $allCategory = $this->_categoryModel->getAll($this->session->userdata('public_lang_code'));
        $allBrand = $this->_categoryModel->getDataByCategoryType($allCategory,'brand');
        if(!empty($allBrand)) foreach ($allBrand as $k => $item){
            $tmp['title'] = $item->title;
            $tmp['link'] = str_replace(base_url(),'',getUrlBrand($item));
            $tmp['parent_id'] = 0;
            $tmp['order'] = $k;
            $tmp['language_code'] = 'vi';
            $this->_data->saveMenu($tmp);
        }
        die('done');
    }*/
}