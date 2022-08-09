<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('getTitle')) {
    function getTitle($oneData = []){
        $_this =& get_instance();
        $_this->load->model('setting_model');
        $settings = $_this->setting_model->getAll();
        if(!empty($oneData)) $title = !empty($oneData->meta_title)?$oneData->meta_title:(!empty($oneData->title)?$oneData->title:'');
        else $title = $settings['title'];
        return str_replace('"','\'',$title)." - ".$settings['name'];
    }
}

if (!function_exists('getTeamName')) {
    function getTeamName($item){
        $gender = '';
        if($item->gender === "m") $gender = "Nam";
        if($item->gender === "f") $gender = "Nữ";
        return $item->name . " ($gender)". (!empty($item->country) ? " | $item->country" :'');
    }
}


if (!function_exists('getScore')) {
    function getScore($oneData = []){
        $status = trim((string)$oneData->status);
        if(in_array($status,['AP','FRO','FT','AET','Coverage c'])){
            $html = "<strong>
                <span data-id='$oneData->id' class=\"text-primary\">
             $oneData->score_home - $oneData->score_away ($status)
             </span>                                 
             </strong>";
            return $html;
        } elseif(empty($status) || in_array($status,['-','Canceled'])) {
            $html = "<strong>
                <span data-id='$oneData->id' class=\"text-primary\">
                    vs
                </span>
             </strong>";
            return $html;
        }else{
            $img = base_url('public/images/livescore.gif');
            $html = "<strong>
            <span data-id='$oneData->id' class=\"text-primary text-live\">
                <img src=\"$img\" style=\"width: 12px; height: auto; padding-bottom: 3px;\">
                <i>$status | $oneData->score_home - $oneData->score_away</i>
             </span>                                 
             </strong>";
            return $html;
        }
    }
}

if (!function_exists('getTranslator')) {
    function getTranslator($text){
        $CI =& get_instance();
        $CI->load->library('translates'); // load library
        $trans = new Translates();
        $result = $trans->trans($text);
        return $result;
    }
}