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
class Soicau extends STEVEN_Controller
{
    protected $_data;
    protected $_data_category;
    protected $_lang_code;
    protected $_all_category;

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

    public function soicauwap(){
        $list = $this->_data_category->getChildByDow(date('N'));
        $data = [];
        foreach ($list as $item){
            $code = strtolower($item->code);
            $data["$code"] = $this->getDataSoiCauWap($code);
        }
        echo json_encode($data);
        exit();
    }
    public function getDataSoiCauWap($code){
        $tkdb = $this->last2number($code);
        $tkdb = "Đầu ".$tkdb['head'].", Đuôi ".$tkdb['tail'];

        $rsScbt = $this->jsbachthu($code, 'soicauwap');
        $rsScbt = array_unique($rsScbt);
        for ($i=0; $i<2; $i++){
            $k = array_rand($rsScbt);
            $sc1[] = $rsScbt[$k];
            unset($rsScbt[$k]);
        }
        for ($i=0; $i<4; $i++){
            $k = array_rand($rsScbt);
            $sc2[] = $rsScbt[$k];
            unset($rsScbt[$k]);
            $k = array_rand($rsScbt);
            $sc3[] = $rsScbt[$k];
            unset($rsScbt[$k]);
        }
        for ($i=0; $i<3; $i++){
            $k = array_rand($rsScbt);
            $sc4[] = $rsScbt[$k];
            unset($rsScbt[$k]);
        }

        $rsLoxien = $this->loxien2thinh($code,25);
        $arrKey = array_keys($rsLoxien);
        $loxien = '('.$arrKey[0].') - ('.$arrKey[1].')';
        $loxien = explode('_',$loxien);
        $loxien = implode(' - ',$loxien);
        $data['db'] = $tkdb;
        $data['loVip'] = implode(' - ',$sc1);
        $data['loXien'] = $loxien;
        $data['veNhieu'] = implode(' - ',$sc2);
        $data['kVe'] = implode(' - ',$sc3);
        $data['depNhat'] = implode(' - ',$sc4);
        return $data;
    }

    public function jsbachthu($code,$type = null){
        $code = strtolower($code);
        $replace = ['xsdl'=>'xsdlt','xsdlk'=>'xsdlc','xsdn'=>'xsdni','xsbd'=>'xsbdu'];
        if (in_array($code,array_keys($replace))) $codeCraw = $replace[$code]; else $codeCraw = $code;
        $keyCode = substr($codeCraw,2);
        $keyCraw = "bachthu-$keyCode-".date('d-m-y');
        $dataCraw = $this->getCache($keyCraw);
        if (empty($dataCraw)){
            $crawler = $this->_client->request('GET',"https://ketqua.net/cau-loto-bach-thu");
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
            $time = strtotime('23:59:59') - time();
            $this->setCache($keyCraw,$dataCraw,$time);
        } else {
            extract($dataCraw);
        }
        if ($type === 'soicauwap') return $valuelt;

        echo " var lifetime = new Array(".implode(',',$lifetime).");";
        echo " var valuelt = new Array(".implode(',',$valuelt).");";
        echo " var positionOne = new Array(".implode(',',$positionOne).");";
        echo " var positionTwo = new Array(".implode(',',$positionTwo).");";

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
    public function jslokep($code){
        $code = strtolower($code);
        $replace = ['xsdl'=>'xsdlt','xsdlk'=>'xsdlc','xsdn'=>'xsdni','xsbd'=>'xsbdu'];
        if (in_array($code,array_keys($replace))) $codeCraw = $replace[$code]; else $codeCraw = $code;
        $keyCode = substr($codeCraw,2);
        $keyCraw = "sclokep-$keyCode-".date('d-m-y');
        $dataCraw = $this->getCache($keyCraw);
        if (empty($dataCraw)){
            $crawler = $this->_client->request('GET',"https://ketqua.net/cau-loto-bach-thu");
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
            $time = strtotime('23:59:59') - time();
            $this->setCache($keyCraw,$dataCraw,$time);
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

    public function jsnhieunhay(){
        $keyCraw = "nhieunhay-".date('d-m-y');
        $dataCraw = $this->getCache($keyCraw);
        if (empty($dataCraw)){
            $crawler = $this->_client->request('GET',"https://ketqua.net/cau-hai-nhay");
            $form = $crawler->filter('[type="submit"]')->form();
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
                    $lifetime[] = 2;
                    $valuelt[] = $value[$i];
                }
            }
            for ($j=3; $j<= $maxCount; $j++){
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
            $time = strtotime('23:59:59') - time();
            $this->setCache($keyCraw,$dataCraw,$time);
        } else {
            extract($dataCraw);
        }

        echo " var lifetime = new Array(".implode(',',$lifetime).");";
        echo " var valuelt = new Array(".implode(',',$valuelt).");";
        echo " var positionOne = new Array(".implode(',',$positionOne).");";
        echo " var positionTwo = new Array(".implode(',',$positionTwo).");";

        $listResult = $this->_data->getResultNearest(date('Y-m-d',strtotime('-1 day')),1,16);
        if (empty($listResult)) return false;
        foreach ($listResult as $item){
            $rs = preg_replace('/"\d.*[A-Z]"/','',$item['data_result']);
            preg_match_all('/\d/',$rs,$result);
            echo " var A".date('dmY',strtotime($item['displayed_time']))." = new Array(".implode(',',$result[0]).");";
        }
        echo " var Aloto = new Array(0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,0,1,2,0,0,1,2,0,0,1,2,0,0,1,2,0,0,1,2,0,0,1,2,0,0,1,2,0,0,1,2,0,0,1,2,0,0,1,2,0,0,1,2,0,1,2,0,1,2,0,1,2,1,2,1,2,1,2,1,2);";
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
            $data['data_js'] = $this->getDataJs('https://static.xoso.com.vn/tkxs/CauMBBT.js');
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function venhieunhay(){
        $dataRaw = (object) $this->input->get();
        if(!empty($dataRaw)){
            $code = $dataRaw->code;
            if(empty($code)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số mã tỉnh "code"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $limit = $dataRaw->limit; /*Biên độ chính là limit ngày kết quả*/
            if(empty($limit)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số giới hạn "limit"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $oneItem = $this->_data_category->getByCode($code);
            $dataResult = $this->_data->getDataResultStatistic($oneItem->id,$limit);
            $data['data_list_result'] = $dataResult;
            dd($this->getDataJs('https://static.xoso.com.vn/tkxs/CauMBNN.js'));
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function latlientuc(){
        $dataRaw = (object) $this->input->get();
        if(!empty($dataRaw)){
            $code = $dataRaw->code;
            if(empty($code)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số mã tỉnh "code"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $limit = $dataRaw->limit; /*Biên độ chính là limit ngày kết quả*/
            if(empty($limit)) $this->response->json($this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Thiếu tham số giới hạn "limit"'));$this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
            $oneItem = $this->_data_category->getByCode($code);
            $dataResult = $this->_data->getDataResultStatistic($oneItem->id,$limit);
            $data['data_list_result'] = $dataResult;
            $data['data_js'] = $this->getDataJs('https://static.xoso.com.vn/tkxs/CauMBLLT.js');
            $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
            return $this->response->json($dataJson);
        }
        $dataJson = $this->pack([], self::RESPONSE_REQUEST_ERROR, 'Warning ! Request error !');
        return $this->response->json($dataJson);
    }

    public function data_soicau() {
        $dataRw = (object) $this->input->get();
        $code = $dataRw->code;
        $replace = ['xsdl'=>'xsdlt','xsdlk'=>'xsdlc','xsdn'=>'xsdni','xsbd'=>'xsbdu'];
        if (in_array($code,array_keys($replace))) $codeCraw = $replace[$code]; else $codeCraw = $code;
        $keyCode = substr($codeCraw,2);
        $keyCraw = "data_soicau-$keyCode-".date('d-m-y');
        $dataCraw = $this->getCache($keyCraw);
        if (empty($dataCraw)){
            $crawler = $this->_client->request('GET',"https://ketqua1.net/cau-loto-bach-thu");
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
            $time = strtotime('23:59:59') - time();
            $this->setCache($keyCraw,$dataCraw,$time);
        }
        return $this->response->json($dataCraw);
    }

    private function getDataJs($url){
        $keyCacheData = md5($url);
        $data_js = $this->getCache($keyCacheData);
        if(empty($data_js)){
            $data_js = file_get_contents($url);
            $this->setCache($keyCacheData,$data_js,60*60);
        }
        return $data_js;
    }

    private function last2number($code) {
        $date = date('Y-m-d');
        $oneItem = $this->_data_category->getByCode($code);
        $oneParent = $this->_data_category->_recursive_one_parent($this->_all_category, $oneItem->id);

        $oneResult = $this->_data->getResultNearest($date,$oneItem->id)[0];
        $oneResult = (object) $oneResult;
        $oneDataResult = json_decode($oneResult->data_result,true);

        $rewardDB = $this->rewardBD2S($oneDataResult);
        $last2number = substr($rewardDB,-2);

        $dataResult = $this->_data->getDataResultStatistic($oneItem->id,null,"DESC");

        $data['data_after_reward_1day'] = $this->get2numberAfter1day($dataResult,$oneResult->displayed_time,$last2number,$oneParent);
        $data = $this->getCham($data['data_after_reward_1day']);
        return $data;
    }
    private function rewardBD2S($oneDataResult){
        $oneDataResult = (array) $oneDataResult;
        $oneDataResult = json_encode($oneDataResult);
        preg_match('/\d{6}/',$oneDataResult,$rs);
        if (!$rs) preg_match('/\d{5}/',$oneDataResult,$rs);
        return "$rs[0]";
    }
    private function get2numberAfter1day($data,$date,$last2number,$oneParent = null) {
        $dataByDate = [];
        if (!empty($data)) foreach ($data as $key => $item) {
            $item = (object) $item;
            $result = json_decode($item->data_result,true);
            if(!empty($result[1][0])) {
                $rewardDB = $this->rewardBD2S($result);
                $twonumber = substr($rewardDB, -2);
                //dd($twonumber);
                if (!empty($last2number) && $last2number === $twonumber && $item->displayed_time !== $date) {
                    if (!empty($data[$key + 1]))
                        $item = (object)$data[$key - 1]; //Lấy ngày tiếp theo của giải đặc biệt vì đang sắp xếp mới đến cũ nên -1
                    if(!empty($item)){
                        $result = json_decode($item->data_result, true);
                        if(!empty($result)){
                            $rewardDB = $this->rewardBD2S($result);
                            $twonumber = substr($rewardDB, -2);
                            $tmp['number'] = $rewardDB;
                            $tmp['last_2'] = $twonumber;
                            $tmp['head'] = substr($twonumber, 0, 1);
                            $tmp['tail'] = substr($twonumber, -1);
                            $tmp['sum'] = substr($tmp['head'] + $tmp['tail'],-1);
                            $dataByDate[$item->displayed_time] = $tmp;
                        }

                    }

                }
            }
        }
        return $dataByDate;
    }
    private function getCham($data) {
        $dataCham = [];
        for($i = 0; $i <=9 ; $i ++){
            $countHead = 0;
            $countTail = 0;
            $countSum = 0;
            if(!empty($data)) foreach ($data as $dateDisplay => $item){
                if((int)$item['head'] == $i) $countHead++;
                if((int)$item['tail'] == $i) $countTail++;
                if((int)$item['sum'] == $i) $countSum++;
                if((int)$item['head'] == 0 && $item['tail'] == 0) $countSum++;
            }
            $tmp['number'] = $i;
            $tmp['count_head'] = $countHead;
            $tmp['count_tail'] = $countTail;
            $tmp['count_sum'] = $countSum;
            $dataCham[$i] = $tmp;
        }
        $maxHead = $maxTail = 0;
        for ($i=0; $i<=9; $i++){
            if ($maxHead < $dataCham[$i]['count_head']) $maxHead = $dataCham[$i]['count_head'];
            if ($maxTail < $dataCham[$i]['count_tail']) $maxTail = $dataCham[$i]['count_tail'];
        }
        $data = ['head' => $maxHead, 'tail' => $maxTail];
        return $data;
    }
    private function loxien2thinh($code,$limit){
        $oneItem = $this->_data_category->getByCode($code);
        $dataResult = $this->_data->getResultNearest(date('Y-m-d'),$oneItem->id,$limit);
        $data = $this->getLoXien($dataResult,2);
        return $data;
    }
    private function getLoXien($data,$size = 2){
        $toHop2Arr = [];
        $tmpListResult = [];
        if (!empty($data)) foreach ($data as $item) {
            $dateDisplay = $item['displayed_time'];
            $dataResult = json_decode($item['data_result'],true);
            unset($dataResult[0]);//Lo xien chỉ có miền bắc bỏ cái mã giải đặc biệt
            $result = $this->convertResultTo2number($dataResult);
            $listDataXien2 = $this->combinLoxien($result,$size);
            $listDataXien2 = array_unique($listDataXien2);
            $tmpListResult[$dateDisplay] = json_encode($listDataXien2);
            array_push($toHop2Arr,$listDataXien2);
        }
        $allDataResult = array_merge(...$toHop2Arr);

        $numArr = array_count_values($allDataResult);
        arsort($numArr);
        $numArr = array_slice($numArr,0,50);
        $numArrClearDuplicate = $this->clearToHopDuplicate($numArr);
        return $numArrClearDuplicate;
    }
    private function clearToHopDuplicate($data){
        $tmp = [];
        if(!empty($data)) foreach ($data as $numberCouple => $count){
            $arrNum = explode('_',$numberCouple);
            arsort($arrNum);
            $tmp[] = implode('_',$arrNum);
        }
        $dataNew = array_unique($tmp);

        $rs = [];
        if(!empty($dataNew)) foreach ($dataNew as $item){
            if(!empty($data[$item])) $rs[$item] = $data[$item];
        }
        return $rs;
    }
    private function convertResultTo2number($a){
        $aNew = [];
        if(!empty($a)) foreach ($a as $item){
            if(is_array($item)) foreach ($item as $item2){
                $aNew[] = substr($item2,-2);
            }
        }

        return array_unique($aNew);
    }
    private function combinLoxien($chars, $size, $combinations = array()) {
        if (empty($combinations)) {
            $combinations = $chars;
        }
        if ($size == 1) {
            return $combinations;
        }

        $new_combinations = array();

        foreach ($combinations as $combination) {
            $arrCombin = explode('_',$combination);
            foreach ($chars as $char) {
                if(in_array($char,$arrCombin) != TRUE) {
                    $new_combinations[] = "{$combination}_{$char}";
                }
            }
        }
        return $this->combinLoxien($chars, $size - 1, $new_combinations);
    }
}