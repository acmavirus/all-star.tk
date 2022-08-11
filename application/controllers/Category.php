<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category extends Public_Controller
{
    protected $_post;
    protected $_all_category;
    protected $_category;
    protected $_reviews;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['post_model', 'category_model', 'reviews_model']);
        $this->_post = new Post_model();
        $this->_category = new Category_model();
        $this->_reviews = new Reviews_model();
        $this->_all_category = $this->_category->_all_category();
    }
    public function detail($slug, $page = 1)
    {
        $oneItem = $this->_category->getBySlugCached($slug);
        //if (empty($oneItem)) show_404();
        $data = [];
        $layoutView = '';
        switch ($oneItem->type) {
            case 'vietlot':
                if (in_array(strtoupper($oneItem->code), ['MEGA', 'POWER', 'MAX3D', 'MAX4D'])) {
                    $oneParent = getCateById($oneItem->parent_id);
                    $data = $this->lottery_vietlot_detail($oneParent, $oneItem, $page);
                    $layoutView = '-vietlot';
                } else {
                    $data = $this->lottery_vietlot_home($oneItem);
                    $layoutView = '-vietlot-home';
                }
                $data['main_content'] = $this->load->view(TEMPLATE_PATH . 'lottery/detail' . $layoutView, $data, TRUE);
                break;
            case 'weekday':
                
                $oneParent = getCateById($oneItem->parent_id);
                $data = $this->lottery_weekday($oneParent, $oneItem, $page);
                $layoutView = '-weekday';
                $data['main_content'] = $this->load->view(TEMPLATE_PATH .  'lottery/detail' . $layoutView, $data, TRUE);
                break;
            case 'lottery':
                if (in_array(strtoupper($oneItem->code), ['XSMB', 'XSMT', 'XSMN'])) {
                    $data = $this->lottery_region($oneItem, $page);
                    $layoutView = '-region';
                } else if (in_array(strtoupper($oneItem->code), ['SICBO', 'BAUCUA', 'TAIXIU', 'PK10', 'HANOI'])) {
                    $data = $this->lottery_game($oneItem, $page);
                    $layoutView = '-game';
                } else {
                    $oneParent = getCateById($oneItem->parent_id);
                    $data = $this->lottery_province($oneParent, $oneItem, $page);
                    $layoutView = '-province';
                }
                $data['main_content'] = $this->load->view(TEMPLATE_PATH . $oneItem->type . '/detail' . $layoutView, $data, TRUE);
                break;
            case 'page':
                $data = $this->category_page($oneItem, $page);
                $layoutView = '';
                if (!empty($oneItem->layout)) $layoutView = '-' . $oneItem->layout;
                $data['main_content'] = $this->load->view(TEMPLATE_PATH . $oneItem->type . '/detail' . $layoutView, $data, TRUE);
                break;
            default:
            
                $data = $this->category_post($oneItem, $page);
                $data['main_content'] = $this->load->view(TEMPLATE_PATH . 'post/category', $data, TRUE);
        }

        $this->load->view(TEMPLATE_MAIN, $data);
    }
    // Data 
    /* Danh mục page */
    private function category_page($oneItem, $page)
    {
        $data['oneItem'] = $oneItem;
        if ($oneItem->type !== "page") show_404();
        $data['reviews'] = $this->_reviews->getRate([
            'url' => getUrlPage($oneItem)
        ]);
        $this->breadcrumbs->push("Trang chủ", base_url());
        $this->breadcrumbs->push($oneItem->title, getUrlPage($oneItem));
        $data['breadcrumb'] = $this->breadcrumbs->show();

        $data['SEO'] = [
            'meta_title' => !empty($oneItem->meta_title) ? $oneItem->meta_title : '',
            'meta_description' => !empty($oneItem->meta_description) ? $oneItem->meta_description : '',
            'meta_keyword' => !empty($oneItem->meta_keyword) ? $oneItem->meta_keyword : '',
            'url' => getUrlPage($oneItem),
            'is_robot' => !empty($oneItem->is_robot) ? $oneItem->is_robot : '',
            'image' => !empty($oneItem->thumbnail) ? getImageThumb($oneItem->thumbnail, 470, 246) : '',
        ];
        return $data;
    }
    /* Danh mục post */
    private function category_post($oneItem, $page)
    {
        $data['oneItem'] = $oneItem;
        //        if($oneItem->type !== "post") show_404();
        $id = $oneItem->id;
        $this->_category->_recursive_child_id($this->_all_category, $id);
        $listCateId = $this->_category->_list_category_child_id;
        $limit = 10;
        $data['data'] = $this->_post->getDataFE([
            'category_id' => $listCateId,
            'page' => $page,
            'limit' => $limit,
            'is_status' => 1,
            'order' => ['id' => 'DESC']
        ]);

        $data['recent_post'] = $this->_post->getDataFE([
            'is_status' => 1,
            'limit' => 6,
        ]);
        $data['total'] = $total = $this->_post->getTotalFE([
            'category_id' => $oneItem->id,
            'is_status' => 1
        ]);

        //        $data['page'] = $page;
        //        $this->load->library('pagination');
        //        $paging['base_url'] = getUrlCategory($oneItem);
        //        $paging['first_url'] = getUrlCategory($oneItem);
        //        $paging['total_rows'] = $total;
        //        $paging['per_page'] = $limit;
        //        $paging['cur_page'] = $page;
        //        $this->pagination->initialize($paging);
        //        $data['pagination'] = $this->pagination->create_links();
        // end phân Trang

        $this->breadcrumbs->push('Trang chủ', base_url());
        $this->_category->_recursive_parent($this->_all_category, $oneItem->id);
        if (!empty($this->_category->_list_category_parent)) foreach (array_reverse($this->_category->_list_category_parent) as $item) {
            $this->breadcrumbs->push($item->title, getUrlCategory($item));
        }
        $this->breadcrumbs->push($oneItem->title, getUrlCategory($oneItem));
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $data['SEO'] = [
            'meta_title' => !empty($oneItem->meta_title) ? $oneItem->meta_title : '',
            'meta_description' => !empty($oneItem->meta_description) ? $oneItem->meta_description : '',
            'meta_keyword' => !empty($oneItem->meta_keyword) ? $oneItem->meta_keyword : '',
            'url' => getUrlCategory($oneItem),
            'is_robot' => !empty($oneItem->is_robot) ? $oneItem->is_robot : '',
            'image' => !empty($oneItem->thumbnail) ? getImageThumb($oneItem->thumbnail, 470, 246) : '',
        ];
        return $data;
    }
    /* Xổ số miền */
    private function lottery_region($oneItem, $page = 1)
    {
        $data['oneItem'] = $oneItem;
        if ($oneItem->type !== "lottery") show_404();
        $urlApi = API_DATACENTER . "api/v1/result/getdataresult?api_id=$oneItem->id&page=$page&limit=3";
        $data_json_region = json_decode($this->callCURL($urlApi), true);
        if ($oneItem->code !== 'XSMB') {
            $dataApi = $data_json_region['data']['data'];
            $data['data_region'] = groupDisTime($dataApi);
        } else {
            $data['data_region'] = $data_json_region['data']['data'];
        }

        $params = [
            'type' => 'weekday',
            'parent_id' => $oneItem->id
        ];

        $data['listweekday'] = $this->_category->getDataFE(($params));

        $this->breadcrumbs->push('Trang chủ', base_url());
        $this->breadcrumbs->push("Trang chủ", base_url());
        if ($oneItem->id <= 3) {$this->breadcrumbs->push($oneItem->code, getUrlCategoryRS($oneItem));} else{
            $this->breadcrumbs->push($oneItem->code, getUrlCategory($oneItem));
        }
        $data['breadcrumb'] = $this->breadcrumbs->show();

        $data['SEO'] = [
            'meta_title' => !empty($oneItem->meta_title) ? $oneItem->meta_title : '',
            'meta_description' => !empty($oneItem->meta_description) ? $oneItem->meta_description : '',
            'meta_keyword' => !empty($oneItem->meta_keyword) ? $oneItem->meta_keyword : '',
            'url' => getUrlCategory($oneItem),
            'is_robot' => !empty($oneItem->is_robot) ? $oneItem->is_robot : '',
            'image' => !empty($oneItem->thumbnail) ? getImageThumb($oneItem->thumbnail, 1200, 650) : '',
        ];
        return $data;
    }
    /* Xổ số tỉnh */
    private function lottery_province($oneParent, $oneItem, $page = 1)
    {
        $data['oneItem'] = $oneItem;
        $data['oneParent'] = $oneParent;
        if ($oneItem->type !== "lottery") show_404();
        $urlApi = API_DATACENTER . "api/v1/result/getdataresult?api_id=$oneItem->id&page=$page&limit=3";
        $data_json_region = json_decode($this->callCURL($urlApi), true);
        $data['data_province'] = $data_json_region['data']['data'];
        $this->breadcrumbs->push('Trang chủ', base_url());
        $this->breadcrumbs->push($oneParent->code, getUrlCategory($oneParent));
        $this->breadcrumbs->push($oneParent->code . ' ' . $oneItem->title, getUrlCategory($oneItem));
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $data['SEO'] = [
            'meta_title' => !empty($oneItem->meta_title) ? $oneItem->meta_title : '',
            'meta_description' => !empty($oneItem->meta_description) ? $oneItem->meta_description : '',
            'meta_keyword' => !empty($oneItem->meta_keyword) ? $oneItem->meta_keyword : '',
            'url' => getUrlCategory($oneItem),
            'is_robot' => !empty($oneItem->is_robot) ? $oneItem->is_robot : '',
            'image' => !empty($oneItem->thumbnail) ? getImageThumb($oneItem->thumbnail, 470, 246) : '',
        ];
        return $data;
    }
    /* Xổ số thứ */
    private function lottery_weekday($oneParent, $oneItem, $page = 1)
    {
        $data['oneItem'] = $oneItem;
        $urlApi = API_DATACENTER . "api/v1/result/getDataByWeekday?api_id=$oneParent->id&weekday=$oneItem->code&page=$page";
        $data_json = json_decode($this->callCURL($urlApi), true);
        if (in_array(strtoupper($oneParent->code), ['XSMT', 'XSMN'])) {
            $dataApi = $data_json['data']['data'];
            $dataResult = array();
            if (!empty($dataApi)) {
                foreach ($dataApi as $key => $item) {
                    $dataResult[$key] = array();
                    foreach ($item as $key1 => $item1) {
                        $dataResult[$key] = array_merge($dataResult[$key], $item1);
                    }
                }
            }
            $data['data_api'] = array_values($dataResult);
        } elseif ($oneParent->code == 'XSMB') {
            $data['data_api'] = $data_json['data']['data'];
        } elseif ($oneParent->code == 'MEGA') {
            $data['data_api'] = $data_json['data']['data'];
        } elseif ($oneParent->code == 'POWER') {
            $data['data_api'] = $data_json['data']['data'];
        } elseif ($oneParent->code == 'MAX3D') {
            $data['data_api'] = $data_json['data']['data'];
        } elseif ($oneParent->code == 'MAX4D') {
            $data['data_api'] = $data_json['data']['data'];
        }

        $data['oneParent'] = $oneParent;
        $params = [
            'type' => 'weekday',
            'parent_id' => $oneParent->id
        ];

        $data['listweekday'] = $this->_category->getDataFE(($params));

        $this->breadcrumbs->push("Trang chủ", base_url());
        if ($oneParent->id <= 3) {$this->breadcrumbs->push($oneParent->code, getUrlCategoryRS($oneParent));} else{
            $this->breadcrumbs->push($oneParent->code, getUrlCategory($oneParent));
        }
        $this->breadcrumbs->push($oneParent->code . ' ' . $oneItem->title, getUrlCategory($oneItem));
        $data['breadcrumb'] = $this->breadcrumbs->show();

        $data['SEO'] = [
            'meta_title' => !empty($oneItem->meta_title) ? $oneItem->meta_title : '',
            'meta_description' => !empty($oneItem->meta_description) ? $oneItem->meta_description : '',
            'meta_keyword' => !empty($oneItem->meta_keyword) ? $oneItem->meta_keyword : '',
            'url' => getUrlCategory($oneItem),
            'is_robot' => !empty($oneItem->is_robot) ? $oneItem->is_robot : '',
            'image' => !empty($oneItem->thumbnail) ? getImageThumb($oneItem->thumbnail, 1200, 650) : '',
        ];
        return $data;
    }
    /* Xổ số + viettlot ngày */
    public function date($code, $date)
    {
        $oneParent = $this->_category->getByCode($code);
        if (empty($oneParent)) show_404();
        $data['date'] = $date = date('Y-m-d', strtotime($date));
        $urlApi = API_DATACENTER . "api/v1/result/getFromDayToDay?api_id=$oneParent->id&date_begin=$date&date_end=$date";
        $data_json_date = json_decode($this->callCURL($urlApi), true);
        $dataApi = $data_json_date['data']['data'];
        if (empty($dataApi)) show_404();
        if ($oneParent->code !== 'XSMB') {
            $data['data_date'] = groupDisTime($dataApi);
        } else {
            $data['data_date'] = $dataApi;
        }
        $data['oneParent'] = $oneParent;
        $params = [
            'type' => 'weekday',
            'parent_id' => $oneParent->id
        ];
        $data['listweekday'] = $this->_category->getDataFE(($params));
        $this->breadcrumbs->push("HOME", base_url());
        $this->breadcrumbs->push($oneParent->code, getUrlCategory($oneParent));
        $this->breadcrumbs->push($oneParent->code . ' ' . getDayOfWeek($date),  getUrlWeekday($oneParent, date('w', strtotime($date))));
        $this->breadcrumbs->push($oneParent->code . ' ' . date('d-m-Y', strtotime($date)), getUrlDate($oneParent, $date));
        $data['breadcrumb'] = $this->breadcrumbs->show();

        $data['SEO'] = [
            'meta_title' => !empty($oneParent->meta_title) ? $oneParent->meta_title : '',
            'meta_description' => !empty($oneParent->meta_description) ? $oneParent->meta_description : '',
            'meta_keyword' => !empty($oneParent->meta_keyword) ? $oneParent->meta_keyword : '',
            'url' => getUrlCategory($oneParent),
            'is_robot' => !empty($oneParent->is_robot) ? $oneParent->is_robot : '',
            'image' => !empty($oneParent->thumbnail) ? getImageThumb($oneParent->thumbnail, 1200, 650) : '',
        ];
        $layoutView = '-date';
        $data['main_content'] = $this->load->view(TEMPLATE_PATH . 'lottery/detail' . $layoutView, $data, TRUE);
        $this->load->view(TEMPLATE_MAIN, $data);
    }
    /* viettlot */
    private function lottery_vietlot_home($oneItem)
    {
        $data['oneItem'] = $oneItem;
        $this->breadcrumbs->push('Trang chủ', base_url());
        $this->breadcrumbs->push($oneItem->title, getUrlCategory($oneItem));
        $data['breadcrumb'] = $this->breadcrumbs->show();

        $data['data_vietlot_645'] = getResultVietlot(42);
        $data['parent_645'] = getCateById(42);

        $data['data_vietlot_655'] = getResultVietlot(43);
        $data['parent_655'] = getCateById(43);

        $data['data_vietlot_3d'] = getResultVietlot(44);
        $data['parent_3d'] = getCateById(44);

        $data['data_vietlot_4d'] = getResultVietlot(45);
        $data['parent_4d'] = getCateById(45);



        $data['SEO'] = [
            'meta_title' => !empty($oneItem->meta_title) ? $oneItem->meta_title : '',
            'meta_description' => !empty($oneItem->meta_description) ? $oneItem->meta_description : '',
            'meta_keyword' => !empty($oneItem->meta_keyword) ? $oneItem->meta_keyword : '',
            'url' => getUrlCategory($oneItem),
            'is_robot' => !empty($oneItem->is_robot) ? $oneItem->is_robot : '',
            'image' => !empty($oneItem->thumbnail) ? getImageThumb($oneItem->thumbnail, 470, 246) : '',
        ];
        return $data;
    }
    private  function lottery_vietlot_detail($oneParent, $oneItem, $page = 1)
    {
        $data['oneItem'] = $oneItem;
        $data['oneParent'] = $oneParent;
        $urlApi = API_DATACENTER . "api/v1/result/getdataresult?api_id=$oneItem->id&limit=3&page=$page";
        $data_json = json_decode($this->callCURL($urlApi), true);
        $data['data_vietlot'] = $data_json['data']['data'];
        $this->breadcrumbs->push('Trang chủ', base_url());
        $this->breadcrumbs->push($oneParent->code, getUrlCategory($oneParent));
        $this->breadcrumbs->push($oneParent->code . ' ' . $oneItem->title, getUrlCategory($oneItem));
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $data['SEO'] = [
            'meta_title' => !empty($oneItem->meta_title) ? $oneItem->meta_title : '',
            'meta_description' => !empty($oneItem->meta_description) ? $oneItem->meta_description : '',
            'meta_keyword' => !empty($oneItem->meta_keyword) ? $oneItem->meta_keyword : '',
            'url' => getUrlCategory($oneItem),
            'is_robot' => !empty($oneItem->is_robot) ? $oneItem->is_robot : '',
            'image' => !empty($oneItem->thumbnail) ? getImageThumb($oneItem->thumbnail, 470, 246) : '',
        ];
        return $data;
    }

    public function game_result($game)
    {
        $this->load->model('game_model');
        $gameModel = new Game_model();
        $data = $gameModel->getResult($game);
        echo $data;
    }
    private function lottery_game($oneItem, $page = 1)
    {
        $this->load->model('game_model');
        $gameModel = new Game_model();
        $data['oneItem'] = $oneItem;
        if ($oneItem->type !== "lottery") show_404();
        /*['SICBO', 'BAUCUA', 'TAIXIU', 'PK10']*/
        switch ($oneItem->code) {
            case "SICBO":
                $game = 'SIC_BO';
                break;
            case "BAUCUA":
                $game = 'HOO_HEY_HOW';
                break;
            case "TAIXIU":
                $game = 'GUESS_BIG_SMALL';
                break;
            case "PK10":
                $game = 'PK_10';
                break;
            case "HANOI":
                $game = 'HANOI_LOTTERY';
                break;
            default:
                $game = '';
        }
        $data['data_current'] = $gameModel->getResultCurrent($game);
        $data['data'] = $gameModel->getDataResult([
            'game' => $game,
            'page' => $page,
            'size' => '20'
        ]);

        $this->breadcrumbs->push('Trang chủ', base_url());
        $this->breadcrumbs->push($oneItem->title, getUrlCategory($oneItem));
        $data['breadcrumb'] = $this->breadcrumbs->show();

        $data['SEO'] = [
            'meta_title' => !empty($oneItem->meta_title) ? $oneItem->meta_title : '',
            'meta_description' => !empty($oneItem->meta_description) ? $oneItem->meta_description : '',
            'meta_keyword' => !empty($oneItem->meta_keyword) ? $oneItem->meta_keyword : '',
            'url' => getUrlCategory($oneItem),
            'is_robot' => !empty($oneItem->is_robot) ? $oneItem->is_robot : '',
            'image' => !empty($oneItem->thumbnail) ? getImageThumb($oneItem->thumbnail, 1200, 650) : '',
        ];
        return $data;
    }
    
    public function tags($slug, $page = 1)
    {
        $data['oneItem']  = $oneItem = $this->_category->getBySlugCached($slug);
        if (empty($oneItem)) show_404();
        $limit = 10;
        $params = [
            'tag_id' => $oneItem->id,
            'page' => $page,
            'limit' => $limit
        ];
        $data['data'] = $this->_post->getDataFE($params);
        $totalPost = $this->_post->getTotalFE($params);
        $this->load->library('pagination');
        $paging['base_url'] = getUrlTag($oneItem);
        $paging['total_rows'] = $totalPost;
        $paging['per_page'] = $limit;
        $paging['cur_page'] = $page;
        $this->pagination->initialize($paging);
        $data['pagination'] = $this->pagination->create_links();
        // end phân Trang

        $this->breadcrumbs->push('Trang chủ', base_url());
        $this->breadcrumbs->push("Tag: " . $oneItem->title, getUrlTag($oneItem));
        $data['breadcrumb'] = $this->breadcrumbs->show();

        $data['SEO'] = [
            'meta_title' => !empty($oneItem->meta_title) ? $oneItem->meta_title : $oneItem->title,
            'meta_description' => !empty($oneItem->meta_description) ? $oneItem->meta_description : $oneItem->description,
            'meta_keyword' => !empty($oneItem->meta_keyword) ? $oneItem->meta_keyword : '',
            'url' => getUrlTag($oneItem),
            'is_robot' => !empty($oneItem->is_robot) ? $oneItem->is_robot : '',
            'image' => !empty($oneItem->thumbnail) ? getImageThumb($oneItem->thumbnail, 470, 246) : '',
        ];
        $data['main_content'] = $this->load->view(TEMPLATE_PATH . 'post/tags', $data, TRUE);
        $this->load->view(TEMPLATE_MAIN, $data);
    }
}
