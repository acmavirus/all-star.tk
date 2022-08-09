<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Cookie;
use Symfony\Component\DomCrawler\Crawler as domCrawler;

class Xoso extends STEVEN_Controller
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

    public function test()
    {
        $this->crawlLiveResultMTMNMinhNgoc('https://www.minhngoc.net/xo-so-truc-tiep/mien-nam.html');
    }

    public function getLiveResult()
    {
        echo "------Begin crawler live--------\n";
        if (date("H") >= 16 && date("H") < 17) {
            echo "------Crawler live XSMN--------\n";
            // $this->crawlLiveResultMTMN('https://xoso.me/xo-so-truc-tiep/xsmn-mien-nam.html');
            $this->crawlLiveResultMTMNMinhNgoc('https://www.minhngoc.net/xo-so-truc-tiep/mien-nam.html');
            // $this->crawler_live_mn_xosocomvn();
            echo "------Completed crawler live XSMN--------\n";
        } else echo "Chua toi gio quay mien nam \n\n";


        if (date("H") >= 17 && date("H") < 18) {
            echo "------Crawler live XSMT--------\n";
            $this->crawlLiveResultMTMN('https://xoso.me/xo-so-truc-tiep/xsmt-mien-trung.html');
            // $this->crawlLiveResultMTMNMinhNgoc('https://www.minhngoc.net/xo-so-truc-tiep/mien-nam.html');
            // $this->crawler_live_mt_xosocomvn();
            echo "------Completed crawler live XSMT--------\n";
        } else echo "Chua toi gio quay mien trung \n\n";

        if (date("H") >= 18 && date("H") < 19) {

            echo "------Crawler live XSMB--------\n";
            $this->crawlLiveResultMB('https://xoso.me/xo-so-truc-tiep/xsmb-mien-bac.html', 1);
            // $this->crawler_live_mb_minhngocnet();
            // $this->crawler_live_mb_xosocomvn();
            echo "------Completed crawler live XSMB--------\n";

            if (date("i") > 40) {
                echo " -------------- CRAWLER LIVE POWER XOSO.ME ------------------ \n";
                //$this->crawlLivePowerMega_xosome('Power');
                $this->crawlLivePowerMega_Vietlottvn('Power');
                echo " -------------- END CRAWLER LIVE POWER XOSO.ME ------------------ \n";
                echo " -------------- CRAWLER LIVE MEGA XOSO.ME ------------------ \n";
                //$this->crawlLivePowerMega_xosome('Mega');
                $this->crawlLivePowerMega_Vietlottvn('Mega');
                echo " -------------- END CRAWLER LIVE MEGA XOSO.ME ------------------ \n";
                echo " -------------- CRAWLER LIVE 3D XOSO.ME ------------------ \n";
                //$this->crawlerLive3d_xosome();
                $this->crawlLiveResultMax3d();
                echo " -------------- END CRAWLER LIVE 3D XOSO.ME ------------------ \n";

                /*crawler max 4d xoso me*/
                //$this->crawlerAll4d_xosome();
                $this->crawlLiveResultMax4d();

                echo " -------------- CRAWLER LIVE 4D atrungroi ------------------ \n";
                //                $this->crawlerLive4d_atrungroi();
                echo " -------------- END CRAWLER LIVE 4D atrungroi ------------------ \n";
            }


            echo "------Crawler live Dien toan--------\n";
            $this->crawlerLiveLottery123();
            $this->crawlerLiveThantai4();
            $this->crawlerLiveLottery6x36();
            echo "------Completed crawler live Dien toan--------\n";
        } else echo "Chua toi gio quay mien bac \n\n";
        die("Done crawl live result");
    }

    public function getLiveKeno()
    {
        $this->crawlerLiveKeno();
    }

    private function crawlLiveResultMTMN($url, $date = null)
    {
        if (empty($date)) $date = date('Y-m-d');
        try {
            $crawler = $this->_client->request('GET', $url);
            $response = $this->_client->getResponse();
            if (empty($response->getContent())) {
                $running = false;
                if (date("H") >= 16 && date("H") < 17) {
                    $this->crawlLiveResultMTMNMinhNgoc('https://www.minhngoc.net/xo-so-truc-tiep/mien-nam.html');
                };
                if (date("H") >= 17 && date("H") < 18) {
                    $this->crawlLiveResultMTMNMinhNgoc('https://www.minhngoc.net/xo-so-truc-tiep/mien-nam.html');
                };
            } else {
                //check ajax loading (cl-rl) dang random number
                //if ($crawler->filterXPath('//*[@id="kq"]//table//tbody//tr[position()>1]//td//div[@class="cl-rl"]')->count() > 0 || $crawler->filterXPath('//*[@id="kq"]//table//tbody//tr[position()>1]//td//div[@class="imgloadig"]')->count() > 0 || date('i', time()) < 50) {
                $textDate = $crawler->filterXPath('//h2')->text();
                preg_match('/\d{1,2}-\d{1,2}-\d\d\d\d/', $textDate, $textDate);
                $getDate = end($textDate);
                if (strtotime($getDate) == strtotime($date)) {

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
                            //                            echo $listCategoryCode[$key] . " - $category_id \n";
                            if ($category_id == false) $running = false;
                            $dataResult = $crawler->filterXPath('//*[@data-id="kq"]//table//tbody//tr[position()>1]/td[position()=' . ($key + 2) . ']')->each(function ($result) {
                                $arrNumber = $result->filterXPath('//div')->each(function ($number) {
                                    return trim($number->text());
                                });
                                return $arrNumber;
                            });
                            $data_res[$category_id] = $dataResult;
                        }
                        //                        dd($data_res);
                        $keyTitleCate = 0;
                        if (!empty($data_res)) foreach ($data_res as $category_id => $oneResult) {
                            /*if (date('i') == '01') {
                                if (!$this->getCache('sendBot' . $category_id)) {
                                    $this->setCache('sendBot' . $category_id, 1, 90);
                                    $this->sendBot("$listCategory[$keyTitleCate] crawler success");
                                }
                            }*/
                            $params = array(
                                'category_id' => $category_id,
                                'displayed_time' => $date
                            );
                            $getDataToday = $this->_data_result->getDataByParams('id', $params);
                            if (!empty($getDataToday)) {
                                $id_cat = $getDataToday->id;
                                $item = array(
                                    'data_result' => json_encode($oneResult)
                                );
                                if ($this->updateResult($id_cat, $item)) echo "\nVua cap nhat so cua giai moi \n";
                                //else echo "\nChua co giai nao moi cap nhat ! \n";
                            } else {
                                $title = "KQXS {$listCategory[$keyTitleCate]} ngày $date";
                                $data = [
                                    'category_id' => $category_id,
                                    'title' => $title,
                                    'slug' => $this->toSlug($title),
                                    'displayed_time' => $date,
                                    'data_result' => json_encode($oneResult)
                                ];
                                //covid: Tạm dừng Kontum
                                //                                if (!in_array($category_id, [13])) {
                                if (empty($lastId = $this->saveResult([$data]))) die("Insert data into db errror ! \n");
                                echo "Inserted result id => $lastId from url $url success ! \n ";
                                //                                }
                                //$lastId = $this->saveResult([$data]);
                                //                                $this->sendBot("$listCategory[$keyTitleCate] crawler success");
                            }
                            $keyTitleCate++;
                        }
                    }
                } else {
                    echo "\n\nChua den gio quay thuong ! \n\n";
                }
                //} else echo "-------Co quay deo dau ma quet !------------\n\n";
            }
        } catch (\Exception $exception) {
            echo "<pre>";
            print_r($exception);
            //            $this->sendBot("Crawler {$this->_controller} / {$this->_method} error:  error: " . json_encode($exception));
            log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
            exit;
        }
    }

    public function crawlLivePowerMega_Vietlottvn($name)
    {
        $id = 0;
        $running = true;
        if ($name == 'Power') {
            $type = 655;
            $category_id = 43;
        };
        if ($name == 'Mega') {
            $type = 645;
            $category_id = 42;
        };
        try {
            $url = "https://vietlott.vn/vi/trung-thuong/ket-qua-trung-thuong/{$type}";
            $crawler = $this->_client->request('GET', $url);
            $response = $this->_client->getResponse();
            if (empty($response->getContent())) echo "cannot";
            else {
                if ($crawler->filter('.chitietketqua_title')->count() > 0) {
                    $period = str_replace('#', '', $crawler->filter('.chitietketqua_title b:nth-child(1)')->text());
                    $slug = "result {$name} {$type} period {$period}";
                    $day = str_replace('/', '-', $crawler->filter('.chitietketqua_title b:nth-child(2)')->text());
                    if (strtotime($day) == strtotime(date('d-m-Y'))) {
                        if ($this->_data_result->checkExistByField('slug', $this->toSlug($slug), $this->_data_result->table)) {
                            echo "Record from $slug exists \n";
                        } else {
                            $data_info = [];
                            $data_info[] = $period;

                            $data_res = $crawler->filter('.day_so_ket_qua_v2 > span')->each(function ($result) {
                                $arrNumber = trim($result->text());
                                return $arrNumber;
                            });

                            $data_info[] = $crawler->filter('.chitietketqua_table tbody tr')->each(function ($result) {
                                $arrNumber = $result->filter('td:nth-child(n+3)')->each(function ($number) {
                                    return trim(preg_replace('/\./', '', $number->text()));
                                });
                                return $arrNumber;
                            });

                            $data = [
                                'category_id' => $category_id,
                                'data_result' => json_encode($data_res),
                                'data_info' => json_encode($data_info),
                                'displayed_time' => date('Y-m-d', strtotime($day)),
                                'title' => "Result $type ngày $day",
                                'slug' => $this->toSlug($slug)
                            ];
                            $lastId = $this->saveResult([$data]);
                            if (empty($lastId)) die("Insert data into db errror ! \n");
                            echo "Inserted result id => $lastId from $type + $period url $url success ! \n ";
                        }
                    } else {
                        echo "Chưa đến ngày quay \n";
                    }
                } else {
                    $running = false;
                }
            }
        } catch (\Exception $exception) {
            echo "<pre>";
            print_r($exception);
            log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
            exit;
        };
    }

    public function crawlLiveResultMax3d()
    {
        try {
            $url = "https://vietlott.vn/vi/trung-thuong/ket-qua-trung-thuong/max-3d";
            $crawler = $this->_client->request('GET', $url);
            $response = $this->_client->getResponse();
            if (empty($response->getContent())) $running = false;
            else {
                if ($crawler->filter('.chitietketqua_title')->count() > 0) {
                    $date = $crawler->filter('.chitietketqua_title b:nth-child(2)')->text();
                    echo $date . " \n";
                    if ($date == date('d/m/Y')) {
                        $data_info[] = $period = substr($crawler->filter('.chitietketqua_title b:first-child')->text(), 1);

                        $slug = "result Max3D period {$period}";

                        if ($this->_data_result->checkExistByField('slug', $this->toSlug($slug), $this->_data_result->table)) {
                            echo "Record from $slug exists \n";
                        } else {
                            $day = str_replace('/', '-', $date);
                            $dataResult = $crawler->filterXPath('//*[@class="tong_day_so_ket_qua text-center"]/div[@class="row"]//div//div')->each(function ($result) {
                                $arrNumber = $result->filterXPath('//span')->each(function ($number) {
                                    return trim($number->text());
                                });
                                return implode($arrNumber);
                            });
                            $data_res = array(
                                array($dataResult[0], $dataResult[1]),
                                array($dataResult[2], $dataResult[3], $dataResult[4], $dataResult[5]),
                                array($dataResult[6], $dataResult[7], $dataResult[8], $dataResult[9], $dataResult[10], $dataResult[11]),
                                array($dataResult[12], $dataResult[13], $dataResult[14], $dataResult[15], $dataResult[16], $dataResult[17], $dataResult[18], $dataResult[19])
                            );

                            $numberofprize = $crawler->filterXPath('//*[@class="chitietketqua_table max3D_table table-responsive"]//table//tbody//tr')->each(function ($result) {
                                $arrNumber = $result->filterXPath('//td[position()=3]')->each(function ($number) {
                                    return trim(preg_replace('/\,/', '', $number->text()));
                                });
                                return $arrNumber;
                            });
                            $data_info[] = array(
                                array($numberofprize[0], $numberofprize[1], $numberofprize[2], $numberofprize[3]),
                                array($numberofprize[4], $numberofprize[5], $numberofprize[6], $numberofprize[7], $numberofprize[8], $numberofprize[9], $numberofprize[10])
                            );
                            $data = [
                                'category_id' => 44,
                                'title' => "Result Max 3D ngày $day",
                                'slug' => $this->toSlug($slug),
                                'displayed_time' => date('Y-m-d', strtotime($day)),
                                'data_result' => json_encode($data_res),
                                'data_info' => json_encode($data_info)
                            ];

                            $params = array(
                                'category_id' => 44,
                                'displayed_time' => date('Y-m-d', strtotime($day))
                            );

                            $getDataToday = $this->_data_result->getDataByParams('id', $params);

                            if (empty($getDataToday)) {
                                $lastId = $this->saveResult([$data]);
                                if (empty($lastId)) die("Insert data into db errror ! \n");
                                echo "Inserted result id => $lastId from $period >> $url success ! \n ";
                            } else {
                                echo "Da co ban ghi roi \n ";
                            }
                        }
                    } else {
                        echo "Chua den ngay quay \n ";
                    }
                } else {
                    $running = false;
                }
            }
        } catch (\Exception $exception) {
            echo "<pre>";
            print_r($exception);
            log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
            exit;
        };
    }

    public function crawlLiveResultMax4d()
    {
        $url = "http://vietlott.vn/vi/trung-thuong/ket-qua-trung-thuong/max-4d";
        $crawler = $this->_client->request('GET', $url);
        $response = $this->_client->getResponse();
        if (empty($response->getContent())) $running = false;
        else {
            if ($crawler->filter('.chitietketqua_title')->count() > 0) {
                $date = $crawler->filter('.chitietketqua_title b:nth-child(2)')->text();
                if ($date) {
                    $data_info[] = $period = substr($crawler->filter('.chitietketqua_title b:first-child')->text(), 1);
                    $slug = "result Max4D period {$period}";
                    if ($slug) {
                        echo "Record from $slug exists \n";
                    } else {
                        $day = str_replace('/', '-', $crawler->filter('.chitietketqua_title b:nth-child(2)')->text());
                        $dataResult = $crawler->filterXPath('//*[@class="tong_day_so_ket_qua text-center"]/div[@class="row"]//div//div')->each(function ($result) {
                            $arrNumber = $result->filterXPath('//span')->each(function ($number) {
                                return trim($number->text());
                            });
                            return $arrNumber;
                        });
                        $data_res = array(
                            $dataResult[0],
                            array($dataResult[1], $dataResult[2]),
                            array($dataResult[3], $dataResult[4], $dataResult[5]),
                            $dataResult[6],
                            $dataResult[7]
                        );

                        $data_info[] = $crawler->filterXPath('//*[@class="chitietketqua_table max4d_table table-responsive"]//table//tbody//tr[position()>=3]')->each(function ($result) {
                            $arrNumber = $result->filterXPath('//td[position()=3]')->each(function ($number) {
                                return trim(preg_replace('/\./', '', $number->text()));
                            });
                            return $arrNumber[0];
                        });


                        $data = [
                            'category_id' => 45,
                            'title' => "Result Max 4D ngày $day",
                            'slug' => $this->toSlug($slug),
                            'displayed_time' => date('Y-m-d', strtotime($day)),
                            'data_result' => json_encode($data_res),
                            'data_info' => json_encode($data_info)
                        ];

                        $params = array(
                            'category_id' => 45,
                            'displayed_time' => date('Y-m-d', strtotime($day))
                        );

                        $getDataToday = $this->_data_result->getDataByParams('id', $params);

                        if (empty($getDataToday)) {
                            $lastId = $this->saveResult([$data]);
                            if (empty($lastId)) die("Insert data into db errror ! \n");
                            echo "Inserted result id => $lastId from $period >> $url success ! \n ";
                        } else {
                            dump($data);
                            echo "Da co ban ghi roi \n ";
                        }
                    }
                } else {
                    echo "Chua den ngay quay \n ";
                }
            } else {
                $running = false;
            }
        }
    }

    public function updateResult($id, $data)
    {
        return $this->_data_result->update(['id' => $id], $data);
    }

    public function saveResult($listData)
    {
        if (!empty($listData)) foreach ($listData as $item) {
            $item = (object)$item;
            if (empty($item->slug)) return;
            return $this->_data_result->save($item);
        }
    }

    /*/////////////*/

    public function crawlLiveResultMB($url, $category)
    {
        $date = date('Y-m-d');
        $slug = "xsmb-$date.html";
        try {
            $crawler = $this->_client->request('GET', $url);
            $response = $this->_client->getResponse();
            if (empty($response->getContent())) {
                $running = false;
                $this->crawler_live_mb_minhngocnet();
            } else {
                $textDate = $crawler->filterXPath('//h2')->text();
                preg_match('/\d{1,2}-\d{1,2}-\d\d\d\d/', $textDate, $textDate);
                $getDate = $textDate[0];
                if (strtotime($getDate) == strtotime(date('Y-m-d'))) {
                    if ($crawler->filterXPath('//*[@data-id="kq"]')->count() > 0) {
                        /*if (date('i') == '01') {
                            if (!$this->getCache('sendBot')) {
                                $this->sendBot("XSMB crawler success");
                                $this->setCache('sendBot', 1, 90);
                            }
                        }*/
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

                        //                        $dataResultNew = array(
                        //                            $dataResult[0],
                        //                            $dataResult[1],
                        //                            $dataResult[2],
                        //                            $dataResult[3],
                        //                            array_merge($dataResult[4], $dataResult[5]),
                        //                            $dataResult[6],
                        //                            array_merge($dataResult[7], $dataResult[8]),
                        //                            $dataResult[9],
                        //                            $dataResult[10]
                        //                        );

                        $params = array(
                            'category_id' => $category,
                            'displayed_time' => $date
                        );
                        $getDataToday = $this->_data_result->getDataByParams('id', $params);
                        if (!empty($getDataToday)) {
                            $id_cat = $getDataToday->id;
                            $item = array(
                                'data_result' => json_encode($dataResultNew)
                            );
                            $this->updateResult($id_cat, $item);
                            echo 'update \n';
                        } else {
                            $data = [
                                'category_id' => $category,
                                'title' => "XSMB ngày $date",
                                'slug' => $slug,
                                'displayed_time' => date('Y-m-d'),
                                'data_result' => json_encode($dataResultNew),
                            ];
                            $lastId = $this->saveResult([$data]);
                            if (empty($lastId)) die("Insert data into db errror ! \n");
                            echo "Inserted result id => $lastId from url $url success ! \n ";
                            //                            $this->sendBot("XSMB crawler success");
                        }
                    }
                } else {
                    echo "chua den ngay quay \n";
                }
            }
        } catch (\Exception $exception) {
            echo "<pre>";
            print_r($exception);
            //            $this->sendBot("Crawler {$this->_controller} / {$this->_method} error:  error:  " . json_encode($exception));
            log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
            exit;
        }
    }

    public function crawlLivePowerMega_xosome($name)
    {
        if ($name == 'Power') {
            $type = 655;
            $category_id = 43;
            $url = "https://xoso.me/kqxs-power-6-55-ket-qua-xo-so-power-6-55-vietlott-ngay-hom-nay.html";
            $text = 'load_kq_power_0';
            $period = $this->_data_result->getTotalByCategory($category_id);
        };
        if ($name == 'Mega') {
            $type = 645;
            $category_id = 42;
            $url = "https://xoso.me/kqxs-mega-645-ket-qua-xo-so-mega-6-45-vietlott-ngay-hom-nay.html";
            $text = 'load_kq_mega_0';
            $period = $this->_data_result->getTotalByCategory($category_id) - 1;
        };
        $datenow = date('d/m/Y');

        try {
            $data_info[] = $period = substr('00000' . ($period), -5, 5);
            $slug = "result {$name} {$type} period {$period}";
            $crawler = $this->_client->request('GET', $url);
            $response = $this->_client->getResponse();
            if (empty($response->getContent())) $running = false;
            else {
                if ($crawler->filter("#$text")->count() > 0) {
                    if ($crawler->filter("#$text i.imgloadig")->count() == 0) {
                        $date = $crawler->filter('h2')->text();
                        preg_match('/\d\d-\d\d-\d{4}/', $date, $date);
                        $date = $date[0];
                        if (strtotime(str_replace('/', '-', $date)) == strtotime(str_replace('/', '-', $datenow))) {
                            $data_res = $crawler->filter("#$text > div i")->each(function ($result) {
                                return $result->html();
                            });
                            $data_res = json_encode($data_res);
                            $info = $crawler->filter("#$text > table tr:nth-child(n+2)")->each(function ($result, $i) {
                                $rs[] = trim(str_replace('.', '', $result->filter("td:nth-child(3)")->html()));
                                $rs[] = trim(str_replace('.', '', $result->filter("td:nth-child(4)")->html()));
                                return $rs;
                            });
                            $data_info[] = $info;
                            $data_info = json_encode($data_info);

                            $data = [
                                'category_id' => $category_id,
                                'title' => "Result {$type} ngày $date",
                                'slug' => $this->toSlug($slug),
                                'displayed_time' => date('Y-m-d', strtotime(str_replace('/', '-', $date))),
                                'data_result' => $data_res,
                                'data_info' => $data_info
                            ];
                            $params = array(
                                'category_id' => $category_id,
                                'displayed_time' => date('Y-m-d', strtotime(str_replace('/', '-', $date)))
                            );

                            $getDataToday = $this->_data_result->getDataByParams('id', $params);

                            if (!empty($getDataToday)) {
                                $id_cat = $getDataToday->id;
                                $this->updateResult($id_cat, $data);
                                echo "Update result {$name} success ! \n ";
                            } else {
                                $lastId = $this->saveResult([$data]);
                                if (empty($lastId)) die("Insert data into db errror ! \n");
                                echo "Inserted result id => $lastId from url $url success ! \n ";
                                shell_exec("curl https://xoso888.com/debug/updateCacheVietlott");
                                //                                $this->sendBot("$name crawler success");
                            }
                        } else {
                            echo "chua den ngay quay \n";
                        }
                    } else {
                        echo "chua den gio quay \n";
                    }
                } else {
                    echo "khong lay duoc \n";
                    //                    $this->sendBot("Crawler error $name");
                }
            }
        } catch (\Exception $exception) {
            echo "<pre>";
            print_r($exception);
            //            $this->sendBot("Crawler {$this->_controller} / {$this->_method} error:  error:  " . json_encode($exception));
            log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
            exit;
        }
    }

    public function crawlerLive3d_xosome()
    {
        $url = "https://xoso.me/kqxs-max3d-ket-qua-xo-so-max-3d-vietlott.html";
        $category_id = 44;
        $text = "load_kq_3d_0";
        $datenow = date('d/m/Y', time());

        $period = $this->_data_result->getTotalByCategory($category_id) + 2;
        try {
            $data_info[] = $period = substr('00000' . ($period), -5, 5);
            $slug = "result max3d period {$period}";
            $crawler = $this->_client->request('GET', $url);
            $response = $this->_client->getResponse();
            if (empty($response->getContent())) $running = false;
            else {
                if ($crawler->filter("#$text")->count() > 0) {
                    if ($crawler->filter("#$text i.imgloadig")->count() == 0) {
                        $date = $crawler->filter('h2')->text();
                        preg_match('/[^a-z]*$/i', $date, $date);
                        $date = trim($date[0]);
                        if (strtotime(str_replace('/', '-', $date)) === strtotime(str_replace('/', '-', $datenow))) {
                            $data_res = $crawler->filterXPath('//*[@id="' . $text . '"]//div[@data-id="kq"]//table//tbody')->each(function ($result, $i) {
                                $arrNumber = $result->filterXPath('//tr[position() > 1]//td')->each(function ($number) {
                                    return $number->text();
                                });
                                return $arrNumber;
                            })[0];
                            $data_info = $crawler->filterXPath('//*[@id="' . $text . '"]//div[@id="dd"]//table//tbody')->each(function ($result, $i) {
                                $arrNumber = $result->filterXPath('//tr//td[position() = 3]')->each(function ($number) {
                                    return [trim(preg_replace('/\./', '', $number->text()))];
                                });
                                return $arrNumber;
                            });

                            /**/
                            $data_info = [
                                $period,
                                [
                                    [[$data_res[3]], [$data_res[10]], [$data_res[16]], [$data_res[26]]],
                                    $data_info[0]
                                ]
                            ];
                            $data_res = [
                                [$data_res[1], $data_res[2]],
                                [$data_res[6], $data_res[7], $data_res[8], $data_res[9]],
                                [$data_res[13], $data_res[14], $data_res[15], $data_res[18], $data_res[19], $data_res[20]],
                                [$data_res[22], $data_res[23], $data_res[24], $data_res[25], $data_res[28], $data_res[29], $data_res[30], $data_res[31]]
                            ];

                            $data = [
                                'category_id' => $category_id,
                                'title' => $slug,
                                'slug' => $this->toSlug($slug),
                                'displayed_time' => date('Y-m-d', strtotime(str_replace('/', '-', $date))),
                                'data_result' => json_encode($data_res),
                                'data_info' => json_encode($data_info)
                            ];

                            $params = array(
                                'category_id' => $category_id,
                                'displayed_time' => date('Y-m-d', strtotime(str_replace('/', '-', $date)))
                            );

                            $getDataToday = $this->_data_result->getDataByParams('id', $params);

                            if (!empty($getDataToday)) {
                                $id_cat = $getDataToday->id;
                                $this->updateResult($id_cat, $data);
                                echo "Update no result 3D success ! \n ";
                            } else {
                                $lastId = $this->saveResult([$data]);
                                if (empty($lastId)) die("Insert data into db errror ! \n");
                                echo "Inserted result id => $lastId from url $url success ! \n ";
                                shell_exec("curl https://xoso888.com/debug/updateCacheVietlott");
                                //                                $this->sendBot("max3d crawler success");
                            }
                        } else {
                            echo "chua den ngay quay \n";
                        }
                    } else {
                        echo "chua den gio quay \n";
                    }
                } else {
                    echo "khong lay duoc \n";
                    //                    $this->sendBot("Crawler error max3d");
                }
            }
        } catch (\Exception $exception) {
            echo "<pre>";
            print_r($exception);
            //            $this->sendBot("Crawler {$this->_controller} / {$this->_method} error:  error:  " . json_encode($exception));
            log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
            exit;
        }
    }

    public function crawlerAll4d_xosome()
    {
        log_message('error', 'xo so me 4d 1');
        $url = "https://xoso.me/kqxs-max4d-ket-qua-xo-so-dien-toan-tu-chon-so-max-4d-vietlott-ngay-hom-nay.html";
        //        $html = $this->_client->request('GET', $url);
        //        file_put_contents('test.html', $html->html());
        //        dd(123);
        //        $html = file_get_contents('test.html');
        //        $domHtml = new domCrawler($html);
        $domHtml = $this->_client->request('GET', $url);
        if (empty($this->_client->getResponse()))
            dd('die');
        log_message('error', 'xo so me 4d co respon');

        $cateId = 45;
        $period = '00000' . ($this->_data_result->getTotalByCategory($cateId) + 1);
        $period = substr($period, -5, 5);
        $date = $domHtml->filter('.tit-mien')->eq(0)->filter('h2')->html();
        preg_match('#\d{2}-\d{2}-\d{4}#', $date, $match);
        $date = $match[0];
        if (strtotime($date) != strtotime(date('Y-m-d'))) {
            log_message('error', 'xo so me 4d Chưa có kết quả mới');
            dd('Chưa có kết quả mới');
        }

        $resultCraw = $domHtml->filter('.kqmax4d')->eq(0)->filter('tr:nth-child(n+2) strong')->each(function ($b) {
            $textResult = 'XX' . $b->html();
            $textResult = substr($textResult, -4, 4);
            return str_split($textResult);
        });
        $result[] = array_splice($resultCraw, 0, 1)[0];
        $result[] = array_splice($resultCraw, 0, 2);
        $result[] = array_splice($resultCraw, 0, 3);
        $result[] = array_splice($resultCraw, 0, 1)[0];
        $result[] = array_splice($resultCraw, 0, 1)[0];
        $result = json_encode($result);

        $info[] = $period;
        $info[] = $domHtml->filter('.kqmax4d')->eq(0)->filter('tr:nth-child(n+2) td:nth-last-child(2)')->each(function ($b) {
            return $b->html();
        });
        $info = json_encode($info);
        $title = "result Max4D period $period";
        $dataSave = [
            'category_id' => $cateId,
            'data_result' => $result,
            'data_info' => $info,
            'displayed_time' => date('Y-m-d', strtotime($date)),
            'title' => $title,
            'slug' => $this->toSlug($title)
        ];
        if ($this->_data_result->getByField('slug', $dataSave['slug'])) {
            log_message('error', 'xo so me 4d update');
            $this->_data_result->update("slug = {$dataSave['slug']}", $dataSave);
        } else {
            log_message('error', 'xo so me 4d save');
            $this->_data_result->save($dataSave);
        }
    }

    public function crawlerLiveLottery123()
    {
        $running = true;
        try {
            $url = "https://xoso.me/kq-xsdt-123-ket-qua-xo-so-dien-toan-123-truc-tiep-hom-nay.html";
            $crawler = $this->_client->request('GET', $url);
            $response = $this->_client->getResponse();
            if (empty($response->getContent())) {
                echo "cannot";
            } else {
                if ($crawler->filterXPath('//*[@class="dientoan-ball clearfix"]')->count() > 0) {
                    $title = $crawler->filterXPath('//*[@class="dientoan-ball clearfix"]//div//h2/strong')->html();
                    $arr_title = explode(' ', $title);
                    $last_date = end($arr_title);
                    if (strtotime($last_date) === strtotime(date('d/m/Y'))) {
                        $slug = "dien toan 123 " . str_replace('/', '-', $last_date);
                        if ($this->_data_result->checkExistByField('slug', $this->toSlug($slug), $this->_data_result->table)) {
                            echo "Record from $slug exists \n";
                        } else {
                            $arrNumber = $crawler->filterXPath('//*[@class="dientoan-ball clearfix"]//div[position()=1]//li//div//span')->each(function ($number) {
                                return $number->text();
                            });
                            $data = [
                                'category_id' => 54,
                                'data_result' => json_encode($arrNumber),
                                'displayed_time' => date('Y-m-d', time()),
                                'title' => $slug,
                                'slug' => $this->toSlug($slug)
                            ];
                            $lastId = $this->saveResult([$data]);
                            if (empty($lastId)) die("Insert data into db errror ! \n");
                            echo "Inserted result id => $lastId \n ";
                            //                            $this->sendBot("dien toan 123 crawler success");
                        }
                    } else {
                        echo "chua ra so moi";
                    }
                } else {
                    $running = false;
                }
            }
        } catch (\Exception $exception) {
            echo "<pre>";
            print_r($exception);
            log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
            exit;
        };
    }

    public function crawlerLiveThantai4()
    {
        $running = true;
        try {
            $url = "https://xoso.me/xo-so-dien-toan-than-tai-hom-nay.html";
            $crawler = $this->_client->request('GET', $url);
            $response = $this->_client->getResponse();
            if (empty($response->getContent())) {
                echo "cannot";
            } else {
                if ($crawler->filterXPath('//*[@class="dientoan-ball clearfix"]')->count() > 0) {
                    $title = $crawler->filterXPath('//*[@class="dientoan-ball clearfix"]//div//h2/strong')->html();
                    $arr_title = explode(' ', $title);
                    $last_date = end($arr_title);
                    if (strtotime($last_date) === strtotime(date('d/m/Y'))) {
                        $slug = "than tai 4 " . str_replace('/', '-', $last_date);
                        if ($this->_data_result->checkExistByField('slug', $this->toSlug($slug), $this->_data_result->table)) {
                            echo "Record from $slug exists \n";
                        } else {
                            $arrNumber = $crawler->filterXPath('//*[@class="dientoan-ball clearfix"]//div[position()=1]//li//div//span')->each(function ($number) {
                                return $number->text();
                            });
                            $data = [
                                'category_id' => 55,
                                'data_result' => json_encode($arrNumber),
                                'displayed_time' => date('Y-m-d', time()),
                                'title' => $slug,
                                'slug' => $this->toSlug($slug)
                            ];
                            $lastId = $this->saveResult([$data]);
                            if (empty($lastId)) die("Insert data into db errror ! \n");
                            echo "Inserted result id => $lastId \n ";
                            //                            $this->sendBot("dien toan than tai crawler success");
                        }
                    } else {
                        echo "chua ra so moi";
                    }
                } else {
                    $running = false;
                }
            }
        } catch (\Exception $exception) {
            echo "<pre>";
            print_r($exception);
            log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
            exit;
        };
    }

    public function crawlerLiveLottery6x36()
    {
        $running = true;
        try {
            $url = "https://xoso.me/kq-xsdt-6-36-ket-qua-xo-so-dien-toan-6-36-truc-tiep-hom-nay.html";
            $crawler = $this->_client->request('GET', $url);
            $response = $this->_client->getResponse();
            if (empty($response->getContent())) {
                echo "cannot";
            } else {
                if ($crawler->filterXPath('//*[@class="dientoan-ball clearfix"]')->count() > 0) {
                    $title = $crawler->filterXPath('//*[@class="dientoan-ball clearfix"]//div//h2/strong')->html();
                    $arr_title = explode(' ', $title);
                    $last_date = end($arr_title);
                    if (strtotime($last_date) === strtotime(date('d/m/Y'))) {
                        $slug = "dien toan 6x36 " . str_replace('/', '-', $last_date);
                        if ($this->_data_result->checkExistByField('slug', $this->toSlug($slug), $this->_data_result->table)) {
                            echo "Record from $slug exists \n";
                        } else {
                            $arrNumber = $crawler->filterXPath('//*[@class="dientoan-ball clearfix"]//div[position()=1]//li//div//span')->each(function ($number) {
                                return $number->text();
                            });
                            $data = [
                                'category_id' => 56,
                                'data_result' => json_encode($arrNumber),
                                'displayed_time' => date('Y-m-d', time()),
                                'title' => $slug,
                                'slug' => $this->toSlug($slug)
                            ];
                            $lastId = $this->saveResult([$data]);
                            if (empty($lastId)) die("Insert data into db errror ! \n");
                            echo "Inserted result id => $lastId \n ";
                            //                            $this->sendBot("dien toan 6/36 crawler success");
                        }
                    } else {
                        echo "chua ra so moi";
                    }
                } else {
                    $running = false;
                }
            }
        } catch (\Exception $exception) {
            echo "<pre>";
            print_r($exception);
            log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
            exit;
        };
    }

    public function crawlerLiveKeno()
    {

        $running = true;
        try {
            $url = "https://xosominhngoc.net.vn/xskeno";
            $crawler = $this->_client->request('GET', $url);
            $response = $this->_client->getResponse();
            if (empty($response->getContent())) {
                $running = false;
                echo "\n CLOSE RUN \n";
            } else {
                echo "\n ==== START CRAWLER ==== \n ";

                if ($crawler->filterXPath('//*[@class="xskeno"][position() = 1]')->count() > 0) {
                    echo "\n URL empty \n";
                    $period = 0;
                    $arrNumber = [];
                    $info_keno = [];
                    $arrResult = [];
                    $period = $crawler->filterXPath('//*[@class="xskeno"][position() = 1]//span[@class="kyve"]')->text();
                    $period = str_replace('#', '', $period);
                    $date = $this->roundDownToMinuteInterval(new \DateTime(date('Y-m-d H:i:s')))->format('Y-m-d H:i:s');

//                    $date = $crawler->filterXPath('//*[@class="xskeno"][position() = 1]//span[@class="ngay"]')->text();
                    // $date = explode(' ', $date);
//                    $date = str_replace('/', '-', $date);
//                    $date = date('Y-d-m H:i:s', strtotime($date));

                    $arrNumber = $crawler->filterXPath('//*[@class="xskeno"][position() = 1]//div[@class="result"]//span')->each(function ($number) {
                        return $number->text();
                    });

                    $arrResult = $crawler->filterXPath('//*[@class="xskeno"][position() = 1]//div[@role="tabpanel"]')->each(function ($para) {
                        $arrResult = $para->filterXPath('//table//tr[position()>1]')->each(function ($para) {
                            $arrResult2 = $para->filterXPath('//td')->each(function ($para2) {
                                return trim($para2->text());
                            });
                            return $arrResult2;
                        });
                        return $arrResult;
                    });

                    $info_keno = $crawler->filterXPath('//*[@class="xskeno"][position() = 1]//span[@class="tk"]//div')->each(function ($number) {
                        return $number->text();
                    });

                    $data = [
                        'period' => $period,
                        'displayed_time' => $date,
                        'result' => json_encode($arrNumber),
                        'info_keno' => json_encode($info_keno),
                        'info_reward' => json_encode($arrResult)
                    ];

                    if ($this->_data_result->checkExistByField('period', $period, 'st_keno')) {
                        echo "Record period $period exists \n";
                        $lastId = $this->_data_result->update(['period' => $period], $data, 'st_keno');
                        if (empty($lastId)) die("Update data into db errror ! \n");
                    } else {
                        $lastId = $this->_data_result->save($data, 'st_keno');
                        if (empty($lastId)) die("Insert data into db errror ! \n");
                        echo "\n => url: $url \n ";
                        echo "Inserted result displayed_time => $date , period => $period\n ";
                    };
                } else {
                    $running = false;
                    echo "\n URL not empty \n";
                }

                echo "\n ==== END CRAWLER ==== \n ";
            }
        } catch (\Exception $exception) {
            echo "<pre>";
            print_r($exception);
            log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
            exit;
        };
    }
    public function roundDownToMinuteInterval(\DateTime $dateTime, $minuteInterval = 10)
    {
        return $dateTime->setTime(
            $dateTime->format('H'),
            floor($dateTime->format('i') / $minuteInterval) * $minuteInterval,
            0
        );
    }
    /*===>>> START CRAWLER RESULT MINHNGOC.NET <<<===*/

    public function crawlerLive4d_atrungroi()
    {
        $url = "https://atrungroi.com/vietlott-max-4d.html";
        $category_id = 45;
        $text = "vietlott-item-content";
        $datenow = date('d/m/Y', time());
        $period = $this->_data_result->getTotalByCategory($category_id) + 1;
        try {
            $data_info[] = $period = substr('00000' . ($period), -5, 5);
            $slug = "result Max4D period {$period}";
            $crawler = $this->_client->request('GET', $url);
            $response = $this->_client->getResponse();
            if (empty($response->getContent())) $running = false;
            else {
                if ($crawler->filterXPath('//*[@class="' . $text . '"]')->count() > 0) {
                    $date = $crawler->filterXPath('//*[@class="vietlott-item max4d first"]//div')->text();
                    $pieces = explode(" ", trim($date));
                    $date = array_pop($pieces);
                    if (strtotime(str_replace('/', '-', $date)) === strtotime(str_replace('/', '-', $datenow))) {
                        $data_res = $crawler->filterXPath('//*[@class="' . $text . '"][position() = 1]//div//table//tbody//tr//td[position() = 2]')->each(function ($result, $i) {
                            if ($i > 0 && $i < 3) {
                                $arrNumber2 = $result->filterXPath('//div')->each(function ($number2) {
                                    $arrNumber3 = $number2->filterXPath('//span')->each(function ($number3) {
                                        return trim($number3->text());
                                    });
                                    return $arrNumber3;
                                });
                            } else {
                                $arrNumber2 = $result->filterXPath('//div//span')->each(function ($number3) {
                                    return trim($number3->text());
                                });
                                if ($i == 3) {
                                    $arr1 = ['X'];
                                    $arrNumber2 = array_merge($arr1, $arrNumber2);
                                }
                                if ($i == 4) {
                                    $arr2 = ['X', 'X'];
                                    $arrNumber2 = array_merge($arr2, $arrNumber2);
                                }
                            }
                            return $arrNumber2;
                        });
                        $data_info = $crawler->filterXPath('//*[@class="' . $text . '"][position() = 1]//div//table//tbody//tr')->each(function ($result, $i) {
                            $arrNumber = $result->filterXPath('//td[position() = 4]//span')->text();
                            return trim($arrNumber);
                        });
                        $data_info = [$period, $data_info];
                        /**/


                        $data = [
                            'category_id' => $category_id,
                            'title' => $slug,
                            'slug' => $this->toSlug($slug),
                            'displayed_time' => date('Y-m-d', strtotime(str_replace('/', '-', $date))),
                            'data_result' => json_encode($data_res),
                            'data_info' => json_encode($data_info)
                        ];

                        //dd($data);
                        $params = array(
                            'category_id' => $category_id,
                            'displayed_time' => date('Y-m-d', strtotime(str_replace('/', '-', $date)))
                        );

                        $getDataToday = $this->_data_result->getDataByParams('id', $params);

                        if (!empty($getDataToday)) {
                            $id_cat = $getDataToday->id;
                            $this->updateResult($id_cat, $data);
                            echo "Update not result 4D success ! \n ";
                        } else {
                            $lastId = $this->saveResult([$data]);
                            if (empty($lastId)) die("Insert data into db errror ! \n");
                            echo "Inserted result id => $lastId from url $url success ! \n ";
                            shell_exec("curl https://xoso888.com/debug/updateCacheVietlott");
                            //                            $this->sendBot("max4d crawler success");
                        }
                    } else {
                        echo "chua den ngay quay \n";
                    }
                } else {
                    echo "khong lay duoc \n";
                    //                    $this->sendBot("Crawler error max4d");
                }
            }
        } catch (\Exception $exception) {
            echo "<pre>";
            print_r($exception);
            //            $this->sendBot("Crawler {$this->_controller} / {$this->_method} error:  error:  " . json_encode($exception));
            log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
            exit;
        }
    }

    public function crawler_live_mb_minhngocnet()
    {
        try {
            $crawler = $this->_client->request('GET', "https://www.minhngoc.net/xo-so-truc-tiep/mien-bac.html");
            $response = $this->_client->getResponse();
            if (empty($response->getContent())) $running = false;
            else {
                $getDate = $crawler->filterXPath('//div[@class="box_kqxs box_kqxstt_mienbac"]//div[@class="title"]')->text();
                $getDate = trim(substr($getDate, strrpos($getDate, ' '), strlen($getDate)));
                if ($getDate == date('d/m/Y', strtotime('+0 days'))) {
                    if ($crawler->filterXPath('//*[@class="content"]')->count() > 0) {
                        $dataResult = $crawler->filterXPath('//*[@class="bkqmienbac"]//table//table//tbody//tr[position() > 1]//td[position() > 1]')->each(function ($result) {
                            $arrNumber = $result->filterXPath('//div')->each(function ($number) {
                                return trim($number->text());
                            });
                            return $arrNumber;
                        });
                        if ($crawler->filterXPath('//*[@class="bkqmienbac"]//table//table//tbody//tr[position()=1]//td[position()=2]//div//span[position() = 1]')->count() > 0) {
                            $ma_db = $crawler->filterXPath('//*[@class="bkqmienbac"]//table//table//tbody//tr[position()=1]//td[position()=2]//div//span[position() = 1]')->html();
                            $ma_db = explode('-', $ma_db);
                        } else $ma_db = [""];

                        array_unshift($dataResult, $ma_db);
                        $date = date('Y-m-d');
                        $slug = "xsmb " . $date;
                        $params = array(
                            'category_id' => 1,
                            'displayed_time' => $date
                        );
                        $getDataToday = $this->_data_result->getDataByParams('id', $params);
                        if (!empty($getDataToday)) {
                            $id_cat = $getDataToday->id;
                            $item = array(
                                'data_result' => json_encode($dataResult)
                            );
                            $this->updateResult($id_cat, $item);
                            echo 'update \n';
                        } else {
                            $data = [
                                'category_id' => 1,
                                'title' => "XSMB ngày $date",
                                'slug' => $slug,
                                'displayed_time' => date('Y-m-d'),
                                'data_result' => json_encode($dataResult),
                            ];
                            $lastId = $this->saveResult([$data]);
                            if (empty($lastId)) die("Insert data into db errror ! \n");
                            echo "Inserted result id => $lastId from url mingngoc.net success ! \n ";
                        }
                    } else echo 'khong lay data duoc...<br/> \n';
                } else echo 'chua den gio live...<br/> \n';
            }
        } catch (\Exception $exception) {
            echo "<pre>";
            print_r($exception);
            log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
            exit;
        }
    }

    public function crawlLiveResultMTMNMinhNgoc($url)
    {
        try {
            $crawler = $this->_client->request('GET', $url);
            $response = $this->_client->getResponse();
            if (empty($response->getContent())) $running = false;
            else {
                $getDate = $crawler->filterXPath('//*[@id="box_tructiepkqxs"]//table[position()=1]//tbody//tr//td[@class="ngay"]')->text();
                $getDate = trim(substr($getDate, strrpos($getDate, ' '), strlen($getDate)));

                if ($getDate == date('d/m/y')) {
                    if ($crawler->filterXPath('//*[@id="box_tructiepkqxs"]//table[position()=1]')->count() > 0) {

                        $data_res = $crawler->filterXPath('//*[@id="box_tructiepkqxs"]//table[position()=1]//td[@class="tinh"]')->each(function ($category) {
                            $date = date('Y-m-d');
                            $text = trim($category->text());
                            if ($text == 'TP. HCM') $text = 'TP Hồ Chí Minh';
                            $text = $this->toSlug($text);
                            $title = "KQXS $text ngày $date";
                            $category_id = $this->_data_result->getCategoryBySlug($text);
                            if ($category_id == false) $running = false;
                            $data_result = $category->closest('tbody');
                            $datars = $data_result->filterXPath('//tr[position() > 2]')->each(function ($rs) {
                                if ($rs->filterXPath('//td//div')->count() > 1) {
                                    return $rs->filterXPath('//td//div')->each(function ($crs) {
                                        return $crs->text();
                                    });
                                } else {
                                    return [$rs->text()];
                                };
                            });
                            return [
                                'category_id' => $category_id,
                                'data_result'     => json_encode($datars),
                                'displayed_time' => $date,
                                'title' => $title,
                                'slug' => $this->toSlug($title),
                            ];
                        });

                        if (!empty($data_res)) foreach ($data_res as $key => $oneResult) {
                            $params = array(
                                'category_id' => $oneResult['category_id'],
                                'displayed_time' => $oneResult['displayed_time']
                            );
                            $getDataToday = $this->_data_result->getDataByParams('id', $params);
                            // dd($getDataToday);
                            if (!empty($getDataToday)) {
                                $idrs = $getDataToday->id;

                                if ($this->updateResult($idrs, $oneResult)) echo "\nVua cap nhat so cua giai moi \n";
                                else echo "\nChua co giai nao moi cap nhat ! \n";
                            } else {
                                $lastId = $this->saveResult([$oneResult]);
                                if (empty($lastId)) die("Insert data into db errror ! \n");
                                echo "Inserted result id => $lastId from url $url success ! \n ";
                            }
                        }
                    }
                } else {
                    echo "\n\nChua den gio quay thuong ! \n\n";
                }
            }
        } catch (\Exception $exception) {
            echo "<pre>";
            print_r($exception);
            log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
            exit;
        }
    }

    public function crawler_result_mb_minhngocnet($startDate)
    {
        print "---------Crawler list post----------<br>\n";
        $running = true;
        $i = 1;
        $endDate = date('d-m-Y');
        while ($running == true) {
            try {
                $date = date('d-m-Y', strtotime("$startDate +$i day"));
                $slug = "ket-qua-xo-so/mien-bac/{$date}.html";
                echo "Crawling result $slug \n";
                if ($date == $endDate) $running = false;

                if ($this->_data_result->checkExistByField('slug', $slug, $this->_data_result->table)) {
                    echo "Record from $slug exists \n";
                } else {
                    $crawler = $this->_client->request('GET', "https://www.minhngoc.net/$slug");
                    $response = $this->_client->getResponse();
                    if (empty($response->getContent())) $running = false;
                    else {
                        $dataResult = $crawler->filterXPath('//*[@class="content"][position() = 1]//table//table//table//tbody//tr[position() > 1]/td[position() > 1]')->each(function ($result) {
                            $arrNumber = $result->filterXPath('//div')->each(function ($number) {
                                return trim($number->text());
                            });
                            return $arrNumber;
                        });
                        if ($crawler->filterXPath('//*[@class="content"][position() = 1]//table//table//table//tbody//tr[position() = 1]/td/div/div/div[@class="loaive_content"]')->count() > 0) {
                            $ma_db = $crawler->filterXPath('//*[@class="content"][position() = 1]//table//table//table//tbody//tr[position() = 1]/td/div/div/div[@class="loaive_content"]')->html();
                            $ma_db = explode('-', $ma_db);
                        } else $ma_db = [""];
                        array_unshift($dataResult, $ma_db);
                        $data = [
                            'category_id' => 1,
                            'title' => "XSMB ngày $date",
                            'slug' => $slug,
                            'displayed_time' => date('Y-m-d', strtotime($date)),
                            'data_result' => json_encode($dataResult),
                        ];

                        $lastId = $this->saveResult([$data]);
                        if (empty($lastId)) die("Insert data into db errror ! \n");
                        echo "Inserted result id =>  from url $slug success ! \n ";
                    }
                }
            } catch (\Exception $exception) {
                echo "<pre>";
                print_r($exception);
                log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
                exit;
            }
            $i++;
        }
    }
    /*===>>> ENDD CRAWLER RESULT <<<===*/

    /*===>>> START CRAWLER RESULT XOSO.ME <<<===*/

    public function crawler_result_mtmn_minhngocnet($code, $startDate)
    {
        print "---------Crawler list post----------<br>\n";
        $running = true;
        $i = 1;
        $endDate = date('d-m-Y');
        while ($running == true) {
            try {
                $date = date('d-m-Y', strtotime("$startDate +$i day"));
                $slug = "$code-$date.html";
                echo "Crawling result $slug \n";
                if ($date == $endDate) $running = false;
                if ($this->_data_result->checkExistByField('slug', $slug, $this->_data_result->table)) {
                    echo "Record from $slug exists \n";
                } else {
                    $crawler = $this->_client->request('GET', "https://xoso.com.vn/$slug");
                    $response = $this->_client->getResponse();
                    if (empty($response->getContent())) $running = false;
                    else {
                        if ($crawler->filterXPath('//*[@class="box-ketqua"]')->count() > 0) {
                            $listCategory = $crawler->filterXPath('//*[@class="box-ketqua"]//table//tbody//tr[position()=1]/td')->each(function ($category) {
                                return trim($category->text());
                            });
                            unset($listCategory[0]);
                            $data_res = [];
                            if (!empty($listCategory)) foreach ($listCategory as $key => $category_id) {
                                $category_id = $this->_data_result->getCategoryBySlug($category_id);
                                $dataResult = $crawler->filterXPath('//*[@class="box-ketqua"]//table//tbody//tr[position()>1]/td[position()=' . ($key + 1) . ']')->each(function ($result) {
                                    $arrNumber = $result->filterXPath('//span')->each(function ($number) {
                                        return trim($number->text());
                                    });
                                    return $arrNumber;
                                });
                                $data_res[$category_id] = $dataResult;
                            }

                            $keyTitleCate = 1;
                            if (!empty($data_res)) foreach ($data_res as $category_id => $oneResult) {
                                $title = "KQXS {$listCategory[$keyTitleCate]} ngày $date";
                                $data = [
                                    'category_id' => $category_id,
                                    'title' => "{$code} {$listCategory[$keyTitleCate]} ngày $date",
                                    'slug' => $this->toSlug($title),
                                    'displayed_time' => date('Y-m-d', strtotime($date)),
                                    'data_result' => json_encode($oneResult),
                                ];
                                if ($this->_data_result->checkExistByField('slug', $this->toSlug($title), $this->_data_result->table)) {
                                    echo "Record from {$this->toSlug($title)} exists \n";
                                } else {
                                    $lastId = $this->saveResult([$data]);
                                    if (empty($lastId)) die("Insert data into db errror ! \n");
                                    echo "Inserted result id => $lastId from url $slug success ! \n ";
                                }
                                $keyTitleCate++;
                            }
                        }
                    }
                }
            } catch (\Exception $exception) {
                echo "<pre>";
                print_r($exception);
                log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
                exit;
            }
            $i++;
        }
    }
    /*===>>> ENDD CRAWLER RESULT <<<===*/
    /*===>>><<<===*/

    // ======>    XOSO.COM.VN     <=======
    public function crawler_live_mb_xosocomvn()
    {
        try {
            $crawler = $this->_client->request('GET', "https://xoso.com.vn/tuong-thuat-mien-bac/xsmb-tructiep.html");
            $response = $this->_client->getResponse();
            if (empty($response->getContent())) {
                $running = false;
            } else {
                $coun_result = $crawler->filterXPath('//*[@class="table-result"]')->count();
                if ($coun_result > 0) {
                    $date = date('Y-m-d');
                    $title = "XSMB ngày $date";
                    $data = $crawler->filterXPath('//*[@class="table-result"]//tbody//tr')->each(function ($result) {
                        $arrNumber = $result->filterXPath('//td//span')->each(function ($number) {
                            return trim($number->text());
                        });
                        return $arrNumber;
                    });
                    $data_result = [
                        'category_id' => 1,
                        'data_result'     => json_encode($data),
                        'displayed_time' => $date,
                        'title' => $title,
                        'slug' => $this->toSlug($title),
                    ];

                    $params = [
                        'category_id' => 1,
                        'displayed_time' => $date,
                        'slug' => $this->toSlug($title)
                    ];

                    $getDataToday = $this->_data_result->getDataByParams('id', $params);

                    if (!empty($getDataToday)) {
                        $idrs = $getDataToday->id;

                        if ($this->updateResult($idrs, $data_result)) echo "\nVua cap nhat so cua giai moi \n";
                        else echo "\nChua co giai nao moi cap nhat ! \n";
                    } else {
                        $lastId = $this->saveResult([$data_result]);
                        if (empty($lastId)) die("Insert data into db errror ! \n");
                        echo "Inserted result id => $lastId from url success ! \n ";
                    }
                };
            }
        } catch (\Exception $exception) {
            echo "<pre>";
            print_r($exception);
            log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
            exit;
        }
    }

    public function crawler_live_mt_xosocomvn()
    {
        try {
            $crawler = $this->_client->request('GET', "https://xoso.com.vn/tuong-thuat-mien-trung/xsmt-tructiep.html");
            $response = $this->_client->getResponse();
            if (empty($response->getContent())) {
                $running = false;
            } else {
                $coun_result = $crawler->filterXPath('//*[@class="table-result"]')->count();
                if ($coun_result > 0) {
                    $listData = $crawler->filterXPath('//*[@class="table-result"]//thead//tr//th[position() > 1]')->each(function ($result) {
                        $date = date('Y-m-d');
                        $title = trim($result->text());
                        if ($title == 'Huế') $title = "Thừa Thiên Huế";
                        if ($title == 'TP. HCM') $title = 'TP Hồ Chí Minh';
                        $text = $this->toSlug($title);
                        $title = "KQXS $title ngày $date";
                        $category_id = $this->_data_result->getCategoryBySlug($text);
                        if ($category_id == false) $running = false;
                        return [
                            'category_id' => $category_id,
                            'data_result'     => [],
                            'displayed_time' => $date,
                            'title' => $title,
                            'slug' => $this->toSlug($title),
                        ];
                    });
                    foreach ($listData as $key => $item) {
                        $data_result = $crawler->filterXPath('//*[@class="table-result"][position() = 1]//tbody//tr//td[position() = ' . ($key + 1) . ']')->each(function ($rs) {
                            if ($rs->filterXPath('//span')->count() > 1) {
                                return $rs->filterXPath('//span')->each(function ($crs) {
                                    return $crs->text();
                                });
                            } else {
                                return [$rs->text()];
                            };
                        });
                        $listData[$key]['data_result'] = json_encode($data_result);
                    };

                    foreach ($listData as $key => $item) {
                        $params = [
                            'category_id' => 1,
                            'displayed_time' => $item['displayed_time'],
                            'slug' => $item['slug']
                        ];

                        $getDataToday = $this->_data_result->getDataByParams('id', $params);

                        if (!empty($getDataToday)) {
                            $idrs = $getDataToday->id;
                            if ($this->updateResult($idrs, $item)) echo "\nVua cap nhat so cua giai moi \n";
                            else echo "\nChua co giai nao moi cap nhat ! \n";
                        } else {
                            $lastId = $this->saveResult([$item]);
                            if (empty($lastId)) die("Insert data into db errror ! \n");
                            echo "Inserted result id => $lastId from url success ! \n ";
                        }
                    };
                };
            }
        } catch (\Exception $exception) {
            echo "<pre>";
            print_r($exception);
            log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
            exit;
        }
    }

    public function crawler_live_mn_xosocomvn()
    {
        try {
            $crawler = $this->_client->request('GET', "https://xoso.com.vn/tuong-thuat-mien-nam/xsmn-tructiep.html");
            $response = $this->_client->getResponse();
            if (empty($response->getContent())) {
                $running = false;
            } else {
                $coun_result = $crawler->filterXPath('//*[@class="table-result"]')->count();
                if ($coun_result > 0) {
                    $listData = $crawler->filterXPath('//*[@class="table-result"]//thead//tr//th[position() > 1]')->each(function ($result) {
                        $date = date('Y-m-d');
                        $title = trim($result->text());
                        if ($title == 'Huế') $title = "Thừa Thiên Huế";
                        if ($title == 'TP. HCM') $title = 'TP Hồ Chí Minh';
                        $text = $this->toSlug($title);
                        $title = "KQXS $title ngày $date";
                        $category_id = $this->_data_result->getCategoryBySlug($text);
                        if ($category_id == false) $running = false;
                        return [
                            'category_id' => $category_id,
                            'data_result'     => [],
                            'displayed_time' => $date,
                            'title' => $title,
                            'slug' => $this->toSlug($title),
                        ];
                    });
                    foreach ($listData as $key => $item) {
                        $data_result = $crawler->filterXPath('//*[@class="table-result"][position() = 1]//tbody//tr//td[position() = ' . ($key + 1) . ']')->each(function ($rs) {
                            if ($rs->filterXPath('//span')->count() > 1) {
                                return $rs->filterXPath('//span')->each(function ($crs) {
                                    return $crs->text();
                                });
                            } else {
                                return [$rs->text()];
                            };
                        });
                        $listData[$key]['data_result'] = json_encode($data_result);
                    };

                    foreach ($listData as $key => $item) {
                        $params = [
                            'category_id' => 1,
                            'displayed_time' => $item['displayed_time'],
                            'slug' => $item['slug']
                        ];

                        $getDataToday = $this->_data_result->getDataByParams('id', $params);

                        if (!empty($getDataToday)) {
                            $idrs = $getDataToday->id;
                            if ($this->updateResult($idrs, $item)) echo "\nVua cap nhat so cua giai moi \n";
                            else echo "\nChua co giai nao moi cap nhat ! \n";
                        } else {
                            $lastId = $this->saveResult([$item]);
                            if (empty($lastId)) die("Insert data into db errror ! \n");
                            echo "Inserted result id => $lastId from url success ! \n ";
                        }
                    };
                };
            }
        } catch (\Exception $exception) {
            echo "<pre>";
            print_r($exception);
            log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
            exit;
        }
    }
    // ======>                     <=======

    public function crawlResultPowerMega($name)
    {
        $id = 0;
        $running = true;
        if ($name == 'Power') {
            $type = 655;
            $category_id = 43;
        };
        if ($name == 'Mega') {
            $type = 645;
            $category_id = 42;
        };
        while ($running == true) {
            $id++;
            try {
                $period = substr('00000' . $id, -5, 5);
                $url = "https://vietlott.vn/vi/trung-thuong/ket-qua-trung-thuong/{$type}?id={$period}";
                $slug = "result {$name} {$type} period {$period}";
                if ($this->_data_result->checkExistByField('slug', $this->toSlug($slug), $this->_data_result->table)) {
                    echo "Record from $slug exists \n";
                } else {
                    $crawler = $this->_client->request('GET', $url);
                    $response = $this->_client->getResponse();
                    if (empty($response->getContent())) $running = false;
                    else {
                        if ($crawler->filterXPath('//*[@class="chitietketqua_title"]')->count() > 0) {
                            $data_info = [];
                            $data_info[] = $period;
                            $day = str_replace('/', '-', $crawler->filterXPath('//*[@class="chitietketqua_title"]//b[position()=2]')->text());

                            $data_res = $crawler->filterXPath('//*[@class="day_so_ket_qua_v2"]')->each(function ($result) {
                                $arrNumber = $result->filterXPath('//span')->each(function ($number) {
                                    return trim($number->text());
                                });
                                return $arrNumber;
                            });

                            $data_info[] = $crawler->filterXPath('//*[@class="chitietketqua_table maga645_table"]//table//tbody//tr')->each(function ($result) {
                                $arrNumber = $result->filterXPath('//td[position()>=3]')->each(function ($number) {
                                    return trim(preg_replace('/\./', '', $number->text()));
                                });
                                return $arrNumber;
                            });

                            $data = [
                                'category_id' => $category_id,
                                'title' => "Result $type ngày $day",
                                'slug' => $this->toSlug($slug),
                                'displayed_time' => date('Y-m-d', strtotime($day)),
                                'data_result' => json_encode($data_res[0]),
                                'data_info' => json_encode($data_info)
                            ];
                            $lastId = $this->saveResult([$data]);
                            if (empty($lastId)) die("Insert data into db errror ! \n");
                            echo "Inserted result id => $lastId from $type + $period url $url success ! \n ";
                        } else {
                            $running = false;
                        }
                    }
                }
            } catch (\Exception $exception) {
                echo "<pre>";
                print_r($exception);
                log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
                exit;
            };
        }
    }

    public function crawlResultMax3D()
    {
        $id = 410;
        $running = true;
        while ($running == true) {
            $id++;
            try {
                $period = substr('00000' . $id, -5, 5);
                $url = "https://vietlott.vn/vi/trung-thuong/ket-qua-trung-thuong/max-3D?id={$period}&nocatche=1";
                $slug = "result Max3D period {$period}";
                if ($this->_data_result->checkExistByField('slug', $this->toSlug($slug), $this->_data_result->table)) {
                    echo "Record from $url exists \n";
                } else {
                    $crawler = $this->_client->request('GET', $url);
                    $response = $this->_client->getResponse();
                    if (empty($response->getContent())) $running = false;
                    else {
                        if ($crawler->filterXPath('//*[@class="chitietketqua_title"]')->count() > 0) {
                            $data_info = [];
                            $data_info[] = $period;
                            $day = str_replace('/', '-', $crawler->filterXPath('//*[@class="chitietketqua_title"]//b[position()=2]')->text());


                            $dataResult = $crawler->filterXPath('//*[@class="tong_day_so_ket_qua text-center"]/div[@class="row"]//div//div')->each(function ($result) {
                                $arrNumber = $result->filterXPath('//span')->each(function ($number) {
                                    return trim($number->text());
                                });
                                return implode($arrNumber);
                            });
                            $data_res = array(
                                array($dataResult[0], $dataResult[1]),
                                array($dataResult[2], $dataResult[3], $dataResult[4], $dataResult[5]),
                                array($dataResult[6], $dataResult[7], $dataResult[8], $dataResult[9], $dataResult[10], $dataResult[11]),
                                array($dataResult[12], $dataResult[13], $dataResult[14], $dataResult[15], $dataResult[16], $dataResult[17], $dataResult[18], $dataResult[19])
                            );

                            $numberofprize = $crawler->filterXPath('//*[@class="chitietketqua_table max3D_table table-responsive"]//table//tbody//tr')->each(function ($result) {
                                $arrNumber = $result->filterXPath('//td[position()=3]')->each(function ($number) {
                                    return trim($number->text());
                                });
                                return $arrNumber;
                            });
                            $data_info[] = array(
                                array($numberofprize[0], $numberofprize[1], $numberofprize[2], $numberofprize[3]),
                                array($numberofprize[5], $numberofprize[6], $numberofprize[7], $numberofprize[8], $numberofprize[9], $numberofprize[10], $numberofprize[11])
                            );
                            $data = [
                                'category_id' => 44,
                                'title' => "Result Max 3D ngày $day",
                                'slug' => $this->toSlug($slug),
                                'displayed_time' => date('Y-m-d', strtotime($day)),
                                'data_result' => json_encode($data_res),
                                'data_info' => json_encode($data_info)
                            ];
                            $lastId = $this->saveResult([$data]);
                            if (empty($lastId)) die("Insert data into db errror ! \n");
                            echo "Inserted result id => $lastId from $period >> $url success ! \n ";
                        } else {
                            $running = false;
                        }
                    }
                }
            } catch (\Exception $exception) {
                echo "<pre>";
                print_r($exception);
                log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
                exit;
            };
        }
    }

    /*+++++++++++++*/

    public function crawlResultMax4D()
    {
        $id = 0;
        $running = true;
        while ($running == true) {
            $id++;
            try {
                $period = substr('00000' . $id, -5, 5);
                $url = "https://vietlott.vn/vi/trung-thuong/ket-qua-trung-thuong/max-4D?id={$period}&nocatche=1";
                $slug = "result Max4D period {$period}";

                if ($this->_data_result->checkExistByField('slug', $this->toSlug($slug), $this->_data_result->table)) {
                    echo "Record from $url exists \n";
                } else {
                    $crawler = $this->_client->request('GET', $url);
                    $response = $this->_client->getResponse();
                    if (empty($response->getContent())) $running = false;
                    else {
                        if ($crawler->filterXPath('//*[@class="chitietketqua_title"]')->count() > 0) {
                            $data_info = [];
                            $data_info[] = $period;
                            $day = str_replace('/', '-', $crawler->filterXPath('//*[@class="chitietketqua_title"]//b[position()=2]')->text());
                            $dataResult = $crawler->filterXPath('//*[@class="tong_day_so_ket_qua text-center"]/div[@class="row"]//div//div')->each(function ($result) {
                                $arrNumber = $result->filterXPath('//span')->each(function ($number) {
                                    return trim($number->text());
                                });
                                return $arrNumber;
                            });
                            $data_res = array(
                                $dataResult[0],
                                array($dataResult[1], $dataResult[2]),
                                array($dataResult[3], $dataResult[4], $dataResult[5]),
                                $dataResult[6],
                                $dataResult[7]
                            );

                            ($period >= 259) ? $pos = 3 : $pos = 1; //thay đổi vị trí lấy data do bảng thay đổi từ lần quay 259

                            $data_info[] = $crawler->filterXPath('//*[@class="chitietketqua_table max4d_table table-responsive"]//table//tbody//tr[position()>=' . $pos . ']')->each(function ($result) {
                                $arrNumber = $result->filterXPath('//td[position()=3]')->each(function ($number) {
                                    return trim(preg_replace('/\./', '', $number->text()));
                                });
                                return $arrNumber[0];
                            });

                            $data = [
                                'category_id' => 45,
                                'title' => "Result Max 4D ngày $day",
                                'slug' => $this->toSlug($slug),
                                'displayed_time' => date('Y-m-d', strtotime($day)),
                                'data_result' => json_encode($data_res),
                                'data_info' => json_encode($data_info)
                            ];
                            $lastId = $this->saveResult([$data]);
                            if (empty($lastId)) die("Insert data into db errror ! \n");
                            echo "Inserted result id => $lastId from $period >> $url success ! \n ";
                        } else {
                            $running = false;
                        }
                    }
                }
            } catch (\Exception $exception) {
                echo "<pre>";
                print_r($exception);
                log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
                exit;
            };
        }
    }

    public function crawlerLiveLotteryKeno()
    {
        $id = 0; //5415
        $running = true;
        try {
            $id++;
            $period = substr('0000000' . $id, -7, 7);
            $url = "https://vietlott.vn/vi/trung-thuong/ket-qua-trung-thuong/view-detail-keno-result?id={$period}";
            $crawler = $this->_client->request('GET', $url);
            $response = $this->_client->getResponse();
            if (empty($response->getContent())) {
                $running = false;
            } else {
                $slug = "keno {$period}";
                if ($this->_data_result->checkExistByField('slug', $this->toSlug($slug), $this->_data_result->table)) {
                    echo "Record from $slug exists \n";
                } else {
                    if ($crawler->filterXPath('//*[@class="day_so_ket_qua_v2"]')->count() > 0) {
                        $date = $crawler->filterXPath('//*[@class="table-result-info"]//tr[position() = 3]//td[position()=1]')->text();
                        $arrNumber = $crawler->filterXPath('//*[@class="day_so_ket_qua_v2"]//span')->each(function ($number) {
                            return $number->text();
                        });

                        $arrResult = $crawler->filterXPath('//*[@class="tab-content"]//div[@class="tab-pane"]')->each(function ($para) {
                            $arrResult = $para->filterXPath('//table//tr[position()>1]//td[position()>1]')->each(function ($para) {
                                return trim($para->text());
                            });
                            return $arrResult;
                        });
                    } else {
                        $running = false;
                    }
                    $data = [
                        'category_id' => 57,
                        'data_result' => json_encode($arrNumber),
                        'data_info' => json_encode($arrResult),
                        'displayed_time' => date('Y-m-d', strtotime(str_replace('/', '-', $date))),
                        'title' => $slug,
                        'slug' => $this->toSlug($slug)
                    ];
                    $lastId = $this->saveResult([$data]);
                    if (empty($lastId)) die("Insert data into db errror ! \n");
                    echo "Inserted result id => $lastId \n ";
                }
            }
        } catch (\Exception $exception) {
            echo "<pre>";
            print_r($exception);
            log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
            exit;
        };
    }

    public function crawlerDataLottey()
    {
        $status = true;
        $i = 0;
        while ($status == true) {
            $running = true;
            try {
                $last_date = date('d-m-Y', strtotime("-{$i} days", time()));
                $end_date = '01-01-2018';
                if ($last_date == $end_date) $status = false;
                $url = "https://xoso.me/ket-qua-xo-so-dien-toan-ngay-{$last_date}.html";
                $crawler = $this->_client->request('GET', $url);
                $response = $this->_client->getResponse();
                if (empty($response->getContent())) {
                    echo "cannot";
                } else {
                    if ($crawler->filterXPath('//*[@class="dientoan clearfix"]')->count() > 0) {
                        $data = $crawler->filterXPath('//*[@class="dientoan clearfix"]//li')->each(function ($oneView) {
                            $v = $oneView->filterXPath('//div//span')->each(function ($number) {
                                return $number->text();
                            });
                            return $v;
                        });

                        if (!empty($data)) foreach ($data as $k => $item) {
                            $count = count($item);
                            if ($count === 3) {
                                $slug = "dien toan 123 " . $last_date;
                                $cate = 54;
                            }
                            if ($count === 1) {
                                $slug = "than tai 4 " . $last_date;
                                $cate = 55;
                            }
                            if ($count === 6) {
                                $slug = "dien toan 6x36 " . $last_date;
                                $cate = 56;
                            }

                            if ($this->_data_result->checkExistByField('slug', $this->toSlug($slug), $this->_data_result->table)) {
                                echo "Record from $slug exists \n";
                            } else {
                                $dataView = [
                                    'category_id' => $cate,
                                    'data_result' => json_encode($item),
                                    'displayed_time' => date('Y-m-d', strtotime($last_date)),
                                    'title' => $slug,
                                    'slug' => $this->toSlug($slug)
                                ];
                                $lastId = $this->saveResult([$dataView]);
                                if (empty($lastId)) die("Insert data into db errror ! \n");
                                echo "Inserted result id => $lastId \n ";
                            }
                        }
                    } else {
                        $running = false;
                    }
                }
            } catch (\Exception $exception) {
                echo "<pre>";
                print_r($exception);
                log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
                exit;
            };
            $i = $i + 1;
        }
    }

    /*==>> Data Lottery <<==*/

    public function insert($oneCategory, $item)
    {
        $item = (object)$item;
        $lastId = $this->_data_result->save([
            'data_result' => json_encode($item->data_result),
            'displayed_time' => $item->day,
            'source' => $item->source,
            'source_hash' => $item->source_hash,
            'is_status' => 1
        ]);
        if ($lastId == false) die('Insert error');
        $title = "Kết quả $oneCategory->title - $item->title";
        $dataTrans = [
            'id' => $lastId,
            'language_code' => $this->_lang_code,
            'title' => $title,
            'slug' => $this->toSlug($title),
            'meta_title' => $title,
            'description' => $title,
            'meta_description' => $title,
            'meta_keyword' => $title,
        ];
        $this->_data_result->save($dataTrans, $this->_data_result->table_trans);

        $dataCate = [
            'result_id' => $lastId,
            'category_id' => $oneCategory->id
        ];
        $this->_data_result->save($dataCate, $this->_data_result->table_category);
        echo "Inserted result id => $lastId from url $item->source \n";
    }

    public function update($id, $item)
    {
        $item = (object)$item;
        $this->_data_result->update(['id' => $id], [
            'data_result' => json_encode($item->data_result),
        ]);
        echo "Updated result id => $id from url $item->source \n";
    }

    public function sendMail($to_mail, $subject, $contentHtml, $emailToCC = '', $emailToBCC = '')
    {
        try {
            $this->load->library('email');
            if (!empty($this->settings['protocol'])) {
                $this->email->protocol = $this->settings['protocol'];
                $this->email->smtp_host = $this->settings['smtp_host'];
                $this->email->smtp_user = $this->settings['smtp_user'];
                $this->email->smtp_port = $this->settings['smtp_port'];
            }
            if (!empty($this->settings['email_admin'])) {
                $from_mail = $this->settings['email_admin'];
            } else {
                $from_mail = $this->email->smtp_user;
            }
            $this->email->from($from_mail);
            $this->email->to($to_mail);
            if (!empty($emailToCC)) $this->email->cc($emailToCC);
            if (!empty($emailToBCC)) $this->email->bcc($emailToBCC);
            $this->email->subject($subject);
            $this->email->message($contentHtml);
            if ($this->email->send()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            $this->_message = array(
                'type' => 'danger',
                'message' => 'Co lỗi khi gửi mail'
            );
        }
    }

    public function getGoldPrice24h($date = '', $update = 1)
    {
        if (date('H') % 2 == 1) {
            $date = date('Y-m-d');
        } else {
            $date = date('Y-m-d', strtotime('-1 day'));
        }
        //if(empty($date)) $date = date('Y-m-d');
        $url = 'https://www.24h.com.vn/gia-vang-hom-nay-c425.html?d=' . $date;
        #
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $html = curl_exec($curl);
        #
        preg_match_all("/<div class=\"tabBody mgbt15\">(.*?)<\/div>/si", $html, $matches);
        $data = [];
        if (!empty(preg_match_all("/<tr>(.*?)<\/tr>/si", $matches[0][0], $matches))) {
            if (!empty($matches[1])) foreach ($matches[1] as $k => $item) {
                if (preg_match("/<h2>(.*?)<\/h2>/si", $item, $code) != null) {
                    $code = $code[1];
                } else {
                    $code = '';
                }
                if (preg_match_all("/<span (.*?)>(.*?)<\/span>/si", $item, $matches) != null) {
                    $buy_price = preg_replace('/[^0-9]/', '', $matches[2][0]);
                    $sell_price = preg_replace('/[^0-9]/', '', $matches[2][2]);
                } else {
                    $buy_price = '';
                    $sell_price = '';
                }
                $rowData['code'] = $code;
                $rowData['buy_price'] = $buy_price;
                $rowData['sell_price'] = $sell_price;
                $data[] = $rowData;
            }
        }
        $data_update['date'] = $date;
        $data_update['data'] = json_encode($data);
        if (!empty($data)) $this->_data_gold_price->updateGoldPrice($date, $data_update, $update);
        echo $date;
        return;
        //        $url_next = '/crawler/getGoldPrice24h/'.date('Y-m-d', strtotime("+1 day", strtotime($date)));
        //        if ($date == '2020-04-07') die('completed');
        //        header("Refresh:0; url=".$url_next);
    }

    public function getExchangeRate24h($date = '', $update = 1)
    {
        if (date('H') % 2 == 1) {
            $date = date('Y-m-d');
        } else {
            $date = date('Y-m-d', strtotime('-2 day'));
        }
        //if(empty($date)) $date = date('Y-m-d');
        $url = 'https://www.24h.com.vn/ty-gia-ngoai-te-ttcb-c426.html?d=' . $date;
        #
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $html = curl_exec($curl);
        #
        preg_match_all("/<div class=\"tabBody mgbt15\">(.*?)<\/div>/si", $html, $matches);
        $data = [];
        if (!empty(preg_match_all("/<tr>(.*?)<\/tr>/si", $matches[0][0], $matches))) {
            foreach ($matches[1] as $k => $item) {
                if (!empty(preg_match_all("/<td (.*?)>(.*?)<\/td>/si", $item, $matches))) {
                    $code = trim(strip_tags($matches[0][0]));

                    preg_match("/<span(.*?)>(.*?)<\/span>/si", $matches[0][1], $buy_price);
                    $buy_price = trim(strip_tags($buy_price[0]));
                    $buy_price = str_replace(',', '', $buy_price);

                    preg_match("/<span(.*?)>(.*?)<\/span>/si", $matches[0][2], $transfer_price);
                    $transfer_price = trim(strip_tags($transfer_price[0]));
                    $transfer_price = str_replace(',', '', $transfer_price);

                    preg_match("/<span(.*?)>(.*?)<\/span>/si", $matches[0][3], $sell_price);
                    $sell_price = trim(strip_tags($sell_price[0]));
                    $sell_price = str_replace(',', '', $sell_price);

                    $rowData['code'] = $code;
                    $rowData['buy_price'] = $buy_price;
                    $rowData['transfer_price'] = $transfer_price;
                    $rowData['sell_price'] = $sell_price;
                    $data[] = $rowData;
                }
            }
        }
        $data_update['date'] = $date;
        $data_update['data'] = json_encode($data);
        if (!empty($data)) $this->_data_exchange_rate->updateExchangeRate($date, $data_update, $update);
        echo $date;
        return;
        //        $url_next = '/crawler/getExchangeRate24h/'.date('Y-m-d', strtotime("+1 day", strtotime($date)));
        //        if ($date == '2020-04-11') die('completed');
        //        header("Refresh:0; url=".$url_next);
    }

    /*==>> Data Lottery <<==*/

    public function crawlerKeno_minhchinh($page = '')
    {
        if (empty($page)) $page = 1;
        require_once('application/libraries/simple_html_dom.php');
        $param = array(
            'page' => $page,
        );
        $url = 'https://www.minhchinh.com/xo-so-dien-toan-keno.html';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, count($param));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        $data = curl_exec($ch);
        curl_close($ch);
        $html = str_get_html($data);
        $data_update = [];
        foreach ($html->find('.wrapperKQKeno') as $item) {
            $period = $item->find('.kyKQKeno', 0)->plaintext;
            $period = str_replace('#', '', $period);
            $displayed_time1 = str_replace('/', '-', $item->find('.timeKQ div', 0)->plaintext);
            $displayed_time2 = $item->find('.timeKQ div', 1)->plaintext;
            $fullTime = $displayed_time1 . ' ' . $displayed_time2;
            $displayed_time = date('Y-m-d H:i:s', strtotime($fullTime));
            $result = [];
            foreach ($item->find('.boxKQKeno div') as $i) {
                $result[] = $i->plaintext;
            }
            $result = json_encode($result);
            $item_update = [
                'period' => $period,
                'displayed_time' => $displayed_time,
                'result' => $result,
            ];
            $data_update[] = $item_update;
        }
        $data_update = array_reverse($data_update);
        foreach ($data_update as $item_update) {
            $update = $this->_data_keno->updateKeno($item_update['period'], $item_update);
            if ($update == 1) echo 'Insert Success #' . $item_update['period'] . '<br>';
        }
        //        $page--;
        //        $url_next = '/crawler/crawlerKeno_minhchinh/'.$page;
        //        if ($page == 0) die('completed');
        //        header("Refresh:0; url=".$url_next);
    }

    public function crawlUpdatePowerMega_xosome($name)
    {
        if ($name == 'Power') {
            $type = 655;
            $category_id = 43;
            $url = "https://xoso.me/kqxs-power-6-55-ket-qua-xo-so-power-6-55-vietlott-ngay-hom-nay.html";
            $text = 'load_kq_power_0';
            $period = $this->_data_result->getTotalByCategory($category_id);
        };
        if ($name == 'Mega') {
            $type = 645;
            $category_id = 42;
            $url = "https://xoso.me/kqxs-mega-645-ket-qua-xo-so-mega-6-45-vietlott-ngay-hom-nay.html";
            $text = 'load_kq_mega_0';
            $period = $this->_data_result->getTotalByCategory($category_id) - 1;
        };

        try {
            $data_info[] = $period = substr('00000' . ($period), -5, 5);
            $slug = "result {$name} {$type} period {$period}";
            $crawler = $this->_client->request('GET', $url);
            $response = $this->_client->getResponse();
            if (!empty($response->getContent())) {
                if ($crawler->filter("#$text")->count() > 0) {
                    if ($crawler->filter("#$text i.imgloadig")->count() == 0) {
                        $date = $crawler->filter('h2')->text();
                        preg_match('/\d\d-\d\d-\d{4}/', $date, $date);
                        $date = $date[0];
                        $data_res = $crawler->filter("#$text > div i")->each(function ($result) {
                            return $result->html();
                        });
                        $data_res = json_encode($data_res);
                        $info = $crawler->filter("#$text > table tr:nth-child(n+2)")->each(function ($result, $i) {
                            $rs[] = trim(str_replace('.', '', $result->filter("td:nth-child(3)")->html()));
                            $rs[] = trim(str_replace('.', '', $result->filter("td:nth-child(4)")->html()));
                            return $rs;
                        });
                        $data_info[] = $info;
                        $data_info = json_encode($data_info);

                        $data = [
                            'category_id' => $category_id,
                            'title' => "Result {$type} ngày $date",
                            'slug' => $this->toSlug($slug),
                            'displayed_time' => date('Y-m-d', strtotime(str_replace('/', '-', $date))),
                            'data_result' => $data_res,
                            'data_info' => $data_info
                        ];
                        $params = array(
                            'category_id' => $category_id,
                            'displayed_time' => date('Y-m-d', strtotime(str_replace('/', '-', $date)))
                        );

                        $getDataToday = $this->_data_result->getDataByParams('id', $params);

                        if (!empty($getDataToday)) {
                            $id_cat = $getDataToday->id;
                            $this->updateResult($id_cat, $data);
                            echo "Update result {$name} success ! \n ";
                        } else {
                            $lastId = $this->saveResult([$data]);
                            if (empty($lastId)) die("Insert data into db errror ! \n");
                            echo "Inserted result id => $lastId from url $url success ! \n ";
                            shell_exec("curl https://xoso888.com/debug/updateCacheVietlott");
                        }
                    } else {
                        echo "chua den gio quay \n";
                    }
                } else {
                    echo "khong lay duoc \n";
                }
            }
        } catch (\Exception $exception) {
            echo "<pre>";
            print_r($exception);
            //            $this->sendBot("Crawler {$this->_controller} / {$this->_method} error:  error:  " . json_encode($exception));
            log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
            exit;
        }
    }

    private function crawlListResultMienBac($startDate)
    {
        print "---------Crawler list post----------<br>\n";
        $running = true;
        $i = 1;
        $endDate = date('d-m-Y');
        while ($running == true) {
            try {
                $date = date('d-m-Y', strtotime("$startDate +$i day"));
                $slug = "xsmb-$date.html";
                echo "Crawling result $slug \n";
                if ($date == $endDate) $running = false;
                if ($this->_data_result->checkExistByField('slug', $slug, $this->_data_result->table)) {
                    echo "Record from $slug exists \n";
                } else {
                    $crawler = $this->_client->request('GET', "http://xoso.com.vn/$slug");
                    $response = $this->_client->getResponse();
                    if (empty($response->getContent())) $running = false;
                    else {
                        $dataResult = $crawler->filterXPath('//*[@id="kqngay_' . date('dmY', strtotime($date)) . '"]//*[@class="box-ketqua"]//table//tbody//tr/td[position()=last()]')->each(function ($result) {
                            $arrNumber = $result->filterXPath('//span/text()')->each(function ($number) {
                                return trim($number->text());
                            });
                            return $arrNumber;
                        });

                        $data = [
                            'category_id' => 1,
                            'title' => "XSMB ngày $date",
                            'slug' => $slug,
                            'displayed_time' => date('Y-m-d', strtotime($date)),
                            'data_result' => json_encode($dataResult),
                        ];
                        $lastId = $this->saveResult([$data]);
                        if (empty($lastId)) die("Insert data into db errror ! \n");
                        echo "Inserted result id => $lastId from url $slug success ! \n ";
                    }
                }
            } catch (\Exception $exception) {
                echo "<pre>";
                print_r($exception);
                log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
                exit;
            }
            $i++;
        }
    }

    private function crawlListResultMTMN($code, $startDate)
    {
        print "---------Crawler list post----------<br>\n";
        $running = true;
        $i = 1;
        $endDate = date('d-m-Y');
        while ($running == true) {
            try {
                $date = date('d-m-Y', strtotime("$startDate +$i day"));
                $slug = "$code-$date.html";
                echo "Crawling result $slug \n";
                if ($date == $endDate) $running = false;
                if ($this->_data_result->checkExistByField('slug', $slug, $this->_data_result->table)) {
                    echo "Record from $slug exists \n";
                } else {
                    $crawler = $this->_client->request('GET', "https://xoso.com.vn/$slug");
                    $response = $this->_client->getResponse();
                    if (empty($response->getContent())) $running = false;
                    else {
                        if ($crawler->filterXPath('//*[@class="box-ketqua"]')->count() > 0) {
                            $listCategory = $crawler->filterXPath('//*[@class="box-ketqua"]//table//tbody//tr[position()=1]/td')->each(function ($category) {
                                return trim($category->text());
                            });
                            unset($listCategory[0]);
                            $data_res = [];
                            if (!empty($listCategory)) foreach ($listCategory as $key => $category_id) {
                                $category_id = $this->_data_result->getCategoryBySlug($category_id);
                                $dataResult = $crawler->filterXPath('//*[@class="box-ketqua"]//table//tbody//tr[position()>1]/td[position()=' . ($key + 1) . ']')->each(function ($result) {
                                    $arrNumber = $result->filterXPath('//span')->each(function ($number) {
                                        return trim($number->text());
                                    });
                                    return $arrNumber;
                                });
                                $data_res[$category_id] = $dataResult;
                            }

                            $keyTitleCate = 1;
                            if (!empty($data_res)) foreach ($data_res as $category_id => $oneResult) {
                                $title = "KQXS {$listCategory[$keyTitleCate]} ngày $date";
                                $data = [
                                    'category_id' => $category_id,
                                    'title' => "{$code} {$listCategory[$keyTitleCate]} ngày $date",
                                    'slug' => $this->toSlug($title),
                                    'displayed_time' => date('Y-m-d', strtotime($date)),
                                    'data_result' => json_encode($oneResult),
                                ];
                                if ($this->_data_result->checkExistByField('slug', $this->toSlug($title), $this->_data_result->table)) {
                                    echo "Record from {$this->toSlug($title)} exists \n";
                                } else {
                                    $lastId = $this->saveResult([$data]);
                                    if (empty($lastId)) die("Insert data into db errror ! \n");
                                    echo "Inserted result id => $lastId from url $slug success ! \n ";
                                }
                                $keyTitleCate++;
                            }
                        }
                    }
                }
            } catch (\Exception $exception) {
                echo "<pre>";
                print_r($exception);
                log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
                exit;
            }
            $i++;
        }
    }

    private function crawlListResultMTMNFixListDay($code, $startDate, $endDate = null)
    {
        print "---------Crawler Fix List Day post----------<br>\n";
        $running = true;
        $i = 7;
        if (empty($endDate)) $endDate = date('d-m-Y');

        while ($running == true) {
            try {
                $date = date('d-m-Y', strtotime("$startDate +$i day"));
                $slug = "$code-$date.html";
                echo "Crawling result $slug \n";
                if (strtotime($date) >= strtotime($endDate)) {
                    echo "false 1";
                    $running = false;
                }
                if ($this->_data_result->checkExistByField('slug', $slug, $this->_data_result->table)) {
                    echo "Record from $slug exists \n";
                } else {
                    $crawler = $this->_client->request('GET', "https://xoso.com.vn/$slug");
                    $response = $this->_client->getResponse();
                    if (empty($response->getContent())) {
                        echo "false 2";
                        $running = false;
                    } else {
                        if ($crawler->filterXPath('//*[@class="box-ketqua"]')->count() > 0) {
                            $listCategory = $crawler->filterXPath('//*[@class="box-ketqua"]//table//tbody//tr[position()=1]/td')->each(function ($category) {
                                return trim($category->text());
                            });
                            unset($listCategory[0]);
                            $data_res = [];
                            if (!empty($listCategory)) foreach ($listCategory as $key => $category_id) {
                                $category_id = $this->_data_result->getCategoryBySlug($category_id);
                                $dataResult = $crawler->filterXPath('//*[@class="box-ketqua"]//table//tbody//tr[position()>1]/td[position()=' . ($key + 1) . ']')->each(function ($result) {
                                    $arrNumber = $result->filterXPath('//span')->each(function ($number) {
                                        return trim($number->text());
                                    });
                                    return $arrNumber;
                                });
                                $data_res[] = $dataResult;
                            }


                            $data_res = array_reverse($data_res);
                            $new_data_res = [];
                            $listCategory = array_reverse($listCategory);

                            if (!empty($listCategory)) foreach ($listCategory as $key => $category_id) {
                                $category_id = $this->_data_result->getCategoryBySlug($category_id);
                                $new_data_res[$category_id] = $data_res[$key];
                            }
                            //dd($listCategory);

                            $keyTitleCate = 0;
                            if (!empty($data_res)) foreach ($new_data_res as $category_id => $oneResult) {

                                $category_id = $this->_data_result->getCategoryBySlug($listCategory[$keyTitleCate]);

                                $title = "KQXS {$listCategory[$keyTitleCate]} ngày $date";
                                $data = [
                                    'category_id' => $category_id,
                                    'title' => "{$code} {$listCategory[$keyTitleCate]} ngày $date",
                                    'slug' => $this->toSlug($title),
                                    'displayed_time' => date('Y-m-d', strtotime($date)),
                                    'data_result' => json_encode($oneResult),
                                ];
                                //dump($title);

                                //echo ">> {$i}";

                                $checkDelete = $this->deleteResult($data);
                                if (empty($checkDelete)) die("Insert data into db errror ! \n");
                                echo "Delete result id => $checkDelete from url $slug success ! \n ";

                                $lastId = $this->saveResult([$data]);
                                if (empty($lastId)) die("Insert data into db errror ! \n");
                                echo "Inserted result id => $lastId from url $slug success ! \n ";
                                $keyTitleCate++;
                            }
                        }
                    }
                }
            } catch (\Exception $exception) {
                echo "<pre>";
                print_r($exception);
                log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
                exit;
            }
            $i = $i + 7;
        }
    }

    public function deleteResult($listData)
    {
        return $this->_data_result->deleteRow($listData);
    }

    private function crawler_result_mb_xosome($startDate)
    {
        print "---------Crawler list post----------<br>\n";
        $running = true;
        $i = 1;
        $endDate = date('j-n-Y');
        while ($running == true) {
            try {
                $date = date('j-n-Y', strtotime("$startDate +$i day"));
                $slug = "xsmb-{$date}-ket-qua-xo-so-mien-bac-ngay-{$date}.html";
                echo "==>> Crawling result $slug <br/>\n";
                if ($date == $endDate) $running = false;
                $crawler = $this->_client->request('GET', "https://xoso.me/$slug");
                $response = $this->_client->getResponse();
                if (empty($response->getContent())) $running = false;
                else {
                    if ($this->_data_result->checkExistByField('slug', $slug, $this->_data_result->table)) {
                        echo "Record from $slug exists <br/>\n";
                    } else {
                        $dataResult = $crawler->filterXPath('//*[@data-id="kq"]//table//tbody//tr')->each(function ($result, $i) {
                            if ($i == 0) {
                                $arrNumber = $result->filterXPath('//td//b')->each(function ($number) {
                                    if (strlen(trim($number->text())) > 0) {
                                        return trim($number->text());
                                    } else {
                                        return "...";
                                    }
                                });
                                if (empty($arrNumber)) return ['...', '...', '...'];
                            } else {
                                $arrNumber = $result->filterXPath('//td[@class="number"]/b')->each(function ($number) {
                                    if (strlen(trim($number->text())) > 0) {
                                        return trim($number->text());
                                    } else {
                                        return "...";
                                    }
                                });
                            }
                            return $arrNumber;
                        });

                        $dataResultNew = array(
                            $dataResult[0],
                            $dataResult[1],
                            $dataResult[2],
                            $dataResult[3],
                            array_merge($dataResult[4], $dataResult[5]),
                            $dataResult[6],
                            array_merge($dataResult[7], $dataResult[8]),
                            $dataResult[9],
                            $dataResult[10]
                        );

                        $data = [
                            'category_id' => 1,
                            'title' => "XSMB ngày $date",
                            'slug' => $slug,
                            'displayed_time' => date('Y-m-d', strtotime($date)),
                            'data_result' => json_encode($dataResultNew),
                        ];

                        $lastId = $this->saveResult([$data]);
                        if (empty($lastId)) die("Insert data into db errror ! <br/>\n");
                        echo "Inserted result id => $lastId from url $slug success ! <br/>\n ";
                    }
                }
            } catch (\Exception $exception) {
                echo "<pre>";
                print_r($exception);
                log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
                exit;
            }
            $i++;
        }
    }

    private function crawler_result_mt_xosome($code, $startDate)
    {
        print "---------Crawler list post----------<br>\n";
        $running = true;
        $i = 1;
        $endDate = date('j-n-Y');
        //$endDate = '20-1-2009';
        while ($running == true) {
            try {
                $date = date('j-n-Y', strtotime("$startDate +$i day"));
                $slug = "xsmt-{$date}-ket-qua-xo-so-mien-trung-ngay-{$date}.html";
                echo "==>> Crawling result $slug <br/>\n";
                if (strtotime($date) >= strtotime($endDate)) $running = false;
                if ($this->_data_result->checkExistByField('slug', $slug, $this->_data_result->table)) {
                    echo "Record from $slug exists <br/>\n";
                } else {
                    $crawler = $this->_client->request('GET', "https://xoso.me/$slug");
                    $response = $this->_client->getResponse();
                    if (empty($response->getContent())) $running = false;
                    else {
                        if ($crawler->filterXPath('//*[@data-id="kq"]')->count() > 0) {
                            $listCategory = $crawler->filterXPath('//*[@data-id="kq"]//table//tbody//tr[position()=1]/th[position()>1]//span')->each(function ($category) {
                                return trim($category->text());
                            });
                            $data_res = [];
                            if (!empty($listCategory)) foreach ($listCategory as $key => $category_id) {
                                $category_id = $this->_data_result->getCategoryBySlug($category_id);
                                $dataResult = $crawler->filterXPath('//*[@data-id="kq"]//table//tbody//tr[position()>1]/td[position()=' . ($key + 2) . ']')->each(function ($result) {
                                    $arrNumber = $result->filterXPath('//div')->each(function ($number) {
                                        return trim($number->text());
                                    });
                                    return $arrNumber;
                                });
                                $data_res[$category_id] = $dataResult;
                            }

                            $keyTitleCate = 0;
                            if (!empty($data_res)) foreach ($data_res as $category_id => $oneResult) {
                                $title = "KQXS {$listCategory[$keyTitleCate]} ngày $date";
                                $data = [
                                    'category_id' => $category_id,
                                    'title' => "{$code} {$listCategory[$keyTitleCate]} ngày $date",
                                    'slug' => $this->toSlug($title),
                                    'displayed_time' => date('Y-m-d', strtotime($date)),
                                    'data_result' => json_encode($oneResult),
                                ];
                                if ($this->_data_result->checkExistByField('slug', $this->toSlug($title), $this->_data_result->table)) {
                                    echo "Record from {$this->toSlug($title)} exists \n";
                                } else {
                                    $lastId = $this->saveResult([$data]);
                                    if (empty($lastId)) die("Insert data into db errror ! \n");
                                    echo "Inserted result id => $lastId from url $slug success ! \n ";
                                }
                                $keyTitleCate++;
                            }
                        }
                    }
                }
            } catch (\Exception $exception) {
                echo "<pre>";
                print_r($exception);
                log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
                exit;
            }
            $i++;
        }
    }

    private function crawler_result_mn_xosome($code, $startDate)
    {
        print "---------Crawler list post----------<br>\n";
        $running = true;
        $i = 1;
        $endDate = date('j-n-Y');
        //$endDate = '20-1-2009';
        while ($running == true) {
            try {
                $date = date('j-n-Y', strtotime("$startDate +$i day"));
                $slug = "xsmn-{$date}-ket-qua-xo-so-mien-nam-ngay-{$date}.html";
                echo "==>> Crawling result $slug <br/>\n";
                if (strtotime($date) >= strtotime($endDate)) $running = false;
                if ($this->_data_result->checkExistByField('slug', $slug, $this->_data_result->table)) {
                    echo "Record from $slug exists <br/>\n";
                } else {
                    $crawler = $this->_client->request('GET', "https://xoso.me/$slug");
                    $response = $this->_client->getResponse();
                    if (empty($response->getContent())) $running = false;
                    else {
                        if ($crawler->filterXPath('//*[@data-id="kq"]')->count() > 0) {
                            $listCategory = $crawler->filterXPath('//*[@data-id="kq"]//table//tbody//tr[position()=1]/th[position()>1]//span')->each(function ($category) {
                                return trim($category->text());
                            });
                            $data_res = [];
                            if (!empty($listCategory)) foreach ($listCategory as $key => $category_id) {
                                $category_id = $this->_data_result->getCategoryBySlug($category_id);
                                $dataResult = $crawler->filterXPath('//*[@data-id="kq"]//table//tbody//tr[position()>1]/td[position()=' . ($key + 2) . ']')->each(function ($result) {
                                    $arrNumber = $result->filterXPath('//div')->each(function ($number) {
                                        return trim($number->text());
                                    });
                                    return $arrNumber;
                                });
                                $data_res[$category_id] = $dataResult;
                            }

                            $keyTitleCate = 0;
                            if (!empty($data_res)) foreach ($data_res as $category_id => $oneResult) {
                                $title = "KQXS {$listCategory[$keyTitleCate]} ngày $date";
                                $data = [
                                    'category_id' => $category_id,
                                    'title' => "{$code} {$listCategory[$keyTitleCate]} ngày $date",
                                    'slug' => $this->toSlug($title),
                                    'displayed_time' => date('Y-m-d', strtotime($date)),
                                    'data_result' => json_encode($oneResult),
                                ];
                                if ($this->_data_result->checkExistByField('slug', $this->toSlug($title), $this->_data_result->table)) {
                                    echo "Record from {$this->toSlug($title)} exists \n";
                                } else {
                                    $lastId = $this->saveResult([$data]);
                                    if (empty($lastId)) die("Insert data into db errror ! \n");
                                    echo "Inserted result id => $lastId from url $slug success ! \n ";
                                }
                                $keyTitleCate++;
                            }
                        }
                    }
                }
            } catch (\Exception $exception) {
                echo "<pre>";
                print_r($exception);
                log_message('error', 'Caught exception function "' . $this->_method . '": ' . $exception->getMessage());
                exit;
            }
            $i++;
        }
    }

    private function mime_content_type($link)
    {
        $ch = curl_init($link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        return curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    }

    private function is_webfile($webfile)
    {
        $fp = @fopen($webfile, "r");
        if ($fp !== false)
            fclose($fp);

        return ($fp);
    }

    private function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}
