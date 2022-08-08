<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Result_model extends STEVEN_Model
{
    public function __construct(){
        parent::__construct();
        $this->table            = "category";
    }

    public function getDataByCategory($params, $timeCache = 3600, $updateCache = false){
        $keyCache = "getDataByCategory".md5(json_encode($params));
        $data = $this->getCache($keyCache);
        if (empty($data) || $updateCache){
            $data = $this->getDataAPIXoso("result/getDataByCategory",$params);
            $this->setCache($keyCache, $data, $timeCache);
        }
        return $data;
    }
    public function getDataByCategoryDayOfWeek($params, $timeCache = 3600, $updateCache = false){
        $keyCache = "getDataByCategoryDayOfWeek".md5(json_encode($params));
        $data = $this->getCache($keyCache);
        if (empty($data) || $updateCache){
            $data = $this->getDataAPIXoso("result/getDataByCategoryDayOfWeek",$params);
            $this->setCache($keyCache, $data, $timeCache);
        }
        return $data;
    }
    public function getFromDayToDay($params, $timeCache = 3600, $updateCache = false){
        $keyCache = "getFromDayToDay".md5(json_encode($params));
        $data = $this->getCache($keyCache);
        if (empty($data) || $updateCache){
            $data = $this->getDataAPIXoso("result/getFromDayToDay",$params);
            $this->setCache($keyCache, $data, $timeCache);
        }
        return $data;
    }
    public function getDataNearestByDay($params, $timeCache = 3600, $updateCache = false){
        $keyCache = "getDataNearestByDay".md5(json_encode($params));
        $data = $this->getCache($keyCache);
        if (empty($data) || $updateCache){
            $data = $this->getDataAPIXoso("result/getDataNearestByDay",$params);
            $this->setCache($keyCache, $data, $timeCache);
        }
        return $data;
    }
    public function getAllResultForSiteMap($category = [], $limit = 1, $page = 1, $after = ''){
        $params = [
            'category_id' => $category,
            'limit' => $limit,
            'page' => $page,
            'after' => $after
        ];
        $keyCache = "all-result-after-".md5(json_encode($params));
        $data = $this->getCache($keyCache);
        if (empty($data)){
            $data = $this->getDataAPIXoso('result/getAllResultForSiteMap', $params);
            $this->setCache($keyCache, $data, 60*5);
        }
        return $data;
    }
}