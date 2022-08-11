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
        $this->setCacheFile(8);
        /* MB */
        $data_json_MB = json_decode($this->callCURL(API_DATACENTER . 'api/v1/result/getdataresult?api_id=1&limit=1'), true);
        if (!empty($data_json_MB['data']['data'][0])) $data['data_MB'] = $data_json_MB['data']['data'][0];
        if (!empty($data['data_MB'])) $data['oneParentMB'] = getCateById($data['data_MB']['category_id']);

        /* MT */
        $data_json_MT = json_decode($this->callCURL(API_DATACENTER . 'api/v1/result/getdataresult?api_id=2&limit=3'), true);
        $dataApiMT = groupDisTime($data_json_MT['data']['data']);
        $data['data_MT'] = reset($dataApiMT);
        if (!empty($data['data_MT'])) $data['oneMT'] = getCateById($data['data_MT'][0]['category_id']);
        if (!empty($data['oneMT'])) $data['oneParentMT'] = getCateById($data['oneMT']->parent_id);

        /* MN */
        $data_json_MN = json_decode($this->callCURL(API_DATACENTER . 'api/v1/result/getdataresult?api_id=3&limit=1'), true);
        $dataApiMN = $data_json_MN['data']['data'];
        $data['data_MN'] = ($dataApiMN);
        if (!empty($data['data_MN'])) $data['oneMN'] = getCateById($data['data_MN'][0]['category_id']);
        if (!empty($data['oneMN'])) $data['oneParentMN'] = getCateById($data['oneMN']->parent_id);
        $data['SEO'] = [
            'meta_title' => !empty($this->_settings->meta_title) ? $this->_settings->meta_title : '',
            'meta_description' => !empty($this->_settings->meta_description) ? $this->_settings->meta_description : '',
            'meta_keyword' => !empty($this->_settings->meta_keyword) ? $this->_settings->meta_keyword : '',
            'url' => base_url(),
            'is_robot' => true,
            'image' => !empty($this->_settings->thumbnail) ? getImageThumb($this->_settings->thumbnail, 1200, 650) : '',
        ];
        $data['main_content'] = $this->load->view(TEMPLATE_PATH . 'home/index', $data, TRUE);
        $this->load->view(TEMPLATE_MAIN, $data);
    }
}
