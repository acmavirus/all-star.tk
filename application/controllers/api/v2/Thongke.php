<?php

/**
 * Created by PhpStorm.
 * User: ducto
 * Date: 8/9/2018
 * Time: 3:07 PM
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Thongke extends API_Controller
{
    protected $_data;
    protected $_data_category;
    protected $_all_category;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['category_model', 'result_model']);
        $this->load->helper(['date']);
        $this->_data = new Result_model();
        $this->_data_category = new Category_model();
        $this->_all_category = $this->_data_category->_all_category();
    }

    // ============================================================== Thống kê 247
    public function TKlogan()
    {
        $dataRaw = (object) $this->input->get();
        if (empty($dataRaw)) $dataRaw = (object) $this->input->post();
        if (!empty($dataRaw)) {
            $category_id = $dataRaw->category_id;
            $bien_do = $dataRaw->bien_do;
            // Get data
            $data = $this->getdata($category_id, true);
            $logan_true = $this->getLogan($data, $bien_do);
            $data = $this->getdata($category_id, false);
            $logan_false = $this->getLogan($data, $bien_do);
            // $lokep = $this->getLoXien($data);

            // return
            $data = [
                'logan' => $logan_false,
                'logan_db' => $logan_true,
                // 'lokep' => $lokep
            ];
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function TKlogan2()
    {
        $category_id = 1;
        $bien_do = 10;
        // Get data
        $data = $this->getdata($category_id, true);
        $logan_true = $this->getLogan($data, $bien_do);
        $data = $this->getdata($category_id, false);
        $logan_false = $this->getLogan($data, $bien_do);
        $lokep = $this->getLoXien($data);

        // return
        $data = [
            'logan' => $logan_false,
            'logan_db' => $logan_true,
            'lokep' => $lokep
        ];
        $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
        return $this->response->json($dataJson);
    }

    public function TKdacbiet()
    {
        $dataRaw = (object) $this->input->get();
        if (empty($dataRaw)) $dataRaw = (object) $this->input->post();
        if (!empty($dataRaw)) {
            $category_id = $dataRaw->category_id;
            $end_date = $dataRaw->end_date;
            // Get data
            $date = !empty($dataRaw->date) ? date('Y-m-d', strtotime($end_date)) : date('Y-m-d');
            $oneItem = $this->_data_category->getById($category_id);
            $oneParent = $this->_data_category->_recursive_one_parent($this->_all_category, $oneItem->id);
            $oneResult = $this->_data->getResultNearest($date, $oneItem->id)[0];
            $oneResult = (object) $oneResult;
            $oneDataResult = json_decode($oneResult->data_result, true);
            $dateAdd1day = date('Y-m-d', strtotime($oneResult->displayed_time . " +1 day"));
            $rewardDB = $this->rewardBD2S($oneDataResult);
            $last2number = substr($rewardDB, -2);
            $dataResult = $this->_data->getDataResultStatistic($oneItem->id, null, "DESC");
            $data['data_result_today'] = [
                'date' => $oneResult->displayed_time,
                'number' => $this->rewardBD2S($oneDataResult)
            ];
            $data['data_same_reward'] = $this->get2number($dataResult, $oneResult->displayed_time, $last2number, $oneParent);
            $data['data_after_reward_1day'] = $this->get2numberAfter1day($dataResult, $oneResult->displayed_time, $last2number, $oneParent);
            $data['data_lastyear_reward'] = $this->get2numberBeforeYear($dataResult, $oneResult->displayed_time, $oneParent);
            $data['data_lastyear_reward_1day'] = $this->get2numberBeforeYear($dataResult, $dateAdd1day, $oneParent);
            $data['data_cham_after_reward'] = $this->getCham($data['data_after_reward_1day']);

            $data_2so = $this->getDB($data, strtolower($oneItem->code), $date);
            // return
            $dataJson = $this->pack($data_2so, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    // ==============================================================

    private function getdata($category_id = 1, $dac_biet = false)
    {
        $listcat = $category_id;
        if (!empty($category_id) && ($category_id == 2 || $category_id == 3)) {
            $this->_data_category->_recursive_child_id($this->_data_category->_all_category(), $category_id);
            $listcat = $this->_data_category->_list_category_child_id;
        };
        $dataResult = $this->_data->getDataResultStatistic($listcat, null, "ASC");
        if (!empty($dac_biet) && $category_id == 1) {
            $rs = [];
            foreach ($dataResult as $key => $item) {
                $result = $item['data_result'];
                $result = json_decode($result)[1];
                $dataResult[$key]['data_result'] = json_encode($result);
            };
        };
        if (!empty($dac_biet) && $category_id > 1) {
            $rs = [];
            foreach ($dataResult as $key => $item) {
                $result = $item['data_result'];
                $result = json_decode($result)[8];
                $dataResult[$key]['data_result'] = json_encode($result);
            };
        };
        return $dataResult;
    }
    private function getLogan($data, $bien_do = 0)
    {
        $numArr = [];
        for ($i = 0; $i <= 99; $i++) {
            $num = sprintf("%'02d", $i);
            $gan = 0;
            $maxGan = 0;
            $date = null;
            $maxGanDate = null;
            if (!empty($data)) foreach ($data as $item) {
                $dateDisplay = $item['displayed_time'];
                $result = $item['data_result'];
                if ($this->checkNumberShow($result, $num) == false) {
                    $gan++;
                    if ($gan > $maxGan) {
                        $maxGan = $gan;
                        $maxGanDate = $date;
                    }
                } else {
                    $gan = 0;
                    $date = $dateDisplay;
                }
            }
            if ($gan < $bien_do) continue;
            $start = strtotime($date);
            if (date('H') >= 19) {
                $end = strtotime(date('Y-m-d'));
            } else {
                $end = strtotime(date('Y-m-d', strtotime('-1 days')));
            }
            $tmp['count'] = $gan;
            $tmp['max_gan']['count'] = $maxGan;
            $tmp['max_gan']['date'] = $maxGanDate;
            $tmp['date'] = $date;
            $tmp['number'] = $num;
            $tmp['ngaychuara'] = ceil(abs($end - $start) / 86400);
            $numArr[] = $tmp;
        }
        usort($numArr, function ($a, $b) {
            return $a['ngaychuara'] < $b['ngaychuara'] ? 1 : -1;
        });
        return $numArr;
    }
    private function getDB($data, $code, $date)
    {
        $rs = (object) $data;
        $data = null;
        foreach ($rs as $k => $i) {
            $data["$k"] = $i;
        }

        foreach ($rs->data_after_reward_1day as $k => $i) {
            $rate[] = $i['last_2'];
        }
        $rate = array_count_values($rate);
        arsort($rate);
        $count = 0;
        foreach ($rate as $k => $i) {
            if ($count == 8) break;
            $rateTop[$k] = $i;
            $count++;
        }
        $data['rate'] = $rate;
        $data['rateTop'] = $rateTop;
        foreach ($rs->data_same_reward as $k => $i) {
            if (date('N', strtotime($date . '-1 day')) == date('N', strtotime($k))) {
                $index = array_search($k, array_keys((array)$rs->data_same_reward));
                if (!empty(array_keys((array)$data['data_after_reward_1day'])[$index])) $key = array_keys((array)$data['data_after_reward_1day'])[$index];
                $data['data_same_day'][$k] = $i;
                $data['data_same_next_day'][$key] = $data['data_after_reward_1day'][$key];
            }
        }
        $data['code'] = $code;
        return $data;
    }
    // ==============================================================
    private function rewardBD2S($oneDataResult)
    {
        $oneDataResult = (array) $oneDataResult;
        $oneDataResult = json_encode($oneDataResult);
        preg_match('/\d{6}/', $oneDataResult, $rs);
        if (!$rs) preg_match('/\d{5}/', $oneDataResult, $rs);
        if (!empty($rs[0])) return "$rs[0]";
    }
    private function get2number($data, $date, $last2number = null, $oneParent = null)
    {
        $dataByDate = [];
        if (!empty($data)) foreach ($data as $key => $item) {
            $item = (object) $item;
            $result = json_decode($item->data_result, true);
            if (!empty($result)) {
                $rewardDB = $this->rewardBD2S($result);
                $twonumber = substr($rewardDB, -2);
                if (!empty($last2number) && $last2number === $twonumber && $item->displayed_time !== $date) {
                    $dataByDate[$item->displayed_time] = $rewardDB;
                }
            }
        }

        return $dataByDate;
    }
    private function get2numberAfter1day($data, $date, $last2number, $oneParent = null)
    {
        $dataByDate = [];
        if (!empty($data)) foreach ($data as $key => $item) {
            $item = (object) $item;
            $result = json_decode($item->data_result, true);
            if (!empty($result[1][0])) {
                $rewardDB = $this->rewardBD2S($result);
                $twonumber = substr($rewardDB, -2);
                //dd($twonumber);
                if (!empty($last2number) && $last2number === $twonumber && $item->displayed_time !== $date) {
                    if (!empty($data[$key + 1]))
                        $item = (object)$data[$key - 1]; //Lấy ngày tiếp theo của giải đặc biệt vì đang sắp xếp mới đến cũ nên -1
                    if (!empty($item)) {
                        $result = json_decode($item->data_result, true);
                        if (!empty($result)) {
                            $rewardDB = $this->rewardBD2S($result);
                            $twonumber = substr($rewardDB, -2);
                            $tmp['number'] = $rewardDB;
                            $tmp['last_2'] = $twonumber;
                            $tmp['head'] = substr($twonumber, 0, 1);
                            $tmp['tail'] = substr($twonumber, -1);
                            $tmp['sum'] = substr($tmp['head'] + $tmp['tail'], -1);
                            $dataByDate[$item->displayed_time] = $tmp;
                        }
                    }
                }
            }
        }
        return $dataByDate;
    }
    private function get2numberBeforeYear($data, $date, $oneParent)
    {
        $dataByDate = [];
        if (!empty($data)) foreach ($data as $key => $item) {
            $item = (object) $item;
            $result = json_decode($item->data_result, true);
            if (!empty($result[1][0])) {
                $rewardDB = $this->rewardBD2S($result);
                if (date('m-d', strtotime($item->displayed_time)) === date('m-d', strtotime($date))) {
                    $dataByDate[$item->displayed_time] = $rewardDB;
                }
            }
        }
        return $dataByDate;
    }
    private function getCham($data)
    {
        $dataCham = [];
        for ($i = 0; $i <= 9; $i++) {
            $countHead = 0;
            $countTail = 0;
            $countSum = 0;
            if (!empty($data)) foreach ($data as $dateDisplay => $item) {
                if ((int)$item['head'] == $i) $countHead++;
                if ((int)$item['tail'] == $i) $countTail++;
                if ((int)$item['sum'] == $i) $countSum++;
                if ((int)$item['head'] == 0 && $item['tail'] == 0) $countSum++;
            }
            $tmp['number'] = $i;
            $tmp['count_head'] = $countHead;
            $tmp['count_tail'] = $countTail;
            $tmp['count_sum'] = $countSum;
            $dataCham[$i] = $tmp;
        }

        return $dataCham;
    }
    private function checkNumberShow($content, $number)
    {
        $pattern = "/($number\")/";
        if (is_array($content)) $content = json_encode($content);
        preg_match_all($pattern, $content, $total);
        return count($total[0]) > 0 ? true : false;
    }
    private function getLoXien($data, $size = 2)
    {
        $toHop2Arr = [];
        $tmpListResult = [];
        if (!empty($data)) foreach ($data as $item) {
            $dateDisplay = $item['displayed_time'];
            $dataResult = json_decode($item['data_result'], true);
            unset($dataResult[0]); //Lo xien chỉ có miền bắc bỏ cái mã giải đặc biệt
            $result = $this->convertResultTo2number($dataResult);
            $listDataXien2 = $this->combinLoxien($result, $size);
            $listDataXien2 = array_unique($listDataXien2);
            $tmpListResult[$dateDisplay] = json_encode($listDataXien2);
            array_push($toHop2Arr, $listDataXien2);
        }
        $allDataResult = array_merge(...$toHop2Arr);

        $numArr = array_count_values($allDataResult);
        arsort($numArr);
        $numArr = array_slice($numArr, 0, 50);
        $numArrClearDuplicate = $this->clearToHopDuplicate($numArr);

        $dataNew = [];
        if (!empty($numArrClearDuplicate)) foreach ($numArrClearDuplicate as $coupleNumber => $count) {
            $tmp = [];
            if (!empty($tmpListResult)) foreach ($tmpListResult as $date => $itemResult) {
                if ($this->checkNumberCombinShow($itemResult, $coupleNumber)) {
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
    private function convertResultTo2number($a)
    {
        $aNew = [];
        if (!empty($a)) foreach ($a as $item) {
            if (is_array($item)) foreach ($item as $item2) {
                $aNew[] = substr($item2, -2);
            }
        }

        return array_unique($aNew);
    }
    private function combinLoxien($chars, $size, $combinations = array())
    {
        if (empty($combinations)) {
            $combinations = $chars;
        }
        if ($size == 1) {
            return $combinations;
        }

        $new_combinations = array();

        foreach ($combinations as $combination) {
            $arrCombin = explode('_', $combination);
            foreach ($chars as $char) {
                if (in_array($char, $arrCombin) != TRUE) {
                    $new_combinations[] = "{$combination}_{$char}";
                }
            }
        }
        return $this->combinLoxien($chars, $size - 1, $new_combinations);
    }
    private function clearToHopDuplicate($data)
    {
        $tmp = [];
        if (!empty($data)) foreach ($data as $numberCouple => $count) {
            $arrNum = explode('_', $numberCouple);
            arsort($arrNum);
            $tmp[] = implode('_', $arrNum);
        }
        $dataNew = array_unique($tmp);

        $rs = [];
        if (!empty($dataNew)) foreach ($dataNew as $item) {
            if (!empty($data[$item])) $rs[$item] = $data[$item];
        }
        return $rs;
    }
    private function checkNumberCombinShow($content, $number)
    {
        $number = str_replace('_', '\_', $number);
        $pattern = "/($number)/";
        if (is_array($content)) $content = json_encode($content);
        preg_match_all($pattern, $content, $total);
        return count($total[0]) > 0 ? true : false;
    }
    // ==============================================================
}
