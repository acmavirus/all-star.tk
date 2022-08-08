<?php

namespace App\Http\Controllers\Crawler;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;

use App\Core\CoreController;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class Crap extends CoreController
{
    protected $_clince;
    public function __construct()
    {
        parent::__construct();
        $this->_client = new Client();
    }

    public function index()
    {
        $tablet = simplexml_load_file("https://origami.guide/category-sitemap.xml");
        foreach ($tablet->url as $url_list) {
            $url = $url_list->loc;
            echo "\n $url \n";
            $crawler = $this->_client->request('GET', $url);
            $response = $this->_client->getResponse();
            if (empty($response->getContent())) {
                echo "cannot";
            } else {
                $data = $crawler->filterXPath('//*[@class="wpupg-grid-with-filters"]//*[@class="hoover"]')->each(function ($item) {
                    $title = $item->filterXPath('//*[@class="wpupg-item-title"]')->text();
                    $thumbnail = $item->filterXPath('//img')->attr("src");
                    $url = $item->closest('a')->attr("href");
                    $db = $this->getContent($url);
                    return [
                        'title' => $title,
                        'description' => $db['description'],
                        'thumbnail' => $thumbnail,
                        'main_crawler' => $url,
                        'content' => $db['content']
                    ];
                });
                foreach ($data as $key => $item) {
                    $listAll = DB::table('st_post')
                        ->where('slug', toSlug($item['title']))
                        ->get();
                    if (empty($listAll[0])) {
                        $id = DB::table('st_post')->insertGetId([
                            'title' => $item['title'],
                            'description' => $item['description'],
                            'content' => $item['content'],
                            'meta_title' => $item['title'],
                            'slug' => toSlug($item['title']),
                            'meta_description' => $item['description'],
                            'meta_keyword' => str_replace(" ", ",", $item['title']),
                            'thumbnail' => $item['thumbnail'],
                            'type' => 'post'
                        ]);
                        if (!empty($id)) {
                            echo "\n Cập nhật thành công ! \n";
                            DB::table('st_post_category')->insertGetId([
                                'post_id' => $id,
                                'category_id' => 2,
                                'is_primary' => 1
                            ]);
                        } else {
                            echo "\n Cập nhật thất bại !    \n";
                        }
                    } else {
                        echo "\n Cập nhật thất bại ! Bài viết đã có rồi ^^   \n";
                    }
                }
            }
        }
    }

    private function getContent($url)
    {
        $crawler = $this->_client->request('GET', $url);
        $response = $this->_client->getResponse();
        if (empty($response->getContent())) {
            echo "cannot";
        } else {
            $content = $crawler->filterXPath('//*[@class="gallery-post"]')->html();
            $description = $crawler->filterXPath('//*[@class="post-text"]')->text();
            return [
                'description' => $description,
                'content' => $content
            ];
        }
    }
}

// $newest = DB::table('st_post')
// ->where('id', '>', 21)
// ->get();
// foreach ($newest as $key => $value) {
// $content = $value->content;
// $newcontent = '';
// $crawler = new Crawler($content);
// $data = $crawler->filterXPath('//li')->each(function ($li) {
//     $step = $li->filterXPath('//p')->html();
//     $img = $li->filterXPath('//*[@itemprop="image"]')->attr('href');
//     return [
//         'img' => $img,
//         'step' => $step
//     ];
// });
// foreach ($data as $key => $liststep) {
//     $newcontent .= '<li><div class="image"><img src="' . $liststep['img'] . '" width="100%"/></div><p>' . $liststep['step'] . '</p></li>';
// };
// DB::table('st_post')
//     ->where('id', $value->id)
//     ->update(['content' => $newcontent]);
// }