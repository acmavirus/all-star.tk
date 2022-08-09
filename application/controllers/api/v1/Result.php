<?php

/**
 * Created by PhpStorm.
 * User: ducto
 * Date: 8/9/2018
 * Time: 3:07 PM
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Result extends API_Controller
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

    /* getDataByCategory($api_id, $page=1, $limit=1)
        XSMB, XSMT, XSMN sẽ lấy limit = số ngày quay
        các tỉnh thì sẽ lấy limit = số lần quay
    */
    public function getDataResult()
    {

        $dataRaw = (object) $this->input->get();
        if (!empty($dataRaw)) {
            $api_id = $dataRaw->api_id;
            if (!isset($api_id)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số "api_id"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $page = !empty($dataRaw->page) ? $dataRaw->page : 1;
            $limit = !empty($dataRaw->limit) ? $dataRaw->limit : 1; /*Biên độ chính là limit ngày kết quả*/

            if (!$api_id) {
                $data['data'] = $this->_data->getByCategory(0, $limit, $page);
                $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
                return $this->response->json($dataJson);
            }
            $oneItem = $this->_data_category->getByIdCached($api_id);

            /*Check 3 miền*/
            if ($oneItem->id <= 3) {
                $this->_data_category->_recursive_child_id($this->_data_category->_all_category(), $oneItem->id);
                $api_id = $this->_data_category->_list_category_child_id;

                $hour = [
                    'XSMN' => "16:00",
                    'XSMT' => "17:00",
                    'XSMB' => "18:00"
                ];

                $date_end = date('Y-m-d', strtotime("-" . ($page - 1) * $limit . " day"));
                if (date('H:i') < $hour[$oneItem->code]) $date_end = date('Y-m-d', strtotime($date_end . ' -1 day'));
                $date_begin = date('Y-m-d', strtotime($date_end . " -" . ($limit - 1) . " day"));

                $dataApi = $this->_data->getFromDayToDay($date_begin, $date_end, $api_id);
            } else {
                $dataApi = $this->_data->getByCategory($api_id, $limit, $page);
            }

            $data['data'] = $dataApi;
            if ($api_id) $data['total'] = $this->_data->getTotalByCategory($api_id);
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function getDataKeno()
    {

        $dataRaw = (object) $this->input->get();
        if (!empty($dataRaw)) {
            $page = !empty($dataRaw->page) ? $dataRaw->page : 1;
            $limit = !empty($dataRaw->limit) ? $dataRaw->limit : 10; /*Biên độ chính là limit ngày kết quả*/
            $dataApi = $this->_data->getByKeno($limit, $page);
            $data['data'] = $dataApi;
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function getKenoDaytoday()
    {
        $dataRaw = (object) $this->input->get();
        if (!empty($dataRaw)) {
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            if (empty($dataRaw->date_begin)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số "date_begin" FORMAT: Y-m-d'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            if (empty($dataRaw->date_end)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số "date_end"  FORMAT: Y-m-d'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');

            $date_end = date('Y-m-d 22:00:00', strtotime($dataRaw->date_end));
            $date_begin = date('Y-m-d', strtotime($dataRaw->date_begin));
            $dataApi = $this->_data->kenodaybyday($date_begin, $date_end);
            $data['data'] = $this->array_group_by($dataApi, 'displayed_time');
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function getDataByWeekday()
    {
        $dataRaw = (object) $this->input->get();
        if (!empty($dataRaw)) {
            $api_id = $id = $dataRaw->api_id;
            if (empty($api_id)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số "api_id"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $weekday = $dataRaw->weekday; // 2->8 là thứ
            if (!($weekday >= 2 && $weekday <= 8)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Sai tham số "weekday". Thứ trong tuần chỉ nằm trong logic 2 -> 8'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $page = !empty($dataRaw->page) ? $dataRaw->page : 1;
            $limit = !empty($dataRaw->limit) ? $dataRaw->limit : 7; /*Biên độ chính là limit ngày kết quả*/
            $date_end = !empty($dataRaw->date_end) ? date('Y-m-d', strtotime($dataRaw->date_end)) : '';
            if (empty($limit)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số giới hạn "limit"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            // date(N)   1->7 mon->sun
            // sql 1->7 sun->sat
            // day prize 2->8 mon->sun

            if ($api_id == 2 || $api_id == 3) {
                $countProvByWeekday = $this->countDataByWeekday($api_id, $weekday);
                $limit = ($countProvByWeekday * $limit);
                $this->_data_category->_recursive_child_id($this->_data_category->_all_category(), $api_id);
                $api_id = $this->_data_category->_list_category_child_id;
            };
            // $d_w_o = get by date("w")
            $listResult = $this->_data->getDataByWeekday($api_id, $weekday, $limit, $page, $date_end);
            $data['data'] = $this->array_group_by($listResult, 'displayed_time', 'category_id');
            $data['total'] = $this->_data->getTotalByCategory($api_id);
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }
    public function getLastResultCategory()
    {
        $dataRaw = (object) $this->input->get();

        if (!empty($dataRaw)) {
            $api_id = $dataRaw->api_id;
            if (empty($api_id)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số "api_id"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');

            if ($api_id == 2 || $api_id == 3) {
                if ($api_id == 2) {
                    $time = 17;
                } else {
                    $time = 16;
                }
                if (date('H') >= $time) $weekday = date('w');
                else $weekday = date('w', strtotime('-1 day'));
                if ($weekday == 0) $weekday = 7;
                $api_id = $this->_data_category->getListChildByDOW($api_id, $weekday, 'id');
            };
            $data['data'] = $this->_data->getLastResultByCategory($api_id);
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }
    public function getDataDayByPrize()
    {
        $dataRaw = (object) $this->input->get();
        if (!empty($dataRaw)) {
            $api_id = $dataRaw->api_id;
            if (empty($api_id)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số "api_id"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $prize = $dataRaw->prize;
            $page = !empty($dataRaw->page) ? $dataRaw->page : 1;
            $limit = !empty($dataRaw->limit) ? $dataRaw->limit : 7; /*Biên độ chính là limit ngày kết quả*/
            if (empty($limit)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số giới hạn "limit"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $data['data'] = $this->_data->getDataDayByPrize($api_id, $prize, $limit, $page);
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
    public function getFromDayToDay()
    {
        $dataRaw = (object) $this->input->get();
        if (!empty($dataRaw)) {
            $api_id = $dataRaw->api_id;
            if (empty($api_id)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số "api_id"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            if (empty($dataRaw->date_begin)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số "date_begin" FORMAT: Y-m-d'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            if (empty($dataRaw->date_end)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số "date_end"  FORMAT: Y-m-d'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');

            $date_end = date('Y-m-d', strtotime($dataRaw->date_end));
            $date_begin = date('Y-m-d', strtotime($dataRaw->date_begin));

            if ($api_id == 2 || $api_id == 3) {
                $this->_data_category->_recursive_child_id($this->_data_category->_all_category(), $api_id);
                $api_id = $this->_data_category->_list_category_child_id;
            };

            $result = $this->_data->getFromDayToDay($date_begin, $date_end, $api_id);

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
    public function getDataNearestByDay()
    {
        $dataRaw = (object) $this->input->get();
        if (!empty($dataRaw)) {
            $api_id = $id = $dataRaw->api_id;
            if (empty($api_id)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số "api_id"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            if (!empty($dataRaw->day)) $day = date('Y-m-d', strtotime($dataRaw->day));
            else $day = date('Y-m-d');
            if (!empty($dataRaw->limit)) $limit = $dataRaw->limit;
            else $limit = 1;

            if (in_array($api_id, [2, 3])) {
                $date_begin = date('Y-m-d', strtotime($day . ' -' . ($limit - 1) . ' day'));
                $this->_data_category->_recursive_child_id($this->_data_category->_all_category(), $api_id);
                $listCateId = $this->_data_category->_list_category_child_id;
                $result = $this->_data->getFromDayToDay($date_begin, $day, $listCateId);
            } else {
                $result = $this->_data->getResultNearest($day, $api_id, $limit);
            }

            $data['data'] = $result;
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function getAllResult()
    {
        extract($this->input->get());
        if (empty($limit)) $limit = 1;
        if (empty($page)) $page = 1;
        if (empty($after)) $after = '2005-01-01';
        $data = $this->_data->getAllResult($limit, $page, $after);
        echo json_encode($data);
        exit();
    }
    public function getTotalResult()
    {
        extract($this->input->get());
        if (empty($year)) $year = 2019;
        $data = $this->_data->getTotalResult($year);
        echo json_encode($data);
        exit();
    }
    public function getAllResultById()
    {
        extract($this->input->get());
        if (empty($id)) $id = 1;
        $data = $this->_data->getAllResultById($id);
        echo json_encode($data);
        exit();
    }
    public function getLive()
    {
        $dataRaw = (object) $this->input->get();
        if (!empty($dataRaw->hour)) {
            $date = $dataRaw->hour;
            $minute = date('Hi');
        } else {
            $date = date('H');
            $minute = date('Hi');
        }
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
        if (!empty($dataRaw)) {
            if (!empty($dataRaw->api_id)) {
                $api_id = $dataRaw->api_id;
            };
            if (!empty($dataRaw->day)) {
                $day = date('Y-m-d', strtotime($dataRaw->day));
            };
        }

        if (!empty($api_id)) {
            $oneItem = $this->_data_category->getById($api_id);
            $code = $oneItem->code;

            if ($api_id == 2 || $api_id == 3) {
                $this->_data_category->_recursive_child_id($this->_data_category->_all_category(), $api_id);
                $api_id = $this->_data_category->_list_category_child_id;
            };

            $dataReturn = $this->getCache($keyCache);
            if (empty($dataReturn)) {
                $dataReturn = $this->_data->getByLive($day, $api_id);
                if (!empty($dataRaw->hour) && empty($dataReturn)) {
                    $dataReturn = $this->_data->getByLive(date('Y-m-d', strtotime("-1 days", time())), $api_id);
                };
                if ($api_id > 1) {
                    $dataReturn = $this->array_group_by($dataReturn, function ($i) {
                        return $i['code'];
                    });
                };
                $this->setCache($keyCache, $dataReturn, 5);
            }

            $dataReturn[$code] = $dataReturn;
            $data['data'] = $dataReturn;
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }

        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }
    public function destroyCache()
    {
        $key = $this->input->get('key');
        if ($key === 'anchoi365') {
            if ($this->deleteCache())
                echo 'clear all';
            else echo 'false';
        }
    }
    /*Check total category by day of week*/
    private function countDataByWeekday($id, $weekday)
    {
        $total = 0;
        if (!empty($this->_all_category)) foreach ($this->_all_category as $k => $item) {
            if (in_array($weekday, (array)json_decode($item->weekday)) && $item->parent_id == $id) {
                $total = $total + 1;
            }
        }
        return $total;
    }

    public function getSpinTime()
    {
        $_this = &get_instance();
        $_this->load->model('category_model');
        $allCate = $this->_data_category->_all_category();
        $cateLotteryVietlot = array_filter($allCate, function ($a) {
            return !empty($a->code);
        });

        $time = SPIN_TIME;
        $arrReturn = [];

        foreach ($cateLotteryVietlot as $item) {
            $oneParent = $this->_data_category->getOneParent($item);
            $period = $this->_data->getTotal(['category_id' => $item->id]);

            if (in_array($item->code, ['POWER', 'MEGA', 'MAX3D', 'MAX3DPLUS', 'MAX3DPRO', 'MAX4D', 'KENO']))
                $checkCode = $item->code;
            else
                $checkCode = $oneParent->code;

            if ($item->code != 'KENO') {
                $arrWeekDay = $weekdayItem = json_decode($item->weekday);
                $tmp = $weekdayItem;
                for ($i = 1; $i <= 10; $i++) {
                    array_walk($tmp, function (&$a) {
                        $a += 7;
                    });
                    $arrWeekDay = array_merge($arrWeekDay, $tmp);
                }
                $arrWeekDay = array_slice($arrWeekDay, 0, 10);
                $weekdayNow = date('N') + 1;
                if (in_array($weekdayNow, $arrWeekDay) && now() > strtotime($time[$checkCode]))
                    ++$weekdayNow;

                $return = [];
                foreach ($arrWeekDay as $num) {
                    if ($num >= $weekdayNow) {
                        $return[++$period] = date('Y-m-d H:i:s', strtotime("+" . ($num - $weekdayNow) . 'day ' . $time[$checkCode]));
                    }
                }
            } else {
                $keno = $this->result__kyketiep();
                $dateTime = $keno['displayed_time'];
                $period = $keno['period'];
                $timeStap = strtotime($dateTime);
                $maxToday = strtotime(date("Y-m-d", $timeStap) . " 22:00:00");
                $return = [];
                while ($maxToday >= $timeStap) {
                    if ($timeStap > time()) {
                        $return[$period] = date("Y-m-d H:i:s", $timeStap);
                    }
                    $period++;
                    $timeStap = $timeStap + (600);
                }
            }
            $arrReturn[$item->code] = $return;
        }

        echo json_encode($arrReturn);
    }
    public function getAllResultForSiteMap()
    {
        $input = $this->input->get();
        $data = $this->_data->getAllResultForSiteMap($input);
        echo json_encode($data);
    }

    public function sodauduoi()
    {
        $dataRaw = (object) $this->input->get();
        if (!empty($dataRaw)) {
            $page = (!empty($dataRaw->page)) ? $dataRaw->page : 1;
            $limit = (!empty($dataRaw->limit)) ? $dataRaw->limit : 10;
            if (empty($dataRaw->api_id)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số "api_id"'));
            $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $api_id = $dataRaw->api_id;
            if ($api_id == 2 || $api_id == 3) {
                $this->_data_category->_recursive_child_id($this->_data_category->_all_category(), $api_id);
                $api_id = $this->_data_category->_list_category_child_id;
            };
            $offset = ($page - 1) * $limit;
            $offset2 = 7 * $limit;
            $date_end = date('Y-m-d', strtotime("-$offset days"));
            $date_begin = (!empty($dataRaw->dayofweek)) ? date('Y-m-d', strtotime($date_end . "- $offset2 days")) : date('Y-m-d', strtotime($date_end . "- $limit days"));
            if ($api_id > 3) {
                $date_begin = date('Y-m-d', strtotime($date_end . "- $offset2 days"));
            };
            $result = (!empty($dataRaw->dayofweek)) ? $this->_data->getFromWeekdayvsDayToDay($date_begin, $date_end, $api_id, $dataRaw->dayofweek) : $this->_data->getFromDayToDay($date_begin, $date_end, $api_id);
            $result = $this->array_group_by($result, 'displayed_time');
            $newdata = [];
            foreach ($result as $key => $listitem) {
                $newdata2 = [];
                foreach ($listitem as $key2 => $item) {
                    $data_result = json_decode($item['data_result']);
                    $newdata2[] = [
                        'title' => $this->_data_category->getById($item['category_id'], 'title')->title,
                        'head' => $data_result[0][0],
                        'tail' => substr(end($data_result)[0], -2, 2)
                    ];
                };
                $newdata[] = [
                    'date_title' => weekDay($key),
                    'displayed_time' => $key,
                    'result' => $newdata2
                ];
            };

            $data['data'] = $newdata;
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        };
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

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

}
