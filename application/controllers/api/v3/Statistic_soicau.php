<?php

/**
 * Created by PhpStorm.
 * User: ducto
 * Date: 8/9/2018
 * Time: 3:07 PM
 */
defined('BASEPATH') or exit('No direct script access allowed');

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Cookie;
use Symfony\Component\DomCrawler\Crawler as domCrawler;

class Statistic_soicau extends API_Controller
{
    protected $_data;
    protected $_data_category;
    protected $_lang_code;
    protected $_all_category;
    protected $_client;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['category_model', 'result_model']);
        $this->_data = new Result_model();
        $this->_data_category = new Category_model();
        $this->_all_category = $this->_data_category->_all_category();
        $this->_lang_code = $this->session->userdata('public_lang_code');

        $this->_client = new Client();
        $guzzleClient = new GuzzleClient([
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

    public function soicau()
    {
        $data = [];
        $dataRaw = (object) $this->input->get();
        $setmode = (!empty($dataRaw->setmode)) ? $dataRaw->setmode : 'full';
        $ngay = (!empty($dataRaw->ngay)) ? $dataRaw->ngay : date('Y-m-d');
        $limit = (!empty($dataRaw->limit)) ? $dataRaw->limit : 5;
        $exactlimit = (!empty($dataRaw->exactlimit)) ? $dataRaw->exactlimit : 0;
        $lon = (!empty($dataRaw->lon)) ? $dataRaw->lon : 1;
        $db = (!empty($dataRaw->db)) ? $dataRaw->db : 0;
        $nhay = (!empty($dataRaw->nhay)) ? $dataRaw->nhay : 1;
        $timcau = (!empty($dataRaw->timcau)) ? $dataRaw->timcau : 0;
        $url = "https://rongbachkim.com/soicau.php?soi&setmode=$setmode&ngay=$ngay&limit=$limit&exactlimit=$exactlimit&lon=$lon&db=$db&nhay=$nhay&timcau=$timcau";

        $crawler = $this->_client->request('GET', $url);
        $limit = $crawler->filterXPath('//*[@class="showdays_td"]//a')->each(function ($day) {
            return trim($day->text());
        }); // ngay_chay_cau
        $table1 = $crawler->filterXPath('//*[@class="a_cau"]')->each(function ($day) {
            return trim($day->text());
        }); // Bảng vị trí cầu
        $table2 = $crawler->filterXPath('//*[@class="a_cau a_cau_more"]')->each(function ($day) {
            return trim($day->text());
        }); // Bảng vị trí cầu
        $table = [
            'min' => $table1,
            'max' => $table2,
            'all' => array_merge($table1, $table2)
        ];

        $table3 = $crawler->filterXPath('//*[@class="tbl1"]//tr')->each(function ($table) {
            $listdata = $table->filterXPath('//td')->each(function ($tabletd) {
                return $tabletd->html();
            });
            return $listdata;
        }); // Thống kê cầu lặp

        $datars = [
            'limit' => $limit,
            'result' => $table,
            'stati' => $table3
        ];
        $dataJson = $this->pack($datars, self::RESPONSE_SUCCESS, 'Success');
        return $this->response->json($dataJson);
    }
}
