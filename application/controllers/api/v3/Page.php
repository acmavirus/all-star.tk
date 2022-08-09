<?php

/**
 * Developer: linhth
 * Controller rongbackkim
 */
defined('BASEPATH') or exit('No direct script access allowed');

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Cookie;
use thiagoalessio\TesseractOCR\TesseractOCR;

class Page extends API_Controller
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
    }

    public function lo_to()
    {
        $data = $this->getCache("rongbachkim");
        if (!$data) {
            $crawler = $this->_client->request('GET', "https://rongbachkim.com/");
            $response = $this->_client->getResponse();
            if (empty($response->getContent())) {
            } else {
                $data['caudep']['lotto'] = $crawler->filterXPath('//*[@id="caudep"]//div[position() = 1]//a')->each(function ($node) {
                    return $result = explode(',', $node->text());
                });
                $data['caudep']['2nhay'] = $crawler->filterXPath('//*[@id="caudep"]//div[position() = 2]//a')->each(function ($node) {
                    return $result = explode(',', $node->text());
                });
                $data['caudep']['dacbiet'] = $crawler->filterXPath('//*[@id="caudep"]//div[position() = 3]//a')->each(function ($node) {
                    return $result = explode(',', $node->text());
                });
                $data['lotop']['homnay'] = $crawler->filterXPath('//*[@id="trendplace"]//div[position() = 1]//a')->each(function ($node) {
                    return $result = substr($node->text(), 0, 2);
                });
                $data['lotop']['homqua'] = $crawler->filterXPath('//*[@id="trendplace"]//div[position() = 1]//a')->each(function ($node) {
                    return $result = substr($node->text(), 0, 2);
                });
            };
            $time = strtotime("23:59") - strtotime(date('H:i:s'));
            $this->setCache("rongbachkim", $data, $time);
        }

        $dataJson = $this->pack($data, self::RESPONSE_SUCCESS, 'Success');
        return $this->response->json($dataJson);
    }
}
