<?php
/**
 * Developer: linhth
 * Controller rongbackkim
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Cookie;
use thiagoalessio\TesseractOCR\TesseractOCR;

class Page extends STEVEN_Controller
{
    protected $_data_category;
    protected $_data_result;
    protected $_lang_code = 'vi';
    protected $_client;
    protected $_method;
    protected $_obj_result = [];

    public function __construct()
    {
        parent::__construct();

        $this->_method = $this->router->fetch_class();
        /*Check secret*/
        //if($this->input->get('secret') !== 'Abc1234@') die('Not permission !');
        /*Check secret*/

        $this->_client = new Client();
        $guzzleClient = new GuzzleClient([
            'base_uri' => 'https://rongbachkim.com/',
            'cookies' => true,
            'headers' => [
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
                'Accept-Encoding' => 'gzip',
                'Accept-Language' => 'en-US,en;q=0.8',
                'Cache-Control' => 'private',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.75 Safari/537.36'
            ]
        ]);
        $this->_client->setClient($guzzleClient);
        $this->load->model(array('result_model', 'category_model'));
        $this->_data_category = new Category_model();
        $this->_data_result = new Result_model();

        $this->load->helper('link');
        //$this->load->driver('cache', array('adapter' => 'file', 'backup' => 'file', 'key_prefix' => CACHE_PREFIX_NAME));
    }

    public function lo_to()
    {
        $data = $this->getCache("rongbachkim");
        if (!$data){
//            $toDay = date('Y-m-d');
            try {
                $crawler = $this->_client->request('GET', "https://rongbachkim.com");
                $response = $this->_client->getResponse();
                if ($response->getStatus() == 200){
                    $caudep = $crawler->filter('#caudep');
                    if ($caudep->filter('.contentbox')->count() == 3){
                        $data['data_nhieu_nguoi_danh'] = $caudep->filter('.contentbox:nth-child(1) a')->each(function ($node){
                            return $node->text();
                        });
                        $data['data_hai_nhay_dep'] = $caudep->filter('.contentbox:nth-child(2) a')->each(function ($node){
                            return $node->text();
                        });
                        $data['data_dac_biet_dep'] = $caudep->filter('.contentbox:nth-child(3) a')->each(function ($node){
                            return $node->text();
                        });
                    } else {
                        $data['data_nhieu_nguoi_danh'] = $caudep->filter('.contentbox:nth-child(1) a')->each(function ($node){
                            return $node->text();
                        });
                        $data['data_dac_biet_dep'] = $caudep->filter('.contentbox:nth-child(2) a')->each(function ($node){
                            return $node->text();
                        });
                        $data_hai_nhay_dep = getRandomNumberSongThu(4, ' - ', ',');
                        $data['data_hai_nhay_dep'] = explode(' - ', $data_hai_nhay_dep);
                    }
                }
                print "\n";
            } catch (\Exception $exception) {
                log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
                exit;
            }
            $this->setCache("rongbachkim",$data, 3600);
        }

        $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
        return $this->response->json($dataJson);
    }
    public function lo_to_1()
    {
//        $data = file_get_contents('https://ketqua247.com/home/test');
//        echo $data; die();
        try {
            $crawler = $this->_client->request('GET', "https://rongbachkim.com");
            $response = $this->_client->getResponse();
            if ($response->getStatus() == 200){
                $caudep = $crawler->filter('#caudep');
                if ($caudep->filter('.contentbox')->count() == 3){
                    $data['data_nhieu_nguoi_danh'] = $caudep->filter('.contentbox:nth-child(1) a')->each(function ($node){
                        return $node->text();
                    });
                    $data['data_hai_nhay_dep'] = $caudep->filter('.contentbox:nth-child(2) a')->each(function ($node){
                        return $node->text();
                    });
                    $data['data_dac_biet_dep'] = $caudep->filter('.contentbox:nth-child(3) a')->each(function ($node){
                        return $node->text();
                    });
                } else {
                    $data['data_nhieu_nguoi_danh'] = $caudep->filter('.contentbox:nth-child(1) a')->each(function ($node){
                        return $node->text();
                    });
                    $data['data_dac_biet_dep'] = $caudep->filter('.contentbox:nth-child(2) a')->each(function ($node){
                        return $node->text();
                    });
                }
            }
            print "\n";
        } catch (\Exception $exception) {
            log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
            exit;
        }

        $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
        return $this->response->json($dataJson);
    }
    public function lo_top(){
        $crawler = $this->_client->request('GET', "https://rongbachkim.com/cache/trend_20.htm");
        if ($this->_client->getResponse()->getStatus() == 200){
            $data['lo_top'] = $crawler->filter('.trendholder > a')->each(function ($node){
                return $node->text();
            });
            echo json_encode($data);
        }
    }
}
