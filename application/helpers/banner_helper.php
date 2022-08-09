<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('getBanner')) {
    function getBanner($position)
    {
        $_this =& get_instance();
        if(!empty($_this->settings['banner'])){
            $banner = $_this->settings['banner'];
            $url_parts = parse_url(current_url());
            $domainCurrent = str_replace('www.', '', $url_parts['host']);
            $domainCurrent = str_replace('.', '', $domainCurrent);
            if($domainCurrent === 'vuivetvtest' || $domainCurrent === 'vuivelivetest' || $domainCurrent === 'vuivelive') $domainCurrent = 'vuivetv';
            $content = $banner[$domainCurrent][$position];
            if(!empty($content['expired'])){
                if (strtotime($content['expired']) > time()) {
                    return ($content['content'] === 'placeholder') ? '<img src="https://via.placeholder.com/' . $content['size'] . '.png?text=' . $position . '">' : $content['content'];
                }
            }else{
                return ($content['content'] === 'placeholder') ? '<img src="https://via.placeholder.com/' . $content['size'] . '.png?text=' . $position . '">' : $content['content'];
            }
        }
        return false;
    }
}
?>