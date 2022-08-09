<?php

if (!function_exists('base_url')) {
    function base_url($uri = '')
    {
        return BASE_URL . $uri;
    }
}

if (!function_exists('site_url')) {
    function site_url($uri = '')
    {
        return BASE_URL . $uri;
    }
}

if (!function_exists('current_url')) {
    function current_url($uri = '')
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }
}

if (!function_exists('site_admin_url')) {
    function site_admin_url($uri = '')
    {
        return BASE_ADMIN_URL . $uri;
    }
}

// =============================================
if (!function_exists('page_url')) {
    function page_url($oneItem = '')
    {
        $oneItem = (object) $oneItem;
        return BASE_URL . "page/$oneItem->slug.html";
    }
}
if (!function_exists('post_url')) {
    function post_url($oneItem = '')
    {
        $oneItem = (object) $oneItem;
        return BASE_URL . "puid-$oneItem->slug.html";
    }
}