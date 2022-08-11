<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
if (!function_exists('toNormalTitle')) {
    function toNormalTitle($str)
    {
        $str = str_replace("'", '', $str);
        $str = str_replace('"', '', $str);
        return $str;
    }
}
if (!function_exists('renderStar')) {
    function renderStar($number)
    {
        $html = "";
        if (!empty($number)) {
            if ($number > 1 || $number < 5) {
                $denominator = "";
                if (fmod($number, 1) !== 0.00) {
                    $arr = explode(".", $number);
                    $full_number = $arr[0] . $arr[1];
                    for ($i = 0; $i < strlen($arr[1]); $i++) {
                        $denominator = $denominator . "0";
                    }
                    $denominator = "1" . $denominator;
                    $numerator = $full_number % $denominator;
                    $checkEndStar = 0;
                    for ($loop = 1; $loop <= 5; $loop++) {
                        if($loop <= $number){
                            $html = $html . '<i class="fa fa-star"></i>';
                        }else{
                            if($checkEndStar == 1){
                                $html = $html . '<i class="far fa-star"></i>';
                            }else{
                                $checkEndStar = 1;
                                if ($numerator <= 7) {
                                    $html = $html . '<i class="fa fa-star-half-alt"></i>';
                                } else{
                                    $html = $html . '<i class="fa fa-star"></i>';
                                }
                            }
                        }
                    }

                }
            }
        }
        return $html;
    }
}
