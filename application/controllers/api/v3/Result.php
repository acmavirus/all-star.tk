<?php
/**
 * Created by PhpStorm.
 * User: ducto
 * Date: 8/9/2018
 * Time: 3:07 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Result extends STEVEN_Controller
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

            if ($oneItem->id <= 3) {
                $this->_data_category->_recursive_child_id($this->_data_category->_all_category(),$oneItem->id);
                $api_id = $this->_data_category->_list_category_child_id;

                $hour = [
                    'XSMN' => "16:00",
                    'XSMT' => "17:00",
                    'XSMB' => "18:00"
                ];

                $date_end = date('Y-m-d',strtotime("-".($page-1)*$limit." day"));
                if (date('H:i') < $hour[$oneItem->code]) $date_end = date('Y-m-d',strtotime($date_end. ' -1 day'));
                $date_begin = date('Y-m-d',strtotime($date_end." -".($limit-1)." day"));

                $dataApi = $this->_data->getFromDayToDay($date_begin,$date_end,$api_id);
            }

            else {
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
            $d_o_w = $dataRaw->dayofweek+1;
            if(!($d_o_w >=1 && $d_o_w <=8)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Sai tham số "dayofweek" (0->6)'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $page = !empty($dataRaw->page) ? $dataRaw->page : 1;
            $limit = !empty($dataRaw->limit) ? $dataRaw->limit : 7; /*Biên độ chính là limit ngày kết quả*/
            $date_end = !empty($dataRaw->date_end) ? date('Y-m-d', strtotime($dataRaw->date_end)) : '';
            if(empty($limit)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số giới hạn "limit"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            // date(w)   0->6 sun->sat
            // sql 1->7 sun->sat
            // day prize 1->7 mon->sun

            if ($api_id == 2 || $api_id == 3){
                $dpr = ($d_o_w == 1) ? 7 : ($d_o_w-1);
                $limit = ($this->countCategoryByDOB($api_id,$dpr)*$limit);
                $api_id = $this->_data_category->getListChild($this->_data_category->getAll(),$api_id,'id');
            };
            // $d_w_o = get by date("w")
            $listResult = $this->_data->getByCategoryDayOfWeek($api_id,$d_o_w,$limit,$page,$date_end);

            $data['data'] = $listResult;
            $data['total'] = $this->_data->getTotalByCategory($api_id);
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }
    public function getLastResultCategory(){
        $dataRaw = (object) $this->input->get();

        if(!empty($dataRaw)){
            $api_id = $dataRaw->api_id;
            if(empty($api_id)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số "api_id"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');

            if ($api_id == 2 || $api_id == 3){
                if ($api_id == 2){
                    $time = 17;
                }else{
                    $time = 16;
                }
                if(date('H') >= $time) $dayOfWeek = date('w');
                else $dayOfWeek = date('w',strtotime('-1 day'));
                if ($dayOfWeek == 0) $dayOfWeek =7;
                $api_id = $this->_data_category->getListChildByDOW($api_id,$dayOfWeek,'id');
            };
            $data['data'] = $this->_data->getLastResultByCategory($api_id);
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }
    public function getDataDayByPrize(){
        $dataRaw = (object) $this->input->get();
        if(!empty($dataRaw)){
            $api_id = $dataRaw->api_id;
            if(empty($api_id)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số "api_id"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $prize = $dataRaw->prize;
            $page = !empty($dataRaw->page) ? $dataRaw->page : 1;
            $limit = !empty($dataRaw->limit) ? $dataRaw->limit : 7; /*Biên độ chính là limit ngày kết quả*/
            if(empty($limit)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số giới hạn "limit"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $data['data'] = $this->_data->getDataDayByPrize($api_id,$prize,$limit,$page);
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
    public function getFromDayToDay(){
        $dataRaw = (object) $this->input->get();
        if(!empty($dataRaw)){
            $api_id =$id= $dataRaw->api_id;
            if(empty($api_id)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số "api_id"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $date_end = date('Y-m-d',strtotime($dataRaw->date_end));
            $date_begin = date('Y-m-d',strtotime($dataRaw->date_begin));
            $updateCache = (isset($dataRaw->updateCache) && !empty($dataRaw->updateCache)) ? true : false;

            if ($api_id == 2 || $api_id == 3){
                $api_id = $this->_data_category->getListChild($this->_data_category->getAll(),$api_id,'id');
            };

            $result = $this->_data->getFromDayToDay($date_begin,$date_end,$api_id,$updateCache);

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

            if (in_array($api_id, [2, 3])){
                $date_begin = date('Y-m-d',strtotime($day. ' -'.($limit-1).' day'));
                $this->_data_category->_recursive_child_id($this->_data_category->_all_category(),$api_id);
                $listCateId = $this->_data_category->_list_category_child_id;
                $result = $this->_data->getFromDayToDay($date_begin,$day,$listCateId);
            } else{
                $result = $this->_data->getResultNearest($day,$api_id,$limit,$offset);
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
        $data = $this->_data->getAllResult($limit,$page);
        echo json_encode($data);
        exit();
    }
    public function getTotalResult() {
        extract($this->input->get());
        if (empty($year)) $year = 2019;
        $data = $this->_data->getTotalResult($year);
        echo json_encode($data);
        exit();
    }
    public function getAllResultById() {
        extract($this->input->get());
        if (empty($id)) $id = 1;
        $data = $this->_data->getAllResultById($id);
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

}