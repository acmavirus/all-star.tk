<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['default_controller'] = 'home';
$route['default'] = 'home';
$route['translate_uri_dashes'] = FALSE;
$route['404.html'] = 'page/notfound';
$route['admin'] = 'admin/dashboard';
/*post*/
$route['(:any)-p(:num)'] = 'post/detail/$1/$2';

/*Vote*/
$route['reviews/ajax_vote']  = 'reviews/ajax_vote';

$route['tags/(:any)'] = 'category/tags/$1';
$route['tags/(:any)/(:num)'] = 'category/tags/$1/$2';

/*Search*/
$route['tim-kiem']  = 'search/index';
/* SOI CAU */
$route['soi-cau-(:any).html'] = 'Page/soicau/$1';
/*Category*/
$route['(:any)-xo-so-(:any).html'] = 'category/detail/$2';
$route['([a-z]{4,5})-(\d\d-\d\d-\d\d\d\d)'] = 'category/date/$1/$2';
$route['([a-zA-Z0-9]{5})-(\d\d-\d\d-\d\d\d\d)'] = 'category/date/$1/$2';
/*Quay thử*/
$route['quay-thu-(:any).html'] = 'Page/spin/$1';

/*Sitemap*/
$route['sitemap-new2022.xml']  = 'seo/index';
$route['sitemap-news_hot2022.xml']  = 'seo/sitemap_google_news';
$route['sitemap-category_new2022.xml'] = 'seo/sitemap_category';
$route['sitemap-result_new2022.xml'] = 'seo/sitemap_result';
$route['sitemap-post_new2022__(:num).xml'] = 'seo/sitemap_post/$1';
/*Sitemap*/

// all
$route['(:any).html'] = 'category/detail/$1';
$route['(:any).html/(:num)'] = 'category/detail/$1/$2';
