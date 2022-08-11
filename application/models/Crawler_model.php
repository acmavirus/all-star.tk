<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Symfony\Component\DomCrawler\Crawler;
class Crawler_model extends STEVEN_Model {
    public function __construct() {
        parent::__construct();

        $this->table_keo      = 'keonhacai';

    }

    public function getDataKeonhacai()
    {
        $this->db->select("*");
        $this->db->from($this->table_keo);
        $data = $this->db->get()->result();
        return $data;
    }

    //Lịch thi đấu keonhacai1
    public function crawler_match_schedule($update_cache = false){
        $key_cache = CACHE_PREFIX_NAME . 'crawler_match_schedule';
        $result = $this->getCache($key_cache);
        if ($result === false || $update_cache === true) {
            try {
                $html = $this->cUrl('http://keonhacai1.net','GET');
                $crawler = new Crawler($html);
                $result = $crawler->filter('.ajax')->eq(1)->html();
                $this->setCache($key_cache, $result, 60*60);
            } catch (Exception $e) {
                
            }
            
        }
        return $result;
    }


    //Lịch phát sóng trực tiếp keonhacai1
    public function crawler_match_live($update_cache = false)
    {
        $key_cache = CACHE_PREFIX_NAME . 'crawler_match_live';
        $result = $this->getCache($key_cache);
        if ($result === false || $update_cache === true) {
            $html = $this->cUrl('http://keonhacai1.net/lich-phat-song-bong-da.htm','GET');
            $crawler = new Crawler($html);
            $matches = $crawler->filter('.match-info');
            $result = $matches->each(function($el, $i){
                $logo_home = $el->filter('.icon')->eq(0)->filterXpath('//img')->extract(['src'])[0];
                $logo_away = $el->filter('.icon')->eq(1)->filterXpath('//img')->extract(['src'])[0];
                $result['name_home'] = $el->filter('.team')->eq(0)->text();
                $result['name_away'] = $el->filter('.team')->eq(1)->text();
                $result['start_time'] = $el->filter('.meta h4')->eq(0)->text();
                $result['tournament_name'] = $el->filter('.meta span')->eq(0)->text();
                $result['link_live'] = 'http://keonhacai1.net/' . $el->filter('.livelink a')->attr('href');
                $result['logo_home'] = str_replace('http://www.gmodules.com/gadgets/proxy?refresh=86400&container=ig&url=', '', $logo_home);
                $result['logo_away'] = str_replace('http://www.gmodules.com/gadgets/proxy?refresh=86400&container=ig&url=', '', $logo_away);
                return $result;
            });
        }
        $this->setCache($key_cache, $result, 30*60);
        return $result;
    }

    //Dữ liệu trận đấu bongdalu
    public function crawler_data_match ($id_bongda_lu, $update_cache = false) {
        $key_cache = CACHE_PREFIX_NAME . 'crawler_data_match' . $id_bongda_lu;
        $result = $this->getCache($key_cache);
        if ($result === false || $update_cache === true) {
            $result = $this->cUrl_API("http://api.sblradar.net/api/v2/analysis/getDetail?crawler_id=$id_bongda_lu");
            $result = json_decode($result);
            $this->setCache($key_cache, $result, 3*60);
        }
        return $result;
    }

    //Dữ liệu kèo trận đấu bongdalu: châu Á, châu Âu, tài xỉu
    public function crawler_data_bet ($id_bongda_lu, $update_cache = false) {
        $key_cache = CACHE_PREFIX_NAME . 'crawler_data_bet' . $id_bongda_lu;
        $result = $this->getCache($key_cache);
        if ($result === false || $update_cache === true) {
            $result = $this->cUrl("http://www.bongdalu.com/Ajax.aspx?type=23&ID=$id_bongda_lu", 'GET');
            $result = data_bet($result);
            $this->setCache($key_cache, $result, 3*60);
        }


        return $result;
    }


    //Bảng tỷ lệ kèo bongda365
    public function crawler_ty_le_keo($update_cache = false) {
        $key_cache = CACHE_PREFIX_NAME . 'crawler_ty_le_keo';
        $result = $this->getCache($key_cache);
        if ($result === false || $update_cache === true) {
            $result = $this->cUrl_API("http://api.sblradar.net/api/v2/page/getPage?id=1");
            $result = json_decode($result);
            $this->setCache($key_cache, $result, 1*60);
        }
        return $result;
    }

    //Bảng tỷ lệ kèo bongda365 new
    public function crawler_ty_le_keo_new($update_cache = false) {
        $key_cache = CACHE_PREFIX_NAME . 'crawler_ty_le_keo_new';
        $result = $this->getCache($key_cache);
        if ($result === false || $update_cache === true) {
            $result = $this->cUrl_API("http://api.sblradar.net/api/v2/page/getPage?id=2");
            $result = json_decode($result);
            $this->setCache($key_cache, $result, 1*60);
        }
        return $result;
    }

    public function crawler_livescore($update_cache = false) {
        $key_cache = CACHE_PREFIX_NAME . 'crawler_livescore';
        $result = $this->getCache($key_cache);
        if ($result === false || $update_cache === true) {
            $resultPc = $this->cUrl_API("http://api.sblradar.net/api/v2/page/getPage?id=4");
            $resultMobile = $this->cUrl_API("http://api.sblradar.net/api/v2/page/getPage?id=5");
            $resultPc = json_decode($resultPc);
            $resultMobile = json_decode($resultMobile);
            $result['pc'] = empty($resultPc) ? null : $resultPc->data;
            $result['mobile'] = empty($resultMobile) ? null : $resultMobile->data;
            $this->setCache($key_cache, $result, 1*60);
        }
        return $result;
    }

    public function cUrl($url, $custom_request ) {
        $pointer = curl_init();
        curl_setopt($pointer, CURLOPT_URL, $url);
        // curl_setopt($pointer, CURLOPT_PROXY, $proxy[rand(0,1)]);

        curl_setopt($pointer, CURLOPT_TIMEOUT, 5);
        curl_setopt($pointer, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($pointer, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.28 Safari/534.10");
        curl_setopt($pointer, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($pointer, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($pointer, CURLOPT_CUSTOMREQUEST, $custom_request);
        curl_setopt($pointer, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($pointer, CURLOPT_HEADER, false);
        curl_setopt($pointer, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($pointer, CURLOPT_AUTOREFERER, true);



        $return_val = curl_exec($pointer);
        $http_code = curl_getinfo($pointer, CURLINFO_HTTP_CODE);

        if ($http_code == 404) {
            return false;
        }
        curl_close($pointer);
        unset($pointer);
        return $return_val;
    }

    public function cUrl_API($url, $data = array(), $type = "GET"){
        $resource = curl_init();
        curl_setopt($resource, CURLOPT_URL, $url);

        if ($type == "POST") {
            curl_setopt($resource, CURLOPT_POST, true);
            curl_setopt($resource, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        curl_setopt($resource, CURLOPT_HTTPHEADER, array(
            'Token: a095d4d8cf09fb8b0b29afaa53a4e06c'
        ));
        curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($resource, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($resource,CURLOPT_TIMEOUT,40);
        curl_getinfo($resource, CURLINFO_HTTP_CODE);
        $result = curl_exec($resource);
        curl_close($resource);
        return $result;
    }
}