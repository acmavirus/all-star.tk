<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Cookie;
use Symfony\Component\DomCrawler\Crawler as domCrawler;

class Xoso extends STEVEN_Controller
{
    protected $_client;
    protected $_category;
    protected $_post;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['category_model', 'post_model']);
        $this->_post = new Post_model();
        $this->_category = new Category_model();

        $this->_client = new Client();
    }

    public function getcategory()
    {
        // $url = "https://origami.guide/";
        // $crawler = $this->_client->request('GET', $url);
        // $response = $this->_client->getResponse();
        // if (empty($response->getContent())) echo "\n error \n";
        // else {
        //     if ($crawler->filter('#wpupg-grid-category-grid-home-page')->count() > 0) {
        //         $data_res = $crawler->filter('#wpupg-grid-category-grid-home-page > a')->each(function ($a) {
        //         $title = $a->text();
        //         $thumbnail = $this->downloadImage($a->filter('img')->attr('src'), $this->toSlug($title), 'origami');
        //         $url_crawler = $a->attr('href');
        //         $arrNumber = [
        //             'parent_id' => 1,
        //             'title' => $title,
        //             'slug' => $this->toSlug($title),
        //             'thumbnail' => $thumbnail,
        //             'type' => 'category',
        //             'url_crawler' => $url_crawler
        //         ];
        //         $this->_category->insert($arrNumber);
        //         });
        //     } else echo "\n error 404 \n";
        // }
    }

    public function getpost()
    {
        // $allcategory = $this->_category->getData(['type' => 'category', 'limit' => 60]);
        // foreach ($allcategory as $key => $onecategory) {
        //     $url = $onecategory->url_crawler;
        //     $crawler = $this->_client->request('GET', $url);
        //     $response = $this->_client->getResponse();
        //     if (empty($response->getContent())) echo "\n error \n";
        //     else {
        //         echo "\n start get $url \n";
        //         if ($crawler->filter('#wpupg-grid-category-grid')->count() > 0) {
        //             $data_res = $crawler->filter('#wpupg-grid-category-grid > a')->each(function ($a) {
        //                 $title = $a->text();
        //                 $thumbnail = $this->downloadImage($a->filter('img')->attr('src'), $this->toSlug($title), 'origami');
        //                 $url_crawler = $a->attr('href');
        //                 $arrNumber = [
        //                     'title' => $title,
        //                     'slug' => $this->toSlug($title),
        //                     'thumbnail' => $thumbnail,
        //                     'type' => 'origami',
        //                     'url_crawler' => $url_crawler
        //                 ];
        //                 return $arrNumber;
        //             });
        //             foreach ($data_res as $key => $value) {
        //                 $id = $this->_post->insert($value);
        //                 if (!empty($id)) {
        //                     $arrCategory = [
        //                         'post_id' => $id,
        //                         'category_id' => $onecategory->id,
        //                         'is_primary' => 1
        //                     ];
        //                     $this->_post->insert($arrCategory, 'st_post_category');
        //                 }
        //             }
        //         } else echo "\n error 404 \n";
        //     }
        // }
    }

    public function downloadImage($link, $name = '', $folder = '')
    {
        $link = strtok($link, '?');
        $ext = pathinfo($link, PATHINFO_EXTENSION);
        if (empty($name)) $name = $this->toSlug(pathinfo($link, PATHINFO_FILENAME));
        $fileName = $folder . "/" . $name . '.' . (!empty($ext) ? $ext : 'png');
        if (file_exists(MEDIA_PATH . $fileName) == false) {
            if (!is_dir(dirname(MEDIA_PATH . $fileName))) {
                mkdir(dirname(MEDIA_PATH . $fileName), 0755, TRUE);
            }
            file_put_contents(MEDIA_PATH . $fileName, file_get_contents($link));
            return $fileName;
        } else return $fileName;
    }
}
