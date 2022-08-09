<?php
/**
 * Created by PhpStorm.
 * User: ducto
 * Date: 8/9/2018
 * Time: 3:07 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Statistic_loto extends API_Controller
{
    protected $_data;
    protected $_data_category;
    protected $_lang_code;
    protected $_all_category, $_loto;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['category_model', 'result_model','loto_model']);
        $this->_data = new Result_model();
        $this->_data_category = new Category_model();
        $this->_loto = new Loto_model();
        $this->_all_category = $this->_data_category->_all_category();
        $this->_lang_code = $this->session->userdata('public_lang_code');
    }

    public function gan_dau_duoi_tong() {
        $input = $this->input->get();
        if (empty($input['code'])) $input['code'] = 'xsmb';
        if (empty($input['dateEnd'])){
            $input['dateEnd'] = date('Y-m-d');
        } else {
            $input['dateEnd'] = str_replace('/', '-', $input['dateEnd']);
        }
        $keyCache = "gan_dau_duoi_tong_".md5(json_encode($input));
        $data = $this->getCache($keyCache);
        if (empty($data)) {
            $dataResult = $this->_data->getResultNearest($input['dateEnd'], 1, 0);
            $dataResult = array_filter($dataResult, function ($item) {
                return !empty($item['data_result']) && $item['data_result'] != '[["...","...","..."],["..."],["..."],["...","..."],["...","...","...","...","...","..."],["...","...","...","..."],["...","...","...","...","...","..."],["...","...","..."],["...","...","...","..."]]';
            });
            $dataResult = array_map(function ($item) {
                $db = $item['data_result'];
                $db = explode('"]', $db);
                $db = substr($db[1], -2);
                return [
                    'displayed_time' => $item['displayed_time'],
                    'db' => $db
                ];
            }, $dataResult);
            $data = [];
            foreach ($dataResult as $item) {
                $dau = substr($item['db'], 0, 1);
                $duoi = substr($item['db'], -1, 1);
                $tong = substr($dau + $duoi, -1, 1);

                if (empty($data['dau'][$dau])) {
                    $data['dau'][$dau]['gan'] = $data['dau'][$dau]['max_gan'] = (strtotime($input['dateEnd']) - strtotime($item['displayed_time'])) / 86400;
                    $data['dau'][$dau]['date_gan'] = $data['dau'][$dau]['displayed_time'] = $item['displayed_time'];
                } else {
                    $gan = (strtotime($data['dau'][$dau]['displayed_time']) - strtotime($item['displayed_time'])) / 86400;
                    if ($gan > $data['dau'][$dau]['max_gan']) $data['dau'][$dau]['max_gan'] = $gan;
                    $data['dau'][$dau]['displayed_time'] = $item['displayed_time'];
                }

                if (empty($data['duoi'][$duoi])) {
                    $data['duoi'][$duoi]['gan'] = $data['duoi'][$duoi]['max_gan'] = (strtotime($input['dateEnd']) - strtotime($item['displayed_time'])) / 86400;
                    $data['duoi'][$duoi]['date_gan'] = $data['duoi'][$duoi]['displayed_time'] = $item['displayed_time'];
                } else {
                    $gan = (strtotime($data['duoi'][$duoi]['displayed_time']) - strtotime($item['displayed_time'])) / 86400;
                    if ($gan > $data['duoi'][$duoi]['max_gan']) $data['duoi'][$duoi]['max_gan'] = $gan;
                    $data['duoi'][$duoi]['displayed_time'] = $item['displayed_time'];
                }

                if (empty($data['tong'][$tong])) {
                    $data['tong'][$tong]['gan'] = $data['tong'][$tong]['max_gan'] = (strtotime($input['dateEnd']) - strtotime($item['displayed_time'])) / 86400;
                    $data['tong'][$tong]['date_gan'] = $data['tong'][$tong]['displayed_time'] = $item['displayed_time'];
                } else {
                    $gan = (strtotime($data['tong'][$tong]['displayed_time']) - strtotime($item['displayed_time'])) / 86400;
                    if ($gan > $data['tong'][$tong]['max_gan']) $data['tong'][$tong]['max_gan'] = $gan;
                    $data['tong'][$tong]['displayed_time'] = $item['displayed_time'];
                }

                if (empty($data['cham'][$dau])) {
                    $data['cham'][$dau]['gan'] = $data['cham'][$dau]['max_gan'] = (strtotime($input['dateEnd']) - strtotime($item['displayed_time'])) / 86400;
                    $data['cham'][$dau]['date_gan'] = $data['cham'][$dau]['displayed_time'] = $item['displayed_time'];
                } else {
                    $gan = (strtotime($data['cham'][$dau]['displayed_time']) - strtotime($item['displayed_time'])) / 86400;
                    if ($gan > $data['cham'][$dau]['max_gan']) $data['cham'][$dau]['max_gan'] = $gan;
                    $data['cham'][$dau]['displayed_time'] = $item['displayed_time'];
                }

                if (empty($data['cham'][$duoi])) {
                    $data['cham'][$duoi]['gan'] = $data['cham'][$duoi]['max_gan'] = (strtotime($input['dateEnd']) - strtotime($item['displayed_time'])) / 86400;
                    $data['cham'][$duoi]['date_gan'] = $data['cham'][$duoi]['displayed_time'] = $item['displayed_time'];
                } else {
                    $gan = (strtotime($data['cham'][$duoi]['displayed_time']) - strtotime($item['displayed_time'])) / 86400;
                    if ($gan > $data['cham'][$duoi]['max_gan']) $data['cham'][$duoi]['max_gan'] = $gan;
                    $data['cham'][$duoi]['displayed_time'] = $item['displayed_time'];
                }
            }
            for ($i = 0; $i <= 9; $i++) {
                unset($data['dau'][$i]['displayed_time']);
                unset($data['duoi'][$i]['displayed_time']);
                unset($data['tong'][$i]['displayed_time']);
                unset($data['cham'][$i]['displayed_time']);
            }
            $this->setCache($keyCache, $data, 3600);
        }
        return $this->response->json($data);
    }

    public function gan_dau_duoi_tong_2() {
        $input = $this->input->get();
        if (empty($input['api_id'])) $input['api_id'] = 1;
        if (empty($input['dateEnd'])){
            $input['dateEnd'] = date('Y-m-d');
        } else {
            $input['dateEnd'] = str_replace('/', '-', $input['dateEnd']);
        }
        if (empty($input['dateBegin'])){
            $input['dateBegin'] = date('Y-m-d');
        } else {
            $input['dateBegin'] = str_replace('/', '-', $input['dateBegin']);
        }
        $keyCache = "gan_dau_duoi_tong_2".md5(json_encode($input));
        $data = $this->getCache($keyCache);
        if (empty($data)) {
            $dataResult = $this->_data->getFromDayToDay($input['dateBegin'], $input['dateEnd'], $input['api_id']);
            $dataResult = array_filter($dataResult, function ($item) {
                return !empty($item['data_result']) && $item['data_result'] != '[["...","...","..."],["..."],["..."],["...","..."],["...","...","...","...","...","..."],["...","...","...","..."],["...","...","...","...","...","..."],["...","...","..."],["...","...","...","..."]]';
            });
            $dataResult = array_map(function ($item) {
                $db = $item['data_result'];
                $db = getLoto($db);
                $db = ($item['category_id'] == 1) ? $db[0] : end($db);

                return [
                    'displayed_time' => $item['displayed_time'],
                    'db' => $db
                ];
            }, $dataResult);
            $data = [];
            foreach ($dataResult as $item) {
                $dau = substr($item['db'], 0, 1);
                $duoi = substr($item['db'], -1, 1);
                $tong = substr($dau + $duoi, -1, 1);

                if (empty($data['dau'][$dau])) {
                    $data['dau'][$dau]['gan'] = $data['dau'][$dau]['max_gan'] = (strtotime($input['dateEnd']) - strtotime($item['displayed_time'])) / 86400;
                    $data['dau'][$dau]['date_gan'] = $data['dau'][$dau]['displayed_time'] = $item['displayed_time'];
                } else {
                    $gan = (strtotime($data['dau'][$dau]['displayed_time']) - strtotime($item['displayed_time'])) / 86400;
                    if ($gan > $data['dau'][$dau]['max_gan']) $data['dau'][$dau]['max_gan'] = $gan;
                    $data['dau'][$dau]['displayed_time'] = $item['displayed_time'];
                }

                if (empty($data['duoi'][$duoi])) {
                    $data['duoi'][$duoi]['gan'] = $data['duoi'][$duoi]['max_gan'] = (strtotime($input['dateEnd']) - strtotime($item['displayed_time'])) / 86400;
                    $data['duoi'][$duoi]['date_gan'] = $data['duoi'][$duoi]['displayed_time'] = $item['displayed_time'];
                } else {
                    $gan = (strtotime($data['duoi'][$duoi]['displayed_time']) - strtotime($item['displayed_time'])) / 86400;
                    if ($gan > $data['duoi'][$duoi]['max_gan']) $data['duoi'][$duoi]['max_gan'] = $gan;
                    $data['duoi'][$duoi]['displayed_time'] = $item['displayed_time'];
                }

                if (empty($data['tong'][$tong])) {
                    $data['tong'][$tong]['gan'] = $data['tong'][$tong]['max_gan'] = (strtotime($input['dateEnd']) - strtotime($item['displayed_time'])) / 86400;
                    $data['tong'][$tong]['date_gan'] = $data['tong'][$tong]['displayed_time'] = $item['displayed_time'];
                } else {
                    $gan = (strtotime($data['tong'][$tong]['displayed_time']) - strtotime($item['displayed_time'])) / 86400;
                    if ($gan > $data['tong'][$tong]['max_gan']) $data['tong'][$tong]['max_gan'] = $gan;
                    $data['tong'][$tong]['displayed_time'] = $item['displayed_time'];
                }
            }
            for ($i = 0; $i <= 9; $i++) {
                unset($data['dau'][$i]['displayed_time']);
                unset($data['duoi'][$i]['displayed_time']);
                unset($data['tong'][$i]['displayed_time']);
            }
            $this->setCache($keyCache, $data, 3600);
        }
        return $this->response->json($data);
    }

    public function logan() {
        $dataRaw = (object) $this->input->get();
        if(!empty($dataRaw)){
            $code = $dataRaw->code;
            if(empty($code)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số mã tỉnh "code"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            if (!empty($dataRaw->date_end)){
                $dataRaw->date_end = str_replace('/', '-', $dataRaw->date_end);
                $date_end = date('Y-m-d',strtotime($dataRaw->date_end));
            } else $date_end = date('Y-m-d');
            if(empty($date_end))
                $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số ngày "date_end"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $bien_do = $dataRaw->bien_do;
            if(empty($bien_do))
                $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số biên độ "bien_do"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            #
            if (is_array($code)){
                foreach ($code as $itemCode){
                    $data[$itemCode] = $this->_loto->logan($itemCode, $date_end, $bien_do);
                }
            } else {
                $data = $this->_loto->logan($code, $date_end, $bien_do);
            }

            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function loganxsp() {
        $input = $this->input->get();
        extract($input);
        if (empty($code)) die('Thieu tham so code'); else $code = strtolower($code);
        if (empty($dateEnd)) $dateEnd = date('Y-m-d');
        if (empty($limit)) $limit = 10;
        if (empty($dacBiet)) $dacBiet = 0;
        else $dacBiet = 1;

        $key = "logan-$code-$dateEnd-lm$limit-$dacBiet";
        $data = $this->getCache($key);
        if (empty($data)){
            $oneItem = $this->_data_category->getByCode($code);
            if (in_array($oneItem->id, [2, 3])) {
                $this->_data_category->_recursive_child_id($this->_data_category->_all_category(),$oneItem->id);
                $category_id = $this->_data_category->_list_category_child_id;
            } else {
                $category_id = $oneItem->id;
            }

            if ($oneItem->parent_id == 1) {
                $day_prize = $oneItem->day_prize;
                $dataResult = $this->_data->getResultNearest($dateEnd, 1, 0, 0, '', $day_prize);
            } else {
                $dataResult = $this->_data->getResultNearest($dateEnd,$category_id,0);
            }

            if ($dacBiet){
                foreach ($dataResult as $k => $i){
                    $rs = json_decode($i['data_result']);
                    $dataResult[$k]['data_result'] = json_encode($rs[1]);
                }
            }

            $dataResult = array_reverse($dataResult);
            $topGan = $this->getLogan($dataResult,$limit);
            usort($topGan, function($a, $b) {
                return $b['count'] - $a['count'];
            });
            $data['data_top'] = $topGan;
            $data['data_list_number'] = $this->getLogan($dataResult);
            $data = json_encode($data);
            $this->setCache($key,$data,60*10);
        }
        echo $data;
        exit();
    }

    public function tansuat_loto(){
        $dataRaw = (object) $this->input->get();

        if(!empty($dataRaw)){
            $code = strtolower($dataRaw->code);

            if (!empty($dataRaw->date_end)){
                $dataRaw->date_end = str_replace('/', '-', $dataRaw->date_end);
                $dateEnd = date('Y-m-d', strtotime($dataRaw->date_end));
            } else {
                $dateEnd = date('Y-m-d');
            }

            $limit = $dataRaw->limit;

            if (!empty($dataRaw->is_special))
                $db = 1;
            else
                $db = 0;

            if (!empty($dataRaw->dayofweek))
                $dayofweek = $dataRaw->dayofweek;
            #
            $code = strtolower($code);

            $oneItem = $this->_data_category->getByCode($code);
            $listId = $oneItem->id;
            if($oneItem->parent_id == 0){
                $this->_data_category->_recursive_child_id($this->_all_category,$oneItem->id);
                $listId = $this->_data_category->_list_category_child_id;
            }

            $code = strtolower($code);
            $arr_provinceMB = [
                'xstb' => '1',
                'xshn' => '2, 5',
                'xsqn' => '3',
                'xsbn' => '4',
                'xshp' => '6',
                'xsnd' => '7',
            ];

            if (!empty($dayofweek)){
                $dataResult = $this->_data->getByCategoryDayOfWeek($listId, $dayofweek, $limit, 1, $dateEnd);
            } elseif (isset($arr_provinceMB[$code])){
                $dataResult = $this->_data->getDataDayByPrize(1, $arr_provinceMB[$code], $limit);
            } else {
                $dataResult = $this->_data->getResultNearest($dateEnd, $listId, $limit);
            }

            $data['data'] = $this->getTanSuatLoto($dataResult, $code, $db);
            $listDb = [];
            foreach ($dataResult as $item){
                $result = json_decode($item['data_result'],true);
                if (count($result[0]) == 1){
                    $num = substr($result[0][0],-2,2);
                } else $num = substr($result[1][0],-2,2);
                $listDb[] = $num;
                $listDate[] = $item['displayed_time'];
            }
            if (!empty($db)) $listDb = array_slice($listDb, 0, 1);
            $listDb = array_count_values($listDb);
            $data['listDb'] = $listDb;
            $data['listDate'] = $listDate;

            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function thongkedbvenhieunhat()
    {
        $this->checkRequestPostAjax();
        $code = $this->input->post('code');
        if($this->_data_category->getByCode($code) == null){$code = 'xsmb';}
        $new_data['onecat'] = $oneCat = $this->_data_category->getByCode($code);
        $time = $this->input->post('time');

        $CacheKey = 'TK_dbvnn_'.$code.'_'.$time;
        if (!empty($this->getCache($CacheKey))){
            echo $this->getCache($CacheKey);
        }else {
            if (date('H') > 16) {
                $new_data['end'] = $end = date('Y-m-d', strtotime("+0 days"));
            } else {
                $new_data['end'] = $end = date('Y-m-d', strtotime("-1 days"));
            }
            $daypriz = ($time / count(json_decode($oneCat->day_prize))) * 7;
            $new_data['begin'] = $begin = date('Y-m-d', strtotime("-" . ($time) . " days"));
            if ($code != 'xsmb') {
                $new_data['begin'] = $begin = date('Y-m-d', strtotime("-" . ($daypriz) . " days"));
            }
            $post_field = array(
                'code' => "$code",
                'date_begin' => "$begin",
                'date_end' => "$end"
            );
            $data = $this->post_API_xoso('http://api.xsradar.com/statistic_dacbiet/getByMonth', $post_field);
            $data = getTableResult($data);
            $new_data = array_reverse($data['data']['data']);
            $new2so = [];
            $new_text = '';
            foreach ($new_data as $k => $value) {
                $so2 = substr($value, 3, 2);
                $new_text .= '-' . $so2 . '-';
                if (substr_count($new_text, $so2) == 1) {
                    array_push($new2so, substr($value, 3, 2));
                }
            }
            $newk2 = [];
            foreach ($new2so as $value) {
                $count = substr_count($new_text, $value);
                array_push($newk2, ['number' => $value, 'count' => $count]);
            }
            usort($newk2, function ($a, $b) {
                return $a['count'] < $b['count'] ? 1 : -1;
            });
            $new_data['data'] = $newk2;
            $new_data['onecat'] = $oneCat = $this->_data_category->getByCode($code);
            $new_html = $this->load->view($this->template_path . '/statistic/thong_ke_db_ve_nhieu_nhat', $new_data, TRUE);
            $this->setCache($CacheKey,$new_html);
            echo $new_html;
        }
    }

    public function dauduoi_loto(){
        $dataRaw = (object) $this->input->get();
        if(!empty($dataRaw)){
            $code = strtolower($dataRaw->code);
            if(empty($code)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số mã tỉnh "code"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');

            $dataRaw->date_begin = str_replace('/', '-', $dataRaw->date_begin);
            $dateBegin = date('Y-m-d',strtotime($dataRaw->date_begin));
            if(empty($dateBegin)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số biên độ "bien_do"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');

            $dataRaw->date_end = str_replace('/', '-', $dataRaw->date_end);
            $dateEnd = date('Y-m-d',strtotime($dataRaw->date_end));
            if(empty($dateEnd)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số biên độ "bien_do"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');

            $oneItem = $this->_data_category->getByCode($code);
            $this->_data_category->_recursive_child_id($this->_data_category->_all_category(),$oneItem->id);
            if ($oneItem->parent_id == 0)
                $dataResult = $this->_data->getFromDayToDay($dateBegin,$dateEnd,$this->_data_category->_list_category_child_id);
            else{
                $limit = intval((strtotime($dateEnd) - strtotime($dateBegin))/86400);
                if ($oneItem->parent_id == 1) {
                    $day_prize = $oneItem->day_prize;
                    $dataResult = $this->_data->getResultNearest($dateEnd, 1, $limit, 0, '', $day_prize);
                } else {
                    $dataResult = $this->_data->getResultNearest($dateEnd,$this->_data_category->_list_category_child_id,$limit);
                }
            }
            uasort($dataResult, function ($a,$b){
                return $a['displayed_time'] < $b['displayed_time'];
            });

            if (!empty($dataResult)) foreach ($dataResult as $item) {
                $numSumArr[] = $this->getTongDauDuoiLotoOneResult($item);
            }
            $data = $numSumArr;
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    /*model done*/
    public function loxien2(){
        $dataRaw = (object) $this->input->get();
        if(!empty($dataRaw)){
            $code = strtolower($dataRaw->code);
            if(empty($code)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số mã tỉnh "code"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $limit = $dataRaw->limit;
            if(empty($limit)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số lần quay "limit"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');

            $dataRaw->date_end = str_replace('/', '-', $dataRaw->date_end);
            $dateEnd = date('Y-m-d',strtotime($dataRaw->date_end));
            if(empty($dateEnd)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số ngày "date"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');

            $data['data'] = $this->_loto->loxien2($code,$limit,$dateEnd);

            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    /*model done*/
    public function loxien3(){
        $dataRaw = (object) $this->input->get();
        if(!empty($dataRaw)){
            $code = strtolower($dataRaw->code);
            if(empty($code)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số mã tỉnh "code"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $limit = $dataRaw->limit;
            if(empty($limit)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số lần quay "limit"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');

            $dataRaw->date_end = str_replace('/', '-', $dataRaw->date_end);
            $dateEnd = date('Y-m-d',strtotime($dataRaw->date_end));
            if(empty($dateEnd)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số ngày "date"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');

            $data['data'] = $this->_loto->loxien3($code,$limit,$dateEnd);

            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function loxien2thinh(){
        $dataRaw = (object) $this->input->get();
        if(!empty($dataRaw)){
            $code = strtolower($dataRaw->code);
            if(empty($code)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số mã tỉnh "code"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $limit = $dataRaw->limit;
            if(empty($limit)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số lần quay "limit"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');

            $dataRaw->date_end = str_replace('/', '-', $dataRaw->date_end);
            $dateEnd = date('Y-m-d',strtotime($dataRaw->date_end));
            if(empty($dateEnd)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số ngày "date"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');

            $oneItem = $this->_data_category->getByCode($code);

            $dataResult = $this->_data->getResultNearest($dateEnd,$oneItem->id,$limit);

            $data['data'] = $this->getLoXien($dataResult,2);
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function loxien3thinh(){
        $dataRaw = (object) $this->input->get();
        if(!empty($dataRaw)){
            $code = strtolower($dataRaw->code);
            if(empty($code)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số mã tỉnh "code"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $limit = $dataRaw->limit;
            if(empty($limit)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số lần quay "limit"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');

            $dataRaw->date_end = str_replace('/', '-', $dataRaw->date_end);
            $dateEnd = date('Y-m-d',strtotime($dataRaw->date_end));
            if(empty($dateEnd)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số ngày "date"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');

            $oneItem = $this->_data_category->getByCode($code);

            $dataResult = $this->_data->getResultNearest($dateEnd,$oneItem->id,$limit);
            $data['data'] = $this->getLoXien($dataResult,3);
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function loxien2_dow(){
        $dataRaw = (object) $this->input->get();
        if(!empty($dataRaw)){
            $code = strtolower($dataRaw->code);
            if(empty($code)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số mã tỉnh "code"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $limit = $dataRaw->limit;
            if(empty($limit)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số lần quay "limit"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $dow = $dataRaw->dow;
            if(!isset($dataRaw->dow)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số dow'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');

            $oneItem = $this->_data_category->getByCode($code);

            $dow ++;
            $dataResult = $this->_data->getByCategoryDayOfWeek($oneItem->id, $dow, $limit);

            $data['data'] = $this->getLoXien($dataResult, 2);
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function loxien3_dow(){
        $dataRaw = (object) $this->input->get();
        if(!empty($dataRaw)){
            $code = strtolower($dataRaw->code);
            if(empty($code)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số mã tỉnh "code"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $limit = $dataRaw->limit;
            if(empty($limit)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số lần quay "limit"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $dow = $dataRaw->dow;
            if(!isset($dataRaw->dow)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số dow'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');

            $oneItem = $this->_data_category->getByCode($code);

            $dow ++;
            $dataResult = $this->_data->getByCategoryDayOfWeek($oneItem->id, $dow, $limit);

            $data['data'] = $this->getLoXien($dataResult, 3);
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    /*model done*/
    public function lokep(){
        $dataRaw = (object) $this->input->get();
        if(!empty($dataRaw)){
            $code = strtolower($dataRaw->code);
            if(empty($code)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số mã tỉnh "code"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $limit = $dataRaw->limit;
            if(empty($limit)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số lần quay "limit"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');

            if (!empty($dataRaw->dateEnd)) {
                $dataRaw->dateEnd = str_replace('/', '-', $dataRaw->dateEnd);
                $dateEnd = date('Y-m-d', strtotime($dataRaw->dateEnd));
            } else {
                $dateEnd = date('Y-m-d');
            }

            $data['data_kep'] = $this->_loto->lokep($code,$limit,$dateEnd);
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function lokepdon(){
        $dataRaw = (object) $this->input->get();
        if(!empty($dataRaw)){
            $code = strtolower($dataRaw->code);
            if(empty($code)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số mã tỉnh "code"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $limit = $dataRaw->limit;
            if(empty($limit)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số lần quay "limit"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');

            if (!empty($dataRaw->dateEnd)) {
                $dataRaw->dateEnd = str_replace('/', '-', $dataRaw->dateEnd);
                $dateEnd = date('Y-m-d', strtotime($dataRaw->dateEnd));
            } else {
                $dateEnd = date('Y-m-d');
            }

            $data = $this->_loto->lokepdon($code,$limit,$dateEnd);
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function lotong(){
        $dataRaw = (object) $this->input->get();
        if(!empty($dataRaw)){
            $code = strtolower($dataRaw->code);
            if(empty($code)) {
                $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số mã tỉnh "code"'));
                $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            }
            $sum = '';
            if(isset($dataRaw->sum)) {
                $sum = $dataRaw->sum;
            }

            if(empty($dataRaw->date_begin)) {
                $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số biên độ "date_begin"'));
                $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            } else {
                $dataRaw->date_begin = str_replace('/', '-', $dataRaw->date_begin);
                $dateBegin = date('Y-m-d',strtotime($dataRaw->date_begin));
            }

            if(empty($dataRaw->date_end)) {
                $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số biên độ "date_end"'));
                $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            } else {
                $dataRaw->date_end = str_replace('/', '-', $dataRaw->date_end);
                $dateEnd = date('Y-m-d',strtotime($dataRaw->date_end));
            }
            $is_special = !empty($dataRaw->is_special) ? true : false;

            $data = $this->_loto->lotong($code,$sum,$dateEnd,$dateBegin,$is_special);

            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function lotong2(){
        $dataRaw = (object) $this->input->get();
        if(!empty($dataRaw)){
            $code = strtolower($dataRaw->code);
            if(empty($code)) {
                $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số mã tỉnh "code"'));
                $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            }
            $sum = '';
            if(isset($dataRaw->sum)) {
                $sum = $dataRaw->sum;
            }

            $limit = '';
            if(isset($dataRaw->limit)) {
                $limit = $dataRaw->limit;
            }

            if(empty($dataRaw->date_end)) {
                $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số biên độ "date_end"'));
                $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            } else {
                $dataRaw->date_end = str_replace('/', '-', $dataRaw->date_end);
                $dateEnd = date('Y-m-d',strtotime($dataRaw->date_end));
            }
            $is_special = !empty($dataRaw->is_special) ? true : false;

            $data = $this->_loto->lotong2($code,$sum,$dateEnd,$limit,$is_special);

            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    private function getTongDauDuoiLoto($data,$sumCheck,$oneParent = null,$is_special = false){
        $numArr = [];
        for ($i = 0; $i <= 99; $i++) {
            $num = sprintf("%'02d",$i);
            $head = substr($num,0,1);
            $tail = substr($num,-1);
            $sum = $head + $tail;
            $totalHeadTail = substr($sum,-1);
            if($sumCheck != '' && $totalHeadTail != $sumCheck) continue;
            $dateShow = '';
            $countNoShow = 0;
            $countShow = 0;
            $tmp = [];
            $tmpGan = [];

            if (!empty($data)) foreach ($data as $item) {
                $dateDisplay = $item['displayed_time'];
                $result = $item['data_result'];
                if($is_special == true){
                    $resultArr = json_decode($result,true);
                    if(!empty($oneParent) && $oneParent->layout !== 'mt_mn'){
                        $result = json_encode($resultArr[1]);
                    }else{
                        $result = json_encode($resultArr[0]);
                    }
                }
                if($this->checkNumberShow($result,$num) == false) {
                    $countNoShow++;
                }else{
                    //$countShow = $this->countNumberShow($result,$num)+1;
                    $countShow = $countShow + $this->countNumberShow($result,$num);
                    $tmpGan[] = $countNoShow + $this->countNumberShow($result,$num);
                    $countNoShow = 0;
                    $dateShow = $dateDisplay;
                }
            }
            $maxGan = !empty($tmpGan) ? max($tmpGan) : 0;
            $tmp['count'] = $countShow;
            $tmp['max_gan'] = $maxGan;
            $tmp['date'] = $dateShow;
            $tmp['number'] = $num;
            $tmp['sum'] = $totalHeadTail;
            $numArr[] = $tmp;
        }


        $dataReturn = $this->array_group_by($numArr, function ($i) {
            return $i['sum'];
        });
        return $dataReturn;
    }

    private function getTongDauDuoiLotoOneResult($oneResult){
        $numSumArr = [];
        $numHeadArr = [];
        $numTailArr = [];
        $result = json_decode($oneResult['data_result'],true);
        for ($i = 0; $i <= 9; $i++) {
            $tmp = [];
            $tmpHead = [];
            $tmpTail = [];
            if (!empty($result)) foreach ($result as $item) {
                foreach ($item as $number) {
                    $num = substr($number, -2);
                    if(is_numeric($num)){
                        $head = substr($num, 0, 1);
                        $tail = substr($num, -1);
                        $sum = $head + $tail;
                        $totalHeadTail = substr($sum, -1);
                        if($totalHeadTail == $i) {
                            $tmp[] = $num;
                        }

                        if($head == $i) {
                            $tmpHead[] = $num;
                        }

                        if($tail == $i) {
                            $tmpTail[] = $num;
                        }
                    }
                }
            }
            $numSumArr[$i] = $tmp;
            $numHeadArr[$i] = $tmpHead;
            $numTailArr[$i] = $tmpTail;
        }
        return [
            'date' => $oneResult['displayed_time'],
            'data_sum' => $numSumArr,
            'data_head' => $numHeadArr,
            'data_tail' => $numTailArr,

        ];
    }

    private function getLoKep($data){
        $numArr = [];
        $checkDuplicate = [];
        for ($i = 0; $i <= 99; $i++) {
            for ($j = 0; $j <= 99; $j++) {
                $num1 = sprintf("%'02d",$i);
                $num1head = substr($num1, 0, 1);
                $num1tail = substr($num1, -1);
                $num2 = sprintf("%'02d",$j);
                $num2head = substr($num2, 0, 1);
                $num2tail = substr($num2, -1);
                $capSo = $num1 . ' - ' . $num2;
                $capSoNguoc = $num2 . ' - ' . $num1;
                if($num1 != $num2 && $num1head == $num1tail && $num2head == $num2tail && !in_array($capSoNguoc,$checkDuplicate)){
                    $countNoShow = 0;
                    $songayve = [];
                    if (!empty($data)) foreach ($data as $item) {
                        $dateDisplay = $item['displayed_time'];
                        $result = $item['data_result'];
                        if($this->checkNumberShow($result,$num1) == true && $this->checkNumberShow($result,$num2) == true){
                            $countNoShow++;
                            $songayve[] = $dateDisplay;
                        }
                    }
                    if($countNoShow > 0){
                        $checkDuplicate[] = $capSo;
                        $tmp['cap_so'] = $capSo;
                        $tmp['count'] = $countNoShow;
                        $tmp['date'] = $songayve;
                        $numArr[] = $tmp;
                    }
                }
            }
        }
        usort($numArr, function($a, $b) {
            return $b['count'] - $a['count'];
        });

        return $numArr;
    }

    private function getLoXien($data,$size = 2){
        $toHop2Arr = [];
        $tmpListResult = [];
        if (!empty($data)) foreach ($data as $item) {
            $dateDisplay = $item['displayed_time'];
            $dataResult = json_decode($item['data_result'],true);
            unset($dataResult[0]);//Lo xien chỉ có miền bắc bỏ cái mã giải đặc biệt
            $result = $this->convertResultTo2number($dataResult);
            $listDataXien2 = $this->combinLoxien($result,$size);
            $listDataXien2 = array_unique($listDataXien2);
            $tmpListResult[$dateDisplay] = json_encode($listDataXien2);
            array_push($toHop2Arr,$listDataXien2);
        }
        $allDataResult = array_merge(...$toHop2Arr);

        $numArr = array_count_values($allDataResult);
        arsort($numArr);
        $numArr = array_slice($numArr,0,50);
        $numArrClearDuplicate = $this->clearToHopDuplicate($numArr);

        $dataNew = [];
        if(!empty($numArrClearDuplicate)) foreach ($numArrClearDuplicate as $coupleNumber => $count){
            $tmp = [];
            if(!empty($tmpListResult)) foreach ($tmpListResult as $date => $itemResult){
                if($this->checkNumberCombinShow($itemResult,$coupleNumber)){
                    $tmp[] = $date;
                }
            }
            $tmpCount['count'] = $count;
            $tmpCount['list_date'] = $tmp;
            $dataNew[$coupleNumber] = $tmpCount;
            unset($tmp);
        }
        return $dataNew;
    }
    private function clearToHopDuplicate($data){
        $tmp = [];
        if(!empty($data)) foreach ($data as $numberCouple => $count){
            $arrNum = explode('_',$numberCouple);
            arsort($arrNum);
            $sorted_num = implode('_',$arrNum);
            if (!in_array($sorted_num, $tmp)) {
                $tmp[] = $sorted_num;
            } else {
                unset($data[$numberCouple]);
            }
        }
        return $data;
    }

    private function unique_multidim_array($array, $key) {
        $temp_array = array();
        $i = 0;
        $key_array = array();

        foreach($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }
    private function combinLoxien($chars, $size, $combinations = array()) {
        if (empty($combinations)) {
            $combinations = $chars;
        }
        if ($size == 1) {
            return $combinations;
        }

        $new_combinations = array();

        foreach ($combinations as $combination) {
            $arrCombin = explode('_',$combination);
            foreach ($chars as $char) {
                if(in_array($char,$arrCombin) != TRUE) {
                    $new_combinations[] = "{$combination}_{$char}";
                }
            }
        }
        return $this->combinLoxien($chars, $size - 1, $new_combinations);
    }

    private function convertResultTo2number($a){
        $aNew = [];
        if(!empty($a)) foreach ($a as $item){
            if(is_array($item)) foreach ($item as $item2){
                $aNew[] = substr($item2,-2);
            }
        }

        return array_unique($aNew);
    }


    private function getTanSuatLoto($data, $code = '', $db = 0){
        $numArr = [];
        for ($i = 0; $i <= 99; $i++) {
            $numOther = 0;
            $num = sprintf("%'02d",$i);
            $dateShow = [];
            $tmp = [];
            if (!empty($data)) foreach ($data as $item) {
                $dateDisplay = $item['displayed_time'];
                $result = $item['data_result'];
                if ($db == 1) {
                    $numbers = json_decode($result, true);
                    if ($code == 'xsmb') {
                        $number_db = $numbers[1][0];
                    } else {
                        $number_db = $numbers[8][0];
                    }
                    if ($num == substr($number_db, -2, 2)) {
                        $dateShow[$dateDisplay] = 1;
                    }
                } else {
                    $countShow = $this->countNumberShow($result, $num, $dateDisplay);
                    if ($countShow > 0) if (!empty($dateShow[$dateDisplay])) {
                        $dateShow[$dateDisplay] = $dateShow[$dateDisplay] + $countShow;
                        $numOther = $numOther + $countShow;
                    } else $dateShow[$dateDisplay] = $countShow;
                    $numOther = $numOther + $countShow;
                }
            }
            $tmp['date'] = $dateShow;
            $tmp['count'] = count($dateShow);
            $tmp['sum'] = array_sum($dateShow);
            $tmp['number'] = $num;
            $numArr[] = $tmp;
        }
        return $numArr;
    }

    private function getLogan($data,$bien_do = 0) {
        $numArr = [];
        for ($i = 0; $i <= 99; $i++) {
            $num = sprintf("%'02d",$i);
            $gan = 0;
            $maxGan = 0;
            $date = null;
            $maxGanDate = null;
            if (!empty($data)) foreach ($data as $item) {
                $dateDisplay = $item['displayed_time'];
                $result = $item['data_result'];
                if($this->checkNumberShow($result,$num) == false) {
                    $gan++;
                    if ($gan > $maxGan) {
                        $maxGan = $gan;
                        $maxGanDate = $date;
                    }
                }else{
                    $gan = 0;
                    $date = $dateDisplay;
                }
            }
            if ($gan < $bien_do) continue;
            $start = strtotime($date);
            if (date('H') >= 16){
                $end = strtotime(date('Y-m-d'));
            }else{
                $end = strtotime(date('Y-m-d',strtotime('-1 days')));
            }
            $tmp['count'] = $gan;
            $tmp['max_gan']['count'] = $maxGan;
            $tmp['max_gan']['date'] = $maxGanDate;
            $tmp['date'] = $date;
            $tmp['number'] = $num;
            $tmp['dayyettocome'] = ceil(abs($end - $start) / 86400);
            $numArr[] = $tmp;
        }
        return $numArr;
    }

    private function checkNumberShow($content, $number){
        $pattern = "/($number\")/";
        if(is_array($content)) $content = json_encode($content);
        preg_match_all($pattern,$content,$total);
        return count($total[0]) > 0 ? true : false;
    }

    private function checkNumberCombinShow($content, $number){
        $number = str_replace('_','\_',$number);
        $pattern = "/($number)/";
        if(is_array($content)) $content = json_encode($content);
        preg_match_all($pattern,$content,$total);
        return count($total[0]) > 0 ? true : false;
    }

    private function countNumberShow($content, $number){
        $pattern = "/($number\")/";
        if(is_array($content)) $content = json_encode($content);
        preg_match_all($pattern,$content,$total);
        return count($total[0]);
    }

    private function countHeadNumberShow($content, $number){
        $pattern = "/({$number}[\d]\")/";
        if(is_array($content)) $content = json_encode($content);
        preg_match_all($pattern,$content,$total);
        return count($total[0]);
    }

    private function countTailNumberShow($content, $number){
        $pattern = "/($number\")/";
        if(is_array($content)) $content = json_encode($content);
        preg_match_all($pattern,$content,$total);
        return count($total[0]);
    }

    public function getCham(){
        $input = $this->input->get();
        if (empty($input['code'])) return;
        $cateResult = $this->_data_category->getByCode($input['code']);

        $input['dateBegin'] = str_replace('/', '-', $input['dateBegin']);
        $input['dateEnd'] = str_replace('/', '-', $input['dateEnd']);
        $dataResult = $this->_data->getFromDayToDay($input['dateBegin'], $input['dateEnd'], $cateResult->id);
        if (isset($input['isSpecial'])){
            foreach ($dataResult as $item){
                $itemResult = json_decode($item['data_result']);
                $dataSpecial[] = $itemResult[1];
            }
            $textResult = json_encode($dataSpecial);
        } else {
            $textResult = array_column($dataResult, 'data_result');
            $textResult = implode($textResult);
        }
        $arrLoto = getLoto($textResult);
        for ($i=0; $i<=9; $i++){
            $dataReturn[$i] = [
                'number' => $i,
                'count_head' => 0,
                'count_tail' => 0,
                'count_sum' => 0
            ];
        }
        foreach ($arrLoto as $num){
            $head = substr($num, 0, 1);
            $tail = substr($num, 1, 1);
            $sum = ($head + $tail) % 10;
            $dataReturn[$head]['count_head']++;
            $dataReturn[$tail]['count_tail']++;
            $dataReturn[$sum]['count_sum']++;
        }
        echo json_encode($dataReturn);
    }
    public function thongke_3cang(){
        $default = [
            'code' => 'xsmb',
            'limit' => 30
        ];
        $input = $this->input->get();
        $dataInput = array_merge($default, $input);
        $cateResult = $this->_data_category->getByCode($dataInput['code']);
        $dataResult = $this->_data->getByCategory($cateResult->id, $dataInput['limit']);
        $textDataResult = implode(array_column($dataResult, 'data_result'));
        $dataLoto = getLoto($textDataResult, 3);
        $dataThongKe = array_count_values($dataLoto);
        arsort($dataThongKe, 1);
        foreach ($dataThongKe as $num => $count){
            $this->textSearch = "$num\"";
            $dataReturn[] = [
                'number' => $num,
                'count' => $count,
                'data' => current(array_filter($dataResult, function ($a){
                        if (strpos($a['data_result'], $this->textSearch))
                            return $a;
                    })
                )
            ];
        }
        if (!empty($dataReturn))
            echo json_encode($dataReturn);
        else return;
    }
}