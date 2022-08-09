<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Cookie;
use Symfony\Component\DomCrawler\Crawler as domCrawler;

class RewriteData extends STEVEN_Controller
{
    protected $_client;
    protected $_data_result, $_data_category, $_data_keno;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('result_model', 'category_model', 'keno_model'));
        $this->load->helper('link');
        $this->_data_category = new Category_model();
        $this->_data_result = new Result_model();
        $this->_data_keno = new Keno_model();

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
    // Nguồn xoso.me kiểm tra và insert tất cả các nội dung.
    public function get_xoso_me()
    {
        $domain_root    = "https://xoso.me/";
        $path_xs[]      = "xsmb-{date}-ket-qua-xo-so-mien-bac-ngay-{date}.html";
        $path_xs[]      = "xsmt-{date}-ket-qua-xo-so-mien-trung-ngay-{date}.html";
        $path_xs[]      = "xsmn-{date}-ket-qua-xo-so-mien-nam-ngay-{date}.html";
        $endDate = '1-7-2022';
        $startDate = date('j-n-Y');

        foreach ($path_xs as $key => $value) {
            $url = $domain_root . $value;
            while (strtotime($startDate) > strtotime($endDate)) {
                try {
                    $startDate = date('j-n-Y', strtotime("$startDate -1 day"));
                    $slug = str_replace("{date}", $startDate, $url);
                    if ($key == 0) {
                        $data_result = $this->xoso_me_xsmb($slug);
                        $params = array(
                            'category_id' => 1,
                            'displayed_time' => date('Y-m-d', strtotime($startDate))
                        );
                        $getDataToday = $this->_data_result->getDataByParams('id', $params);
                        if (!empty($getDataToday)) {
                            // $id_cat = $getDataToday->id;
                            // $item = array(
                            //     'data_result' => json_encode($dataResultNew)
                            // );
                            // $this->updateResult($id_cat, $item);
                            echo 'update';
                        } else {
                            // $data = [
                            //     'category_id' => $category,
                            //     'title' => "XSMB ngày $date",
                            //     'slug' => $slug,
                            //     'displayed_time' => date('Y-m-d'),
                            //     'data_result' => json_encode($dataResultNew),
                            // ];
                            // $lastId = $this->saveResult([$data]);
                            echo "Inserted";
                        }
                    } else {
                        $data_result = $this->xoso_me_xsmtmn($slug);
                        dump($data_result);
                        if (!empty($data_result)) foreach ($data_result as $category_id => $oneResult) {
                            $params = array(
                                'category_id' => $category_id,
                                'displayed_time' => date('Y-m-d', strtotime($startDate))
                            );
                            $getDataToday = $this->_data_result->getDataByParams('id', $params);
                            if (!empty($getDataToday)) {
                                // $id_cat = $getDataToday->id;
                                // $item = array(
                                //     'data_result' => json_encode($oneResult)
                                // );
                                // if ($this->updateResult($id_cat, $item)) echo "\nVua cap nhat so cua giai moi \n";
                                //else echo "\nChua co giai nao moi cap nhat ! \n";
                                echo 'update';
                            } else {
                                // $title = "KQXS {$listCategory[$keyTitleCate]} ngày $date";
                                // $data = [
                                //     'category_id' => $category_id,
                                //     'title' => $title,
                                //     'slug' => $this->toSlug($title),
                                //     'displayed_time' => $date,
                                //     'data_result' => json_encode($oneResult)
                                // ];
                                // //covid: Tạm dừng Kontum
                                // //                                if (!in_array($category_id, [13])) {
                                // if (empty($lastId = $this->saveResult([$data]))) die("Insert data into db errror ! \n");
                                // echo "Inserted result id => $lastId from url $url success ! \n ";
                                //                                }
                                //$lastId = $this->saveResult([$data]);
                                //                                $this->sendBot("$listCategory[$keyTitleCate] crawler success");
                                echo "Inserted";
                            }
                        }
                    }
                } catch (\Exception $exception) {
                    echo "<pre>";
                    print_r($exception);
                    log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
                    exit;
                }
            }
        }
    }

    private function xoso_me_xsmb($slug)
    {
        $crawler = $this->_client->request('GET', $slug);
        $response = $this->_client->getResponse();
        if (empty($response->getContent())) $running = false;
        else {
            if ($crawler->filterXPath('//*[@data-id="kq"]')->count() > 0) {
                $dataResult = $crawler->filterXPath('//*[@data-id="kq"]//table//tbody//tr')->each(function ($result, $i) {
                    if ($i == 0) {
                        $arrNumber = $result->filterXPath('//td//span')->each(function ($number) {
                            return $number->text();
                        });
                        if (empty($arrNumber)) return ['', '', ''];
                    } elseif ($i == 1) {
                        $arrNumber = $result->filterXPath('//td[@class="v-giai number "]/span')->each(function ($number) {
                            return trim($number->text());
                        });
                    } else {
                        $arrNumber = $result->filterXPath('//td[@class="v-giai number"]/span')->each(function ($number) {
                            return trim($number->text());
                        });
                    }
                    return $arrNumber;
                });

                $ma_db = $dataResult[0][0];
                $ma_db = preg_replace('/\s+/', '', $ma_db);
                $ma_db = explode('-', $ma_db);

                $dataResultNew = array(
                    $ma_db,
                    $dataResult[1],
                    $dataResult[2],
                    $dataResult[3],
                    $dataResult[4],
                    $dataResult[5],
                    $dataResult[6],
                    $dataResult[7],
                    $dataResult[8]
                );
            };
            return json_encode($dataResultNew);
        }
    }

    private function xoso_me_xsmtmn($slug)
    {
        $crawler = $this->_client->request('GET', $slug);
        $response = $this->_client->getResponse();
        if (empty($response->getContent())) $running = false;
        else {
            if ($crawler->filterXPath('//*[@data-id="kq"]')->count() > 0) {
                $listCategory = $crawler->filterXPath('//*[@data-id="kq"]//table//tbody//tr[position()=1]//th//a')->each(function ($category) {
                    return trim($category->text());
                });
                $listCategoryCode = $crawler->filterXPath('//*[@data-id="kq"]//table//tbody//tr[position()=1]//th//a')->each(function ($category) {
                    return trim($category->attr('title'));
                });
                $data_res = [];
                if (!empty($listCategory)) foreach ($listCategory as $key => $category_title) {
                    $category_id = $this->_data_result->getCategoryByCode($listCategoryCode[$key], $category_title);
                    if ($category_id == false) $running = false;
                    $dataResult = $crawler->filterXPath('//*[@data-id="kq"]//table//tbody//tr[position()>1]/td[position()=' . ($key + 2) . ']')->each(function ($result) {
                        $arrNumber = $result->filterXPath('//div')->each(function ($number) {
                            return trim($number->text());
                        });
                        return $arrNumber;
                    });
                    $data_res[$category_id] = json_encode($dataResult);
                };
            }
        };
        return ($data_res);
    }
}
