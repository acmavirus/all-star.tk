<?php
/**
 * Created by PhpStorm.
 * User: ducto
 * Date: 8/9/2018
 * Time: 3:07 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Result extends API_Controller
{
    protected $_data;
    protected $_data_category;
    protected $_all_category;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['category_model', 'result_model']);
        $this->load->helper(['datetime']);
        $this->_data = new Result_model();
        $this->_data_category = new Category_model();
        $this->_all_category = $this->_data_category->_all_category();
    }

    /* getDataByCategory($api_id, $page=1, $limit=1)
        XSMB, XSMT, XSMN sẽ lấy limit = số ngày quay
        các tỉnh thì sẽ lấy limit = số lần quay
    */

    public function getDataByCategory(){
        $dataRaw = (object) $this->input->get();
        if(!empty($dataRaw)){
            $api_id = $dataRaw->api_id;
            if(!isset($api_id)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số "api_id"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $page = !empty($dataRaw->page) ? $dataRaw->page : 1;
            $limit = !empty($dataRaw->limit) ? $dataRaw->limit : 1; /*Biên độ chính là limit ngày kết quả*/

            if (!$api_id){
                $data['data'] = $this->_data->getByCategory(0,$limit,$page);
                $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
                return $this->response->json($dataJson);
            }
            $oneItem = $this->_data_category->getById($api_id);

            if ($oneItem->id == 2 || $oneItem->id == 3) {
                $this->_data_category->_recursive_child_id($this->_data_category->_all_category(),$oneItem->id);
                $listCategory = $this->_data_category->_list_category_child_id;
                $listDate = $this->_data->getListDate($listCategory, $page, $limit);
                $dataApi = $this->_data->getFromListDate($listDate, $listCategory);
            } else {
                $dataApi = $this->_data->getByCategory($api_id,$limit,$page);
            }

            $data['data'] = $dataApi;
            if ($api_id) $data['total'] = $this->_data->getTotalByCategory($api_id);
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    /*getDataByCategoryDayOfWeek($api_id, $dayofweek, $page=1, $limit=7)
        $dayofweek = 0->6 sun->sat
    */
    public function getDataByCategoryDayOfWeek(){
        $dataRaw = (object) $this->input->get();
        if(!empty($dataRaw)){
            $api_id = $id = $dataRaw->api_id;
            if(empty($api_id)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số "api_id"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $d_o_w = $dataRaw->dayofweek + 1;
            if(!($d_o_w >=1 && $d_o_w <=8)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Sai tham số "dayofweek" (0->6)'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $page = !empty($dataRaw->page) ? $dataRaw->page : 1;
            $limit = !empty($dataRaw->limit) ? $dataRaw->limit : 7; /*Biên độ chính là limit ngày kết quả*/
            if(empty($limit)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số giới hạn "limit"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $date_end = !empty($dataRaw->date_end) ? $dataRaw->date_end : date('Y-m-d');
            // date(w)   0->6 sun->sat
            // sql 1->7 sun->sat
            // day prize 1->7 mon->sun

            if ($api_id == 2 || $api_id == 3){
                $dpr = ($d_o_w == 1) ? 7 : ($d_o_w-1);
                $limit = ($this->countCategoryByDOB($api_id,$dpr)*$limit);
                $api_id = $this->_data_category->getListChild($this->_data_category->getAll(),$api_id,'id');
            };
            // $d_w_o = get by date("w")
            if ($d_o_w == 8) $d_o_w = 1;
            $listResult = $this->_data->getByCategoryDayOfWeek($api_id,$d_o_w,$limit,$page, $date_end);

            $data['data'] = $listResult;
            $data['total'] = $this->_data->getTotalByCategory($api_id);
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    /* getFromDayToDay($api_id, $date_begin, $date_end)
        lấy result của tỉnh $api_id từ ngày $date_begin đến ngày $date_end
        nếu muốn lấy 1 ngày cụ thể thì nhập $date_end = $date_begin
    */

    public function getFromDay(){
        $dataRaw = (object) $this->input->get();
        if(!empty($dataRaw)){
            $code = $id = $dataRaw->code;
            if(empty($code)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số "code"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $oneItem = $this->_data_category->getByField('code', strtoupper($code));
            $api_id = $oneItem->id;
            $date_end = date('Y-m-d',strtotime($dataRaw->date));
            $date_begin = date('Y-m-d',strtotime($dataRaw->date));

            if ($api_id == 2 || $api_id == 3){
                $this->_data_category->_recursive_child_id($this->_data_category->_all_category(),$oneItem->id);
                $api_id = $this->_data_category->_list_category_child_id;
            };

            $result = $this->_data->getFromDayToDay($date_begin,$date_end,$api_id);

            $data['data'] = $result;
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function getFromDayToDay(){
        $dataRaw = (object) $this->input->get();
        if(!empty($dataRaw)){
            $api_id =$id= $dataRaw->api_id;
            if(empty($api_id)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số "api_id"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $date_end = date('Y-m-d',strtotime($dataRaw->date_end));
            $date_begin = date('Y-m-d',strtotime($dataRaw->date_begin));

            if ($api_id == 2 || $api_id == 3){
                $api_id = $this->_data_category->getListChild($this->_data_category->getAll(),$api_id,'id');
            };

            $result = $this->_data->getFromDayToDay($date_begin,$date_end,$api_id);

            $data['data'] = $result;
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    /* getDataNearestByDay($api_id, $day = null, $limit =1)
        $day mặc định là hôm nay
        lấy $limit result ngay trước ngày $day.
    */
    public function getDataNearestByDay(){
        $dataRaw = (object) $this->input->get();
        if(!empty($dataRaw)){
            $api_id = $id = $dataRaw->api_id;
            if(empty($api_id)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số "api_id"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            if (!empty($dataRaw->day)) $day = date('Y-m-d',strtotime($dataRaw->day)); else $day = date('Y-m-d');
            if (!empty($dataRaw->limit)) $limit = $dataRaw->limit; else $limit = 1;
            if (!empty($dataRaw->offset)) $offset = $dataRaw->offset; else $offset = 0;

            if ($api_id <= 3){
                $date_begin = date('Y-m-d',strtotime($day. ' -'.($limit-1).' day'));
                $this->_data_category->_recursive_child_id($this->_data_category->_all_category(),$api_id);
                $listCateId = $this->_data_category->_list_category_child_id;

                $oneItem = $this->_data_category->getById($api_id);
                $oneParent = $this->_data_category->_recursive_one_parent($this->_data_category->_all_category(), $oneItem->id);

                if ($day == date('Y-m-d')){
                    $tmp = [1=>19, 2=>18, 3=>16];
                    if (date('H') < $tmp[$oneParent->id]) {
                        $date_begin = date('Y-m-d', strtotime($date_begin.'-1 day'));
                        $day = date('Y-m-d', strtotime($day.'-1 day'));
                    }
                }

                $result = $this->_data->getFromDayToDay($date_begin, $day, $listCateId);
            } else{
                $result = $this->_data->getResultNearest($day, $api_id, $limit, $offset);
            }

            $data['data'] = $result;
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function getAllResult(){
        extract($this->input->get());
        if (empty($limit)) $limit = 1;
        if (empty($page)) $page = 1;
        if (empty($before)) $before = null;
        $data = $this->_data->getAllResult($limit,$page,$before);
        echo json_encode($data);
        exit();
    }

    public function getAllResult2(){
        extract($this->input->get());
        if (empty($limit)) $limit = 1;
        if (empty($page)) $page = 1;
        $data = $this->_data->getAllResult2($limit,$page);
        echo json_encode($data);
        exit();
    }

    public function getAllResultSiteMap888(){
        extract($this->input->get());
        if (empty($limit)) $limit = 1;
        if (empty($page)) $page = 1;
        $data = $this->_data->getAllResultSiteMap888($limit,$page);
        echo json_encode($data);
        exit();
    }

    public function getAllResultMB() {
        extract($this->input->get());
        if (empty($before)) $before = null;
        $data = $this->_data->getAllResultMB($before);
        echo json_encode($data);
        exit();
    }

    public function getLive(){
        $date = date('H');
        $minute = date('Hi');
        $keyCache = "liveResult_{$minute}";
        if ($date == 16) {
            $api_id = 3;
        }
        if ($date == 17) {
            $api_id = 2;
        }
        if ($date == 18) {
            $api_id = 1;
        }

        $day = date('Y-m-d');
        $dataRaw = (object) $this->input->get();
        if(!empty($dataRaw)){
            if (!empty($dataRaw->api_id)) {$api_id = $dataRaw->api_id;};
            if (!empty($dataRaw->day)) {$day = date('Y-m-d',strtotime($dataRaw->day));};
        }

        if (!empty($api_id)){
            $oneItem = $this->_data_category->getById($api_id);
            $code = $oneItem->code;

            if ($api_id == 2 || $api_id == 3){
                $api_id = $this->_data_category->getListChild($this->_data_category->getAll(),$api_id,'id');
            };

            $dataReturn = $this->getCache($keyCache);
            if (empty($dataReturn)){
                $dataReturn = $this->_data->getByLive($day,$api_id);
                if ($api_id > 1){
                    $dataReturn = $this->array_group_by($dataReturn, function ($i) {
                        return $i['code'];
                    });
                }
                $this->setCache($keyCache,$dataReturn,5);
            }

            $dataReturn[$code] = $dataReturn;
            $data['data'] = $dataReturn;
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }


        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function getTotalAllResult()
    {
        $total = $this->_data->getTotalAllResult();
        $data['total'] = $total;
        $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
        return $this->response->json($dataJson);
    }

    public function destroyCache(){
        $key = $this->input->get('key');
        if ($key === 'anchoi365') {
            if ($this->deleteCache())
                echo 'clear all';
            else echo 'false';
        }
    }

    /*Check total category by day of week*/
    private function countCategoryByDOB($id, $dayOfWeek)
    {
        $total = 0;
        if (!empty($this->_all_category)) foreach ($this->_all_category as $k => $item) {
            if (in_array($dayOfWeek, (array)json_decode($item->day_prize)) && $item->parent_id == $id){
                $total ++;
            }
        }
        return $total;
    }

    public function insertBeforeLive() {
        //if (date('H') == 15) die();
        if (date('H') == 15) $api_id = 3;
        elseif (date('H') == 16) $api_id = 2;
        elseif (date('H') == 17) $api_id = 1;
        else $api_id = '';

        if ($api_id == 1) {
            if (date('Ymd') <= 20210214) die();
            $title = 'XSMB ngày '.date('Y-m-d');
            $data = [
                'category_id' => 1,
                'title' => $title,
                'slug' => 'xsmb-'.date('Y-m-d').'.html',
                'displayed_time' => date('Y-m-d'),
                'data_result' => '[["","",""],[""],[""],["",""],["","","","","",""],["","","",""],["","","","","",""],["","",""],["","","",""]]'
            ];
            return $this->saveResult($data);
        } else {
            $listChild = $this->_data_category->getListChildByDOW($api_id, date('N'));
            foreach ($listChild as $item) {
                //covid
                //if (in_array($item->id, [13, 9, 16, 18])) continue;
                $title = 'KQXS '.$item->title.' ngày '.date('Y-m-d');
                $data = [
                    'category_id' => $item->id,
                    'title' => $title,
                    'slug' => $this->toSlug($title),
                    'displayed_time' => date('Y-m-d'),
                    'data_result' => '[[""],[""],["","",""],[""],["","","","","","",""],["",""],[""],[""],[""]]'
                ];
                $this->saveResult($data);
            }
        }
    }
    public function saveResult($data)
    {
        if (empty($data['slug'])) return;
        $this->_data->save($data);
    }

    public function getTotalResult() {
        extract($this->input->get());
        if (empty($year)) $year = 2019;
        $data = $this->_data->getTotalResult($year);
        echo json_encode($data);
        exit();
    }

    public function getNextResult(){
        $category_id = $this->input->get('category_id');
        $date = $this->input->get('date');

        $data = $this->_data->getNextResult($category_id, $date);
        if (!empty($data))
            echo json_encode($data);
        return;
    }
}