<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('getBanner')) {
    function getBanner($type, $pos)
    {
        $_this = &get_instance();
        $_this->load->model('setting_model');
        $model = new Setting_model();
        $data_banner = $model->get_setting_by_key('data_banner');
        $data_banner = !empty($data_banner) ? json_decode($data_banner->value_setting) : null;
        if (empty($banner = $data_banner->$type)) return;
        if (empty($banner->$pos)) return;
        return $banner->$pos;
    }
}

if (!function_exists('showContainerBanner')) {
    function showContainerBanner($location, $device = 0, $class = '')
    {
        $checkDevice = '';
        if ($device == 1) {
            $checkDevice = 'd-block d-md-none';
        } elseif ($device == 2) {
            $checkDevice = 'd-none d-md-block';
        }
        $html = '<div class="bannerElement ' . $checkDevice . " " . $class . '"  data-location="' . $location . '" data-device="' . $device . '"></div>';
        return $html;
    }
}

if(!function_exists('showImageBanner')){
    function showImageBanner(){
        $_this = &get_instance();
        $_this->load->model('banner_model');
        $data_banner = $_this->banner_model->getDataBanner();
        return json_encode($data_banner);
    }
}
