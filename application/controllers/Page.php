<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Page extends Public_Controller
{
    protected $_post;
    protected $_all_category;
    protected $_category;
    protected $_soicau;
    protected $_result;
    protected $_reviews;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['post_model', 'category_model', 'reviews_model', 'Soicau_model', 'Result_model']);
        $this->_post = new Post_model();
        $this->_category = new Category_model();
        $this->_result = new Result_model();
        $this->_soicau = new Soicau_model();
        $this->_reviews = new Reviews_model();
        $this->_all_category = $this->_category->_all_category();
    }
    public function spin($code)
    {
        $data = [];
        $oneItem = $this->_category->getByCode($code);
        if (empty($oneItem)) show_404();
        $data['oneItem'] = $oneItem;
        $data['list_province'] = $this->_category->getDataFE([
            'type' => 'lottery',
            'parent_id_not' => true,
            'limit' => 100
        ]);
        $data['list_parent'] = $this->_category->getDataFE([
            'type' => 'lottery',
            'parent_id' => '0'
        ]);
        $data['SEO'] = [
            'meta_title' => $oneItem->meta_title,
            'meta_description' => $oneItem->meta_description,
            'meta_keyword' => $oneItem->meta_keyword,
            'url' => getUrlCategory($oneItem),
            'is_robot' => 1,
        ];
        $data['main_content'] = $this->load->view(TEMPLATE_PATH . 'spin/index', $data, TRUE);
        $this->load->view(TEMPLATE_MAIN, $data);
    }
    public function soicau($layout)
    {
        $data = [];
        $oneItem = $this->_category->getByCode($layout);
        if (empty($oneItem)) {
            $code = 'xsmb';
            $oneItem = $this->_category->getDataByField('layout', $layout);
        } else {
            $code = $layout;
            $layout = 'bach-thu';
        }
        $oneParent = $this->_category->getByCode($code);
        if (empty($oneItem)) show_404();
        $data['oneItem'] = $oneItem;
        $data['oneParent'] = $oneParent;

        $limit = 3;
        if(!empty($_GET['so_cau'])) $limit = $_GET['so_cau'];

        switch ($layout) {
            case 'bach-thu':
                $urlApi = API_DATACENTER . "api/v1/soicau/bachthu?code=$code&limit=$limit";
                $data_json = json_decode($this->callCURL($urlApi), true);
                $data['data_soicau'] = !empty($data_json) ? $data_json['data'] : [];
                if (!empty($data['data_soicau'])) {
                    foreach ($data['data_soicau']['table'] as $key => $item) {
                        if (strlen($key) == 1) {
                            $key = '0' . $key;
                        }
                        $data['data_soicau']['table'][$key] = $item;
                    }
                }
                $js = 'js_data_bach_thu/xsmb';
                break;
            case 'nhieu-nhay':
                $urlApi = API_DATACENTER . "api/v1/soicau/venhieunhay?code=xsmb&limit=$limit";
                $data_json = json_decode($this->callCURL($urlApi), true);
                $data['data_soicau'] = !empty($data_json) ? $data_json['data'] : [];
                $js = 'js_data_ve_nhieu_nhay';
                break;
            case 'lat-lien-tuc':
                $urlApi = API_DATACENTER . "api/v1/soicau/latlientuc?code=xsmb&limit=$limit";
                $data_json = json_decode($this->callCURL($urlApi), true);
                $data['data_soicau'] = !empty($data_json) ? $data_json['data'] : [];
                $js = 'js_data_lat_lien_tuc';
                break;
            case 'mien-trung':
                # code...
                $data['cate_today'] = getCatChildByDOW(2, date('N') + 1);
                $js = '';
                break;
            case 'mien-nam':
                # code...
                $data['cate_today'] = getCatChildByDOW(3, date('N') + 1);
                $js = '';
                break;
        };
        $params = [
            'api_id' => $oneItem->id,
            'limit' => 14
        ];
        $data['data'] = $this->_result->getDataNearestByDay($params);
        $data['js'] = $js;
        $data['SEO'] = [
            'meta_title' => $oneItem->meta_title,
            'meta_description' => $oneItem->meta_description,
            'meta_keyword' => $oneItem->meta_keyword,
            'url' => getUrlCategory($oneItem),
            'is_robot' => 1,
        ];

        $data['main_content'] = $this->load->view(TEMPLATE_PATH . "soicau/detail-$layout", $data, TRUE);
        $this->load->view(TEMPLATE_MAIN, $data);
    }

    public function js_data_bach_thu($code)
    {
        header("Content-type: text/javascript");
        echo $this->_data_statistic->getBachThu($code, 'bachthu', 'js');
        exit;
    }
    public function js_data_lat_lien_tuc()
    {
        header("Content-type: text/javascript");
        echo $this->_data_statistic->getLatLienTuc('js');
        exit;
    }
    public function js_data_ve_nhieu_nhay()
    {
        header("Content-type: text/javascript");
        echo $this->_data_statistic->getBachThu('xsmb', 'nhieunhay', 'js');
        exit;
    }
    public function js_data_lokep($code)
    {
        header("Content-type: text/javascript");
        echo $this->_data_statistic->getBachThu($code, 'lokep', 'js');
        exit;
    }
}
