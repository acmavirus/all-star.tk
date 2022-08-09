<?php
/**
 * Created by PhpStorm.
 * User: ducto
 * Date: 8/9/2018
 * Time: 3:07 PM
 */

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;

defined('BASEPATH') OR exit('No direct script access allowed');
class Soicau extends API_Controller
{
    protected $_data;
    protected $_data_category;
    protected $_lang_code;
    protected $_all_category, $_client;

    public function __construct()
    {
        parent::__construct();
        $this->_client = new Client();
        $guzzleClient = new GuzzleClient([
            'cookies' => true,
            'headers' =>  [
                'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
                'Accept-Encoding' => 'gzip',
                'Accept-Language' => 'en-US,en;q=0.8',
                'Cache-Control'   => 'private',
                'User-Agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.75 Safari/537.36'
            ]
        ]);
        $this->_client->setClient($guzzleClient);

        $this->load->model(['category_model', 'result_model']);
        $this->_data = new Result_model();
        $this->_data_category = new Category_model();
        $this->_all_category = $this->_data_category->_all_category();
        $this->_lang_code = $this->session->userdata('public_lang_code');
    }

    public function jsdacbiet($code, $date = null){
        $url = "cau-giai-dac-biet";
        $code = strtolower($code);
        $date = empty($date)? date('Y-m-d', strtotime('-1 day')) : date('Y-m-d', strtotime($date));
        $count = $code == 'xsmb'? 3: 2;

        $keyCache = "jsdacbiet-$code-$date";
        $content = $this->getCache($keyCache);
        if (empty($content)){
            $content = $this->crawKetQuaNet($url, $code, $count, $date);
            $this->setCache($keyCache, $content, 3600);
        }

        echo $content;
    }

    public function jsbachthu($code = 'xsmb', $date = null){
        $url = "cau-loto-bach-thu";
        $code = strtolower($code);
        $date = empty($date)? date('Y-m-d', strtotime('-1 day')) : date('Y-m-d', strtotime($date));
        $count = $code == 'xsmb'? 3: 2;

        $keyCache = "jsbachthu-$code-$date";
        $content = $this->getCache($keyCache);

        if (empty($content)){
            $content = $this->crawKetQuaNet($url, $code, $count, $date);
            //        check 503 ketqua.net
            if (empty($content)){
                $url = $code == 'xsmb'? 'https://static.xoso.com.vn/tkxs/CauMBBT.js?t='.date('Ymd') : 'https://static.xoso.com.vn/tkxs/Cau'.str_replace('xs', '', $code).'.js?t='.date('Ymd');
                $content = $this->getDataJs($url);
            }
            $this->setCache($keyCache, $content, 10*60);
        }

        echo $content;
    }

    public function jsnhieunhay(){
        $keyCache = "nhieunhay";
        $content = $this->getCache($keyCache);
        if (empty($content)) {
//            lay du lieu tu xoso.com.vn
            $content = $this->getDataJs('https://static.xoso.com.vn/tkxs/CauMBNN.js?t='.date('Ymd'));
            $this->setCache($keyCache, $content, 3600);
        }
        echo $content;
    }

    public function jslokep($code){
        $code = strtolower($code);
        $replace = ['xsdl'=>'xsdlt','xsdlk'=>'xsdlc','xsdn'=>'xsdni','xsbd'=>'xsbdu'];
        if (in_array($code,array_keys($replace))) $codeCraw = $replace[$code]; else $codeCraw = $code;
        $keyCode = substr($codeCraw,2);
        $keyCraw = "sclokep-$keyCode";
        $dataCraw = $this->getCache($keyCraw);
        if (empty($dataCraw)){
            $crawler = $this->_client->request('GET',"https://ketqua1.net/cau-loto-bach-thu");
            if ($crawler->filter('[type="submit"]')->count() == 0){
                return;
            }
            $form = $crawler->filter('[type="submit"]')->form();
            if ($codeCraw !== 'xsmb')
                $crawler = $this->_client->submit($form,['code'=>$keyCode,'count' => 2]);
            $maxCount = $crawler->filter('.max-cau .dosam')->text();
            $td = $crawler->filter(".bangcau_content.vietdam");
            $value = $td->filter(".dosam")->each(function ($i){
                return $i->html();
            });
            $num = $td->each(function ($i){
                preg_match_all('/\d{1,3}-\d{1,3}/',$i->filter(".btn")->attr("data-original-title"),$patt);
                return $patt[0];
            });
            for ($i =0; $i < count($num); $i++){
                foreach ($num[$i] as $k=>$item){
                    $pos = explode('-',$item);
                    $positionOne[] = $pos[0];
                    $positionTwo[] = $pos[1];
                    $lifetime[] = ($code === 'xsmb')? 3: 2;
                    $valuelt[] = $value[$i];
                }
            }
            for ($j=($code === 'xsmb')? 4: 3; $j<= $maxCount; $j++){
                $crawler = $this->_client->submit($form,['count' => $j]);
                $td = $crawler->filter(".bangcau_content.vietdam");
                $value = $td->filter(".dosam")->each(function ($i){
                    return $i->html();
                });
                $num = $td->each(function ($i){
                    preg_match_all('/\d{1,3}-\d{1,3}/',$i->filter(".btn")->attr("data-original-title"),$patt);
                    return $patt[0];
                });
                for ($i =0; $i < count($num); $i++){
                    foreach ($num[$i] as $k=>$item){
                        $pos = explode('-',$item);
                        $positionOne[] = $pos[0];
                        $positionTwo[] = $pos[1];
                        $lifetime[] = $j;
                        $valuelt[] = $value[$i];
                    }
                }
            }
            $dataCraw = [
                'lifetime' => $lifetime,
                'valuelt' => $valuelt,
                'positionOne' => $positionOne,
                'positionTwo' => $positionTwo
            ];
            $this->setCache($keyCraw,$dataCraw,3600);
        } else {
            extract($dataCraw);
        }

        $kepArr = ['00',11,22,33,44,55,66,77,88,99];
        $valuelt = array_intersect($valuelt,$kepArr);
        foreach ($valuelt as $k=>$i){
            $tmpLifetime[] = $lifetime[$k];
            $tmpPositionOne[] = $positionOne[$k];
            $tmpPositionTwo[] = $positionTwo[$k];
        }
        $lifetime = $tmpLifetime;
        $positionOne = $tmpPositionOne;
        $positionTwo = $tmpPositionTwo;

        echo " var lifetime = [".implode(',',$lifetime)."];";
        echo " var valuelt = [".implode(',',$valuelt)."];";
        echo " var positionOne = [".implode(',',$positionOne)."];";
        echo " var positionTwo = [".implode(',',$positionTwo)."];";

        $oneItem = $this->_data_category->getByCode($code);
        $listResult = $this->_data->getResultNearest(date('Y-m-d',strtotime('-1 day')),$oneItem->id,16);
        if (empty($listResult)) return false;
        foreach ($listResult as $item){
            if ($oneItem->code !== 'XSMB'){
                $reverse = json_decode($item['data_result']);
                $reverse = array_reverse($reverse);
                $item['data_result'] = json_encode($reverse);
            }
            $rs = preg_replace('/"\d.*[A-Z]"/','',$item['data_result']);
            preg_match_all('/\d/',$rs,$result);
            echo " var A".date('dmY',strtotime($item['displayed_time']))." = new Array(".implode(',',$result[0]).");";
        }
        if ($code === 'xsmb')
            echo " var Aloto = new Array(0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,1,2,0,0,1,2,0,0,1,2,0,0,1,2,0,0,1,2,0,0,1,2,0,0,1,2,0,0,1,2,0,0,1,2,0,0,1,2,0,1,2,0,1,2,0,1,2,1,2,1,2,1,2,1,2);";
        else
            echo " var Aloto = new Array(0,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,1,2,0,0,1,2,0,0,1,2,0,0,1,2,0,1,2,1,2);";
    }

    public function jslatlientuc(){
        $keyCache = "jslatlientuc";
        $content = $this->getCache($keyCache);
        if (empty($content)){
            $content = $this->getDataJs('https://static.xoso.com.vn/tkxs/CauMBLLT.js?t='.date('Ymd'));
            $this->setCache($keyCache, $content, 3*60*60);
        }
        echo $content;
    }

    public function bachthu(){
        $dataRaw = (object) $this->input->get();
        if(!empty($dataRaw)){
            $code = $dataRaw->code;
            if(empty($code)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số mã tỉnh "code"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $limit = $dataRaw->limit; /*Biên độ chính là limit ngày kết quả*/
            if(empty($limit)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số giới hạn "limit"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $oneItem = $this->_data_category->getByCode($code);
            $dataResult = $this->_data->getDataResultStatistic($oneItem->id,$limit);
            $data['data_list_result'] = $dataResult;
            $data['data_js'] = $this->getDataJs('https://static.xoso.com.vn/tkxs/CauMBBT.js?t='.date('Ymd'));
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function venhieunhay(){
        echo trim($this->getDataJs('https://static.xoso.com.vn/tkxs/CauMBNN.js?t='.date('Ymd')));
        exit();
        $dataRaw = (object) $this->input->get();
        if(!empty($dataRaw)){
            $code = $dataRaw->code;
            if(empty($code)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số mã tỉnh "code"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $limit = $dataRaw->limit; /*Biên độ chính là limit ngày kết quả*/
            if(empty($limit)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số giới hạn "limit"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $oneItem = $this->_data_category->getByCode($code);
            $dataResult = $this->_data->getDataResultStatistic($oneItem->id,$limit);
            $data['data_list_result'] = $dataResult;
            dd($this->getDataJs('https://static.xoso.com.vn/tkxs/CauMBNN.js?t='.date('Ymd')));
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function latlientuc(){
        $content = file_get_contents('https://static.xoso.com.vn/tkxs/CauMBLLT.js?t='.date('Ymd'));
        $content = iconv($in_charset = 'UTF-16LE' , $out_charset = 'UTF-8' , $content);
        $content = str_replace('\'', '', $content);
        echo $content;
        exit();
    }

    private function crawKetQuaNet($url, $code, $count, $date){
        $date = date('d-m-Y', strtotime($date));

        $code = strtolower($code);
        $replace = ['xsdl' => 'xsdlt', 'xsdlk' => 'xsdlc', 'xsdn' => 'xsdni', 'xsbd' => 'xsbdu'];
        if (in_array($code, array_keys($replace)))
            $codeCraw = $replace[$code];
        else
            $codeCraw = $code;
        $keyCode = substr($codeCraw, 2);
        $params = ['code' => $keyCode, 'end_date' => $date, 'count' => $count];

        try {
            $crawler = $this->_client->request('POST', "https://ketqua1.net/".$url, $params);
            $maxCount = $crawler->filter('.max-cau .dosam')->text();
            $td = $crawler->filter(".bangcau_content.vietdam");
            $value = $td->filter(".dosam")->each(function ($i){
                return $i->html();
            });
            $num = $td->each(function ($i){
                preg_match_all('/\d{1,3}-\d{1,3}/',$i->filter(".btn")->attr("data-original-title"),$patt);
                return $patt[0];
            });
            for ($i =0; $i < count($num); $i++){
                foreach ($num[$i] as $k=>$item){
                    $pos = explode('-',$item);
                    $positionOne[] = $pos[0]+1;
                    $positionTwo[] = $pos[1]+1;
                    $lifetime[] = $count;
                    $valuelt[] = $value[$i];
                }
            }
            for ($j = $count + 1; $j<= $maxCount; $j++){
                $params['count'] = $j;
                $crawler = $this->_client->request('POST',$url,$params);
                $td = $crawler->filter(".bangcau_content.vietdam");
                $value = $td->filter(".dosam")->each(function ($i){
                    return $i->html();
                });
                $num = $td->each(function ($i){
                    preg_match_all('/\d{1,3}-\d{1,3}/',$i->filter(".btn")->attr("data-original-title"),$patt);
                    return $patt[0];
                });
                for ($i =0; $i < count($num); $i++){
                    foreach ($num[$i] as $k=>$item){
                        $pos = explode('-',$item);
                        $positionOne[] = $pos[0]+1;
                        $positionTwo[] = $pos[1]+1;
                        $lifetime[] = $j;
                        $valuelt[] = $value[$i];
                    }
                }
            }
            $dataCraw = [
                'positionOne' => $positionOne,
                'positionTwo' => $positionTwo,
                'lifetime' => $lifetime,
                'valuelt' => $valuelt
            ];

            $content = " var lifetime = new Array(" . implode(',', $dataCraw['lifetime']) . ");";
            $content .= " var valuelt = new Array(" . implode(',', $dataCraw['valuelt']) . ");";
            $content .= " var positionOne = new Array(" . implode(',', $dataCraw['positionOne']) . ");";
            $content .= " var positionTwo = new Array(" . implode(',', $dataCraw['positionTwo']) . ");";

            $oneItem = $this->_data_category->getByCode($code);
            $listResult = $this->_data->getResultNearest(date('Y-m-d', strtotime($date)), $oneItem->id, 16);
            if (empty($listResult)) return false;
            foreach ($listResult as $item) {
                if ($oneItem->code !== 'XSMB') {
                    $reverse = json_decode($item['data_result']);
                    $reverse = array_reverse($reverse);
                    $item['data_result'] = json_encode($reverse);
                }
                $rs = preg_replace('/"\d.*[A-Z]"/', '', $item['data_result']);
                preg_match_all('/\d/', $rs, $result);
                $content .= " var A" . date('dmY', strtotime($item['displayed_time'])) . " = new Array(" . implode(',', $result[0]) . ");";
            }
            if ($code === 'xsmb')
                $content .= " var Aloto = new Array(0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,1,2,0,0,1,2,0,0,1,2,0,0,1,2,0,0,1,2,0,0,1,2,0,0,1,2,0,0,1,2,0,0,1,2,0,0,1,2,0,1,2,0,1,2,0,1,2,1,2,1,2,1,2,1,2);";
            else
                $content .= " var Aloto = new Array(0,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,1,2,0,0,1,2,0,0,1,2,0,0,1,2,0,1,2,1,2);";
        } catch (\Exception $exception) {
            $content = false;
        }
        return $content;
    }

    private function getDataJs($url){
        $content = file_get_contents($url);
        $content = iconv($in_charset = 'UTF-16LE' , $out_charset = 'UTF-8' , $content);
        $content = str_replace('\'', '', $content);

        return $content;
    }
}