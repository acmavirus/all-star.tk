<?php

/**
 * Created by PhpStorm.
 * User: ducto
 * Date: 8/9/2018
 * Time: 3:07 PM
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Statistic_loto extends API_Controller
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
    // ==================================== API DONE  ^ ^
    public function logan()
    {
        $dataRaw = (object) $this->input->get();
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

    public function logan_rongbachkim()
    {
        $dataRaw = (object) $this->input->get();

        if (!empty($dataRaw)) {
            if (empty($dataRaw->number)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số "number"'));
            $date_from = date("Y-m-d", strtotime(str_replace("/", "-", $dataRaw->from))) ?? date('Y-m-d', strtotime('- 365 days'));
            $date_to = date("Y-m-d", strtotime(str_replace("/", "-", $dataRaw->to)));
            $min = $dataRaw->min;

            $dataResult = $this->_data->getFromDayToDay($date_from, $date_to, 1);
            $num = sprintf("%'02d", $dataRaw->number);
            $gan = 0;
            $maxGan = 0;
            $date = null;
            $maxGanDate = null;
            $tmp = [];
            if (!empty($dataResult)) {
                foreach ($dataResult as $item) {
                    $dateDisplay = $item['displayed_time'];
                    $result = $item['data_result'];
                    if (!$this->checkNumberShow($result, $dataRaw->number)) {
                        $gan++;
                        if ($gan > $maxGan) {
                            $maxGan = $gan;
                            $maxGanDate = $date;
                        }
                    } else {
                        $gan = 0;
                        $date = $dateDisplay;
                        $tmp[$num]['max_date'][] = $date;
                    }
                }

                if (!empty($tmp[$num]['max_date'])) {
                    $data_date = array_reverse($tmp[$num]['max_date']);
                    for ($i = 0; $i <= count($data_date); $i++) {
                        if ($i % 2 == 0 && !empty($data_date[$i + 1])) {
                            $count = gmdate('d', strtotime($data_date[$i + 1]) - strtotime($data_date[$i]));
                            if ($count >= $min) {
                                $data_response[] = [
                                    'count' => $count,
                                    'date_from' => $data_date[$i],
                                    'date_to' => $data_date[$i + 1]
                                ];
                            }
                        }
                    }
                }
            }
            $dataJson = $this->pack($data_response, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        } else {
            $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        }
    }

    public function lottomax()
    {
        $dataResult = $this->_data->getFromDayToDay('2022-03-16', '2022-04-16', 1);
        $dataResult = $this->returnData($dataResult);
        foreach ($dataResult as $key => $item) {
            foreach ($item['data_result_2s'] as $key2 => $item2) {
                $data2[] = $item2;
            };
        };
        sort($data2);
        $data2 = array_count_values($data2);
        $dataxy = [];
        foreach ($data2 as $key => $value) {
            $dataxy[] = [
                'number' => $key,
                'count' => $value
            ];
        }
        usort($dataxy, function ($a, $b) {
            return $a['count'] < $b['count'] ? 1 : -1;
        });
        $dataJson = $this->pack($dataxy, self::RESPONSE_SUCCESS, 'Success');
        return $this->response->json($dataJson);
    }

    public function lottodblast()
    {
        $data = $this->_data->getDataResultStatistic(1, null, "ASC");
        $numArr = [];
        for ($i = 0; $i <= 99; $i++) {
            $num = sprintf("%'02d", $i);
            $gan = 0;
            $maxGan = 0;
            $date = null;
            if (!empty($data)) foreach ($data as $item) {
                $dateDisplay = $item['displayed_time'];
                $result = json_decode($item['data_result']);
                $result = json_encode($result[1]);
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
            $numArr2[] = $value;
        }
        $dataJson = $this->pack($numArr2, self::RESPONSE_SUCCESS, 'Success');
        return $this->response->json($dataJson);
    }

    public function thong_ke_tsloto()
    {
        $dataRaw = (object) $this->input->get();
        $capso = (!empty($dataRaw->capso)) ? explode('-', $dataRaw->capso) : explode('-', '00-99');
        $limit = (!empty($dataRaw->limit)) ? $dataRaw->limit : 30;
        $start = (!empty($dataRaw->start)) ? date("Y-m-d", strtotime("-$limit days", strtotime($dataRaw->end))) : date("Y-m-d", strtotime("-30 days"));
        $end = (!empty($dataRaw->end)) ? date("Y-m-d", strtotime($dataRaw->end)) : date("Y-m-d");
        $dataResult = $this->_data->getFromDayToDay($start, $end, 1);
        $dataResult = $this->getTanSuatLoto($dataResult);
        $newData = [];
        $number_arr = range($capso[0], $capso[1]);
        foreach ($dataResult as $key => $value) {
            $number = $value['number'];
            if (in_array($number, $number_arr)) {
                $newData[] = $value;
            }
        }
        $dataJson = $this->pack($newData, self::RESPONSE_SUCCESS, 'Success');
        return $this->response->json($dataJson);
    }

    public function thong_ke_tan_suat_loto()
    {
        $dataRaw = (object) $this->input->get();
        $capso = (!empty($dataRaw->capso)) ? explode('-', $dataRaw->capso) : explode('-', '00-10');
        $limit = (!empty($dataRaw->delta)) ? $dataRaw->delta : 20;
        $start = (!empty($dataRaw->from)) ? date("Y-m-d", strtotime(str_replace("/", "-", $dataRaw->from))) : date('Y-m-d', strtotime('-6 month'));
        $end = (!empty($dataRaw->to)) ? date("Y-m-d", strtotime(str_replace("/", "-", $dataRaw->to))) : date("Y-m-d");

        $dataResult = $this->_data->getFromDayToDay($start, $end, 1);
        // $dataResult = $this->getTanSuatLoto($dataResult);

        $strEND = strtotime($end);
        while ($strEND >= strtotime($start)) {
            $listData[] = [
                'end' => date("Y-m-d", strtotime("+$limit days", $strEND)),
                'start' => date("Y-m-d", $strEND)
            ];
            $strEND = strtotime("-1 days", $strEND);
        };

        foreach ($listData as $k => $value) {
            $data_result_2st = [];
            foreach ($dataResult as $key => $ivalue) {
                $start = strtotime($value['start']);
                $end = strtotime($value['end']);
                $date = strtotime($ivalue['displayed_time']);
                if ($date >= $start && $date <= $end) {
                    $data_result = json_decode($ivalue['data_result']);
                    $data_result_2s = [];
                    foreach ($data_result as $keyDR1 => $item) {
                        foreach ($item as $keyDR2 => $item2) {
                            if ($keyDR1 >  0) {
                                $data_result_2s[] = substr($item2, -2, 2);
                            };
                        };
                    };
                    $data_result_2st = array_merge($data_result_2st, $data_result_2s);
                };
            };
            $logan = array_count_values($data_result_2st);
            ksort($logan);
            $listData[$k]['logan'] = $logan;
        };
        $newData = [];
        $number_arr = range($capso[0], $capso[1]);

        foreach ($listData as $key => $item) {
            $start = $item['start'];
            $end = $item['end'];
            $logan = $item['logan'];
            foreach ($number_arr as $k => $number) {
                $num = sprintf("%'02d", $number);
                $newData[$number]['number'] = $num;
                $gannum = (isset($logan[$num])) ? $logan[$num] : 0;
                if (empty($newData[$number]['max'])) $newData[$number]['max'] = 1;
                if ($gannum > $newData[$number]['max']) $newData[$number]['max'] = $gannum;
                $newData[$number]['data'][] = [
                    'start' => $start,
                    'end' => $end,
                    'count' => $gannum
                ];
            }
        }

        $dataJson = $this->pack($newData, self::RESPONSE_SUCCESS, 'Success');
        return $this->response->json($dataJson);
    }

    public function thong_ke_tan_suat_caplon()
    {
        $dataRaw = (object) $this->input->get();
        $capso = (!empty($dataRaw->capso)) ? explode('-', $dataRaw->capso) : explode('-', '00-05');
        $limit = (!empty($dataRaw->delta)) ? $dataRaw->delta : 20;
        $start = (!empty($dataRaw->from)) ? date("Y-m-d", strtotime(str_replace("/", "-", $dataRaw->from))) : date('Y-m-d', strtotime('-6 month'));
        $end = (!empty($dataRaw->to)) ? date("Y-m-d", strtotime(str_replace("/", "-", $dataRaw->to))) : date("Y-m-d");

        $dataResult = $this->_data->getFromDayToDay($start, $end, 1);

        $strEND = strtotime($end);
        while ($strEND >= strtotime($start)) {
            $listData[] = [
                'end' => date("Y-m-d", strtotime("+$limit days", $strEND)),
                'start' => date("Y-m-d", $strEND)
            ];
            $strEND = strtotime("-1 days", $strEND);
        };

        foreach ($listData as $k => $value) {
            $data_result_2st = [];
            foreach ($dataResult as $key => $ivalue) {
                $start = strtotime($value['start']);
                $end = strtotime($value['end']);
                $date = strtotime($ivalue['displayed_time']);
                if ($date >= $start && $date <= $end) {
                    $data_result = json_decode($ivalue['data_result']);
                    $data_result_2s = [];
                    foreach ($data_result as $keyDR1 => $item) {
                        foreach ($item as $keyDR2 => $item2) {
                            if ($keyDR1 >  0) {
                                $data_result_2s[] = substr($item2, -2, 2);
                            };
                        };
                    };
                    $data_result_2st = array_merge($data_result_2st, $data_result_2s);
                };
            };
            $logan = array_count_values($data_result_2st);
            ksort($logan);
            $listData[$k]['logan'] = $logan;
        };
        $newData = [];
        $number_arr1 = ltrim($capso[0], '0');
        if ($number_arr1 == '') $number_arr1 = "0";
        $number_arr2 = ltrim($capso[1], '0');
        if ($number_arr2 == '') $number_arr2 = "0";

        $capl = ["00,55", "01,10", "02,20", "03,30", "04,40", "05,50", "06,60", "07,70", "08,80", "09,90", "11,66", "12,21", "13,31", "14,41", "15,51", "16,61", "17,71", "18,81", "19,91", "22,77", "23,32", "24,42", "25,52", "26,62", "27,72", "28,82", "29,92", "33,88", "34,43", "35,53", "36,63", "37,73", "38,83", "39,93", "44,99", "45,54", "46,64", "47,74", "48,84", "49,94", "56,65", "57,75", "58,85", "59,95", "67,76", "68,86", "69,96", "78,87", "79,97", "89,98"];

        $newCapl = [];
        foreach ($capl as $key => $cl) {
            $cl = explode(',', $cl);
            $number_cl1 = ltrim($cl[0], '0');
            if ($number_cl1 == '') $number_cl1 = "0";
            $number_cl2 = ltrim($cl[1], '0');
            if ($number_arr1 <= $number_cl1 && $number_arr2 >= $number_cl1) $newCapl[] = $cl;
            # code...
        };

        foreach ($listData as $key => $item) {
            $start = $item['start'];
            $end = $item['end'];
            $logan = $item['logan'];
            foreach ($newCapl as $k => $number) {
                $num1 = sprintf("%'02d", $number[0]);
                $num2 = sprintf("%'02d", $number[1]);
                $keyx  = $num1 . "_" . $num2;
                if (empty($logan[$num1]) && empty($logan[$num2])) $count = 0;
                if (!empty($logan[$num1]) && empty($logan[$num2])) $count = $logan[$num1];
                if (empty($logan[$num1]) && !empty($logan[$num2])) $count = $logan[$num2];
                if (!empty($logan[$num1]) && !empty($logan[$num2]) && $logan[$num1] >= $logan[$num2]) $count = $logan[$num1];
                if (!empty($logan[$num1]) && !empty($logan[$num2]) && $logan[$num1] < $logan[$num2]) $count = $logan[$num2];

                if (empty($newData[$keyx]['max'])) $newData[$keyx]['max'] = 1;
                if ($count > $newData[$keyx]['max']) $newData[$keyx]['max'] = $count;
                $newData[$keyx]['data'][] =  [
                    'start' => $start,
                    'end'   => $end,
                    'count' => $count
                ];
            }
        }

        $dataJson = $this->pack($newData, self::RESPONSE_SUCCESS, 'Success');
        return $this->response->json($dataJson);
    }

    public function thong_ke_dauduoi()
    {
        $dataRaw = (object) $this->input->get();
        $capso = (!empty($dataRaw->capso)) ? explode('-', $dataRaw->capso) : explode('-', '0-9');
        $limit = (!empty($dataRaw->delta)) ? $dataRaw->delta : 7;
        $start = (!empty($dataRaw->from)) ? date("Y-m-d", strtotime(str_replace("/", "-", $dataRaw->from))) : date('Y-m-d', strtotime('-6 month'));
        $end = (!empty($dataRaw->to)) ? date("Y-m-d", strtotime(str_replace("/", "-", $dataRaw->to))) : date("Y-m-d");

        $dataResult = $this->_data->getFromDayToDay($start, $end, 1);
        // $dataResult = $this->getTanSuatLoto($dataResult);

        $strEND = strtotime($end);
        while ($strEND >= strtotime($start)) {
            $listData[] = [
                'end' => date("Y-m-d", strtotime("+$limit days", $strEND)),
                'start' => date("Y-m-d", $strEND)
            ];
            $strEND = strtotime("-1 days", $strEND);
        };

        foreach ($listData as $k => $value) {
            $data_result_headst = [];
            $data_result_tailst = [];
            foreach ($dataResult as $key => $ivalue) {
                $start = strtotime($value['start']);
                $end = strtotime($value['end']);
                $date = strtotime($ivalue['displayed_time']);
                if ($date >= $start && $date <= $end) {
                    $data_result = json_decode($ivalue['data_result']);
                    $data_result_head = [];
                    $data_result_tail = [];
                    foreach ($data_result as $keyDR1 => $item) {
                        foreach ($item as $keyDR2 => $item2) {
                            if ($keyDR1 >  0) {
                                $HEAD = substr($item2, -2, 1);
                                $TAIL = substr($item2, -1, 1);
                                $data_result_head[] = $HEAD;
                                $data_result_tail[] = $TAIL;
                            };
                        };
                    };
                    $data_result_headst = array_merge($data_result_headst, $data_result_head);
                    $data_result_tailst = array_merge($data_result_tailst, $data_result_tail);
                };
            };
            $head = array_count_values($data_result_headst);
            ksort($head);
            $tail = array_count_values($data_result_tailst);
            ksort($tail);
            $listData[$k]['head'] = $head;
            $listData[$k]['tail'] = $tail;
        };

        $newData = [];
        $number_arr = range($capso[0], $capso[1]);

        foreach ($listData as $key => $value) {
            $value = (object) $value;
            # code...
            $start = $value->start;
            $end = $value->end;
            $head = $value->head;
            $tail = $value->tail;

            foreach ($head as $kh => $v) {
                if (in_array($kh, $number_arr)) {
                    $num = sprintf("%'02d", $kh);
                    $ki = substr($num, -1, 1);
                    $newData['head'][$ki]['number'] = $num;
                    if (empty($newData['head'][$ki]['max'])) $newData['head'][$ki]['max'] = $v;
                    if (!empty($newData['head'][$ki]['max']) && ($newData['head'][$ki]['max'] < $v)) $newData['head'][$ki]['max'] = $v;
                    $newData['head'][$ki]['data'][] =  [
                        'start' => $start,
                        'end'   => $end,
                        'count' => $v
                    ];
                }
            }

            foreach ($tail as $k => $v) {
                if (in_array($k, $number_arr)) {
                    $num = sprintf("%'02d", $k);
                    $kta = substr($num, -1, 1);
                    $newData['tail'][$kta]['number'] = $num;
                    if (empty($newData['tail'][$kta]['max'])) $newData['tail'][$kta]['max'] = $v;
                    if (!empty($newData['tail'][$kta]['max']) && ($newData['tail'][$kta]['max'] < $v)) $newData['tail'][$kta]['max'] = $v;
                    $newData['tail'][$kta]['data'][] =  [
                        'start' => $start,
                        'end'   => $end,
                        'count' => $v
                    ];
                }
            }
        }

        ksort($newData['tail']);

        $dataJson = $this->pack($newData, self::RESPONSE_SUCCESS, 'Success');
        return $this->response->json($dataJson);
    }

    // ===================================
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
            if ($date) {
                $tmp['count'] = $gan;
                $tmp['max_gan']['count'] = $maxGan;
                $tmp['max_gan']['date'] = $maxGanDate;
                $tmp['date'] = $date;
                $tmp['number'] = $num;
                $tmp['dayyettocome'] = ceil(abs($end - $start) / 86400);
                $numArr[] = $tmp;
            }
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

    private function returnData($data)
    {
        foreach ($data as $key => $value) {
            if ($data[$key]['category_id'] == 1) {
                $data_result = json_decode($data[$key]['data_result']);
                $data[$key]['data_result'] = $data_result;
                unset($data[$key]['id']);
                unset($data[$key]['category_id']);
                $data[$key]['data_result_db'] = [];
                $data[$key]['data_result_2s'] = [];
                foreach ($data_result as $keyDR1 => $item) {
                    foreach ($item as $keyDR2 => $item2) {
                        if ($keyDR1 == 0) {
                            $data[$key]['data_result_db'][] = $item2;
                        };
                        if ($keyDR1 >  0) {
                            $data[$key]['data_result_2s'][] = substr($item2, -2, 2);
                        };
                    };
                };
                $data[$key]['data_result_head'] = [];
                foreach ($data_result as $keyDR1 => $item) {
                    foreach ($item as $keyDR2 => $item2) {
                        if ($keyDR1 > 0) {
                            $data[$key]['data_result_head'][] = substr($item2, -2, 1);
                        };
                    };
                };
                $data[$key]['data_result_tail'] = [];
                foreach ($data_result as $keyDR1 => $item) {
                    foreach ($item as $keyDR2 => $item2) {
                        if ($keyDR1 > 0) {
                            $data[$key]['data_result_tail'][] = substr($item2, -1, 1);
                        };
                    };
                };
            } else {
                $data_result = json_decode($data[$key]['data_result']);
                $data[$key]['data_result'] = $data_result;
                unset($data[$key]['id']);
                unset($data[$key]['category_id']);
                $data[$key]['data_result_db'] = [];
                $data[$key]['data_result_2s'] = [];
                foreach ($data_result as $keyDR1 => $item) {
                    foreach ($item as $keyDR2 => $item2) {
                        if ($keyDR1 == 0) {
                            $data[$key]['data_result_db'][] = $item2;
                        };
                        if ($keyDR1 >=  0) {
                            $data[$key]['data_result_2s'][] = substr($item2, -2, 2);
                        };
                    };
                };
                $data[$key]['data_result_head'] = [];
                foreach ($data_result as $keyDR1 => $item) {
                    foreach ($item as $keyDR2 => $item2) {
                        if ($keyDR1 >= 0) {
                            $data[$key]['data_result_head'][] = substr($item2, -2, 1);
                        };
                    };
                };
                $data[$key]['data_result_tail'] = [];
                foreach ($data_result as $keyDR1 => $item) {
                    foreach ($item as $keyDR2 => $item2) {
                        if ($keyDR1 >= 0) {
                            $data[$key]['data_result_tail'][] = substr($item2, -1, 1);
                        };
                    };
                };
            }
        };
        return $data;
    }

    private function returnRS($data)
    {
        $rs = [];

        foreach ($data as $k => $item) {
            $dataTEXT = "=" . implode('=', $item) . "=";
            $caplon = [
                '00,55', '01,10', '02,20', '03,30', '04,40', '05,50', '06,60', '07,70', '08,80', '09,90'
            ];
            foreach ($caplon as $key => $value) {
                $caplonRS = explode(',', $value);
                $clRS1    = $caplonRS[0];
                $clRS2    = $caplonRS[1];
                $strRS1   = substr_count($dataTEXT, $clRS1);
                $strRS2   = substr_count($dataTEXT, $clRS2);
                if (($strRS1 > 0 && $strRS2 > 0)) {
                    $count = ($strRS1 <= $strRS2) ? $strRS1 : $strRS2;
                    $rs[] = [
                        'key' => $value,
                        'count' => $count
                    ];
                }
            };
        };
        $rs = $this->array_group_by($rs, 'key');
        $newRS2 = [];
        $x = 0;
        foreach ($rs as $key => $value) {
            if ($x >= 9) {
                $newRS1[] = [
                    'key' => $key,
                    'count' => count($value)
                ];
            } else {
                $newRS2[] = [
                    'key' => $key,
                    'count' => count($value)
                ];
            }
            $x++;
        };
        $newRS = array_merge($newRS1, $newRS2);
        return $newRS;
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

    private function countNumberShow($content, $number)
    {
        $pattern = "/($number\")/";
        if (is_array($content)) $content = json_encode($content);
        preg_match_all($pattern, $content, $total);
        return count($total[0]);
    }
    // ====================================
}
