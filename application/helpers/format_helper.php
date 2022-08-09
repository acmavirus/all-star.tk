<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('encrypt')) {
  function encrypt($string)
  {
    $_this = &get_instance();
    return $_this->encrypt_decrypt('encrypt', $string);
  }
}

if (!function_exists('decrypt')) {
  function decrypt($string)
  {
    $_this = &get_instance();
    return $_this->encrypt_decrypt('decrypt', $string);
  }
}

if (!function_exists('formatMoney')) {
  function formatMoney($price, $default = true)
  {
    $_this = &get_instance();
    return !empty($price) ? "<number>" . number_format($price, 0, '', '.') . "</number>đ" : (($default == true) ? 'Liên hệ' : '');
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

if (!function_exists('redirect_login')) {
  function redirect_login()
  {
    return site_url('auth/login?url=' . urlencode(current_url()));
  }
}

if (!function_exists('current_full_url')) {
  function current_full_url()
  {
    $CI = &get_instance();
    $url = $CI->config->site_url($CI->uri->uri_string());
    return $_SERVER['QUERY_STRING'] ? $url . '?' . $_SERVER['QUERY_STRING'] : $url;
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
    return mb_strtoupper($letters, "UTF-8");
  }
}
if (!function_exists('replaceSeo')) {
  function replaceSeo($string, array $dataReplace, $lenght = 0)
  {
    $arrSearch = $arrReplace = [];
    foreach ($dataReplace as $k => $item) {
      $arrSearch[] = "[$k]";
      $arrReplace[] = $item;
    }

    $string = str_replace($arrSearch, $arrReplace, $string);

    if (!empty($lenght)) {
      $string = substr($string, 0, $lenght);
      $string .= '...';
    }

    return $string;
  }
}

if (!function_exists('toSlug')) {
  function toSlug($doc)
  {
    $str = addslashes(html_entity_decode($doc));
    $str = toNormal($str);
    $str = str_replace(":", "-gio-", $str);
    $str = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $str);
    $str = preg_replace("/( )/", '-', $str);
    $str = str_replace('/', '-', $str);
    $str = str_replace("\/", '', $str);
    $str = str_replace("+", "", $str);
    $str = str_replace(" - ", "-", $str);
    $str = str_replace("---", "-", $str);
    $str = strtolower($str);
    $str = stripslashes($str);
    return trim($str, '-');
  }
  function toNormal($str)
  {
    $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
    $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
    $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
    $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
    $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
    $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
    $str = preg_replace("/(đ)/", 'd', $str);
    $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
    $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
    $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
    $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
    $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
    $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
    $str = preg_replace("/(Đ)/", 'D', $str);
    return $str;
  }
}
// shortcode

if (!function_exists('shortcode')) {
  function shortcode($oneItem)
  {
    $ci = &get_instance();
    $ci->load->model(['category_model']);
    $_data_category = new Category_model();
    if (!empty($oneItem->shortcode)) $dataSC = json_decode($oneItem->shortcode);
    if (!empty($dataSC)) {
      $time   = $dataSC->code->date;
      if (time() < $time) return $dataSC->code->content;
    };
    $content = $oneItem->content;
    preg_match_all("/\[(.*?)\]/", $content, $shortcode);
    $code = $shortcode[1];
    foreach ($shortcode[0] as $k1 => $value) {
      // shortcode tĩnh
      switch ($value) {
        case '[ngay]':
          $content = replaceTEXT($value, date("d/m/Y"), $content);
          break;
        case '[homqua]':
          $content = replaceTEXT($value, date("d/m/Y", strtotime("-1 days")), $content);
          break;
        case '[1so]':
          $content = replaceTEXT($value, rand(0, 9), $content);
          break;
        case '[2so]':
          $content = replaceTEXT($value, sprintf("%'02d", rand(0, 99)), $content);
          break;
        case '[4so]':
          $content = replaceTEXT($value, sprintf("%'02d", rand(1000, 9999)), $content);
          break;
        case '[bachthulo]':
          preg_match_all("/(?<=\,)(.*?)(?=\,)/", $content, $shortcode);
          $arr = [];
          foreach ($shortcode[1] as $key => $v) {
            if (is_numeric(trim($v)) == true) $arr[] = trim($v);
          };
          $rd = array_rand($arr, 1);
          $content = replaceTEXT($value, $arr[$rd], $content);
          break;
        case '[songthulo]':
          preg_match_all("/(?<=\,)(.*?)(?=\,)/", $content, $shortcode);
          $arr = [];
          foreach ($shortcode[1] as $key => $v) {
            if (is_numeric(trim($v)) == true) $arr[] = trim($v);
          };
          $rd = array_rand($arr, 2);
          $content = replaceTEXT($value, $arr[$rd[0]] . " - " . $arr[$rd[1]], $content);
          break;
      };
      // shortcode động
      $regex = '[code-(.*?)]';
      if (preg_match($regex, $value)) {
        $list = explode("-", $code[$k1]);
        $xscode = $list[1];
        $catItem = getCateByCode($xscode);
        $cateLive = getCatByWeekDay(date("N") + 1);
        $html = '';
        foreach ($cateLive as $key => $item) {
          if ($catItem->id == $item->parent_id) {
            $html .= "$item->code, ";
          };
          if ($item->parent_id == 0 && $item->code == $catItem->code) {
            $html .= "$item->code, ";
          }
        };
        $html = substr($html, 0, -2);
        $content = replaceTEXT($value, $html, $content);
      }
      $regex = '[ngay-{(.*?)}]';
      if (preg_match($regex, $value)) {
        preg_match_all("/\{(.*?)\}/", $value, $shortcodengay);
        $text = $shortcodengay[1][0];
        $text = str_replace('$d', date("d"), $text);
        $text = str_replace('$m', date("m"), $text);
        $text = str_replace('$Y', date("Y"), $text);
        $content = replaceTEXT($value, $text, $content);
      }
      $regex = '[thu-(.*?)]';
      if (preg_match($regex, $value)) {
        if (!empty($code[$k1])) {
          $list = explode("-", $code[$k1]);
          switch ($list[1]) {
            case 'c':
              $content = replaceTEXT($value, day_of_week_vn(date("N", strtotime("-1 days"))), $content);
              break;
            case 's':
              $content = replaceTEXT($value, day_of_week(date("N", strtotime("-1 days"))), $content);
              break;
            case 'l':
              $content = replaceTEXT($value, day_of_week_lite(date("N", strtotime("-1 days"))), $content);
              break;
          }
        }
      }
      $regex = '[ythu-(.*?)]';
      if (preg_match($regex, $value)) {
        if (!empty($code[$k1])) {
          $list = explode("-", $code[$k1]);
          switch ($list[1]) {
            case 'c':
              $content = replaceTEXT($value, day_of_week_vn(date("N", strtotime("-1 days"))), $content);
              break;
            case 's':
              $content = replaceTEXT($value, day_of_week(date("N", strtotime("-1 days"))), $content);
              break;
            case 'l':
              $content = replaceTEXT($value, day_of_week_lite(date("N", strtotime("-1 days"))), $content);
              break;
          }
        }
      }
      $regex = '[2so-(.*?)]';
      if (preg_match($regex, $value)) {
        $list = explode("-", $code[$k1]);
        switch ($list[1]) {
          case 'cap':
            $content = replaceTEXT($value, randomloxien(2, $list[2]), $content);
            break;
          case 'don':
            $content = replaceTEXT($value, random2so($list[2], ", "), $content);
            break;
        }
      }
      $regex = '[3so-(.*?)]';
      if (preg_match($regex, $value)) {
        $list = explode("-", $code[$k1]);
        switch ($list[1]) {
          case 'cap':
            $content = replaceTEXT($value, randomloxien3(2, $list[2]), $content);
            break;
          case 'don':
            $content = replaceTEXT($value, random3so($list[2], ", "), $content);
            break;
        }
      }
      $regex = '[lokep-(.*?)]';
      if (preg_match($regex, $value)) {
        $list = explode("-", $code[$k1]);
        switch ($list[1]) {
          case 'all':
            $content = replaceTEXT($value, randomlokep(), $content);
            break;
          case 'bang':
            $content = replaceTEXT($value, randomlokep(0, $list[2]), $content);
            break;
          case 'lech':
            $content = replaceTEXT($value, randomlokep(1, $list[2]), $content);
            break;
          case 'am':
            $content = replaceTEXT($value, randomlokep(2, $list[2]), $content);
            break;
          case 'satkep':
            $content = replaceTEXT($value, randomlokep(3, $list[2]), $content);
            break;
        }
      }
      $regex = '[loxien-(.*?)-(.*?)]';
      if (preg_match($regex, $value)) {
        if (!empty($code[$k1])) {
          $list = explode("-", $code[$k1]);
          $content = replaceTEXT($value, randomloxien($list[1], $list[2]), $content);
        }
      }
      // shortcode table
      $regex = '[soi-cau-(.*?)]';
      if (preg_match($regex, $value)) {
        $list = explode("-", $code[$k1]);
        $html = '
        <ul>
        <li>Dàn lô đặc biệt đầu: ' . random2so(6) . '</li>
        <li>Dàn lô đặc biệt đuôi: ' . random2so(6) . '</li>
        <li>Dàn Loto: ' . random2so(18) . '</li>
        <li>Bạch thủ lô hôm nay: ' . random2so(1) . '</li>
        <li>Cặp lô có tỷ lệ về cao nhất: ' . random2so(2) . '</li>
        <li>Lô kép: ' . randomlokep() . '</li>
        <li>Lô xiên 2: ' . randomloxien(2, 3) . '</li>
        <li>Lô xiên 3: ' . randomloxien(3, 3) . '</li>
        <li>Giải đặc biệt 3 số: ' . random3so(2) . '</li>
        </ul>';
        $content = replaceTEXT($value, $html, $content);
      }
      $regex = '[soicau888-(.*?)]';
      if (preg_match($regex, $value)) {
        $list = explode("-", $code[$k1]);
        $html = '
        <ul>
        <li>Giải đặc biệt: ' . sprintf("%'02d", rand(0, 99)) . '</li>
        <li>Cầu bạch thủ: ' . sprintf("%'02d", rand(0, 99)) . '</li>
        <li>Cầu đẹp trong ngày: ' . random2so(6, " - ") . '</li>
        <li>Cầu về nhiều nhất: ' . random2so(6, " - ") . '</li>
        <li>Lô dàn: ' . random2so(4, " - ") . '</li>
        <li>Lô xiên 2: ' . randomloxien(2, 1) . '</li>
        <li>Lô xiên 3: ' . randomloxien(3, 1) . '</li>
        <li>Lô kép: ' . randomlokep() . '</li>
        <li>Soi cầu 888: ' . random2so(3, " - ") . '</li>
        </ul>';
        if ($list[1] == 'homnay') {
          if (time() > $dataSC->homnay->date) {
            $savetime = $dataSC->homnay->date + 86400;
            $dataSC->homqua->date = $dataSC->homnay->date;
            $dataSC->homqua->content = $dataSC->homnay->content;
            $dataSC->homnay->date = $savetime;
            $dataSC->homnay->content = $html;
            $save = json_encode($dataSC);
            $_data_category->update(['id' => $oneItem->id], ['shortcode' => $save]);
          }
          $content = replaceTEXT($value, $dataSC->homnay->content, $content);
        };
        if ($list[1] == 'homqua') {
          if (time() > $dataSC->homnay->date) {
            $savetime = $dataSC->homnay->date + 86400;
            $dataSC->homqua->date = $dataSC->homnay->date;
            $dataSC->homqua->content = $dataSC->homnay->content;
            $dataSC->homnay->date = $savetime;
            $dataSC->homnay->content = $html;
            $save = json_encode($dataSC);
            $_data_category->update(['id' => $oneItem->id], ['shortcode' => $save]);
          }
          $content = replaceTEXT($value, $dataSC->homqua->content, $content);
        };
      }
      $regex = '[soicauwap-(.*?)]';
      if (preg_match($regex, $value)) {
        $list = explode("-", $code[$k1]);
        $xscode = $list[1];
        $catItem = getCateByCode($xscode);
        $ngay = date("d/m/Y");
        $thu = day_of_week(date("N"));
        $html = "
        <ul>
        <li>Tham khảo kết quả soi cau wap $catItem->title $thu ngày $ngay</li>
        
        </ul>
        ";
        $cateLive = getCatByWeekDay(date("N") + 1);
        foreach ($cateLive as $key => $item) {
          if ($catItem->id == $item->parent_id) {
            $html .= "
            <li><b>Tỉnh $item->title</b></li>
            <li>
            <p>Giải đặc biệt:             " . random2so(1) . "</p>
            <p>Cầu loto VIP:             " . random2so(2) . "</p>
            <p>Loto Xiên:                 " . randomloxien(2, 4) . "</p>
            <p>Loto xuất hiện nhiều: " . random2so(4) . "</p>
            <p>Loto lâu không về:     " . random2so(4) . "</p>
            </li>
            ";
          };
          if ($item->parent_id == 0 && $item->code == $catItem->code) {
            $html .= "
            <li>
            <p>Giải đặc biệt:             " . random2so(1) . "</p>
            <p>Cầu loto VIP:             " . random2so(2) . "</p>
            <p>Loto Xiên:                 " . randomloxien(2, 4) . "</p>
            <p>Loto xuất hiện nhiều: " . random2so(4) . "</p>
            <p>Loto lâu không về:     " . random2so(4) . "</p>
            </li>
            ";
          }
        }
        $content = replaceTEXT($value, $html, $content);
      }
      $regex = '[kqxs-homqua-(.*?)]';
      if (preg_match($regex, $value)) {
        $list = explode("-", $code[$k1]);
        $xscode = $list[1];
        $catItem = getCateByCode($xscode);
        $homqua = date("Y-m-d", strtotime("-1 days"));
        if ($list[2] == 'xsmb') {
          $data_json_MB = json_decode($ci->callCURL(API_DATACENTER . "result/getFromDayToDay?api_id=1&date_begin=$homqua&date_end=$homqua"), true);
          $data['data_MB'] = (!empty($data_json_MB) && !empty($data_json_MB['data']['data'])) ? $data_json_MB['data']['data'][0] : array();
          if (!empty($data['data_MB'])) $data['oneParentMB'] = $data['oneItem'] = getCateById($data['data_MB']['category_id']);
          $html = $ci->load->view(TEMPLATE_PATH . 'lottery/_table_detail_single', $data, true);
        } else {
          $data_json_MT = json_decode($ci->callCURL(API_DATACENTER . "result/getFromDayToDay?api_id=2&date_begin=$homqua&date_end=$homqua"), true);
          $dataApiMT = (!empty($data_json_MT)) ? groupDisTime($data_json_MT['data']['data']) : array();
          $data_MT = reset($dataApiMT);
          if (!empty($data_MT)) $oneItem = getCateById($data_MT[0]['category_id']);
          if (!empty($oneItem)) $oneParentMT = getCateById($oneItem->parent_id);
          $html = $ci->load->view(TEMPLATE_PATH . 'lottery/_table_detail_multi', ['data_MT_MN' => $data_MT, 'oneParent' => $oneParentMT], true);
        }
        $content = replaceTEXT($value, $html, $content);
      }
      $regex = '[kqxs-{(.*?)}-(.*?)]';
      if (preg_match($regex, $value)) {
        $list = explode("-", $code[$k1]);
        $xscode = $list[2];
        $ngay = str_replace(["{", "}"], ["", ""], $list[1]);
        $ngay = date("Y-m-d", strtotime(str_replace("/", "-", $ngay)));
        $catItem = getCateByCode($xscode);
        if ($list[2] == 'xsmb') {
          $data_json_MB = json_decode($ci->callCURL(API_DATACENTER . "result/getFromDayToDay?api_id=1&date_begin=$ngay&date_end=$ngay"), true);
          $data['data_MB'] = (!empty($data_json_MB) && !empty($data_json_MB['data']['data'])) ? $data_json_MB['data']['data'][0] : array();
          if (!empty($data['data_MB'])) $data['oneParentMB'] = $data['oneItem'] = getCateById($data['data_MB']['category_id']);
          $html = $ci->load->view(TEMPLATE_PATH . 'lottery/_table_detail_single', $data, true);
        } else if ($list[2] == 'xsmt' || $list[2] == 'xsmn') {
          $data_json_MT = json_decode($ci->callCURL(API_DATACENTER . "result/getFromDayToDay?api_id=$catItem->id&date_begin=$ngay&date_end=$ngay"), true);
          $dataApiMT = (!empty($data_json_MT)) ? groupDisTime($data_json_MT['data']['data']) : array();
          $data_MT = reset($dataApiMT);
          if (!empty($data_MT)) $oneItem = getCateById($data_MT[0]['category_id']);
          if (!empty($oneItem)) $oneParentMT = getCateById($oneItem->parent_id);
          $html = $ci->load->view(TEMPLATE_PATH . 'lottery/_table_detail_multi', ['data_MT_MN' => $data_MT, 'oneParent' => $oneParentMT], true);
        } else {
          $data_json_MB = json_decode($ci->callCURL(API_DATACENTER . "result/getFromDayToDay?api_id=$catItem->id&date_begin=$ngay&date_end=$ngay"), true);
          $html = $ci->load->view(TEMPLATE_PATH . 'lottery/_result-province', [
            'data' => (!empty($data_json_MB) && !empty($data_json_MB['data']['data'])) ? $data_json_MB['data']['data'][0] : array(),
            'oneItem' => $catItem,
            'disTime' => $ngay
          ], true);
        }
        $content = replaceTEXT($value, $html, $content);
      }
      $regex = '[dudoan-homnay-(.*?)]';
      if (preg_match($regex, $value)) {
        $list = explode("-", $code[$k1]);
        $xscode = $list[2];
        $catItem = getCateByCode($xscode);
        $ngay = date("d/m/Y");
        $thu = day_of_week_vn(date("N"));
        $html = "
        <ul>";
        $cateLive = getCatByWeekDay(date("N") + 1);
        foreach ($cateLive as $key => $item) {
          if ($catItem->id == $item->parent_id) {
            if ($item->parent_id == 3) $html .= "<li><b>Dự đoán $item->code ngày $ngay</b></li>";
            if ($item->parent_id == 2) $html .= "<li><b>Dự đoán $item->code ngày $ngay</b></li>";
            $list = random2so(4, "-");
            $list = explode("-", $list);
            $html .= "
            <li>
            <p>Giải tám:                    " . sprintf("%'02d", $list[0]) . "</p>
            <p>Đặc biệt:                    " . sprintf("%'02d", $list[1]) . "</p>
            <p>Loto xiên 2:                 " . randomloxien(2, 1) . "</p>
            <p>Bao lô xiên 3:               " . randomloxien(3, 1) . "</p>
            <p>Bộ lô gan đẹp nhất ngày:     " . sprintf("%'02d", $list[2]) . "</p>
            <p>Cầu bạch thủ đẹp nhất ngày:  " . sprintf("%'02d", $list[3]) . "</p>
            </li>
            ";
          };
          if ($item->parent_id == 0 && $item->code == $catItem->code) {
            $html .= "
            <li>
            <p>Giải tám:                    " . sprintf("%'02d", $list[0]) . "</p>
            <p>Đặc biệt:                    " . sprintf("%'02d", $list[1]) . "</p>
            <p>Loto xiên 2:                 " . randomloxien(2, 1) . "</p>
            <p>Bao lô xiên 3:               " . randomloxien(3, 1) . "</p>
            <p>Bộ lô gan đẹp nhất ngày:     " . sprintf("%'02d", $list[2]) . "</p>
            <p>Cầu bạch thủ đẹp nhất ngày:  " . sprintf("%'02d", $list[3]) . "</p>
            </li>
            ";
          }
        }
        $html .= '</ul>';
        $content = replaceTEXT($value, $html, $content);
      }
      $regex = '[dudoan-{(.*?)}-(.*?)]';
      if (preg_match($regex, $value)) {
        $list = explode("-", $code[$k1]);
        $xscode = $list[2];
        $catItem = getCateByCode($xscode);
        $ngay = str_replace(["{", "}"], ["", ""], $list[1]);
        $thu = day_of_week_vn(date("N"));
        $html = "
        <ul>";
        $cateLive = getCatByWeekDay(date("N") + 1);
        foreach ($cateLive as $key => $item) {
          if ($catItem->id == $item->parent_id) {
            if ($item->parent_id == 3) $html .= "<li><b>Dự đoán $item->code ngày $ngay</b></li>";
            if ($item->parent_id == 2) $html .= "<li><b>Dự đoán $item->code ngày $ngay</b></li>";
            $list = random2so(4, "-");
            $list = explode("-", $list);
            $html .= "
            <li>
            <p>Giải tám:                    " . sprintf("%'02d", $list[0]) . "</p>
            <p>Đặc biệt:                    " . sprintf("%'02d", $list[1]) . "</p>
            <p>Loto xiên 2:                 " . randomloxien(2, 1) . "</p>
            <p>Bao lô xiên 3:               " . randomloxien(3, 1) . "</p>
            <p>Bộ lô gan đẹp nhất ngày:     " . sprintf("%'02d", $list[2]) . "</p>
            <p>Cầu bạch thủ đẹp nhất ngày:  " . sprintf("%'02d", $list[3]) . "</p>
            </li>
            ";
          };
          if ($item->parent_id == 0 && $item->code == $catItem->code) {
            $html .= "
            <li>
            <p>Giải tám:                    " . sprintf("%'02d", $list[0]) . "</p>
            <p>Đặc biệt:                    " . sprintf("%'02d", $list[1]) . "</p>
            <p>Loto xiên 2:                 " . randomloxien(2, 1) . "</p>
            <p>Bao lô xiên 3:               " . randomloxien(3, 1) . "</p>
            <p>Bộ lô gan đẹp nhất ngày:     " . sprintf("%'02d", $list[2]) . "</p>
            <p>Cầu bạch thủ đẹp nhất ngày:  " . sprintf("%'02d", $list[3]) . "</p>
            </li>
            ";
          }
        }
        $html .= '</ul>';
        $content = replaceTEXT($value, $html, $content);
      }
      $regex = '[thongke-homnay-(.*?)]';
      if (preg_match($regex, $value)) {
        $list = explode("-", $code[$k1]);
        $xscode = $list[2];
        $catItem = getCateByCode($xscode);
        $ngay = date("d/m/Y");
        $thu = day_of_week_vn(date("N"));
        $html = "
        <ul>";
        $cateLive = getCatByWeekDay(date("N") + 1);
        foreach ($cateLive as $key => $item) {
          if ($catItem->id == $item->parent_id) {
            $html .= "
            <li><b>Kết quả dự đoán xổ số $item->title ngày $ngay $thu:</b></li>
            <li>
            <p>Thống kê Loto gan lâu không xuất hiện:             " . random2so(5, " - ") . "</p>
            <p>Thống kê Loto về nhiều nhất:             " . random2so(5, " - ") . "</p>
            <p>TK2 số cuối giải ĐB: " . random2so(8, " - ") . "</p>
            <p>TK2 số cuối giải ĐB: " . random2so(8, " - ") . "</p>
            </li>
            ";
          };
          if ($item->parent_id == 0 && $item->code == $catItem->code) {
            $html .= "
            <li>
            <p>Thống kê Loto gan lâu không xuất hiện:             " . random2so(5, " - ") . "</p>
            <p>Thống kê Loto về nhiều nhất:             " . random2so(5, " - ") . "</p>
            <p>TK2 số cuối giải ĐB: " . random2so(8, " - ") . "</p>
            <p>TK2 số cuối giải ĐB: " . random2so(8, " - ") . "</p>
            </li>
            ";
          }
        }
        $html .= '</ul>';
        $content = replaceTEXT($value, $html, $content);
      }
      $regex = '[thongke-(.*?)-{(.*?)}-{(.*?)}]';
      if (preg_match($regex, $value) == 1) {
        if (!empty($code[$k1])) {
          $list = explode("-", $code[$k1]);
          $type = $list[1];
          $codeCat = str_replace(["{", "}"], ["", ""], $list[2]);
          $catItem = getCateByCode($codeCat);
          $date_end = str_replace(["{", "}", "/"], ["", "", "-"], $list[3]);
          $date_begin = date("Y-m-d", strtotime($date_end) - (86400 * 7));
          switch ($type) {
            case 'logan':
              if ($codeCat == 'xsmt' || $codeCat == 'xsmn') {
                $cateLive = getCatByWeekDay(date("N", strtotime($date_end)) + 1);
                $html = '';
                foreach ($cateLive as $key => $item) {
                  if ($item->parent_id == $catItem->id) {
                    $html .= "<li><b>Thống kê lô gan $item->title $date_end</b></li>";
                    $data = json_decode($ci->callCURL("https://dataxoso.webest.asia/api/v4/Statistic_loto/logan?code=$item->code&bien_do=10&date_end=$date_end"), true);
                    $html .= $ci->load->view(TEMPLATE_PATH . 'template/table/logan', ['data' => $data['data']['data_top']], true);
                  }
                }
              } else {
                $data = json_decode($ci->callCURL("https://dataxoso.webest.asia/api/v4/Statistic_loto/logan?code=$codeCat&bien_do=10&date_end=$date_end"), true);
                $html = $ci->load->view(TEMPLATE_PATH . 'template/table/logan', ['data' => $data['data']['data_top']], true);
              }
              $content = replaceTEXT($value, $html, $content);
              break;
            case 'dacbiet':
              $data = json_decode($ci->callCURL("https://dataxoso.webest.asia/api/v4/Statistic_dacbiet/getByMonth?code=$codeCat&date_begin=$date_begin&date_end=$date_end"), true);
              $html = $ci->load->view(TEMPLATE_PATH . 'template/table/dacbiet', ['data' => $data['data']['data']], true);
              $content = replaceTEXT($value, $html, $content);
              break;
            case 'xien23':
              $data = json_decode($ci->callCURL("https://dataxoso.webest.asia/api/v4/Statistic_loto/loxien2?code=$codeCat&limit=30&date_end=$date_end"), true);
              $html = $ci->load->view(TEMPLATE_PATH . 'template/table/xien23', ['data' => $data['data']['data']], true);
              $content = replaceTEXT($value, $html, $content);
              break;
            case 'province':
              $html = "<ul>";
              $cateLive = getCatByWeekDay(date("N", strtotime($date_end)) + 1);
              foreach ($cateLive as $key => $item) {

                if ($catItem->id == $item->parent_id) {
                  if ($item->parent_id == 3) $html .= "<li><b>Thống kê $item->code ngày $ngay</b></li>";
                  if ($item->parent_id == 2) $html .= "<li><b>Thống kê $item->code ngày $ngay</b></li>";
                  $list = random2so(4, "-");
                  $list = explode("-", $list);
                  $html .= "
                  <li>
                  <p><b><i>Loto gan: </i><span class=\"text-danger\">" . sprintf("%'02d", $list[0]) . "</span></b></p>
                  <p><b><i>Cặp số về nhiều nhất: </i><span class=\"text-danger\">" . sprintf("%'02d", $list[1]) . "</span></b></p>
                  <p><b><i>Thống kê giải tám $item->title: </i><span class=\"text-danger\">" . sprintf("%'02d", $list[2]) . "</span></b></p>
                  <p><b><i>TK2 số cuối giải ĐB: </i><span class=\"text-danger\">" . sprintf("%'02d", $list[3]) . "</span></b></p>
                  </li>";
                };

                if ($item->parent_id == 0 && $item->code == $catItem->code) {
                  $list = random2so(4, "-");
                  $list = explode("-", $list);
                  $html .= "
                  <li>
                  <p><b><i>Loto gan: </i><span class=\"text-danger\">" . sprintf("%'02d", $list[0]) . "</span></b></p>
                  <p><b><i>Cặp số về nhiều nhất: </i><span class=\"text-danger\">" . sprintf("%'02d", $list[1]) . "</span></b></p>
                  <p><b><i>Thống kê giải tám $catItem->title: </i><span class=\"text-danger\">" . sprintf("%'02d", $list[2]) . "</span></b></p>
                  <p><b><i>TK2 số cuối giải ĐB: </i><span class=\"text-danger\">" . sprintf("%'02d", $list[3]) . "</span></b></p>
                  </li>";
                }

                if ($item->code == $catItem->code) {
                  $list = random2so(4, "-");
                  $list = explode("-", $list);
                  $html .= "
                  <li>
                  <p><b><i>Loto gan: </i><span class=\"text-danger\">" . sprintf("%'02d", $list[0]) . "</span></b></p>
                  <p><b><i>Cặp số về nhiều nhất: </i><span class=\"text-danger\">" . sprintf("%'02d", $list[1]) . "</span></b></p>
                  <p><b><i>Thống kê giải tám $catItem->title: </i><span class=\"text-danger\">" . sprintf("%'02d", $list[2]) . "</span></b></p>
                  <p><b><i>TK2 số cuối giải ĐB: </i><span class=\"text-danger\">" . sprintf("%'02d", $list[3]) . "</span></b></p>
                  </li>";
                }
              }
              $html .= '</ul>';
              $content = replaceTEXT($value, $html, $content);
              break;
          }
        }
      }
      $regex = '[soicau-(.*?)-{(.*?)}-{(.*?)}]';
      if (preg_match($regex, $value) == 1) {
        if (!empty($code[$k1])) {
          $list = explode("-", $code[$k1]);
          $type = $list[1];
          $codeCat = str_replace(["{", "}"], ["", ""], $list[2]);
          $catItem = getCateByCode($codeCat);
          $date_end = str_replace(["{", "}", "/"], ["", "", "-"], $list[3]);
          $date_begin = date("Y-m-d", strtotime($date_end) - (86400 * 7));
          switch ($type) {
            case 'bachthu':
              if ($codeCat == 'xsmb') {
                $data_json_MB = json_decode($ci->callCURL(API_DATACENTER . "result/getFromDayToDay?api_id=1&date_begin=$ngay&date_end=$ngay"), true);
                $data['data_MB'] = (!empty($data_json_MB) && !empty($data_json_MB['data']['data'])) ? $data_json_MB['data']['data'][0] : array();
                if (!empty($data['data_MB'])) $data['oneParentMB'] = $data['oneItem'] = getCateById($data['data_MB']['category_id']);
                $html = $ci->load->view(TEMPLATE_PATH . 'template/table/_table_rs_mb', $data, true);
              } else if ($list[2] == 'xsmt' || $list[2] == 'xsmn') {
                $data_json_MT = json_decode($ci->callCURL(API_DATACENTER . "result/getFromDayToDay?api_id=$catItem->id&date_begin=$ngay&date_end=$ngay"), true);
                $dataApiMT = (!empty($data_json_MT)) ? groupDisTime($data_json_MT['data']['data']) : array();
                $data_MT = reset($dataApiMT);
                if (!empty($data_MT)) $oneItem = getCateById($data_MT[0]['category_id']);
                if (!empty($oneItem)) $oneParentMT = getCateById($oneItem->parent_id);
                $html = $ci->load->view(TEMPLATE_PATH . 'lottery/_table_detail_multi', ['data_MT_MN' => $data_MT, 'oneParent' => $oneParentMT], true);
              } else {
                $data_json_MB = json_decode($ci->callCURL(API_DATACENTER . "result/getFromDayToDay?api_id=$catItem->id&date_begin=$ngay&date_end=$ngay"), true);
                $html = $ci->load->view(TEMPLATE_PATH . 'lottery/_result-province', [
                  'data' => (!empty($data_json_MB) && !empty($data_json_MB['data']['data'])) ? $data_json_MB['data']['data'][0] : array(),
                  'oneItem' => $catItem,
                  'disTime' => $ngay
                ], true);
              }
              $content = replaceTEXT($value, $html, $content);
              break;
            case 'bacnho':
              $data_json_MB = json_decode($ci->callCURL(API_DATACENTER . "result/getFromDayToDay?api_id=1&date_begin=$ngay&date_end=$ngay"), true);
              $data2soRS = substr(json_decode($data_json_MB['data']['data'][0]['data_result'])[1][0], -2, 2);
              //dd($dataRS);
              $html = "<div id='bacnho'>
              <p>- Hôm qua 2 số cuối giải đặc biệt về $data2soRS lựa chọn được các con số có khả năng về hôm nay là " . randomloxien(2, 1) . "</p>
              <p>- Hôm qua lô về 2 nháy có tần suất 9/30 lần về hôm nay đánh con " . random2so(1) . "</p>
              </div>";
              $content = replaceTEXT($value, $html, $content);
              break;
            case 'dauduoi':
              $list = explode(' - ', randomloxien(3, 1));
              $html = "<div id='dauduoi'>
              <p>- Dự đoán hôm nay miền Bắc bạch thủ về " . $list[0] . ", tần suất lô rơi là 9 lần trong 30 ngày gần đây.</p>
              <p>- Dự đoán bạch thủ hôm nay " . $list[1] . ", tần suất lô rơi là 4 lần trong 30 ngày gần nhất.</p>
              <p>- Cầu kẹp số xuất hiện là " . $list[2] . ", tần suất lô rơi là 4 lần/30 ngày trước đó.</p>
              </div>";
              $content = replaceTEXT($value, $html, $content);
              break;
          }
        }
      }
      $regex = '[bachthu-homnay-(.*?)]';
      if (preg_match($regex, $value)) {
        $list = explode("-", $code[$k1]);
        $xscode = $list[2];
        $catItem = getCateByCode($xscode);
        $ngay = date("d/m/Y");
        $thu = day_of_week_vn(date("N"));
        $html = "
        <ul>";
        $cateLive = getCatByWeekDay(date("N") + 1);
        foreach ($cateLive as $key => $item) {
          if ($catItem->id == $item->parent_id) {
            $html .= "
            <li>
            <p>Bạch thủ lô xổ số $item->title $thu: " . sprintf("%'02d", rand(0, 99)) . "</p>
            </li>
            ";
          };
          if ($item->parent_id == 0 && $item->code == $catItem->code) {
            $html .= "
            <li>
            <p>Bạch thủ lô xổ số $item->title $thu: " . sprintf("%'02d", rand(0, 99)) . "</p>
            </li>
            ";
          }
        }
        $html .= '</ul>';
        $content = replaceTEXT($value, $html, $content);
      }
      $regex = '[quaythu-homnay-(.*?)]';
      if (preg_match($regex, $value)) {
        $list = explode("-", $code[$k1]);
        $xscode = $list[2];
        $catItem = getCateByCode($xscode);
        $ngay = date("d/m/Y");
        $homqua = date("Y-m-d", strtotime("-1 days"));
        $thu = day_of_week_vn(date("N"));
        $html = "
        <ul>";
        $cateLive = getCatByWeekDay(date("N") + 1);
        $html .= $ci->load->view(TEMPLATE_PATH . 'spin/_table', ['oneItem' => $catItem], true);
        foreach ($cateLive as $key => $item) {
          if ($catItem->id == $item->parent_id) {
            $html .= "
            <li>
            <p>Quay thử xổ số $item->title - $item->code ngày $ngay</p>
            </li>
            ";
          };
          if ($item->parent_id == 0 && $item->code == $catItem->code) {
            $html .= "
            <li>
            <p>Quay thử xổ số $item->title - $item->code ngày $ngay</p>
            </li>
            ";
          }
        }
        $html .= '</ul>';
        $content = replaceTEXT($value, $html, $content);
      }
      $regex = '[quaythu-{(.*?)}-(.*?)]';
      if (preg_match($regex, $value)) {
        if (!empty($code[$k1])) {
          $list = explode("-", $code[$k1]);
          $xscode = $list[2];
          $catItem = getCateByCode($xscode);
          $ngay = str_replace(["{", "}"], ["", ""], $list[1]);
          $homqua = date("Y-m-d", strtotime("-1 days"));
          $thu = day_of_week_vn(date("N"));
          $html = "
        <ul>";
          $cateLive = getCatByWeekDay(date("N") + 1);
          $html .= $ci->load->view(TEMPLATE_PATH . 'spin/_table', ['oneItem' => $catItem, 'itemSpin' => date("Y-m-d", strtotime(str_replace("/", "-", $ngay)))], true);
          foreach ($cateLive as $key => $item) {
            if ($catItem->id == $item->parent_id) {
              $html .= "
            <li>
            <p>Quay thử xổ số $item->title - $item->code ngày $ngay</p>
            </li>
            ";
            };
            if ($item->parent_id == 0 && $item->code == $catItem->code) {
              $html .= "
            <li>
            <p>Quay thử xổ số $item->title - $item->code ngày $ngay</p>
            </li>
            ";
            }
          }
          $html .= '</ul>';
          $content = replaceTEXT($value, $html, $content);
        }
      }
      $regex = '[quaythurandom-{(.*?)}-(.*?)]';
      if (preg_match($regex, $value)) {
        if (!empty($code[$k1])) {
          $list = explode("-", $code[$k1]);
          $codeCat = $list[2];
          $catItem = getCateByCode($codeCat);
          $ngay = str_replace(["{", "}", "/"], ["", "", "-"], $list[1]);
          $tuantruoc = date("Y-m-d", strtotime($ngay) - (86400 * 7));
          $dataRS = json_decode($ci->callCURL("https://dataxoso.webest.asia/api/v4/Result/getFromDay?code=$codeCat&date=$tuantruoc"), true);
          foreach ($dataRS['data']['data'] as $key => $item) {
            $dataRS['data']['data'][$key]['displayed_time'] = date("Y-m-d", strtotime($ngay));
            $dataRS['data']['data'][$key]['data_result'] = random_result($dataRS['data']['data'][$key]['data_result'], $catItem->parent_id);
          };
          $html = $ci->load->view(TEMPLATE_PATH . 'template/table/quaythu', ['data' => $dataRS['data']['data'], 'oneItem' => $catItem, 'itemSpin' => date("Y-m-d", strtotime(str_replace("/", "-", $ngay)))], true);
          $content = replaceTEXT($value, $html, $content);
        }
      }
      // ======================
    };
    if (count($shortcode[0]) > 0 && !empty($oneItem->shortcode)) {
      $save = [];
      $savetime = strtotime(date("Y-m-d 19:0:0"));
      if (time() > $savetime) $savetime = $savetime + 86400;
      $dataSC->code->date = $savetime;
      $dataSC->code->content = $content;
      $save = json_encode($dataSC);
      $_data_category->update(['id' => $oneItem->id], ['shortcode' => $save]);
    };
    return $content;
  }

  function replaceTEXT($search, $replace, $string)
  {
    $start = strpos($string, $search);
    $length = strlen($search);
    return substr_replace($string, $replace, $start, $length);
  }
  function random2so($length, $squa = ', ')
  {
    $string = '';
    $loxien = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 84, 85, 86, 87, 88, 89, 90, 91, 92, 93, 94, 95, 96, 97, 98, 99];
    $rd = array_rand($loxien, $length);
    if (is_array($rd) || is_object($rd)) foreach ($rd as $key => $value) {
      $string .= sprintf("%'02d", $loxien[$value]) . $squa;
    }
    $string = substr($string, 0, -2);
    return $string;
  }
  function random3so($length, $squa = ", ")
  {
    $string = '';
    for ($x = 1; $x <= $length; $x++) {
      $string .= rand(100, 999) . $squa;
    }
    $string = substr($string, 0, -2);
    return $string;
  }
  function randomlokep($chonlo = -1, $length = 2) //getlo: chọn array tùy biến, number: số lượng số trả về 1 số hay cặp 2 số
  {
    $string = '';
    $lo = [];
    $lo[] = [00, 11, 22, 33, 44, 55, 66, 77, 88, 99]; //kepbang
    $lo[] = [[05, 50], [16, 61], [27, 72], [38, 83], [49, 94]]; //keplech
    $lo[] = [[14, 41], [07, 70], [29, 92], [58, 85], [36, 63]]; //kepam
    $lo[] = ["010", "090", "121", "232", "343", "434", "565", "676", "787", "898"]; //kepsatkep

    if ($chonlo == -1) $chonlo = rand(0, 3);

    $rd = array_rand($lo[$chonlo], $length);
    if (is_array($rd) || is_object($rd)) foreach ($rd as $key => $value) {
      if ($chonlo <= 2) {
        if (is_array($lo[$chonlo][$value]) || is_object($lo[$chonlo][$value])) foreach ($lo[$chonlo][$value] as $key => $v1) {
          $string .= sprintf("%'02d", $v1) . " - ";
        }
        else {
          $string .= sprintf("%'02d", $lo[$chonlo][$value]) . " - ";
        };
        if (is_array($lo[$chonlo][$value]) || is_object($lo[$chonlo][$value])) {
          $string = substr($string, 0, -2);
          $string .= ", ";
        }
      }
      if ($chonlo > 2) $string .= $lo[$chonlo][$value] . " - ";
    };

    // if ($chonlo == 0) {
    //   $rd = array_rand($lo[0], $number);
    //   if (is_array($rd) || is_object($rd)) foreach ($rd as $key => $value) {
    //     $string .= sprintf("%'02d", $lo[0][$value]) . " - ";
    //   };
    //   if (is_array($rd) || is_object($rd) == false) {
    //     $string .= sprintf("%'02d", $lo[0][rand(0, (count($lo[0]) - 1))]) . " - ";
    //   }
    // } else {
    //   $rd = rand(0, (count($lo[$chonlo]) - 1));
    //   if ($number == 1) {
    //     if (is_array($rd) || is_object($rd)) $string = sprintf("%'02d", $lo[$chonlo][$rd][rand(0, 1)]) . ', ';
    //   } else {
    //     foreach ($lo[$chonlo][$rd] as $key => $value) {
    //       $string .= sprintf("%'02d", $value) . " - ";
    //     }
    //   }
    // }
    $string = substr($string, 0, -2);
    return $string;
  }
  function randomloxien($number, $length)
  {
    $string = '';
    $loxien = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 84, 85, 86, 87, 88, 89, 90, 91, 92, 93, 94, 95, 96, 97, 98, 99];
    for ($x = 1; $x <= $length; $x++) {
      $rd = array_rand($loxien, $number);
      foreach ($rd as $key => $value) {
        $string .= sprintf("%'02d", $loxien[$value]) . " - ";
      }
      $string = substr($string, 0, -3);
      $string .= ", ";
    }
    $string = substr($string, 0, -2);
    return $string;
  }
  function randomloxien3($number, $length)
  {
    $string = '';
    $loxien = [100, 101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123, 124, 125, 126, 127, 128, 129, 130, 131, 132, 133, 134, 135, 136, 137, 138, 139, 140, 141, 142, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 153, 154, 155, 156, 157, 158, 159, 160, 161, 162, 163, 164, 165, 166, 167, 168, 169, 170, 171, 172, 173, 174, 175, 176, 177, 178, 179, 180, 181, 182, 183, 184, 185, 186, 187, 188, 189, 190, 191, 192, 193, 194, 195, 196, 197, 198, 199, 200, 201, 202, 203, 204, 205, 206, 207, 208, 209, 210, 211, 212, 213, 214, 215, 216, 217, 218, 219, 220, 221, 222, 223, 224, 225, 226, 227, 228, 229, 230, 231, 232, 233, 234, 235, 236, 237, 238, 239, 240, 241, 242, 243, 244, 245, 246, 247, 248, 249, 250, 251, 252, 253, 254, 255, 256, 257, 258, 259, 260, 261, 262, 263, 264, 265, 266, 267, 268, 269, 270, 271, 272, 273, 274, 275, 276, 277, 278, 279, 280, 281, 282, 283, 284, 285, 286, 287, 288, 289, 290, 291, 292, 293, 294, 295, 296, 297, 298, 299, 300, 301, 302, 303, 304, 305, 306, 307, 308, 309, 310, 311, 312, 313, 314, 315, 316, 317, 318, 319, 320, 321, 322, 323, 324, 325, 326, 327, 328, 329, 330, 331, 332, 333, 334, 335, 336, 337, 338, 339, 340, 341, 342, 343, 344, 345, 346, 347, 348, 349, 350, 351, 352, 353, 354, 355, 356, 357, 358, 359, 360, 361, 362, 363, 364, 365, 366, 367, 368, 369, 370, 371, 372, 373, 374, 375, 376, 377, 378, 379, 380, 381, 382, 383, 384, 385, 386, 387, 388, 389, 390, 391, 392, 393, 394, 395, 396, 397, 398, 399, 400, 401, 402, 403, 404, 405, 406, 407, 408, 409, 410, 411, 412, 413, 414, 415, 416, 417, 418, 419, 420, 421, 422, 423, 424, 425, 426, 427, 428, 429, 430, 431, 432, 433, 434, 435, 436, 437, 438, 439, 440, 441, 442, 443, 444, 445, 446, 447, 448, 449, 450, 451, 452, 453, 454, 455, 456, 457, 458, 459, 460, 461, 462, 463, 464, 465, 466, 467, 468, 469, 470, 471, 472, 473, 474, 475, 476, 477, 478, 479, 480, 481, 482, 483, 484, 485, 486, 487, 488, 489, 490, 491, 492, 493, 494, 495, 496, 497, 498, 499, 500, 501, 502, 503, 504, 505, 506, 507, 508, 509, 510, 511, 512, 513, 514, 515, 516, 517, 518, 519, 520, 521, 522, 523, 524, 525, 526, 527, 528, 529, 530, 531, 532, 533, 534, 535, 536, 537, 538, 539, 540, 541, 542, 543, 544, 545, 546, 547, 548, 549, 550, 551, 552, 553, 554, 555, 556, 557, 558, 559, 560, 561, 562, 563, 564, 565, 566, 567, 568, 569, 570, 571, 572, 573, 574, 575, 576, 577, 578, 579, 580, 581, 582, 583, 584, 585, 586, 587, 588, 589, 590, 591, 592, 593, 594, 595, 596, 597, 598, 599, 600, 601, 602, 603, 604, 605, 606, 607, 608, 609, 610, 611, 612, 613, 614, 615, 616, 617, 618, 619, 620, 621, 622, 623, 624, 625, 626, 627, 628, 629, 630, 631, 632, 633, 634, 635, 636, 637, 638, 639, 640, 641, 642, 643, 644, 645, 646, 647, 648, 649, 650, 651, 652, 653, 654, 655, 656, 657, 658, 659, 660, 661, 662, 663, 664, 665, 666, 667, 668, 669, 670, 671, 672, 673, 674, 675, 676, 677, 678, 679, 680, 681, 682, 683, 684, 685, 686, 687, 688, 689, 690, 691, 692, 693, 694, 695, 696, 697, 698, 699, 700, 701, 702, 703, 704, 705, 706, 707, 708, 709, 710, 711, 712, 713, 714, 715, 716, 717, 718, 719, 720, 721, 722, 723, 724, 725, 726, 727, 728, 729, 730, 731, 732, 733, 734, 735, 736, 737, 738, 739, 740, 741, 742, 743, 744, 745, 746, 747, 748, 749, 750, 751, 752, 753, 754, 755, 756, 757, 758, 759, 760, 761, 762, 763, 764, 765, 766, 767, 768, 769, 770, 771, 772, 773, 774, 775, 776, 777, 778, 779, 780, 781, 782, 783, 784, 785, 786, 787, 788, 789, 790, 791, 792, 793, 794, 795, 796, 797, 798, 799, 800, 801, 802, 803, 804, 805, 806, 807, 808, 809, 810, 811, 812, 813, 814, 815, 816, 817, 818, 819, 820, 821, 822, 823, 824, 825, 826, 827, 828, 829, 830, 831, 832, 833, 834, 835, 836, 837, 838, 839, 840, 841, 842, 843, 844, 845, 846, 847, 848, 849, 850, 851, 852, 853, 854, 855, 856, 857, 858, 859, 860, 861, 862, 863, 864, 865, 866, 867, 868, 869, 870, 871, 872, 873, 874, 875, 876, 877, 878, 879, 880, 881, 882, 883, 884, 885, 886, 887, 888, 889, 890, 891, 892, 893, 894, 895, 896, 897, 898, 899, 900, 901, 902, 903, 904, 905, 906, 907, 908, 909, 910, 911, 912, 913, 914, 915, 916, 917, 918, 919, 920, 921, 922, 923, 924, 925, 926, 927, 928, 929, 930, 931, 932, 933, 934, 935, 936, 937, 938, 939, 940, 941, 942, 943, 944, 945, 946, 947, 948, 949, 950, 951, 952, 953, 954, 955, 956, 957, 958, 959, 960, 961, 962, 963, 964, 965, 966, 967, 968, 969, 970, 971, 972, 973, 974, 975, 976, 977, 978, 979, 980, 981, 982, 983, 984, 985, 986, 987, 988, 989, 990, 991, 992, 993, 994, 995, 996, 997, 998, 999];
    for ($x = 1; $x <= $length; $x++) {
      $rd = array_rand($loxien, $number);
      foreach ($rd as $key => $value) {
        $string .= sprintf("%'02d", $loxien[$value]) . " - ";
      }
      $string = substr($string, 0, -3);
      $string .= ", ";
    }
    $string = substr($string, 0, -2);
    return $string;
  }

  function random_result($text, $type)
  {
    preg_match_all('/"[0-9]{2}"/', $text, $so2);
    foreach ($so2[0] as $key => $number) {
      $replace = '"' . sprintf("%'02d", rand(0, 99)) . '"';
      $text = replaceTEXT($number, $replace, $text);
    }
    preg_match_all('/"[0-9]{3}"/', $text, $so2);
    foreach ($so2[0] as $key => $number) {
      $replace = '"' . rand(100, 999) . '"';
      $text = replaceTEXT($number, $replace, $text);
    }
    preg_match_all('/"[0-9]{4}"/', $text, $so2);
    foreach ($so2[0] as $key => $number) {
      $replace = '"' . rand(1000, 9999) . '"';
      $text = replaceTEXT($number, $replace, $text);
    }
    preg_match_all('/"[0-9]{5}"/', $text, $so2);
    foreach ($so2[0] as $key => $number) {
      $replace = '"' . rand(10000, 99999) . '"';
      $text = replaceTEXT($number, $replace, $text);
    }
    preg_match_all('/"[0-9]{6}"/', $text, $so2);
    foreach ($so2[0] as $key => $number) {
      $replace = '"' . rand(100000, 999999) . '"';
      $text = replaceTEXT($number, $replace, $text);
    }
    return $text;
  }

  function clear_result($text)
  {
    preg_match_all('/"(.*?)"/', $text, $so2);
    foreach ($so2[0] as $key => $number) {
      $replace = '""';
      $text = replaceTEXT($number, $replace, $text);
    }
    return $text;
  }
}