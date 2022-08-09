<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Soicau_model extends STEVEN_Model
{
    protected $_domain_api = "http://apinew.xsradar.com/";
    protected $_data_category;
    public function __construct()
    {
        parent::__construct();
        $this->table            = "category";
    }

    public function getBachThu($code, $type = 'bachthu', $return = 'js')
    {
        $keyCache = "getBachThu" . md5($type . $code) . date('d');
        $rs = $this->getCache($keyCache);
        if (empty($rs)) {
            $rs = callCURL("soicau/js$type/$code");
            $rs = preg_replace('/\x00/', '', $rs);
            $this->setCache($keyCache, $rs, 3600);
        }
        if ($return == 'js') {
            return $rs;
        } elseif ($return == 'php') {
            preg_match_all('/\((.*?)\)/', $rs, $arr);
            $lifeTime = $arr[1][0];
            $data['lifetime'] = explode(',', $lifeTime);
            $value = $arr[1][1];
            $data['value'] = explode(',', $value);
            return $data;
        }
        return false;
    }
    public function getLatLienTuc($return = 'js')
    {
        $keyCacheData = "js_data_lat_lien_luc" . date('d');
        $data_js = $this->getCache($keyCacheData);
        if (empty($data_js)) {
            $data_js = callCURL("soicau/latlientuc");
            $data_js = preg_replace('/\x00/', '', $data_js);
            $this->setCache($keyCacheData, $data_js, 3600);
        }

        if ($return == 'js')
            return $data_js;
        elseif ($return == 'php') {
            preg_match_all('/\((.*?)\)/', $data_js, $arr);
            $lifeTime = $arr[1][0];
            $data['lifetime'] = explode(',', $lifeTime);
            $value = $arr[1][1];
            $data['value'] = explode(',', str_replace("'", '', $value));
            return $data;
        }
    }
}
