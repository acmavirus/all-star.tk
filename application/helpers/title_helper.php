<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('getTitle')) {
    function getTitle($oneData = []){
        $_this =& get_instance();
        $_this->load->model('setting_model');
        $settings = $_this->setting_model->getAll();
        $setting_title = !empty($settings['title']) ? $settings['title'] : '';
        if(!empty($oneData)) $title = !empty($oneData->meta_title)?$oneData->meta_title:(!empty($oneData->title)?$oneData->title:'');
        else $title = $setting_title;
        return str_replace('"','\'',$title)." - ".$setting_title;
    }
}

if (!function_exists('getTitleRoundTournament')) {
    function getTitleRoundTournament($time){
        return day_of_week_vn(date('N', strtotime($time))) . " ngày ". date('d/m/Y', strtotime($time));
    }
}
if (!function_exists('getStringCut')) {
    function getStringCut($string, $start, $width){
        return mb_strimwidth(trim($string),$start,$width,'','utf-8');
    }
}

if (!function_exists('getTitleStatusMatch')) {
    function getTitleStatusMatch($item){
        $status = $item->status;
        switch ($status){
            case "Ended":
                $title = "FT";break;
            case "Not started":
                $title = "Chưa đá";break;
            default:
                $title = $status;
        }
        return $title;
    }
}

if (!function_exists('getTypeEventMatchHome')) {
    function getTypeEventMatchHome($item){
        $type = $item->type;
        $nameType = $item->name;
        $namePlayer = !empty($item->player->name) ? $item->player->name : '';
        $scorePlayer = !empty($item->scorer->name) ? $item->scorer->name : '';
        $assistsPlayer = !empty($item->assists[0]->name) ? $item->assists[0]->name : '';
        switch ($type){
            case "shotofftarget":
                $title = "$namePlayer <i class='fas fa-remove'></i>";
                break;
            case "card":
                if($item->card === "yellow") $title = "$namePlayer <button type=\"button\" class=\"btn btn-warning\"></button>";
                else $title = "$namePlayer <button type=\"button\" class=\"btn btn-danger\"></button>";
                break;
            case "goal":
                $scorePlayer = !empty($scorePlayer) ? "Ghi bàn: <span class=\"font-weight-bold\">$scorePlayer</span>" : '';
                $assistsPlayer = !empty($assistsPlayer) ? " - Kiến tạo: $assistsPlayer" : '';
                $title = "{$item->result->away}  $scorePlayer $assistsPlayer {$item->result->home} - {$item->result->away} <i class=\"fas fa-futbol\"></i>";
                break;
            default:
                $title = $nameType;
        }
        return $title;
    }
}


if (!function_exists('getTypeEventMatchAway')) {
    function getTypeEventMatchAway($item){
        $type = $item->type;
        $nameType = $item->name;
        $namePlayer = !empty($item->player->name) ? $item->player->name : '';
        $scorePlayer = !empty($item->scorer->name) ? $item->scorer->name : '';
        $assistsPlayer = !empty($item->assists[0]->name) ? $item->assists[0]->name : '';
        switch ($type){
            case "shotofftarget":
                $title = "<i class='fas fa-remove'></i> $namePlayer";
                break;
            case "card":
                if($item->card === "yellow") $title = "<button type=\"button\" class=\"btn btn-warning\"></button> $namePlayer";
                else $title = "<button type=\"button\" class=\"btn btn-danger\"></button> $namePlayer";
                break;
            case "goal":
                $scorePlayer = !empty($scorePlayer) ? "Ghi bàn: <span class=\"font-weight-bold\">$scorePlayer</span>" : '';
                $assistsPlayer = !empty($assistsPlayer) ? " - Kiến tạo: $assistsPlayer" : '';
                $title = "<i class=\"fas fa-futbol\"></i> {$item->result->home} - {$item->result->away}  $scorePlayer $assistsPlayer";
                break;
            default:
                $title = $nameType;
        }
        return $title;
    }
}
if (!function_exists('getDayOfWeek')) {
    function getDayOfWeek($date) {
        switch (date('N', strtotime($date))) {
            case 1:
                $titleDay = "thứ 2";
                break;

            case 2:
                $titleDay = "thứ 3";
                break;

            case 3:
                $titleDay = "thứ 4";
                break;

            case 4:
                $titleDay = "thứ 5";
                break;

            case 5:
                $titleDay = "thứ 6";
                break;

            case 6:
                $titleDay = "thứ 7";
                break;
            case 7:
                $titleDay = "chủ nhật";
                break;
            default:
                $titleDay = "";
        }
        return $titleDay;
    }
}