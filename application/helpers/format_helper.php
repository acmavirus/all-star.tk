<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('encrypt')) {
  function encrypt($string)
  {
    $_this =& get_instance();
    return $_this->encrypt_decrypt('encrypt',$string);
  }
}

if (!function_exists('decrypt')) {
  function decrypt($string)
  {
    $_this =& get_instance();
    return $_this->encrypt_decrypt('decrypt',$string);
  }
}

if (!function_exists('formatMoney')) {
  function formatMoney($price, $default = true)
  {
    $_this =& get_instance();
    return !empty($price) ? "<number>" . number_format($price, 0, '', '.') . "</number>đ" : (($default == true) ? 'Liên hệ' : '');
  }
}

if (!function_exists('showMoney')) {
  function showMoney($item)
  {
    $_this =& get_instance();
    if (!empty($item->price_sale)) {
      $textPrice = '<b>' . formatMoney($item->price_sale) . '</b> <small>' . formatMoney($item->price) . '</small>';
    } elseif (!empty($item->price)) {
      $textPrice = '<b>' . formatMoney($item->price) . '</b>';
    } else {
      $textPrice = "<b>Liên hệ</b>";
    }
    return $textPrice;
  }
}
if (!function_exists('getYoutubeKey')) {
  function getYoutubeKey($url)
  {
    preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
    $youtube_id = $match[1];
    return trim($youtube_id);
  }
}
if (!function_exists('formatCoin')) {
  function formatCoin($price)
  {
    return floatval($price);
  }
}
if (!function_exists('redirect_login')) {
  function redirect_login()
  {
    return site_url('auth/login?url=' . urlencode(current_url()));
  }
}

if (!function_exists('current_full_url')) {
  function current_full_url()
  {
    $CI =& get_instance();
    $url = $CI->config->site_url($CI->uri->uri_string());
    return $_SERVER['QUERY_STRING'] ? $url . '?' . $_SERVER['QUERY_STRING'] : $url;
  }
}
if (!function_exists('status_order')) {
  function status_order($status)
  {
    switch ($status) {

      case 1:
        $title = 'Đang chờ duyệt';
        break;
      case 2:
        $title = 'Đã duyệt';
        break;
      case 3:
        $title = 'Đã duyệt';
        break;
      default:
        $title = 'Hủy';
        break;
    }
    return $title;
  }
}
if (!function_exists('get_first_words')) {

  function get_first_words($string)
  {
    $words = explode(" ", $string);
    $letters = "";
    mb_internal_encoding("UTF-8");
    foreach ($words as $value) {
      $letters .= mb_substr($value, 0, 1);
    }
    return mb_strtoupper($letters,"UTF-8");
  }
}
if (!function_exists('convert_class')) {

  function convert_class($letters)
  {
    switch ($letters){
      case 'NĐ':
        $class='tb-green';
        break;
        case 'QĐ':
        $class='tb-cyan';
        break;
        case 'TB':
        $class='tb-purple';
        break;
        case 'TT':
        $class='tb-red';
        break;
        case 'CT':
        $class='tb-brown';
        break;
        case 'CV':
        $class='tb-blue';
        break;
      default:
        $class='tb-grey';
        break;
    }
    return $class;
  }
}