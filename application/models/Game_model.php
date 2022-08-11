<?php

/**
 * Created by PhpStorm.
 * User: ducto
 * Date: 10/2/2018
 * Time: 11:43 PM
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Game_model extends STEVEN_Model
{
    private $_API_SERVER = 'https://intlgateway.cloudflaregateway.com/',
        $_API_KEY = 'IadXKmm5W!d0J60cD39ATS2&4MioxRWE';

    public function getResultCurrent($game, $updateCache = false)
    {
        $key = "game_getCurrent_$game";
        $data = $this->getCache($key);
        if (empty($data) || $updateCache == true) {
            $params = [
                'game' => $game,
                'i18n' => 'VI',
                'sign' => mb_strtoupper(md5("game=$game&i18n=VI&key={$this->_API_KEY}"))
            ];

            $result = $this->getDataAPIXoso($this->_API_SERVER . 'top-service-lottery/game/api/current', $params);
            $data = !empty($result->success === true) ? $result->data : false;
            $this->setCache($key, $data, 30 * 60);
        }
        return $data;
    }

    public function getResult($game)
    {
        switch ($game) {
            case 'sic-bo.html':
                $url = 'http://94.237.66.85/soft/%e8%ae%a1%e5%88%92/%e9%aa%b0%e5%ae%9d.txt';
                break;
            case 'tai-xiu.html':
                $url = 'http://94.237.66.85/soft/%e8%ae%a1%e5%88%92/%e7%8c%9c%e5%a4%a7%e5%b0%8f.txt';
                break;
            case 'bau-cua.html':
                $url = 'http://94.237.66.85/soft/%e8%ae%a1%e5%88%92/%e9%b1%bc%e8%99%be%e8%9f%b9.txt';
                break;
            case 'pk10.html':
                $url = 'http://94.237.66.85/soft/%e8%ae%a1%e5%88%92/PK10.txt';
                break;
            default:
                return 'Xẩy ra lỗi trong quá trình lấy dữ liệu';
                break;
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 0,
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true
        ));

        $resp = curl_exec($curl);

        curl_close($curl);

        return $resp;
    }

    public function getDataResult($params = [], $updateCache = false)
    {
        $key = "game_getDataResult_" . md5(json_encode($params));
        $data = $this->getCache($key);
        if (empty($data) || $updateCache == true) {
            extract($params);
            $game = !empty($game) ? $game : '';
            $page = !empty($page) ? $page : '';
            $issueNo = !empty($issueNo) ? $issueNo : '';
            $stringKey = "game=$game&i18n=VI&key={$this->_API_KEY}";
            if (!empty($issueNo)) $stringKey .= "&issueNo=$issueNo";
            $params = [
                'game' => $game,
                'i18n' => 'VI',
                'issueNo' => $issueNo,
                'page' => $page,
                'size' => $size ? $size : '20',
                'prizeTimeEnd' => !empty($prizeTimeEnd) ? $prizeTimeEnd : '',
                'prizeTimeStart' => !empty($prizeTimeStart) ? $prizeTimeStart : '',
                'sign' => mb_strtoupper(md5($stringKey))
            ];
            $result = $this->getDataAPIXoso($this->_API_SERVER . 'top-service-lottery/game/api/history', $params);
            $data = !empty($result->success === true) ? $result->data : false;
            $this->setCache($key, $data, 30 * 60);
        }
        return $data;
    }
}
