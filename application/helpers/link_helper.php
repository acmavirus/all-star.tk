<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
if (!function_exists('site_admin_url')) {
    function site_admin_url($uri = '')
    {
        return BASE_ADMIN_URL . $uri;
    }
}

if (!function_exists('getUrlPage')) {
    function getUrlPage($optional)
    {
        $linkReturn = BASE_URL . "$optional->slug";
        return $linkReturn;
    }
}

if (!function_exists('getUrlPost')) {
    function getUrlPost($oneItem)
    {
        $linkReturn = BASE_URL . "$oneItem->slug-p$oneItem->id";
        return $linkReturn;
    }
}

if (!function_exists('getUrlCategory')) {
    function getUrlCategory($oneItem)
    {
        $linkReturn = BASE_URL . "$oneItem->slug" . '.html';
        return $linkReturn;
    }
}

if (!function_exists('getUrlCategoryRS')) {
    function getUrlCategoryRS($oneItem)
    {
        $linkReturn = BASE_URL . strtolower($oneItem->code) ."-xo-so-$oneItem->slug" . '.html';
        return $linkReturn;
    }
}

if (!function_exists('getUrlWeekday')) {
    function getUrlWeekday($oneParent, $weekday, $add=1)
    {
        if (is_object($oneParent)) $oneParent = (array)$oneParent;
        $code = strtolower($oneParent['code']);
        $linkReturn = BASE_URL;
        $weekday = $weekday + $add;
        $slugDay = $code . "-thu-";
        if ($weekday == 8 || $weekday == 1 && $oneParent['id'] < 42) {
            $weekday = 'chu-nhat';
            $slugDay = "$code-";
        } elseif($weekday == 8 || $weekday == 1 && $oneParent['id'] >= 42) {
            $weekday = 'cn';
            $slugDay = "$code-";
        }
        $linkReturn .= "$slugDay$weekday.html";
        return $linkReturn;
    }
}
if (!function_exists('getUrlDate')) {
    function getUrlDate($oneParent, $date)
    {
        if (is_object($oneParent)) $oneParent = (array)$oneParent;
        $linkReturn = strtolower($oneParent['code']).'-'. date('d-m-Y', strtotime($date));
        return $linkReturn;
    }
}

if (!function_exists('getUrlQuayThu')) {
    function getUrlQuayThu($code)
    {
        return base_url('quay-thu-'.strtolower($code).'.html');
    }
}

if (!function_exists('getUrlSoiCau')) {
    function getUrlSoiCau($code)
    {
        return base_url('soi-cau-'.strtolower($code).'.html');
    }
}

if (!function_exists('cutString')) {
    function cutString($chuoi, $max)
    {
        $length_chuoi = strlen($chuoi);
        if ($length_chuoi <= $max) {
            return $chuoi;
        } else {
            return mb_substr($chuoi, 0, $max, 'UTF-8') . '...';
        }
    }
}