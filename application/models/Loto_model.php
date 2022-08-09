<?php
/**
 * Created by PhpStorm.
 * User: ducto
 * Date: 8/9/2018
 * Time: 3:07 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Loto_model extends STEVEN_Model
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
    }

    public function logan($code, $date_end, $bienDo, $date_start = '') {
        if (empty($date_start)) $date_start = '2005-01-01';
        $key = "logan-".md5($code.$date_end.$bienDo.$date_start);
        $data = $this->getCache($key);
        if (empty($data)){
            $oneItem = $this->_data_category->getByCode($code);

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
                $dataResult = $this->_data->getFromDayToDayProvinceMB($date_start, $date_end, 1, $arr_provinceMB[$code]);
            } else {
                $dataResult = $this->_data->getFromDayToDay($date_start, $date_end, $oneItem->id);
            }

            //$dataResult = $this->_data->getFromDayToDay($date_start, $date_end, $oneItem->id);
            $dataResult = array_reverse($dataResult);

            $topGan = $this->getLogan($dataResult, $bienDo);

            usort($topGan, function($a, $b) {
                return $b['count'] - $a['count'];
            });
            $data['data_top'] = $topGan;
            $data['data_list_number'] = $this->getLogan($dataResult);
            $this->setCache($key, $data, 600);
        }
        return $data;
    }
    public function loganxsp() {
        $input = $this->input->get();
        extract($input);
        if (empty($code)) die('Thieu tham so code'); else $code = strtolower($code);
        if (empty($dateEnd)) $dateEnd = date('Y-m-d');
        if (empty($limit)) $limit = 10;

        $key = "logan-$code-$dateEnd-lm$limit";
        $data = $this->getCache($key);
        if (empty($data)){
            $oneItem = $this->_data_category->getByCode($code);
            $dataResult = $this->_data->getResultNearest($dateEnd,$oneItem->id,0);
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

    public function loxien2($code,$limit,$dateEnd){
        $code = strtolower($code);

        $oneItem = $this->_data_category->getByCode($code);
        $this->getListIdChild($oneItem);

        $arr_provinceMB = [
            'xstb' => '1',
            'xshn' => '2, 5',
            'xsqn' => '3',
            'xsbn' => '4',
            'xshp' => '6',
            'xsnd' => '7',
        ];
        if (isset($arr_provinceMB[$code])) {
            $dataResult = $this->_data->getResultNearestProvinceMB($dateEnd, 1, $arr_provinceMB[$code], $limit);
        } elseif ($code==='xsmt' || $code === 'xsmn'){
            $limit--;
            $dateBegin = date('Y-m-d',strtotime($dateEnd." -$limit day"));
            $dataResult = $this->_data->getFromDayToDay($dateBegin,$dateEnd,$this->listIdChild);
        } else
            $dataResult = $this->_data->getResultNearest($dateEnd,$this->listIdChild,$limit);
        $dataResult = $this->array_group_by($dataResult,'displayed_time');

        return $this->getLoXien($dataResult,2);
    }

    public function loxien3($code,$limit,$dateEnd){
        $code = strtolower($code);

        $oneItem = $this->_data_category->getByCode($code);
        $this->getListIdChild($oneItem);

        $arr_provinceMB = [
            'xstb' => '1',
            'xshn' => '2, 5',
            'xsqn' => '3',
            'xsbn' => '4',
            'xshp' => '6',
            'xsnd' => '7',
        ];
        if (isset($arr_provinceMB[$code])) {
            $dataResult = $this->_data->getResultNearestProvinceMB($dateEnd, 1, $arr_provinceMB[$code], $limit);
        } elseif ($code==='xsmt' || $code === 'xsmn') {
            $limit--;
            $dateBegin = date('Y-m-d',strtotime($dateEnd." -$limit day"));
            $dataResult = $this->_data->getFromDayToDay($dateBegin,$dateEnd,$this->listIdChild);
        } else
            $dataResult = $this->_data->getResultNearest($dateEnd,$this->listIdChild,$limit);
        $dataResult = $this->array_group_by($dataResult,'displayed_time');

        return $this->getLoXien($dataResult,3);
    }

    public function lokep($code,$limit,$dateEnd){
        $code = strtolower($code);

        $oneItem = $this->_data_category->getByCode($code);
        $this->getListIdChild($oneItem);

        $arr_provinceMB = [
            'xstb' => '1',
            'xshn' => '2, 5',
            'xsqn' => '3',
            'xsbn' => '4',
            'xshp' => '6',
            'xsnd' => '7',
        ];
        if (isset($arr_provinceMB[$code])) {
            $dataResult = $this->_data->getResultNearestProvinceMB($dateEnd, 1, $arr_provinceMB[$code], $limit);
        } elseif ($code==='xsmt' || $code === 'xsmn') {
            $limit--;
            $dateBegin = date('Y-m-d',strtotime($dateEnd." -$limit day"));
            $dataResult = $this->_data->getFromDayToDay($dateBegin,$dateEnd,$this->listIdChild);
        } else
            $dataResult = $this->_data->getResultNearest($dateEnd,$this->listIdChild,$limit);
        $dataResult = $this->array_group_by($dataResult,'displayed_time');

        return $this->getLoKep($dataResult);
    }

    public function lokepdon($code,$limit,$dateEnd){
        $code = strtolower($code);

        $oneItem = $this->_data_category->getByCode($code);
        $this->getListIdChild($oneItem);

        $arr_provinceMB = [
            'xstb' => '1',
            'xshn' => '2, 5',
            'xsqn' => '3',
            'xsbn' => '4',
            'xshp' => '6',
            'xsnd' => '7',
        ];
        if (isset($arr_provinceMB[$code])) {
            $dataResult = $this->_data->getResultNearestProvinceMB($dateEnd, 1, $arr_provinceMB[$code], $limit);
        } elseif ($code==='xsmt' || $code === 'xsmn') {
            $limit--;
            $dateBegin = date('Y-m-d',strtotime($dateEnd." -$limit day"));
            $dataResult = $this->_data->getFromDayToDay($dateBegin,$dateEnd,$this->listIdChild);
        } else
            $dataResult = $this->_data->getResultNearest($dateEnd,$this->listIdChild,$limit);
        $dataResult = $this->array_group_by($dataResult,'displayed_time');

        $data = $this->getLoKepDon($dataResult);
        return $data;
    }

    public function lotong($code,$sum,$dateEnd,$dateBegin,$is_special){
        $oneItem = $this->_data_category->getByCode($code);
        $this->_data_category->_recursive_child_id($this->_data_category->_all_category(),$oneItem->id);

        $oneParent = $this->_data_category->_recursive_one_parent($this->_all_category, $oneItem->id);
        $dataResult = $this->_data->getFromDayToDay($dateBegin,$dateEnd,$this->_data_category->_list_category_child_id);
        $dataResult = array_reverse($dataResult);

        $data['data'] = $this->getTongDauDuoiLoto($dataResult,$sum,$oneParent,$is_special);
        $data['data_sum_recently'] = $this->getTongDauDuoiLotoOneResult(end($dataResult));
        if(!empty($dataResult)) foreach ($dataResult as $item){
            $data['data_sum'][] = $this->getTongDauDuoiLotoOneResult($item);
        }
        return $data;
    }

    public function lotong2($code, $sum, $dateEnd, $limit, $is_special){
        $oneItem = $this->_data_category->getByCode($code);
        $this->_data_category->_recursive_child_id($this->_data_category->_all_category(),$oneItem->id);

        $oneParent = $this->_data_category->_recursive_one_parent($this->_all_category, $oneItem->id);
        $dataResult = $this->_data->getResultNearest($dateEnd,$this->_data_category->_list_category_child_id,$limit);
        $dataResult = array_reverse($dataResult);

        $data['data'] = $this->getTongDauDuoiLoto($dataResult,$sum,$oneParent,$is_special);
        $data['data_sum_recently'] = $this->getTongDauDuoiLotoOneResult(end($dataResult));
        if(!empty($dataResult)) foreach ($dataResult as $item){
            $data['data_sum'][] = $this->getTongDauDuoiLotoOneResult($item);
        }
        return $data;
    }

    private function getTongDauDuoiLoto($data,$sumCheck,$oneParent = null,$is_special = false){
        $numArr = [];
        for ($i = 0; $i <= 99; $i++) {
            $num = sprintf("%'02d",$i);
            $head = substr($num,0,1);
            $tail = substr($num,-1);
            $sum = $head + $tail;
            $totalHeadTail = substr($sum,-1);
            if(isset($sumCheck) && $totalHeadTail != $sumCheck) continue;
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
                    if(!empty($oneParent) && $oneParent->id == 1){
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

        $dataReturn = $this->array_group_by($numArr, 'sum');
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
                    if (!empty($data)) foreach ($data as $items) {
                        foreach ($items as $item){
                            $dateDisplay = $item['displayed_time'];
                            $result = $item['data_result'];
                            if($this->checkNumberShow($result,$num1) == true && $this->checkNumberShow($result,$num2) == true){
                                $countNoShow++;
                                $songayve[] = $dateDisplay;
                            }
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

    private function getLoKepDon($data){
        $numArr = [];
        for ($i = 0; $i <= 9; $i++) {
            $number = $i.$i;
            $countNoShow = 0;
            $songayve = [];
            if (!empty($data)) foreach ($data as $items) {
                foreach ($items as $item){
                    if($this->checkNumberShow($item['data_result'], $number) == true){
                        $countNoShow++;
                        $songayve[] = $item['displayed_time'];
                    }
                }
            }
            if($countNoShow > 0){
                $tmp['number'] = $number;
                $tmp['count'] = $countNoShow;
                $tmp['date'] = $songayve;
                $numArr[] = $tmp;
            }
        }
        usort($numArr, function($a, $b) {
            return $b['count'] - $a['count'];
        });

        return $numArr;
    }

    private function getLoXien($data,$size = 2){
        $allNum = [];
        if (!empty($data)) foreach ($data as $dateDisplay => $items){
            $listOfDate[$dateDisplay] = [];
            foreach ($items as $item){
                $arrLoto = $this->convertResultTo2number($item['data_result']);
                $arrLoto = array_unique($arrLoto);
                sort($arrLoto);

                $listXienOfDate = $this->combinLoxien($arrLoto,$size);
                $listOfDate[$dateDisplay] = array_merge($listOfDate[$dateDisplay],$listXienOfDate);
            }
            $allNum = array_merge($allNum,array_unique($listOfDate[$dateDisplay]));
        }
        $countNum = array_count_values($allNum);
        arsort($countNum);

        $countNum = array_slice($countNum,0,20);

        $dataNew = [];
        if(!empty($countNum)) foreach ($countNum as $coupleNumber => $count){
            $tmp = [];
            if(!empty($listOfDate)) foreach ($listOfDate as $date => $itemResult){
                if($this->checkNumberCombinShow($itemResult,$coupleNumber)){
                    $tmp[] = $date;
                }
            }
            $tmpCount['count'] = count($tmp);
            $tmpCount['list_date'] = $tmp;
            $dataNew[$coupleNumber] = $tmpCount;
            unset($tmp);
        }
        return $dataNew;
    }

    private function combinLoxien($arr, $size) {
        $arr = array_values($arr);
        $count = count($arr);
        $arrReturn = [];
        if ($size == 2){
            for ($i=0; $i <= $count - 2; $i++){
                for ($j=$i+1; $j <= $count - 1; $j++){
                    $arrReturn[] = $arr[$i].'_'.$arr[$j];
                }
            }
            return $arrReturn;
        }
        if ($size == 3){
            for ($i=0; $i <= $count - 3; $i++){
                for ($j=$i+1; $j <= $count - 2; $j++){
                    for ($k=$j+1; $k <= $count - 1; $k++){
                        $arrReturn[] = $arr[$i].'_'.$arr[$j].'_'.$arr[$k];
                    }
                }
            }
            return $arrReturn;
        }
    }

    private function convertResultTo2number($a){
        preg_match_all('/(\d\d)"/',$a,$arr);
        if (!empty($arr[1])) return $arr[1]; else return false;
    }

    private function getTanSuatLoto($data){
        $numArr = [];
        for ($i = 0; $i <= 99; $i++) {
            $numOther = 0;
            $num = sprintf("%'02d",$i);
            $dateShow = [];
            $tmp = [];
            if (!empty($data)) foreach ($data as $item) {
                $dateDisplay = $item['displayed_time'];
                $result = $item['data_result'];
                $countShow = $this->countNumberShow($result,$num,$dateDisplay);
                if($countShow > 0) if (!empty($dateShow[$dateDisplay])) {
                    $dateShow[$dateDisplay] = $dateShow[$dateDisplay]+$countShow;
                    $numOther = $numOther+$countShow;
                } else $dateShow[$dateDisplay] = $countShow;$numOther = $numOther+$countShow;
            }
            $tmp['date'] = $dateShow;
            $tmp['count'] = count($dateShow);
            $tmp['sum'] = array_sum($dateShow);
            $tmp['number'] = $num;
            $numArr[] = $tmp;
        }
        return $numArr;
    }

    private function getLogan($data, $bien_do = 0) {
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
                if($this->checkNumberShow($result, $num) == false) {
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

    private function getListIdChild($oneItem){
        $this->_data_category->_recursive_child_id($this->_data_category->_all_category(),$oneItem->id);
        $this->listIdChild = $this->_data_category->_list_category_child_id;
    }
}