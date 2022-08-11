<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Facebook\InstantArticles\AMP\AMPArticle;

class Seo extends Public_Controller
{
    protected $urls;
    protected $changefreqs;
    protected $_limit_url = 500;
    protected $_data_post;
    protected $_data_category;
    protected $_data_tags;
    protected $xml;
    public function __construct()
    {
        parent::__construct();
        $this->urls = array();
        $this->changefreqs = array(
            'always',
            'hourly',
            'daily',
            'weekly',
            'monthly',
            'yearly',
            'never'
        );
        $this->load->model(['post_model', 'category_model']);
        $this->_data_post = new Post_model();
        $this->_data_category = new Category_model();
    }

    public function index()
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"/>');

        $child = $xml->addChild('url');
        $child->addChild('loc', site_url('sitemap-category_new2022.xml'));
        $child->addChild('lastmod', date('c'));

        $child = $xml->addChild('url');
        $child->addChild('loc', site_url('sitemap-result_new2022.xml'));
        $child->addChild('lastmod', date('c'));

        $child = $xml->addChild('url');
        $child->addChild('loc', site_url('sitemap-news_hot2022.xml'));
        $child->addChild('lastmod', date('c'));

        $param = [
            'limit' => 1000,
            'order' => ['displayed_time' => 'DESC']
        ];
        $countPost = $this->_data_post->getTotal($param);
        // $data = $this->_data_post->getDataFE($param);
        $totalPost = $countPost >= $this->_limit_url ? ceil($countPost / $this->_limit_url) : 1;
        for ($i = 1; $i <= $totalPost; $i++) {
            $child = $xml->addChild('url');
            $child->addChild('loc', site_url('sitemap-post_new2022__' . $i . '.xml'));
            $child->addChild('lastmod', date('c'));
        }

        $this->output->set_content_type('application/xml')->set_output($xml->asXml());
    }

    public function sitemap_category()
    {
        $param = [
            'limit' => 10000,
            'order' => ['displayed_time' => 'DESC']
        ];
        //        $countPost = $this->_data_post->getTotal($param);
        $data = $this->_data_post->getDataFE($param);

        $dataCate = $this->_data_category->getData([
            'is_status' => 1,
            'limit' => 10000,
        ]);
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"/>');
        $child = $xml->addChild('url');
        $child->addChild('loc', base_url());
        $child->addChild('priority', '1.0');
        $child->addChild('lastmod', date('c'));
        $child->addChild('changefreq', 'hourly');
        if (!empty($dataCate)) foreach ($dataCate as $item) {
            $child = $xml->addChild('url');
            switch ($item->type):
                case "tag":
                    $child->addChild('loc', getUrlTag($item));
                    break;
                default:
                    $child->addChild('loc', getUrlCategory($item));
            endswitch;
            //                $this->add($url,NULL, date( 'c'), 'hourly', 0.9);
            $child->addChild('lastmod', date('c', strtotime($item->updated_time)));
            $child->addChild('changefreq', 'daily');
            $child->addChild('priority', '0.8');
        }
        if (!empty($data)) foreach ($data as $item) {
            $child = $xml->addChild('url');
            $child->addChild('loc', getUrlPost($item));
            /*if (isset($url->image)) {
                  $image = $child->addChild('image:image:image');
                  $image->addChild('image:image:loc',$url->image);
              }*/
            $child->addChild('lastmod', date('c', strtotime($item->updated_time)));
            if (strtotime($item->displayed_time) + 30 * 24 * 60 * 60 < (time())) {
                $child->addChild('changefreq', 'monthly');
                $child->addChild('priority', '0.5');
            } else {
                $child->addChild('changefreq', 'daily');
                $child->addChild('priority', '0.8');
            }
        }
        $this->output->set_content_type('application/xml')->set_output($xml->asXml());
    }
    public function sitemap_post($page = 1)
    {
        $paramPostVideo = [
            'is_status' => 1,
            'is_robot' => 1,
            'is_displayed_time' => 1,
            'page' => $page,
            'limit' => $this->_limit_url,
            'order' => ['displayed_time' => 'DESC']
        ];
        $data = $this->_data_post->getDataFE($paramPostVideo, true);
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"/>');
        if (!empty($data)) foreach ($data as $item) {
            $child = $xml->addChild('url');
            $child->addChild('loc', getUrlPost($item));
            /*if (isset($url->image)) {
                $image = $child->addChild('image:image:image');
                $image->addChild('image:image:loc',$url->image);
            }*/
            $child->addChild('lastmod', date('c', strtotime($item->updated_time)));
            $child->addChild('changefreq', 'always');
            $child->addChild('priority', '0.9');
        }
        $this->output->set_content_type('application/xml')->set_output($xml->asXml());
    }
    public function sitemap_result($page = 1)
    {
        $dataCate = $this->_data_category->getData([
            'type' => 'lottery',
            'is_status' => 1,
            'limit' => 100
        ]);
        foreach ($dataCate as $item) {
            $cate_id[] = $item->id;
        }

        $date_arr = array_reverse($this->display_date('01/02/2022', date('d/m/Y')));

        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"/>');
        if (!empty($dataCate)) {
            foreach ($date_arr as $item_date) {
                foreach ($dataCate as $item) {
                    if ($item->id <= 3) {
                        $day_number = date('N', strtotime($item_date)) + 1;
                        if (in_array($day_number, json_decode($item->weekday))) {
                            $uri = strtolower($item->code) . '-' . $item_date;
                            if (strtotime($item_date) == strtotime(date("Y-m-d")) && $item->code == 'XSMN' && date("H") < 16) continue;
                            if (strtotime($item_date) == strtotime(date("Y-m-d")) && $item->code == 'XSMT' && date("H") < 17) continue;
                            if (strtotime($item_date) == strtotime(date("Y-m-d")) && $item->code == 'XSMB' && date("H") < 18) continue;
                            $child = $xml->addChild('url');
                            $child->addChild('loc', BASE_URL($uri));
                            $child->addChild('lastmod', date('c', strtotime($item_date)));
                            $child->addChild('changefreq', 'always');
                            $child->addChild('priority', '0.9');
                        }
                    }
                }
            }
        }

        $this->output->set_content_type('application/xml')->set_output($xml->asXml());
    }

    /*sitemap google news*/
    public function sitemap_google_news()
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"/>');
        $data = $this->_data_post->getData([
            'displayed_time_elder' => date('Y-m-d H:i:s', strtotime('-2 days')),
            'is_status' => 1,
            'is_robot' => 1,
            'is_displayed_time' => 1,
            'limit' => 300,
            'order' => ['displayed_time' => 'DESC']
        ]);
        if (!empty($data)) foreach ($data as $item) {
            if (empty($item)) continue;
            $url = $xml->addChild('url');
            $url->addChild('loc', getUrlPost($item));
            $news = $url->addChild('news:news:news');
            $newsPublication = $news->addChild('news:news:publication');
            $newsPublication->addChild('news:news:name', $this->_settings->title);
            $newsPublication->addChild('news:news:language', 'vi');
            $news->addChild('news:news:publication_date', date('c', strtotime($item->displayed_time)));
            $news->addChild('news:news:title', htmlspecialchars($item->title));
        }
        $this->output->set_content_type('application/xml')->set_output($xml->asXml());
    }
    public function rss_facebook_instant_article($page = 1)
    {
        $paramPostVideo = [
            'select' => 'st_post.id,title,slug,thumbnail,description,content,st_post.created_time,st_post.updated_time,IF(st_post.displayed_time IS NULL,st_post.created_time,st_post.displayed_time) AS displayed_time',
            'show_user' => 1,
            'category_id' => 86,
            'is_robot' => 1,
            'displayed_time_elder' => date('Y-m-d H:i:s', strtotime('-24 hour')), //Check bài lớn hơn
            'limit' => 10
        ];
        $data = $this->_data_post->getData($paramPostVideo);
        header('Content-Type: text/xml; charset=utf-8', true);
        $rss = new SimpleXMLElement('<rss></rss>');
        $rss->addAttribute('version', '2.0');
        $rss->addAttribute('xmlns:xmlns:content', 'http://purl.org/rss/1.0/modules/content/');
        $channel = $rss->addChild('channel');
        $channel->addChild('title', "Bóng đá 365");
        $channel->addChild('link', base_url());
        $channel->addChild('description', !empty($this->settings['meta_title']) ? $this->settings['meta_title'] : '');
        $channel->addChild('lastBuildDate', date('c'));
        $channel->addChild('language', 'vi-vn');
        if (!empty($data)) foreach ($data as $item) {
            $itemChild = $channel->addChild('item');
            $itemChild->addChild('title', $item->title);
            $itemChild->addChild('link', getUrlPost($item));
            $itemChild->addChild('guid', $item->id);
            $itemChild->addChild('pubDate', date('c', strtotime($item->displayed_time)));
            $itemChild->addChild('author', $item->fullname);
            $itemChild->addChild('description', $item->description);
            $itemChild->addChild('lastBuildDate', date('c', strtotime($item->updated_time)));
            $itemChild->addChild('language', 'vi-vn');
            $itemChild->addChild('content:content:encoded', '
            <![CDATA[<!doctype html><html lang="en" prefix="op: http://media.facebook.com/op#">
              <head>
                <meta charset="utf-8">
                <link rel="canonical" href="' . getUrlPost($item) . '">
                <meta property="op:markup_version" content="v1.0">
              </head>
              <body>
                <article>
                  <header>
                   <h1> ' . $item->title . ' </h1>
                   <h2> ' . $item->description . ' </h2>
                  </header>
                  ' . $this->parse_content_fb_ia($item) . '
                  <footer>
                    <ul class="op-related-articles">
                        <li><a href="https://bongda365.com/">Tin mới nhất</a></li>
                        <li><a href="https://bongda365.com/lich-thi-dau-bong-da-hom-nay-moi-nhat">Lịch thi đấu hôm nay</a></li>
                        <li><a href="https://bongda365.com/ket-qua-bong-da-hom-nay-moi-nhat"></a>Kết quả bóng đá</li>
                     </ul>
            <aside>Bóng đá - Báo Bongda365.com cập nhật nhanh tin bóng đá 24h: lịch thi đấu, bảng xếp hạng, kết quả, video các trận đấu ở các giải vô địch hàng đầu châu lục</aside>

        <small>© Bongda365</small>
                  </footer>
                </article>
              </body>
            </html>]]>');
        }
        $this->output->set_content_type('application/xml')->set_output($rss->asXml());
    }

    private function parse_content_fb_ia($data)
    {
        $this->load->library('Facebook_instant_articles');
        $FIA = new Facebook_instant_articles();
        $canonical_url = getUrlPost($data);
        $author = $data->fullname;
        $thumbnail = getImageThumb($data->thumbnail, 400, 230, true);
        $style = '';

        // your body html generally it comes from a html text editor where you put images paragraphs iframes etc if you need to modify it before
        // do it as your convenience
        $resultStr = $data->content;
        $resultStr = str_replace('"//', '"https://', $resultStr);

        //gettig publishing date
        $publishDate = $data->displayed_time;
        $publishedDate = date("j-M-Y G:i:s", strtotime($publishDate));
        $modifiedDate = date("j-M-Y G:i:s");


        //get url
        $FIA->setCanonicalUrl($canonical_url);

        //set header and the elements check the library
        $FIA->setHeader(
            $data->title,
            $data->description,
            $publishedDate,
            $modifiedDate,
            $author,
            $thumbnail
        );

        //seting default facebook ia style
        $FIA->setStyle($style);

        // build ia article <segment> section
        $FIA->build_body($resultStr);

        //create an add
        $add_url = "";
        $FIA->create_add($add_url);

        //add google analitics
        $FIA->apend_code("<script async src=\"https://www.googletagmanager.com/gtag/js?id=UA-120960690-3\"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-120960690-3');
    </script>");

        //render
        $instantArticleHtml = $FIA->renderInstantArticle();
        return html_entity_decode($instantArticleHtml);
    }

    public function add($loc, $image = NULL, $lastmod = NULL, $changefreq = NULL, $priority = NULL)
    {
        // Do not continue if the changefreq value is not a valid value
        if ($changefreq !== NULL && !in_array($changefreq, $this->changefreqs)) {
            show_error('Unknown value for changefreq: ' . $changefreq);
            return false;
        }
        // Do not continue if the priority value is not a valid number between 0 and 1
        if ($priority !== NULL && ($priority < 0 || $priority > 1)) {
            show_error('Invalid value for priority: ' . $priority);
            return false;
        }
        $item = new stdClass();
        $item->loc = $loc;
        $item->lastmod = $lastmod;
        $item->image = $image;
        $item->changefreq = $changefreq;
        $item->priority = $priority;
        $this->urls[] = $item;
        return true;
    }

    /**
     * Generate the sitemap file and replace any output with the valid XML of the sitemap
     *
     * @param string $type Type of sitemap to be generated. Use 'urlset' for a normal sitemap. Use 'sitemapindex' for a sitemap index file.
     * @access public
     * @return void
     */
    private function output($type = 'urlset')
    {
        $root = $type . " xmlns='http://www.sitemaps.org/schemas/sitemap/0.9' xmlns:xhtml=\"http://www.w3.org/1999/xhtml\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\"";
        if (isset($this->urls[0]->image)) $root .= " xmlns:image='http://www.google.com/schemas/sitemap-image/1.1'";
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><' . $root . '/>');
        if ($type == 'urlset') {
            foreach ($this->urls as $url) {
                $child = $xml->addChild('url');
                $child->addChild('loc', strtolower($url->loc));
                if (isset($url->image)) {
                    $image = $child->addChild('image:image:image');
                    $image->addChild('image:image:loc', $url->image);
                }
                if (isset($url->lastmod)) $child->addChild('lastmod', $url->lastmod);
                if (isset($url->changefreq)) $child->addChild('changefreq', $url->changefreq);
                if (isset($url->priority)) $child->addChild('priority', number_format($url->priority, 1));
            }
        } elseif ($type == 'sitemapindex') {
            foreach ($this->urls as $url) {
                $child = $xml->addChild('sitemap');
                $child->addChild('loc', strtolower($url->loc));
                if (isset($url->lastmod)) $child->addChild('lastmod', $url->lastmod);
            }
        }
        $this->output->set_content_type('application/xml')->set_output($xml->asXml());
    }

    /**
     * Clear all items in the sitemap to be generated
     *
     * @access public
     * @return boolean
     */
    public function clear()
    {
        $this->urls = array();
        return true;
    }

    public function rss()
    {
        $categoryModel = new Category_model();
        $oneItem = new stdClass();
        $oneItem->title = "RSS bongda365.com";
        $oneItem->meta_title = "RSS bongda365.com - Rss tin tức nhận định soi kèo bóng đá cập nhật mới nhất";
        $oneItem->meta_description = "RSS Bongda365.com Chuẩn tựa XML được rút gọn dành cho việc phân tán và khai thác nội dung thông tin Website Bongda365, dễ dàng tạo và phổ biến các nguồn dữ liệu";
        $oneItem->meta_keyword = 'RSS Bongda365.com';
        $oneItem->slug = "rss";
        $data['oneItem'] = $oneItem;
        $data['data_category'] = $categoryModel->getData([
            'limit' => 300
        ]);

        $data['SEO'] = [
            'meta_title' => $oneItem->meta_title,
            'meta_description' => $oneItem->meta_description,
            'meta_keyword' => $oneItem->meta_keyword,
            'url' => base_url('rss'),
            'is_robot' => 1,
            'image' => getImageThumb(),
        ];
        $data['main_content'] = $this->load->view(TEMPLATE_PATH . 'seo/rss', $data, TRUE);
        $this->setCacheFile(60);
        $this->load->view(TEMPLATE_MAIN, $data);
    }


    public function rssGoogleNews()
    {
        header('Content-Type: text/xml; charset=utf-8', true);
        $rss = new SimpleXMLElement('<rss></rss>');
        $rss->addAttribute('version', '2.0');
        $rss->addAttribute('xmlns:xmlns:content', 'http://purl.org/rss/1.0/modules/content/');
        $rss->addAttribute('xmlns:xmlns:media', 'http://search.yahoo.com/mrss/');

        $channel = $rss->addChild('channel'); //add channel node

        $channel->addChild('lastBuildDate', date('r')); //title of the feed
        $channel->addChild('title', "Google News RSS Bongda365.com"); //title of the feed
        $channel->addChild('description', "Google News RSS Bongda365.com"); //feed description
        $channel->addChild('link', BASE_URL); //feed site
        $paramPostVideo = [
            'select' => 'st_post.id,title,slug,description,content,st_post.created_time,st_post.updated_time,IF(st_post.displayed_time IS NULL,st_post.created_time,st_post.displayed_time) AS displayed_time',
            'show_user' => 1,
            'is_robot' => 1,
            'displayed_time_elder' => date('Y-m-d H:i:s', strtotime('-24 hour')), //Check bài lớn hơn
            'limit' => 300
        ];
        $data = $this->post_model->getData($paramPostVideo);
        if (!empty($data)) foreach ($data as $k => $item) {
            $child = $channel->addChild('item'); //add item node
            $guid = $child->addChild('guid', getUrlDetail($item));
            $guid->addAttribute("isPermaLink", "true");
            $child->addChild('title', $item->title); //add title node under item
            if (!empty($item->post_excerpt)) $child->addChild('description', htmlspecialchars($item->description)); //add description
            $child->addChild('content:content:encoded', "<![CDATA[" . htmlspecialchars(html_entity_decode($item->content)) . "]]>"); //add description
            $child->addChild('link', getUrlDetail($item)); //add link node under item
            $child->addChild('pubDate', date('c', strtotime($item->displayed_time))); //add pubDate node
            $child->addChild('author', $item->email . "($item->username)"); //add pubDate node
        }
        $this->output->set_content_type('application/xml')->set_output($rss->asXml());
    }

    protected function display_date($date1, $date2, $format = 'd-m-Y')
    {
        $dates = array();
        $current = strtotime(str_replace("/", "-", $date1));
        $date2 = strtotime(str_replace("/", "-", $date2));
        $stepVal = '+1 day';
        while ($current <= $date2) {
            $dates[] = date($format, $current);
            $current = strtotime($stepVal, $current);
        }
        return $dates;
    }
}
