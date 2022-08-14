<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require 'vendor/autoload.php';
use Intervention\Image\ImageManagerStatic as Image;

if (!function_exists('getImageThumb')) {
    function getImageThumb($image = '',$width = '',$height= '',$crop = true){
        if(empty($image)) {
            return base_url()."public/default-thumbnail.png";
        }
        if(preg_match('/http/i', $image)) return $image;
        if($crop == false) {
            $pathThumb = ltrim($image, '/');
            return MEDIA_URL.$pathThumb;
        }
        $image = trim($image);
        $imageOrigin = MEDIA_PATH . "/" . $image;
        if (!empty($width) && !empty($height)) {
            $sizeText = sprintf('-%dx%d', $width, $height);
            $ext = pathinfo($image, PATHINFO_EXTENSION);
            $newImage = str_replace(".$ext", "$sizeText.$ext", $image);
            $pathThumb = MEDIA_PATH . '/thumb/' . $newImage;
            $pathThumb = str_replace('//', '/', $pathThumb);
            if (!file_exists($pathThumb)) {
                try {
                    if (!is_dir(dirname($pathThumb))) {
                        mkdir(dirname($pathThumb), 0755, TRUE);
                    }
                    // import the Intervention Image Manager Class
                    // configure with favored image driver (gd by default)
                    Image::configure(array('driver' => 'gd'));

                    // and you are ready to go ...
                    $image = Image::make($imageOrigin)->fit(intval($width), intval($height));
                    $image->save($pathThumb,60);
                } catch (Exception $e) {
                }
            }
            return MEDIA_URL . 'thumb/' . ltrim($newImage, '/');
        }
        return MEDIA_URL . ltrim($image,'/') .'?v='.ASSET_VERSION;
    }
}

if (!function_exists('getThumbnail')) {
    function getThumbnail($data,$width = '',$height= '',$class='',$crop=true){
        $data = '<img loading="lazy" class="'.$class.'"  src="'.getImageThumb($data->thumbnail,$width,$height,$crop).'"  alt="'.$data->title.'" onerror="this.src=\'https://via.placeholder.com/'.$width.'x'.$height.'/C4C4C4 \'" />' ;
        return $data;
    }
}

if (!function_exists('getThumbnailStatic')) {
    function getThumbnailStatic($thumbnail,$width = '', $height = '',$alt='',$class=''){
        $data = '<img class="'.$class.'"  src="'.$thumbnail.'" width="'.$width.'" height="'.$height.'" alt="'.$alt.'"/>';
        return $data;
    }
}

if (!function_exists('downloadLogo')) {
    function downloadLogo($link, $name, $folder = '')
    {
        $ext = pathinfo($link, PATHINFO_EXTENSION);
        $fileName = $folder . DIRECTORY_SEPARATOR . $name . '.' . (!empty($ext) ? $ext : 'png');
        if (file_exists(MEDIA_PATH . $fileName) == false) {
            if (!is_dir(dirname(MEDIA_PATH . $fileName))) {
                mkdir(dirname(MEDIA_PATH . $fileName), 0755, TRUE);
            }
            file_put_contents(MEDIA_PATH . $fileName, file_get_contents($link));
            return $fileName;
        } else return $fileName;
    }
}

if (!function_exists('getLogoTournament')) {
    function getLogoTournament($id, $alt='', $class='', $w = null, $h = null){
        $fileName = "$id.png";
        if (file_exists(MEDIA_PATH ."tournament/". $fileName) == false && DEBUG_MODE == FALSE) {
            file_put_contents(MEDIA_PATH ."tournament/". $fileName, file_get_contents("https://api.sofascore.com/api/v1/unique-tournament/$id/image"));
        }
        $src = MEDIA_URL_CDN . "tournament/$id.png?v=".ASSET_VERSION;
        return '<img loading="lazy" class="'.$class.'"  src="'.$src.'" width="'.($w ? $w : '25').'" height="'.($h ? $h : '25').'" alt="'.$alt.'"/>';
    }
}
if (!function_exists('getLogoClub')) {
//    function getLogoClub($id, $alt='', $class='', $w = null, $h = null){
//        $fileName = "$id.png";
//        if (file_exists(MEDIA_PATH ."club/". $fileName) == false && DEBUG_MODE == FALSE) {
//            file_put_contents(MEDIA_PATH ."club/". $fileName, file_get_contents("https://ls.sportradar.com/ls/crest/big/$fileName"));
//        }
//        $src = MEDIA_URL_CDN . "club/$id.png?v=".ASSET_VERSION;
//            return '<img loading="lazy" class="'.$class.'"  src="'.$src.'" width="'.($w ? $w : '40').'" height="'.($h ? $h : '40').'" alt="'.$alt.'"/>';
//
//    }
    function getLogoClub($id, $alt = '', $class = '', $lazy = true)
    {
        $src = MEDIA_NAME . "club/$id.png";
        if(!file_exists($src)){
            $src = TEMPLATES_ASSETS . 'no-logo.png';
        }
        if ($lazy == true) {
            return '<img class="' . $class . '" src="' . $src . '" alt="' . $alt . '"/>';
        } else {
            return $src;
        }
    }
}


if (!function_exists('getLogoTournamentBE')) {
    function getLogoTournamentBE($id){
        $src = MEDIA_URL_CDN . "tournament/$id.png";
        return '<img src="'.$src.'" alt="Logo"/>';
    }
}


if (!function_exists('getImageSwiperLazy')) {
    function getImageSwiperLazy($data,$width = '',$height= '',$class='',$crop = true) {
        $data = '<img class="swiper-lazy '.$class.'"  src="'.getImageThumb($data->thumbnail,$width,$height,$crop).'" alt="'.$data->title.'"/>
        <div class="swiper-lazy-preloader"></div>' ;
        return $data;
    }
}