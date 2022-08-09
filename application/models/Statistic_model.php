<?php
/**
 * Created by PhpStorm.
 * User: ducto
 * Date: 10/2/2018
 * Time: 11:43 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Statistic_model extends STEVEN_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function xien2($params){
        $keyCache = md5('xien2'.json_encode($params)).date('d');
        $rs = $this->getCache($keyCache);
        if (!empty($rs)) return $rs;

        $rs = callCURL(API_DATACENTER."statistic_loto/loxien2thinh", $params);
        $rs = $rs['data']['data'];

        $this->setCache($keyCache, $rs, 3600);
        return $rs;
    }
    public function xien3($params){
        $keyCache = md5('xien3'.json_encode($params)).date('d');
        $rs = $this->getCache($keyCache);
        if (!empty($rs)) return $rs;

        $rs = callCURL(API_DATACENTER."statistic_loto/loxien3thinh", $params);
        $rs = $rs['data']['data'];

        $this->setCache($keyCache, $rs, 3600);
        return $rs;
    }
    public function lokep($params){
//        params: code, limit, dateEnd
        $keyCache = md5('lokep'.json_encode($params)).date('d');
        $rs = $this->getCache($keyCache);
        if (!empty($rs)) return $rs;

        $rs = callCURL(API_DATACENTER."statistic_loto/lokep", $params);
        $rs = $rs['data']['data_kep'];

        $this->setCache($keyCache, $rs, 3600);
        return $rs;
    }
    public function tansuat($params){
//        $params: code, date_end, limit, is_special
        $keyCache = 'tansuat'.md5(json_encode($params)).date('d');
        $rs = $this->getCache($keyCache);
        if (!empty($rs)) return $rs;

        $rs = callCURL(API_DATACENTER."statistic_loto/tansuat_loto", $params);
        $rs = $rs['data'];

        $this->setCache($keyCache, $rs, 3600);
        return $rs;
    }
    public function dbthang($params){
//        $params: code, month, year
        $keyCache = md5('dbthang'.json_encode($params)).date('d');
        $rs = $this->getCache($keyCache);
        if (!empty($rs)) return $rs;

        $rs = callCURL(API_DATACENTER."statistic_dacbiet/getByYear", $params);
        $rs = $rs['data'];

        $this->setCache($keyCache, $rs, 3600);
        return $rs;
    }
    public function dbtuan($params){
        /*@params:
        code
        date_begin
        date_end*/
        $keyCache = md5('dbtuan'.json_encode($params)).date('d');
        $rs = $this->getCache($keyCache);
        if (!empty($rs)) return $rs;

        $rs = callCURL(API_DATACENTER."statistic_dacbiet/getByMonth", $params);
        $rs = $rs['data'];

        $this->setCache($keyCache, $rs, 3600);
        return $rs;
    }
    public function dbtuanprovince($params){
        /*params: code, date_end*/
        $keyCache = md5('dbtuanmb'.json_encode($params)).date('d');
        $rs = $this->getCache($keyCache);
        if (!empty($rs)) return $rs;

        $rs = callCURL(API_DATACENTER."statistic_dacbiet/getByMonthxsp", $params);
        $rs = $rs['data'];

        $this->setCache($keyCache, $rs, 3600);
        return $rs;
    }
    public function dacbiet($params){
        /*params:
        code, date*/
        $keyCache = "api_dacbiet".md5(json_encode($params));
        $return = $this->getCache($keyCache);
        if (empty($return)){
            $rs = callCURL(API_DATACENTER."statistic_dacbiet/last2number", $params)['data'];
            if (empty($rs)) return false;

            $data = $rs;
            $rate = [];
            foreach ($rs['data_after_reward_1day'] as $k => $i) {
                $rate[] = $i['last_2'];
            }
            $rate = array_count_values($rate);
            arsort($rate);
            $rateTop = array_slice($rate, 0, 8, 1);
            $data['rate'] = $rate;
            $data['rateTop'] = $rateTop;

            foreach ($rs['data_same_reward'] as $k => $i) {
                if (date('N', strtotime($params['date'])) == date('N', strtotime($k))) {
                    $index = array_search($k, array_keys((array)$rs['data_same_reward']));
                    $key = array_keys((array)$data['data_after_reward_1day']);
                    $key = isset($key[$index]) ? $key[$index] : '';
                    $data['data_same_day'][$k] = $i;
                    if (!empty($key)) $data['data_same_next_day'][$key] = $data['data_after_reward_1day'][$key];
                }
            }
            $return = $data;
            $this->setCache($keyCache, $return, 600);
        }
        return $return;
    }
    public function logan($params){
        $keyCache = md5('logan'.json_encode($params)).date('d');
        $rs = $this->getCache($keyCache);
        if (!empty($rs)) return $rs;

        $rs = callCURL(API_DATACENTER."statistic_loto/loganxsp",$params);
        $rs = json_decode($rs,true);

        $this->setCache($keyCache, $rs, 3600);
        return $rs;
    }
    public function dauduoi($params){
//        $params: code, date_begin, date_end
        $keyCache = md5('dauduoi'.json_encode($params)).date('d');
        $rs = $this->getCache($keyCache);
        if (!empty($rs)) return $rs;

        $rs = callCURL(API_DATACENTER.'statistic_loto/dauduoi_loto', $params);
        $rs = $rs['data'];

        $this->setCache($keyCache, $rs, 3600);
        return $rs;
    }
    public function ganDauDuoiTong($params){
        $keyCache = md5('ganDauDuoiTong'.json_encode($params)).date('d');
        $rs = $this->getCache($keyCache);
        if (!empty($rs)) return $rs;

        $rs = callCURL(API_DATACENTER.'statistic_loto/gan_dau_duoi_tong', $params);
        $rs = json_decode($rs, true);

        $this->setCache($keyCache, $rs, 3600);
        return $rs;
    }
    public function lotong($params){
//        $params: code, sum, date_begin, date_end
        $key = 'lotong-'.md5(json_encode($params));
        $data = $this->getCache($key);
        if (empty($data)){
            $data = callCURL(API_DATACENTER.'statistic_loto/lotong', $params);
            if ($data['code'] != 200) return false;
            $data = $data['data'];
            $this->setCache($key, $data, 3600);
        }
        return $data;
    }

    /*type:
    bachthu
    dacbiet
    lokep
    nhieunhay*/
    public function getBachThu($code,$type = 'bachthu', $return = 'js'){
        $keyCache = "getBachThu".md5($type.$code).date('d');
        //$rs = $this->getCache($keyCache);
        if (empty($rs)) {
            $rs = callCURL(API_DATACENTER."soicau/js$type/$code", []);
            $rs = preg_replace('/\x00/', '', $rs);
            $this->setCache($keyCache, $rs, 3600);
        }
        if ($return == 'js'){
            return $rs;
        } elseif ($return == 'php'){
            preg_match_all('/\((.*?)\)/',$rs,$arr);
            $lifeTime = $arr[1][0];
            $data['lifetime'] = explode(',', $lifeTime);
            $value = $arr[1][1];
            $data['value'] = explode(',', $value);
            return $data;
        }
        return false;
    }
    public function getLatLienTuc($return = 'js'){
        $keyCacheData = "js_data_lat_lien_luc".date('d');
        $data_js = $this->getCache($keyCacheData);
        if(empty($data_js)){
            $data_js = callCURL(API_DATACENTER."soicau/latlientuc");
            $data_js = preg_replace('/\x00/', '', $data_js);
            $this->setCache($keyCacheData, $data_js, 3600);
        }

        if ($return == 'js')
            return $data_js;
        elseif ($return == 'php'){
            preg_match_all('/\((.*?)\)/', $data_js,$arr);
            $lifeTime = $arr[1][0];
            $data['lifetime'] = explode(',', $lifeTime);
            $value = $arr[1][1];
            $data['value'] = explode(',', str_replace("'", '', $value));
            return $data;
        }
    }
    public function getLoto() {
        $keyCache = "getRbkToday".date('d');
        $data = $this->getCache($keyCache);
        if (empty($data)) {
            $data = callCURL(API_DATACENTER.'page/lo_to');
            $data = $data['data'];

            $this->setCache($keyCache, $data, 3600);
        }
        return $data;
    }
}