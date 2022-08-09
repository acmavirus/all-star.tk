<?php

/**
 * Created by PhpStorm.
 * User: ducto
 * Date: 8/9/2018
 * Time: 3:07 PM
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Statistic extends API_Controller
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

    private function __checkRaw($dataRaw, $key)
    {
        // region
        if (strpos($key, 'region') > 0) {
            if (!empty($dataRaw->region)) $region = $dataRaw->region;
            if (!isset($region)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số "region"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            if (is_numeric($region) == false) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! sai tham số region = 1,2,3'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        }
        // prize
        if (strpos($key, 'prize') > 0) {
            if (!empty($dataRaw->prize)) $prize = $dataRaw->prize;
            if (!isset($prize)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số "prize"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            if (is_array(json_decode($prize, true)) == false) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! sai tham số prize = ["2","3","4","5","6","7","8"]'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        }
        //number
        if (strpos($key, 'number') > 0) {
            if (!empty($dataRaw->number)) $number = $dataRaw->number;
            if (!isset($number)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số "number"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            if (!is_numeric($number) || !in_array(strlen($number), ['2', '3', '4', '5', '6'])) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! sai tham số num = Dãy số là số nguyên và từ 2 đến 6 ký tự'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        }
        //round
        if (strpos($key, 'round') > 0) {
            if (!empty($dataRaw->round)) $round = $dataRaw->round;
            if (!isset($round)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số "round"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            if (is_numeric($round) == false) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Dãy số là số nguyên'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        }
        // type
        if (strpos($key, 'type') > 0) {
            if (!empty($dataRaw->type)) $type = $dataRaw->type;
            if (!isset($type)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số "type"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            if (!in_array($type, ['1', '2', '3'])) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! sai tham số type = 1,2,3'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        }
        //radio
        if (strpos($key, 'radio') > 0) {
            if (!empty($dataRaw->radio)) $radio = json_decode($dataRaw->radio);
            if (!isset($radio)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số "radio"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $d = 0;
            if (in_array(1, $radio)) $d++;
            if (in_array(2, $radio)) $d++;
            if (in_array(3, $radio)) $d++;
            if ($d !== count($radio)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! sai tham số radio = 1,2,3'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        }
        //date_begin
        if (strpos($key, 'date_begin') > 0) {
            if (!empty($dataRaw->date_begin)) $date_begin = $dataRaw->date_begin;
            if (!isset($date_begin)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số "date_begin"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            if (strtotime($dataRaw->date_begin) == false) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! sai tham số date_begin = not datetime'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        }
        //date_end
        if (strpos($key, 'date_end') > 0) {
            if (!empty($dataRaw->date_end)) $date_end = $dataRaw->date_end;
            if (!isset($date_end)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số "date_end"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            if (strtotime($dataRaw->date_end) == false) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! sai tham số date_end = not datetime and date_end > date_begin'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        }
        return $dataRaw;
    }

    public function thongkelo()
    {
        $dataRaw = $this->__checkRaw((object) $this->input->get(), '[region, prize, number, date_begin, date_end]');
        if (!empty($dataRaw)) {
            $listcat = $dataRaw->region;
            if (!empty($dataRaw->region) && ($dataRaw->region == 2 || $dataRaw->region == 3)) {
                $this->_data_category->_recursive_child_id($this->_data_category->_all_category(), $dataRaw->region);
                $listcat = $this->_data_category->_list_category_child_id;
            };

            $result = $this->_data->getFromDayToDaySelect(
                date('Y-m-d', strtotime($dataRaw->date_begin)),
                date('Y-m-d', strtotime($dataRaw->date_end)),
                $listcat,
                $dataRaw->prize,
                'data_result,category_id,displayed_time'
            );

            $data = [
                'dayso' => $this->result__dayso($result, $dataRaw->number),
                'khongxhln' => $this->result__khongxhln($dataRaw->region, 12),
                'ralientiep' => $this->result__ralientiep($dataRaw->region, 3),
                'xuathiennn3n' => $this->result__xuathiennn($dataRaw->region, 3, 5),
                'xuathiennn7n' => $this->result__xuathiennn($dataRaw->region, 7, 7),
                'hangchuc' => $this->result__hangchuc($dataRaw->region),
                'donvi' => $this->result__donvi($dataRaw->region),
            ];
            // return
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        };
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function thongkegan()
    {
        $dataRaw = $this->__checkRaw((object) $this->input->get(), '[region, number, type]');
        if (!empty($dataRaw)) {
            $listcat = $dataRaw->region;
            if (!empty($dataRaw->region) && ($dataRaw->region == 2 || $dataRaw->region == 3)) {
                $this->_data_category->_recursive_child_id($this->_data_category->_all_category(), $dataRaw->region);
                $listcat = $this->_data_category->_list_category_child_id;
            };
            $dataResult = $this->_data->getDataResultStatistic($listcat, null, "ASC");
            $numArr = [];
            for ($i = 0; $i <= 99; $i++) {
                $num = sprintf("%'02d", $i);
                $gan = 0;
                $maxGan = 0;
                $date = null;
                $maxGanDate = null;
                if (!empty($dataResult)) foreach ($dataResult as $item) {
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
                if ($gan < 0) continue;
                $start = strtotime($date);
                if (date('H') >= 16) {
                    $end = strtotime(date('Y-m-d'));
                } else {
                    $end = strtotime(date('Y-m-d', strtotime('-1 days')));
                }
                $tmp['count'] = $gan;
                $tmp['max_gan']['count'] = $maxGan;
                if ($maxGanDate !== null) $tmp['max_gan']['date_start'] = date('Y-m-d', strtotime("-$maxGan days", strtotime($maxGanDate)));
                $tmp['max_gan']['date_end'] = $maxGanDate;
                $tmp['date'] = $date;
                $tmp['number'] = $num;
                $tmp['date_start'] = date('d-m-Y', $start);
                $tmp['date_end'] = date('d-m-Y', $end);
                $tmp['dayyettocome'] = ceil(abs($end - $start) / 86400);
                $numArr[] = $tmp;
            }
            $listNumber = $this->array_group_by($numArr, 'number');
            $data = [
                'gan' => $listNumber[$dataRaw->number],
                'khongxhln' => $this->result__khongxhln($dataRaw->region, 9),
                'ralientiep' => $this->result__ralientiep($dataRaw->region, 6),
                'xuathiennn3n' => $this->result__xuathiennn($dataRaw->region, 3, 5),
                'xuathiennn7n' => $this->result__xuathiennn($dataRaw->region, 7, 7),
                'hangchuc' => $this->result__hangchuc($dataRaw->region),
                'donvi' => $this->result__donvi($dataRaw->region),
            ];
            // return
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        };
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function thongketansuat()
    {
        $dataRaw = $this->__checkRaw((object) $this->input->get(), '[region, round, type]');
        if (!empty($dataRaw)) {
            $listcat = $dataRaw->region;
            if (!empty($dataRaw->region) && ($dataRaw->region == 2 || $dataRaw->region == 3)) {
                $this->_data_category->_recursive_child_id($this->_data_category->_all_category(), $dataRaw->region);
                $listcat = $this->_data_category->_list_category_child_id;
            };
            $result = $this->_data->getDataResultStatistic($listcat, $dataRaw->round);
            $tansuat = $this->getTanSuatLoto($result);
            $data = [
                'tansuat' => $tansuat,
                'khongxhln' => $this->result__khongxhln($dataRaw->region, 9),
                'ralientiep' => $this->result__ralientiep($dataRaw->region, 6),
                'xuathiennn3n' => $this->result__xuathiennn($dataRaw->region, 3, 5),
                'xuathiennn7n' => $this->result__xuathiennn($dataRaw->region, 7, 7),
                'hangchuc' => $this->result__hangchuc($dataRaw->region),
                'donvi' => $this->result__donvi($dataRaw->region),
            ];
            // return
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        };
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function tansuatchitiet()
    {
        $dataRaw = $this->__checkRaw((object) $this->input->get(), '[region, round, date_end]');
        if (!empty($dataRaw)) {
            $listcat = $dataRaw->region;
            if (!empty($dataRaw->region) && ($dataRaw->region == 2 || $dataRaw->region == 3)) {
                $this->_data_category->_recursive_child_id($this->_data_category->_all_category(), $dataRaw->region);
                $listcat = $this->_data_category->_list_category_child_id;
            };
            $dataResult = $this->_data->getResultNearest(date('Y-m-d', strtotime($dataRaw->date_end)), $listcat, $dataRaw->round);
            $data['data'] = $this->getTanSuatLoto($dataResult);
            $listDb = [];
            $listDate = [];
            foreach ($dataResult as $item) {
                $result = json_decode($item['data_result'], true);
                if (count($result[0]) == 1) {
                    $num = substr($result[0][0], -2, 2);
                } else $num = substr($result[1][0], -2, 2);
                $listDb[] = $num;
                $listDate[] = $item['displayed_time'];
            }
            $listDb = array_count_values($listDb);
            $data['listDb'] = $listDb;
            $data['listDate'] = $listDate;
            $rs = [
                'tansuat_loto' => $data
            ];
            // return
            $dataJson = $this->pack($rs, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        };
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function thongkechanle()
    {
        $dataRaw = (object) $this->input->get();
        if (!empty($dataRaw)) {
            $rs = $this->_data->getKenoByDisplayed_time(date("Y-m-d"));
            $chan = [];
            $le   = [];
            $lon  = [];
            $nho  = [];
            foreach ($rs as $key => $value) {
                $info_keno = json_decode($value['info_keno']);
                $chan[] = substr("000$info_keno[0]", -2, 2);
                $le[] = substr("000$info_keno[1]", -2, 2);
                $lon[] = substr("000$info_keno[2]", -2, 2);
                $nho[] = substr("000$info_keno[3]", -2, 2);
                # code...
            };
            $data = [
                'chan' => $chan,
                'le' => $le,
                'lon' => $lon,
                'nho' => $nho,
                'kyketiep' => $this->result__kyketiep(null)
            ];

            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        };
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function thongkeboso()
    {
        $dataRaw = $this->__checkRaw((object) $this->input->get(), '[region]');
        if (!empty($dataRaw)) {
            $cat = $dataRaw->region;
            if (!empty($dataRaw->region) && ($dataRaw->region == 2 || $dataRaw->region == 3)) {
                $this->_data_category->_recursive_child_id($this->_data_category->_all_category(), $dataRaw->region);
                $dataRaw->region = $this->_data_category->_list_category_child_id;
            };

            $data = [
                'nhieunhat' => $this->result__xuathiennn($dataRaw->region, 30, 100),
                'itnhat' => $this->result__xuathienin($dataRaw->region, 30, 200),
                'kyketiep' => $this->result__kyketiep(null)
            ];
            $data['kyketiep'] = $this->result__kyketiep();
            // return
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        };
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function thongkechuave()
    {
        $dataRaw = $this->__checkRaw((object) $this->input->get(), '[region]');
        if (!empty($dataRaw)) {
            $cat = $dataRaw->region;
            if (!empty($dataRaw->region) && ($dataRaw->region == 2 || $dataRaw->region == 3)) {
                $this->_data_category->_recursive_child_id($this->_data_category->_all_category(), $dataRaw->region);
                $dataRaw->region = $this->_data_category->_list_category_child_id;
            };

            $data = [
                'chuave' => $this->result__khongxhln($dataRaw->region, 100),
                'lientiep' => $this->result__ralientiep($cat, 100),
                'kyketiep' => $this->result__kyketiep(null)
            ];
            // return
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        };
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function thongkedauduoi()
    {
        $dataRaw = $this->__checkRaw((object) $this->input->get(), '[region]');
        if (!empty($dataRaw)) {
            $cat = $dataRaw->region;
            if (!empty($dataRaw->region) && ($dataRaw->region == 2 || $dataRaw->region == 3)) {
                $this->_data_category->_recursive_child_id($this->_data_category->_all_category(), $dataRaw->region);
                $dataRaw->region = $this->_data_category->_list_category_child_id;
            };

            $data = [
                'dauso' => $this->result__hangchuc($dataRaw->region),
                'duoiso' => $this->result__donvi($dataRaw->region),
                'kyketiep' => $this->result__kyketiep(null)
            ];
            // return
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        };
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function doveso()
    {
        $dataRaw = (object) $this->input->get();
        if (!empty($dataRaw)) {
            $api_id = $dataRaw->api_id;
            $number = $dataRaw->number;
            if (empty($api_id)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số "api_id"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            if (empty($dataRaw->date)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số "date" FORMAT: Y-m-d'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            if (empty($dataRaw->number)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số "number"  FORMAT: Y-m-d'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $date = date('Y-m-d', strtotime($dataRaw->date));

            if ($api_id == 2 || $api_id == 3) {
                $this->_data_category->_recursive_child_id($this->_data_category->_all_category(), $api_id);
                $api_id = $this->_data_category->_list_category_child_id;
            };

            $result = $this->_data->getResultNearest($date, $api_id);
            if (!empty($result)) {
                $data['result'] = [];
                $data_result = $this->result_doveso($result[0]['data_result']);
                $check = $this->array_group_by($data_result, 'number');
                if (!empty($data_result) && !empty($check[$number]) === true) {
                    $data['result']['status'] = 0;
                    $data['result']['data'] = $check[$number];
                } else {
                    $data['result']['status'] = 1;
                }
            }
            $data['data'] = $result;
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }
    // ==============================================================
    private function result__kyketiep($category = null)
    {
        $hour = date("H");
        $minute = date("i");
        if ($category == null) {
            $lastKeno = $this->_data->getByKeno(1, 1);
            $period   = $lastKeno[0]['period'];
            if ($hour > 21) $time = 1;
            if ($hour < 06) $time = 0;
            if ($hour > 21 || $hour < 06) {
                $data = [
                    'period' => $period + 1,
                    'displayed_time' => date("Y-m-d", strtotime("+$time day")) . " 06:10:00"
                ];
            } else {
                $time = time() + 600;
                $hourNEXT = date("H", $time);
                $minuteNEXT = substr(date("i", $time), -2, 1) . "0";
                $data = [
                    'period' => $period + 1,
                    'displayed_time' => date("Y-m-d", $time) . " $hourNEXT:$minuteNEXT:00"
                ];
            }
            return $data;
        }
    }
    private function result__dayso($result, $num)
    {
        $listNumber = [];
        if (!empty($result)) foreach ($result as $key => $item) {
            if (!empty($item['data_result'])) foreach (json_decode($item['data_result']) as $key => $value) {
                $check = '-' . implode('-', $value) . '-';
                if (strpos($check, $num . '-') > 0) {
                    $listNumber[] = [
                        'number' => $value,
                        'cate' => $item['category_id'],
                        'prize' => $key,
                        'displayed_time' => $item['displayed_time'],
                    ];
                }
            }
        };
        return $listNumber;
    }
    private function result__khongxhln($region, $returnResult)
    {
        $data = $this->_data->getDataResultStatistic($region, null, "ASC");
        $numArr = [];
        for ($i = 0; $i <= 99; $i++) {
            $num = sprintf("%'02d", $i);
            $gan = 0;
            $maxGan = 0;
            $date = null;
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
            if ($gan < 0) continue;
            $start = strtotime($date);
            if (date('H') >= 16) {
                $end = strtotime(date('Y-m-d'));
            } else {
                $end = strtotime(date('Y-m-d', strtotime('-1 days')));
            }
            $tmp['so'] = $num;
            $tmp['ngaychuara'] = ceil(abs($end - $start) / 86400);
            $numArr[] = $tmp;
        };
        usort($numArr, function ($a, $b) {
            return $a['ngaychuara'] < $b['ngaychuara'] ? 1 : -1;
        });
        foreach ($numArr as $key => $value) {
            if ($key >= $returnResult) break;
            $numArr2[] = $value;
        }
        return $numArr2;
    }

    private function result__ralientiep($region, $returnResult = 6)
    {
        $cat = $this->_data_category->getByIdCached($region);
        $code = $cat->code;
        $catDayPrize = json_decode($cat->weekday);
        if (!empty($region) && ($region == 2 || $region == 3)) {
            $this->_data_category->_recursive_child_id($this->_data_category->_all_category(), $region);
            $region = $this->_data_category->_list_category_child_id;
        };
        $dataResult = $this->_data->getResultNearest(date('Y-m-d', time()), $region, 3);
        $data = $this->getTanSuatLoto($dataResult);
        $new_array = [];
        foreach ($data as $key => $item) {
            $item_date = $item['date'];

            $date_default = '';
            $count = 1;
            $resultNextDay = '-1 days'; // cộng một ngày cho xsmb
            if (count($catDayPrize) == 1 && $code != 'xsmb') {
                $resultNextDay = '-7 days';  // tỉnh miền có 1 ngày quay 1 tuần   trừ 7 ngày
            };
            $maxday = 0;
            foreach ($item_date as $k => $itemDate) {
                if ($date_default == '') {
                    $date_default = $k; // set ngày đếm đầu tiên
                } else {
                    $defaultDay = new DateTime($date_default);
                    if (count($catDayPrize) == 2 && $code != 'xsmb') {
                        $resultDefaultDay = $defaultDay->format('N'); //nếu có 2 ngày quay lấy ra ngày bầng số
                        if ($catDayPrize[0] == $resultDefaultDay) {
                            $resultNextDay = '-' . ((7 - $catDayPrize[1]) + ($catDayPrize[0])) . ' days'; // so sánh lấy ngày kế tiếp
                        } else {
                            $resultNextDay = '-' . (($catDayPrize[1]) - ($catDayPrize[0])) . ' days';
                        }
                    };
                    $defaultDay->modify($resultNextDay);
                    $nextDate = $defaultDay->format('Y-m-d');
                    if ($nextDate === $k) {
                        $count++;
                        if ($count > $maxday) {
                            $maxday = $count;
                        }
                        $date_default = $k;
                    } else {
                        $date_default = $k;
                        $count = 1;
                    }
                }
            };
            // return ['number' => $item['number'], 'maxday' => $maxday, 'sum' => $item['sum']];
            array_push($new_array, ['number' => $item['number'], 'maxday' => $maxday, 'sum' => $item['sum']]);
        }
        usort($new_array, function ($a, $b) {
            return $a['maxday'] < $b['maxday'] ? 1 : -1;
        });
        foreach ($new_array as $key => $value) {
            if ($key >= $returnResult) break;
            $numArr2[] = $value;
        }
        return $numArr2;
    }

    private function result__xuathiennn($region, $limit = 3, $returnResult)
    {
        $data = $this->_data->getFromDayToDaySelect(date('Y-m-d', strtotime("-$limit days")), date('Y-m-d', time()), $region, null, "*");
        $numArr = [];
        for ($i = 0; $i <= 99; $i++) {
            $numOther = 0;
            $num = sprintf("%'02d", $i);
            $dateShow = [];
            $tmp = [];
            if (!empty($data)) foreach ($data as $item) {
                $dateDisplay = $item['displayed_time'];
                $result = $item['data_result'];
                $countShow = $this->countNumberShow($result, $num, $dateDisplay);
                if ($countShow > 0) if (!empty($dateShow[$dateDisplay])) {
                    $dateShow[$dateDisplay] = $dateShow[$dateDisplay] + $countShow;
                    $numOther = $numOther + $countShow;
                } else $dateShow[$dateDisplay] = $countShow;
                $numOther = $numOther + $countShow;
            }
            $tmp['date'] = key($dateShow);
            $tmp['count'] = count($dateShow);
            $tmp['sum'] = array_sum($dateShow);
            $tmp['number'] = $num;
            $numArr[] = $tmp;
        }
        usort($numArr, function ($a, $b) {
            return $a['count'] < $b['count'] ? 1 : -1;
        });
        $numArr2 = [];
        foreach ($numArr as $key => $value) {
            if ($key >= $returnResult) break;
            $numArr2[] = $value;
        }
        return $numArr2;
    }

    private function result__xuathienin($region, $limit = 3, $returnResult)
    {
        $data = $this->_data->getFromDayToDaySelect(date('Y-m-d', strtotime("-$limit days")), date('Y-m-d', time()), $region, null, "*");
        $numArr = [];
        for ($i = 0; $i <= 99; $i++) {
            $numOther = 0;
            $num = sprintf("%'02d", $i);
            $dateShow = [];
            $tmp = [];
            if (!empty($data)) foreach ($data as $item) {
                $dateDisplay = $item['displayed_time'];
                $result = $item['data_result'];
                $countShow = $this->countNumberShow($result, $num, $dateDisplay);
                if ($countShow > 0) if (!empty($dateShow[$dateDisplay])) {
                    $dateShow[$dateDisplay] = $dateShow[$dateDisplay] + $countShow;
                    $numOther = $numOther + $countShow;
                } else $dateShow[$dateDisplay] = $countShow;
                $numOther = $numOther + $countShow;
            }
            $tmp['date'] = key($dateShow);
            $tmp['count'] = count($dateShow);
            $tmp['sum'] = array_sum($dateShow);
            $tmp['number'] = $num;
            if (!empty($tmp['date'])) $numArr[] = $tmp;
        }
        usort($numArr, function ($a, $b) {
            return $a['count'] > $b['count'] ? 1 : -1;
        });
        $numArr2 = [];
        foreach ($numArr as $key => $value) {
            if ($key >= $returnResult) break;
            $numArr2[] = $value;
        }
        return $numArr2;
    }

    private function result__hangchuc($region)
    {
        $result = $this->_data->getFromDayToDaySelect(date('Y-m-d', strtotime("-3 days")), date('Y-m-d', time()), $region, null, "*");
        $listNumber = [];
        $arrNum = [];
        if (!empty($result)) foreach ($result as $key => $item) {
            if (!empty($item['data_result'])) foreach (json_decode($item['data_result']) as $key => $value) {
                if (is_array($value)) foreach ($value as $keychild => $child) {
                    $listNumber[]['number'] = substr($child, -2, 1);
                }
                else {
                    $listNumber[]['number'] = substr($value, -2, 1);
                }
            }
        };
        usort($listNumber, function ($a, $b) {
            return $a['number'] < $b['number'] ? 1 : -1;
        });
        $listNumber = $this->array_group_by($listNumber, 'number');
        $new = [];
        foreach ($listNumber as $key => $value) {
            if (is_numeric($key) == true) $new[$key] = count($value);
        };
        $fruitArrayObject = new ArrayObject($new);
        $fruitArrayObject->ksort();
        return $fruitArrayObject;
    }

    private function result__donvi($region)
    {
        $result = $this->_data->getFromDayToDaySelect(date('Y-m-d', strtotime("-3 days")), date('Y-m-d', time()), $region, null, "*");
        $listNumber = [];
        $arrNum = [];
        if (!empty($result)) foreach ($result as $key => $item) {
            if (!empty($item['data_result'])) foreach (json_decode($item['data_result']) as $key => $value) {
                if (is_array($value)) foreach ($value as $keychild => $child) {
                    $listNumber[]['number'] = substr($child, -1, 1);
                }
                else {
                    $listNumber[]['number'] = substr($value, -1, 1);
                }
            }
        };
        usort($listNumber, function ($a, $b) {
            return $a['number'] < $b['number'] ? 1 : -1;
        });
        $listNumber = $this->array_group_by($listNumber, 'number');
        $new = [];
        foreach ($listNumber as $key => $value) {
            if (is_numeric($key) == true) $new[$key] = count($value);
        };
        $fruitArrayObject = new ArrayObject($new);
        $fruitArrayObject->ksort();
        return $fruitArrayObject;
    }

    private function result_doveso($result)
    {
        $new_result = [];
        if (!empty($result)) foreach (json_decode($result) as $key => $value) {
            if (is_array($value)) foreach ($value as $keychild => $child) {
                $number = $child;
                $reward = $key;
                $new_result[] = [
                    'number' => $number,
                    'reward' => $reward,
                ];
            }
            else {
                $number = $value;
                $reward = $key;
                $new_result[] = [
                    'number' => $number,
                    'reward' => $reward
                ];
            }
        }
        return $new_result;
    }
    // ==============================================================
    public function logan($params)
    {
        $dataRaw = (object)$params;
        if (!empty($dataRaw)) {
            $code = strtolower($dataRaw->code);
            if (empty($code)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số mã tỉnh "code"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            if (!empty($dataRaw->date_end)) $date_end = date('Y-m-d', strtotime($dataRaw->date_end));
            else $date_end = date('Y-m-d');
            if (empty($date_end)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số ngày "date_end"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $bien_do = $dataRaw->bien_do;
            if (empty($bien_do)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số biên độ "bien_do"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $oneItem = $this->_data_category->getByCode($code);
            $this->_data_category->_recursive_child_id($this->_data_category->_all_category(), $oneItem->id);
            $dataResult = $this->_data->getDataResultStatistic($this->_data_category->_list_category_child_id, null, "ASC");
            $topGan = $this->getLogan($dataResult, $bien_do);

            usort($topGan, function ($a, $b) {
                return $b['count'] - $a['count'];
            });
            $data['data_top'] = $topGan;
            $data['data_list_number'] = $this->getLogan($dataResult);
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }
    public function loganxsp()
    {
        $input = $this->input->get();
        extract($input);
        if (empty($code)) die('Thieu tham so code');
        else $code = strtolower($code);
        if (empty($dateEnd)) $dateEnd = date('Y-m-d');
        if (empty($limit)) $limit = 10;

        $key = "logan-$code-$dateEnd-lm$limit";
        $data = $this->getCache($key);
        if (empty($data)) {
            $oneItem = $this->_data_category->getByCode($code);
            $dataResult = $this->_data->getResultNearest($dateEnd, $oneItem->id, 0);
            $dataResult = array_reverse($dataResult);
            $topGan = $this->getLogan($dataResult, $limit);

            usort($topGan, function ($a, $b) {
                return $b['count'] - $a['count'];
            });
            $data['data_top'] = $topGan;
            $data['data_list_number'] = $this->getLogan($dataResult);
            $data = json_encode($data);
            $this->setCache($key, $data, 60 * 30);
        }
        echo $data;
        exit();
    }

    public function tansuat_loto()
    {
        $dataRaw = (object) $this->input->get();
        if (!empty($dataRaw)) {
            $code = strtolower($dataRaw->code);
            if (empty($code)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số mã tỉnh "code"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $dateEnd = date('Y-m-d', strtotime($dataRaw->date_end));
            if (empty($dateEnd)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số biên độ "date_end"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $limit = $dataRaw->limit;
            if (empty($limit)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số biên độ "limit"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $oneItem = $this->_data_category->getByCode($code);
            $listId = $oneItem->id;

            if ($oneItem->parent_id == 0) {
                $this->_data_category->_recursive_child_id($this->_all_category, $oneItem->id);
                $listId = $this->_data_category->_list_category_child_id;
            }

            $dataResult = $this->_data->getResultNearest($dateEnd, $listId, $limit);

            $data['data'] = $this->getTanSuatLoto($dataResult);
            $listDb = [];
            foreach ($dataResult as $item) {
                $result = json_decode($item['data_result'], true);
                if (count($result[0]) == 1) {
                    $num = substr($result[0][0], -2, 2);
                } else $num = substr($result[1][0], -2, 2);
                $listDb[] = $num;
                $listDate[] = $item['displayed_time'];
            }
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
        $code = $this->input->post('code');
        if ($this->_data_category->getByCode($code) == null) {
            $code = 'xsmb';
        }
        $new_data['onecat'] = $oneCat = $this->_data_category->getByCode($code);
        $time = $this->input->post('time');

        $CacheKey = 'TK_dbvnn_' . $code . '_' . $time;
        if (!empty($this->getCache($CacheKey))) {
            echo $this->getCache($CacheKey);
        } else {
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
            $data = $this->getByMonth($post_field);
            $data = json_decode($data);
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
            $this->setCache($CacheKey, $new_html);
            echo $new_html;
        }
    }

    public function dauduoi_loto()
    {
        $dataRaw = json_decode($this->request->getRawBody());
        if (!empty($dataRaw)) {
            $code = strtolower($dataRaw->code);
            if (empty($code)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số mã tỉnh "code"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $dateBegin = date('Y-m-d', strtotime($dataRaw->date_begin));
            if (empty($dateBegin)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số biên độ "bien_do"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $dateEnd = date('Y-m-d', strtotime($dataRaw->date_end));
            if (empty($dateEnd)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số biên độ "bien_do"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $oneItem = $this->_data_category->getByCode($code);
            $this->_data_category->_recursive_child_id($this->_data_category->_all_category(), $oneItem->id);
            if ($oneItem->parent_id == 0)
                $dataResult = $this->_data->getFromDayToDay($dateBegin, $dateEnd, $this->_data_category->_list_category_child_id);
            else {
                $limit = intval((strtotime($dateEnd) - strtotime($dateBegin)) / 86400) + 1;
                $dataResult = $this->_data->getResultNearest($dateEnd, $this->_data_category->_list_category_child_id, $limit);
            }
            uasort($dataResult, function ($a, $b) {
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

    public function loxien2()
    {
        $dataRaw = json_decode($this->request->getRawBody());
        if (!empty($dataRaw)) {
            $code = strtolower($dataRaw->code);
            if (empty($code)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số mã tỉnh "code"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $limit = $dataRaw->limit;
            if (empty($limit)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số lần quay "limit"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $dateEnd = date('Y-m-d', strtotime($dataRaw->date_end));
            if (empty($dateEnd)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số ngày "date"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');

            $oneItem = $this->_data_category->getByCode($code);
            $this->_data_category->_recursive_child_id($this->_data_category->_all_category(), $oneItem->id);

            $params = [
                'select' => 'data_result, displayed_time',
                'is_status' => 1,
                'category_id' => $this->_data_category->_list_category_child_id,
                'until' => $dateEnd,
                'order' => ['displayed_time' => 'DESC'],
                'limit' => $limit
            ];
            $dataResult = $this->_data->getData($params, 'array');
            $data['data'] = $this->getLoXien($dataResult, 2);
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function loxien3()
    {
        $dataRaw = json_decode($this->request->getRawBody());
        if (!empty($dataRaw)) {
            $code = strtolower($dataRaw->code);
            if (empty($code)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số mã tỉnh "code"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $limit = $dataRaw->limit;
            if (empty($limit)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số lần quay "limit"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $dateEnd = date('Y-m-d', strtotime($dataRaw->date_end));
            if (empty($dateEnd)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số ngày "date"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');

            $oneItem = $this->_data_category->getByCode($code);
            $this->_data_category->_recursive_child_id($this->_data_category->_all_category(), $oneItem->id);

            $params = [
                'select' => 'data_result, displayed_time',
                'is_status' => 1,
                'category_id' => $this->_data_category->_list_category_child_id,
                'until' => $dateEnd,
                'order' => ['displayed_time' => 'DESC'],
                'limit' => $limit
            ];
            $dataResult = $this->_data->getData($params, 'array');
            $data['data'] = $this->getLoXien($dataResult, 3);
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function loxien2thinh()
    {
        $dataRaw = json_decode($this->request->getRawBody());
        if (!empty($dataRaw)) {
            $code = strtolower($dataRaw->code);
            if (empty($code)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số mã tỉnh "code"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $limit = $dataRaw->limit;
            if (empty($limit)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số lần quay "limit"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $dateEnd = date('Y-m-d', strtotime($dataRaw->date_end));
            if (empty($dateEnd)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số ngày "date"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');

            $oneItem = $this->_data_category->getByCode($code);

            $dataResult = $this->_data->getResultNearest($dateEnd, $oneItem->id, $limit);

            $data['data'] = $this->getLoXien($dataResult, 2);
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function loxien3thinh()
    {
        $dataRaw = json_decode($this->request->getRawBody());
        if (!empty($dataRaw)) {
            $code = strtolower($dataRaw->code);
            if (empty($code)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số mã tỉnh "code"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $limit = $dataRaw->limit;
            if (empty($limit)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số lần quay "limit"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $dateEnd = date('Y-m-d', strtotime($dataRaw->date_end));
            if (empty($dateEnd)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số ngày "date"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');

            $oneItem = $this->_data_category->getByCode($code);

            $dataResult = $this->_data->getResultNearest($dateEnd, $oneItem->id, $limit);
            $data['data'] = $this->getLoXien($dataResult, 3);
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function lokep()
    {
        $dataRaw = json_decode($this->request->getRawBody());
        if (!empty($dataRaw)) {
            $code = strtolower($dataRaw->code);
            if (empty($code)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số mã tỉnh "code"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $limit = $dataRaw->limit;
            if (empty($limit)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số lần quay "limit"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');

            $oneItem = $this->_data_category->getByCode($code);
            $this->_data_category->_recursive_child_id($this->_data_category->_all_category(), $oneItem->id);
            $dataResult = $this->_data->getDataResultStatistic($this->_data_category->_list_category_child_id, $limit, "DESC");
            $data['data_kep'] = $this->getLoKep($dataResult);
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function lotong()
    {
        $dataRaw = json_decode($this->request->getRawBody());
        if (!empty($dataRaw)) {
            $code = strtolower($dataRaw->code);
            if (empty($code)) {
                $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số mã tỉnh "code"'));
                $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            }
            $sum = '';
            if (isset($dataRaw->sum)) {
                $sum = $dataRaw->sum;
            }

            $dateBegin = date('Y-m-d', strtotime($dataRaw->date_begin));
            if (empty($dateBegin)) {
                $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số biên độ "date_begin"'));
                $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            }
            $dateEnd = date('Y-m-d', strtotime($dataRaw->date_end));
            if (empty($dateEnd)) {
                $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số biên độ "date_end"'));
                $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            }
            $is_special = !empty($dataRaw->is_special) ? true : false;

            $oneItem = $this->_data_category->getByCode($code);
            $this->_data_category->_recursive_child_id($this->_data_category->_all_category(), $oneItem->id);

            $oneParent = $this->_data_category->_recursive_one_parent($this->_all_category, $oneItem->id);
            $dataResult = $this->_data->getDataResultStatisticByDate($this->_data_category->_list_category_child_id, $dateBegin, $dateEnd, "ASC");

            $data['data'] = $this->getTongDauDuoiLoto($dataResult, $sum, $oneParent, $is_special);
            $data['data_sum_recently'] = $this->getTongDauDuoiLotoOneResult(end($dataResult));
            if (!empty($dataResult)) foreach ($dataResult as $item) {
                $data['data_sum'][] = $this->getTongDauDuoiLotoOneResult($item);
            }

            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function checkDataRaw()
    {
    }

    private function getTongDauDuoiLoto($data, $sumCheck, $oneParent = null, $is_special = false)
    {
        $numArr = [];
        for ($i = 0; $i <= 99; $i++) {
            $num = sprintf("%'02d", $i);
            $head = substr($num, 0, 1);
            $tail = substr($num, -1);
            $sum = $head + $tail;
            $totalHeadTail = substr($sum, -1);
            if ($sumCheck != '' && $totalHeadTail != $sumCheck) continue;
            $dateShow = '';
            $countNoShow = 0;
            $countShow = 0;
            $tmp = [];
            $tmpGan = [];

            if (!empty($data)) foreach ($data as $item) {
                $dateDisplay = $item['displayed_time'];
                $result = $item['data_result'];
                if ($is_special == true) {
                    $resultArr = json_decode($result, true);
                    if (!empty($oneParent) && $oneParent->layout !== 'mt_mn') {
                        $result = json_encode($resultArr[1]);
                    } else {
                        $result = json_encode($resultArr[0]);
                    }
                }
                if ($this->checkNumberShow($result, $num) == false) {
                    $countNoShow++;
                } else {
                    //$countShow = $this->countNumberShow($result,$num)+1;
                    $countShow = $countShow + $this->countNumberShow($result, $num);
                    $tmpGan[] = $countNoShow + $this->countNumberShow($result, $num);
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

    private function getTongDauDuoiLotoOneResult($oneResult)
    {
        $numSumArr = [];
        $numHeadArr = [];
        $numTailArr = [];
        $result = json_decode($oneResult['data_result'], true);
        for ($i = 0; $i <= 9; $i++) {
            $tmp = [];
            $tmpHead = [];
            $tmpTail = [];
            if (!empty($result)) foreach ($result as $item) {
                foreach ($item as $number) {
                    $num = substr($number, -2);
                    if (is_numeric($num)) {
                        $head = substr($num, 0, 1);
                        $tail = substr($num, -1);
                        $sum = $head + $tail;
                        $totalHeadTail = substr($sum, -1);
                        if ($totalHeadTail == $i) {
                            $tmp[] = $num;
                        }

                        if ($head == $i) {
                            $tmpHead[] = $num;
                        }

                        if ($tail == $i) {
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

    private function getLoKep($data)
    {
        $numArr = [];
        $checkDuplicate = [];
        for ($i = 0; $i <= 99; $i++) {
            for ($j = 0; $j <= 99; $j++) {
                $num1 = sprintf("%'02d", $i);
                $num1head = substr($num1, 0, 1);
                $num1tail = substr($num1, -1);
                $num2 = sprintf("%'02d", $j);
                $num2head = substr($num2, 0, 1);
                $num2tail = substr($num2, -1);
                $capSo = $num1 . ' - ' . $num2;
                $capSoNguoc = $num2 . ' - ' . $num1;
                if ($num1 != $num2 && $num1head == $num1tail && $num2head == $num2tail && !in_array($capSoNguoc, $checkDuplicate)) {
                    $countNoShow = 0;
                    $songayve = [];
                    if (!empty($data)) foreach ($data as $item) {
                        $dateDisplay = $item['displayed_time'];
                        $result = $item['data_result'];
                        if ($this->checkNumberShow($result, $num1) == true && $this->checkNumberShow($result, $num2) == true) {
                            $countNoShow++;
                            $songayve[] = $dateDisplay;
                        }
                    }
                    if ($countNoShow > 0) {
                        $checkDuplicate[] = $capSo;
                        $tmp['cap_so'] = $capSo;
                        $tmp['count'] = $countNoShow;
                        $tmp['date'] = $songayve;
                        $numArr[] = $tmp;
                    }
                }
            }
        }
        usort($numArr, function ($a, $b) {
            return $b['count'] - $a['count'];
        });

        return $numArr;
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

    private function unique_multidim_array($array, $key)
    {
        $temp_array = array();
        $i = 0;
        $key_array = array();

        foreach ($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
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


    private function getTanSuatLoto($data)
    {
        $numArr = [];
        for ($i = 0; $i <= 99; $i++) {
            $numOther = 0;
            $num = sprintf("%'02d", $i);
            $dateShow = [];
            $tmp = [];
            if (!empty($data)) foreach ($data as $item) {
                $dateDisplay = $item['displayed_time'];
                $result = $item['data_result'];
                $countShow = $this->countNumberShow($result, $num, $dateDisplay);
                if ($countShow > 0) if (!empty($dateShow[$dateDisplay])) {
                    $dateShow[$dateDisplay] = $dateShow[$dateDisplay] + $countShow;
                    $numOther = $numOther + $countShow;
                } else $dateShow[$dateDisplay] = $countShow;
                $numOther = $numOther + $countShow;
            }
            $tmp['date'] = $dateShow;
            $tmp['count'] = count($dateShow);
            $tmp['sum'] = array_sum($dateShow);
            $tmp['number'] = $num;
            $numArr[] = $tmp;
        }
        return $numArr;
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
            if (date('H') >= 16) {
                $end = strtotime(date('Y-m-d'));
            } else {
                $end = strtotime(date('Y-m-d', strtotime('-1 days')));
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

    private function checkNumberShow($content, $number)
    {
        $pattern = "/($number\")/";
        if (is_array($content)) $content = json_encode($content);
        preg_match_all($pattern, $content, $total);
        return count($total[0]) > 0 ? true : false;
    }

    private function checkNumberCombinShow($content, $number)
    {
        $number = str_replace('_', '\_', $number);
        $pattern = "/($number)/";
        if (is_array($content)) $content = json_encode($content);
        preg_match_all($pattern, $content, $total);
        return count($total[0]) > 0 ? true : false;
    }

    private function countNumberShow($content, $number)
    {
        $pattern = "/($number\")/";
        if (is_array($content)) $content = json_encode($content);
        preg_match_all($pattern, $content, $total);
        return count($total[0]);
    }

    private function countHeadNumberShow($content, $number)
    {
        $pattern = "/({$number}[\d]\")/";
        if (is_array($content)) $content = json_encode($content);
        preg_match_all($pattern, $content, $total);
        return count($total[0]);
    }

    private function countTailNumberShow($content, $number)
    {
        $pattern = "/($number\")/";
        if (is_array($content)) $content = json_encode($content);
        preg_match_all($pattern, $content, $total);
        return count($total[0]);
    }

    private function getByMonth()
    {
        $dataRaw = json_decode($this->request->getRawBody());
        if (!empty($dataRaw)) {
            $code = $dataRaw->code;
            if (empty($code)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số mã tỉnh "code"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $dateBegin = date('Y-m-d', strtotime($dataRaw->date_begin));
            if (empty($dateBegin)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số biên độ "date_begin"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $dateEnd = date('Y-m-d', strtotime($dataRaw->date_end));
            if (empty($dateEnd)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số biên độ "date_end"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $oneItem = $this->_data_category->getByCode($code);
            $this->_data_category->_recursive_child_id($this->_data_category->_all_category(), $oneItem->id);
            $dataResult = $this->_data->getDataResultStatisticByDate($this->_data_category->_list_category_child_id, $dateBegin, $dateEnd);
            $dataByDate = [];
            $data2Number = [];

            if (!empty($dataResult)) foreach ($dataResult as $key => $item) {
                $item = (object) $item;
                $result = json_decode($item->data_result, true);
                if (!empty($result[1][0])) {
                    if ($code === 'xsmb') $rewardDB = $result[1][0];
                    else $rewardDB = $result[8][0];
                    if ($code == 'xsmt' || $code == 'xsmn') $dataByDate[$item->displayed_time][] = $rewardDB;
                    else $dataByDate[$item->displayed_time] = $rewardDB;
                    $data2Number[] = substr($rewardDB, -2, 2);
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
}
