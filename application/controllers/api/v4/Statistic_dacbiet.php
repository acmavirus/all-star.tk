<?php
/**
 * Created by PhpStorm.
 * User: ducto
 * Date: 8/9/2018
 * Time: 3:07 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Statistic_dacbiet extends API_Controller
{
    protected $_data;
    protected $_data_category;
    protected $_lang_code;
    protected $_all_category;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['category_model', 'result_model']);
        $this->_data = new Result_model();
        $this->_data_category = new Category_model();
        $this->_all_category = $this->_data_category->_all_category();
        $this->_lang_code = $this->session->userdata('public_lang_code');
    }

    public function rewardBD2S($oneDataResult){
        $oneDataResult = (array) $oneDataResult;
        $oneDataResult = json_encode($oneDataResult);
        preg_match('/\d{6}/',$oneDataResult,$rs);
        if (!$rs) preg_match('/\d{5}/',$oneDataResult,$rs);
        if (!empty($rs[0]))
            return "$rs[0]";
        return 0;
    }
    public function last2number() {
        $dataRaw = (object) $this->input->get();
        if(!empty($dataRaw)){
            $code = $dataRaw->code;
            if(empty($code)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số mã tỉnh "code"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');

            $dataRaw->date = str_replace('/', '-', $dataRaw->date);
            $date = !empty($dataRaw->date) ? date('Y-m-d',strtotime($dataRaw->date)) : date('Y-m-d',strtotime('-1 day'));

            $oneItem = $this->_data_category->getByCode($code);
            $oneParent = $this->_data_category->_recursive_one_parent($this->_all_category, $oneItem->id);

            if (in_array($oneItem->id, [2, 3])) {
                $this->_data_category->_recursive_child_id($this->_data_category->_all_category(),$oneItem->id);
                $category_id = $this->_data_category->_list_category_child_id;
            } else {
                $category_id = $oneItem->id;
            }

            $oneResult = $this->_data->getResultNearest($date,$category_id)[0];
            $oneResult = (object) $oneResult;
            $oneDataResult = json_decode($oneResult->data_result,true);

            //$dateAdd1day = date('Y-m-d',strtotime($oneResult->displayed_time." +1 day"));
            $dateAdd1day = getNextDateByCode($code);

            $rewardDB = $this->rewardBD2S($oneDataResult);

            $last2number = substr($rewardDB,-2);

            $dataResult = $this->_data->getDataResultStatistic($category_id,null,"DESC");

            $data['data_result_today'] = [
                'date' => $oneResult->displayed_time,
                'number' => $this->rewardBD2S($oneDataResult)
            ];

            $data['data_same_reward'] = $this->get2number($dataResult,$oneResult->displayed_time,$last2number,$oneParent);
            $data['data_after_reward_1day'] = $this->get2numberAfter1day($dataResult,$oneResult->displayed_time,$last2number,$oneParent);
            $data['data_lastyear_reward'] = $this->get2numberBeforeYear($dataResult,$oneResult->displayed_time,$oneParent);
            $data['data_lastyear_reward_1day'] = $this->get2numberBeforeYear($dataResult,$dateAdd1day,$oneParent);
            $data['data_cham_after_reward'] = $this->getCham($data['data_after_reward_1day']);
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);

    }

    public function getByMonth(){
        $dataRaw = (object) $this->input->get();
        if(!empty($dataRaw)){
            if(empty($dataRaw->code)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số mã tỉnh "code"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $code = $dataRaw->code;

            $dataRaw->date_begin = str_replace('/', '-', $dataRaw->date_begin);
            if(empty($dataRaw->date_begin)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số biên độ "date_begin"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $dateBegin = date('Y-m-d',strtotime($dataRaw->date_begin));

            $dataRaw->date_end = str_replace('/', '-', $dataRaw->date_end);
            if(empty($dataRaw->date_end) && empty($dataRaw->limit)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số biên độ "date_end"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            if (!empty($dataRaw->date_end)){
                $dateEnd = date('Y-m-d',strtotime($dataRaw->date_end));
            }

            #
            $code = strtolower($code);
            $arr_provinceMB = [
                'xstb' => '1',
                'xshn' => '2, 5',
                'xsqn' => '3',
                'xsbn' => '4',
                'xshp' => '6',
                'xsnd' => '7',
            ];
            if (isset($arr_provinceMB[$code])) {
                $dataResult = $this->_data->getDataResultStatisticProvinceMBByDate(1, $dateBegin, $dateEnd, $arr_provinceMB[$code]);
            } else {
                $oneItem = $this->_data_category->getByCode($code);
                $this->_data_category->_recursive_child_id($this->_data_category->_all_category(),$oneItem->id);
                $dataResult = $this->_data->getDataResultStatisticByDate($this->_data_category->_list_category_child_id,$dateBegin,$dateEnd);
            }
            #
            $dataByDate = [];
            $data2Number = [];

            if (!empty($dataResult)) foreach ($dataResult as $key => $item) {
                $item = (object) $item;
                $result = json_decode($item->data_result,true);
                if(!empty($result[1][0])){
                    if($code === 'xsmb') {
                        $rewardDB = $result[1][0];
                    }
                    else {
                        $rewardDB = $result[8][0];
                        $rewardG8 = $result[0][0];
                    }

                    if ($code == 'xsmt' || $code == 'xsmn') $dataByDate[$item->displayed_time][] = $rewardDB;
                    else {
                        $dataByDate[$item->displayed_time] = $rewardDB;
                        if ($code !== 'xsmb'){
                            $g8byDate[$item->displayed_time] = $rewardG8;
                        }
                    }
                    $data2Number[] = substr($rewardDB,-2,2);
                }
            }
            $data['data'] = $dataByDate;
            $data['twoNumber'] = $data2Number;
            if (!empty($g8byDate))
                $data['g8'] = $g8byDate;
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function getByMonthProvince(){
        $dataRaw = (object) $this->input->get();
        if(!empty($dataRaw)){
            if(empty($dataRaw->code)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số mã tỉnh "code"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $code = $dataRaw->code;

            $dataRaw->date_end = str_replace('/', '-', $dataRaw->date_end);
            if(empty($dataRaw->date_end) && empty($dataRaw->limit)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số biên độ "date_end"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            if (!empty($dataRaw->date_end)){
                $date_end = date('Y-m-d', strtotime($dataRaw->date_end));
            }
            if(empty($dataRaw->limit)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số limit'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $limit = $dataRaw->limit;
            #
            $code = strtolower($code);
            $arr_provinceMB = [
                'xstb' => '1',
                'xshn' => '2, 5',
                'xsqn' => '3',
                'xsbn' => '4',
                'xshp' => '6',
                'xsnd' => '7',
            ];
            if (isset($arr_provinceMB[$code])) {
                $dataResult = $this->_data->getDataResultStatisticProvinceMBByDate2(1, $limit, $date_end, $arr_provinceMB[$code]);
            } else {
                $oneItem = $this->_data_category->getByCode($code);
                $this->_data_category->_recursive_child_id($this->_data_category->_all_category(),$oneItem->id);
                $dataResult = $this->_data->getDataResultStatisticByDate2($this->_data_category->_list_category_child_id, $limit, $date_end);
            }
            #
            $dataByDate = [];
            $data2Number = [];

            if (!empty($dataResult)) foreach ($dataResult as $key => $item) {
                $item = (object) $item;
                $result = json_decode($item->data_result,true);
                if(!empty($result[1][0])){
                    if($code === 'xsmb') {
                        $rewardDB = $result[1][0];
                    }
                    else {
                        $rewardDB = $result[8][0];
                        $rewardG8 = $result[0][0];
                    }

                    if ($code == 'xsmt' || $code == 'xsmn') $dataByDate[$item->displayed_time][] = $rewardDB;
                    else {
                        $dataByDate[$item->displayed_time] = $rewardDB;
                        if ($code !== 'xsmb'){
                            $g8byDate[$item->displayed_time] = $rewardG8;
                        }
                    }
                    $data2Number[] = substr($rewardDB,-2,2);
                }
            }
            $data['data'] = $dataByDate;
            $data['twoNumber'] = $data2Number;
            if (!empty($g8byDate))
                $data['g8'] = $g8byDate;
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function getByMonthXSP(){
        $dataRaw = (object) $this->input->get();
        if(!empty($dataRaw)){
            $code = $dataRaw->code;
            if(empty($code)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số mã tỉnh "code"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');

            $dataRaw->date_end = str_replace('/', '-', $dataRaw->date_end);
            !empty($dataRaw->date_end)? $date_end = date('Y-m-d',strtotime($dataRaw->date_end)) : $date_end = date('Y-m-d');

            $oneItem = $this->_data_category->getByCode($code);

            $this->_data_category->_recursive_child_id($this->_data_category->_all_category(),$oneItem->id);
            $dataResult = $this->_data->getDataResultStatisticByMonth($this->_data_category->_list_category_child_id,$date_end);
            $dataByDate = [];
            $data2Number = [];

            if (!empty($dataResult)) foreach ($dataResult as $key => $item) {
                $item = (object) $item;
                $result = json_decode($item->data_result,true);
                if(!empty($result[1][0])){
                    if($code === 'xsmb') $rewardDB = $result[1][0];
                    else $rewardDB = $result[8][0];
                    if ($code == 'xsmt' || $code == 'xsmn') $dataByDate[$item->displayed_time][] = $rewardDB;
                    else $dataByDate[$item->displayed_time] = $rewardDB;
                    $data2Number[] = substr($rewardDB,-2,2);
                }
            }
            $data['data'] = $dataByDate;
            $data['twoNumber'] = $data2Number;
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function getByYear(){
        $dataRaw = (object) $this->input->get();
        if(!empty($dataRaw)){
            $code = $dataRaw->code;
            if(empty($code)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số mã tỉnh "code"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $year = $dataRaw->year;
            if(empty($year)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số năm "year"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $month = $dataRaw->month;
            if(empty($month)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số tháng "month"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');

            $oneItem = $this->_data_category->getByCode($code);

            $this->_data_category->_recursive_child_id($this->_data_category->_all_category(),$oneItem->id);
            $dataResult = $this->_data->getDataResultStatisticByYear($this->_data_category->_list_category_child_id,$year,$month);
            $dataByDate = [];
            if (!empty($dataResult)) foreach ($dataResult as $key => $item) {
                $item = (object) $item;
                $result = json_decode($item->data_result,true);

                if(!empty($result[1][0])){
                    $rewardDB = $result[1][0];
                    $twonumber = substr($rewardDB, -2);
                    $tmp['number'] = $rewardDB;
                    $tmp['last_2'] = $twonumber;
                    $tmp['head'] = substr($twonumber, 0, 1);
                    $tmp['tail'] = substr($twonumber, -1);
                    $tmp['sum'] = ((int)$tmp['head'] + (int)$tmp['tail']) % 10;
                    $dataByDate[$item->displayed_time] = $tmp;
                }else{
                    $tmp['number'] = null;
                    $tmp['last_2'] = null;
                    $tmp['head'] = null;
                    $tmp['tail'] = null;
                    $tmp['sum'] = null;
                    $dataByDate[$item->displayed_time] = $tmp;
                }
            }
            $data['data'] = $dataByDate;
            $data['data_cham'] = $this->getCham($dataByDate);
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function getByNumber($number = ''){
        $allResult = $this->_data->getAllResultMB();
        if (empty($number))
            $number = substr(json_decode($allResult[0]['data_result'])[1][0], -2);
        $arrGroupByDate = [];
        foreach ($allResult as $item){
            $arrGroupByDate[$item['displayed_time']] = $item;
        }
        $arrData = $dataCount = [];
        $dataCham = array_fill(0, 10, [
            'head' => 0,
            'tail' => 0,
            'sum' => 0
        ]);

        foreach ($arrGroupByDate as $date => $item){
            preg_match('/\d\d\d(\d\d)/', $item['data_result'], $match);
            if ($match[1] == $number){
                $dateTomorrow = date('Y-m-d', strtotime($date .'+1 day'));
                if (isset($arrGroupByDate[$dateTomorrow])){
                    $dacBietTomorrow = json_decode($arrGroupByDate[$dateTomorrow]['data_result'])[1][0];
                    $lotoTomorrow = getLoto($arrGroupByDate[$dateTomorrow]['data_result'], 2);
                    $head = substr($dacBietTomorrow, -2, 1);
                    $tail = substr($dacBietTomorrow, -1, 1);
                    $sum = ($head + $tail) % 10;
                    $dataCham[$head]['head']++;
                    $dataCham[$tail]['tail']++;
                    $dataCham[$sum]['sum']++;

                    $dataCount[] = $head.$tail;
                } else {
                    $dacBietTomorrow = '';
                    $lotoTomorrow = [];
                }
                $arrData[] = [
                    'date' => $date,
                    'number' => json_decode($item['data_result'])[1][0],
                    'tomorrow' => $dateTomorrow,
                    'numberTomorrow' => $dacBietTomorrow,
                    'lotoTomorrow' => $lotoTomorrow
                ];
            }
        }
        echo json_encode(['data' => $arrData, 'cham' => $dataCham, 'count' => array_count_values($dataCount)]);
    }
    public function getByDow(){
        $input = $this->input->get();
        $oneCate = $this->_data_category->getByCode($input['code']);

        $this->_data_category->_recursive_child_id($this->_data_category->_all_category(), $oneCate->id);
        $allResult = $this->_data->getByCategoryDayOfWeek($this->_data_category->_list_category_child_id, $input['dayofweek'], $input['limit']);
        $data = $this->getNumberDacBiet($allResult, $oneCate->code == 'XSMB'? 1: 0);
        $arr2Number = getLoto($data, 2);
        $arrCount = array_count_values($arr2Number);
        echo json_encode($arrCount);
    }


    private function get2number($data,$date,$last2number = null,$oneParent = null) {
        $dataByDate = [];
        if (!empty($data)) foreach ($data as $key => $item) {
            $item = (object) $item;
            $result = json_decode($item->data_result,true);
            if(!empty($result)){
                $rewardDB = $this->rewardBD2S($result);
                $twonumber = substr($rewardDB,-2);
                if(!empty($last2number) && $last2number === $twonumber && $item->displayed_time !== $date) {
                    $dataByDate[$item->displayed_time] = $rewardDB;
                }
            }
        }

        return $dataByDate;
    }

    private function get2numberAfter1day($data,$date,$last2number,$oneParent = null) {
        $dataByDate = [];
        if (!empty($data)) foreach ($data as $key => $item) {
            $item = (object) $item;
            $result = json_decode($item->data_result,true);
            if(!empty($result[1][0])) {
                $rewardDB = $this->rewardBD2S($result);
                $twonumber = substr($rewardDB, -2);
                //dd($twonumber);
                if (!empty($last2number) && $last2number === $twonumber && $item->displayed_time !== $date) {

                    //if (!empty($data[$key + 1])) $item = (object)$data[$key-1]; //Lấy ngày tiếp theo của giải đặc biệt vì đang sắp xếp mới đến cũ nên -1
                    if(!empty($data[$key + 1]) && !empty($data[$key -1 ])){
                        $item = (object)$data[$key - 1];
                        $result = json_decode($item->data_result, true);
                        if(!empty($result)){
                            $rewardDB = $this->rewardBD2S($result);
                            $twonumber = substr($rewardDB, -2);
                            $tmp['number'] = $rewardDB;
                            $tmp['last_2'] = $twonumber;
                            $tmp['head'] = substr($twonumber, 0, 1);
                            $tmp['tail'] = substr($twonumber, -1);
                            $tmp['sum'] = substr($tmp['head'] + $tmp['tail'],-1);
//                            $tmp['g8'] = (count($result[0]) == 1 && strlen($result[0][0]) == 2) ? $result[0][0] : '';
                            $dataByDate[$item->displayed_time] = $tmp;
                        }

                    }

                }
            }
        }
        return $dataByDate;
    }

    private function get2numberBeforeYear($data,$date,$oneParent) {
        $dataByDate = [];
        if (!empty($data)) foreach ($data as $key => $item) {
            $item = (object) $item;
            $result = json_decode($item->data_result,true);
            if(!empty($result[1][0])){
                $rewardDB = $this->rewardBD2S($result);
                if(date('m-d',strtotime($item->displayed_time)) === date('m-d',strtotime($date))) {
                    $dataByDate[$item->displayed_time] = $rewardDB;
                }
            }

        }
        return $dataByDate;
    }

    /*Thống kê chạm*/
    private function getCham($data) {
        $dataCham = [];
        for($i = 0; $i <=9 ; $i ++){
            $countHead = 0;
            $countTail = 0;
            $countSum = 0;
            if(!empty($data)) foreach ($data as $dateDisplay => $item){
                if((int)$item['head'] == $i) $countHead++;
                if((int)$item['tail'] == $i) $countTail++;
                if((int)$item['sum'] == $i) $countSum++;
                if((int)$item['head'] == 0 && $item['tail'] == 0) $countSum++;
            }
            $tmp['number'] = $i;
            $tmp['count_head'] = $countHead;
            $tmp['count_tail'] = $countTail;
            $tmp['count_sum'] = $countSum;
            $dataCham[$i] = $tmp;
        }

        return $dataCham;
    }
    public function thongKeDacBiet(){
        $arrDefault = [
            'code' => 'xsmb',
            'limit' => 10,
            'date_end' => date('Y-m-d')
        ];
        $get = $this->input->get();
        $params = array_merge($arrDefault, $get);
        $params['date_end'] = str_replace('/', '-', $params['date_end']);

        $cateResult = $this->_data_category->getByCode($params['code']);
        if (in_array($params['code'], ['xsmt', 'xsmn'])){
            $allCate = $this->_data_category->_all_category('result');
            $listCateChild = $this->_data_category->getListChild($allCate, $cateResult->id);
            $listCateChildId = array_column($listCateChild, 'id');
            $data = $this->_data->getFromDayToDay(date('Y-m-d', strtotime($params['date_end']."-{$params['limit']} day")), $params['date_end'], $listCateChildId);
        } else {
            $data = $this->_data->getResultNearest($params['date_end'], $cateResult->id, $params['limit']);
        }

        $arrDacBiet = $this->getNumberDacBiet($data, $params['code'] == 'xsmb'? 1: 0);

        $loto = getLoto($arrDacBiet, 2);
        $arrCount = array_count_values($loto);
        arsort($arrCount);
        $return['count_value'] = $arrCount;
        $return['cham']['head'] = $return['cham']['tail'] = $return['cham']['sum'] = array_fill(0, 10, 0);

        foreach ($loto as $num){
            $head = substr($num, 0, 1);
            $tail = substr($num, 1, 1);
            $sum = ($head + $tail) % 10;
            $return['cham']['head'][$head]++;
            $return['cham']['tail'][$tail]++;
            $return['cham']['sum'][$sum]++;
        }
        echo json_encode($return);
    }
    private function getNumberDacBiet($data, $is_mb){
        $textResult = implode('', array_column($data, 'data_result'));
        if ($is_mb){
            preg_match_all('/\["(\d*?)"\],\["\d*?"\]/', $textResult, $arrDacBiet);
            return $arrDacBiet[1];
        } else {
            preg_match_all('/\d{6}/', $textResult, $arrDacBiet);
            return $arrDacBiet[0];
        }
    }
}