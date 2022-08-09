<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('site_admin_url')) {
    function site_admin_url($uri = '')
    {
        return BASE_ADMIN_URL . $uri;
    }
}
if (!function_exists('getUrlCateNews')) {
    function getUrlCateNews($optional){
        if(is_object($optional)) $optional = (array) $optional;
        $id = $optional['id'];
        $slug = $optional['slug'];
        $linkReturn = BASE_URL;
        $linkReturn .= "$slug-c$id";
        if(isset($optional['page'])) $linkReturn .= '/page/';
        return $linkReturn;
    }
}

if (!function_exists('getUrlNews')) {
    function getUrlNews($optional){
        if(is_object($optional)) $optional = (array) $optional;
        $id = $optional['id'];
        $slug = $optional['slug'];
        $linkReturn = BASE_URL;
        $linkReturn .= "$slug-d$id";
        if(isset($optional['page'])) $linkReturn .= '/page/';
        return $linkReturn;
    }
}

if (!function_exists('getUrlMatch')) {
    function getUrlMatch($optional){
        $_this =& get_instance();
        if(is_object($optional)) $optional = (array) $optional;
        $id = $optional['id'];
        $slug = $optional['name_home'] . " vs " . $optional['name_away'];
        $slug = $_this->toSlug($slug);
        $linkReturn = "$slug-m$id?server=0";
        return $linkReturn;
    }
}


if (!function_exists('getUrlBrand')) {
    function getUrlBrand($optional){
        if(is_object($optional)) $optional = (array) $optional;
        $id = $optional['id'];
        $slug = $optional['slug'];
        $linkReturn = BASE_URL;
        $linkReturn .= "$slug-b$id";
        if(isset($optional['page'])) $linkReturn .= '/page/';
        return $linkReturn;
    }
}
if (!function_exists('getUrlPage')) {
    function getUrlPage($optional = []){
        if(is_object($optional)) $optional = (array) $optional;
        $linkReturn = BASE_URL;
        if(!empty($optional['slug'])) {
            $slug = $optional['slug'];
            $linkReturn .= "$slug.html";
        }
        return $linkReturn;
    }
}

if (!function_exists('getUrlCateProduct')) {
    function getUrlCateProduct($optional){
        if(is_object($optional)) $optional = (array) $optional;
        $id = $optional['id'];
        $slug = $optional['slug'];
        $linkReturn = BASE_URL;
        $linkReturn .= "$slug-cp$id";
        if(isset($optional['page'])) $linkReturn .= '/page/';
        return $linkReturn;
    }
}
if (!function_exists('getUrlProduct')) {
    function getUrlProduct($optional){
        if(is_object($optional)) $optional = (array) $optional;
        $id = $optional['id'];
        $slug = $optional['slug'];
        $linkReturn = BASE_URL;
        $linkReturn .= "$slug";
        return $linkReturn;
    }
}

if (!function_exists('getUrlAbout')) {
    function getUrlAbout($optional = []){
        if(is_object($optional)) $optional = (array) $optional;
        $_this =& get_instance();
        $_this->load->model('page_model','page');
        $linkReturn = BASE_URL.'about';
        if(!empty($optional['slug'])) {
            $slug = $optional['slug'];
            $linkReturn .= "/$slug";
        }
        return $linkReturn;
    }
}

if (!function_exists('getUrlTag')) {
    function getUrlTag($keyword){
        $_this =& get_instance();
        $_this->load->library('session');
        $_this->load->helper('url');
        $slug = $_this->toSlug($keyword);
        $linkReturn = BASE_URL."tags/$slug";
        return $linkReturn;
    }
}

if (!function_exists('getUrlSearch')) {
    function getUrlSearch($keyword){
        $_this =& get_instance();
        //$slug = $_this->toSlug($keyword);
        $linkReturn = BASE_URL."search";
        return $linkReturn;
    }
}


if (!function_exists('getUrlDownload')) {
    function getUrlDownload($link){
        $linkReturn = MEDIA_URL.$link;
        return $linkReturn;
    }
}


if (!function_exists('cutString')){
    function cutString($chuoi,$max){
        $length_chuoi = strlen($chuoi);
        if($length_chuoi <= $max){
            return $chuoi;
        }else{
            return mb_substr($chuoi,0,$max,'UTF-8').'...';
        }
    }
}

if (!function_exists('getUrlLogin')){
    function getUrlLogin(){
      $_this =& get_instance();
      return $_this->getUrlLogin();
    }
}
if (!function_exists('getUrlProfile')){
  function getUrlProfile($slug=''){
    $linkReturn = BASE_URL.'profile';
    if(!empty($slug)) {
      $linkReturn .= "/$slug";
    }
    return $linkReturn;
  }
}


if (!function_exists('getUrlPlayMatch')) {
    function getUrlPlayMatch($optional){
        if(is_object($optional)) $optional = (array) $optional;
        $id = $optional['id'];
        $slug = $optional['slug'];
        $linkReturn = DOMAIN_PLAY;
        $linkReturn .= "$slug-m$id";
        return $linkReturn;
    }
}

if (!function_exists('getUrlPlayer')) {
    function getUrlPlayer($optional, $index = 0, $isVip = false){
        $_this =& get_instance();
        if(is_object($optional)) $optional = (array) $optional;
        $id = $optional['id'];
        $slug = $_this->toSlug($optional['title']);
        $linkReturn = '/';
        if(empty($index)) $index = 0;
        if($isVip == true) $linkReturn .= "watch/".md5(date('Y-m-d H'))."/$slug-w$id-c$index";
        else $linkReturn .= "watch_other/".md5(date('Y-m-d H'))."/$slug-w$id-c$index";
        return $linkReturn;
    }
}