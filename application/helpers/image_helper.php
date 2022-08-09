<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('getImageThumb')) {
    function getImageThumb($image = '',$width = '',$height= '', $crop = false, $watermark = false){
        if(preg_match("/http/i",$image)) return $image;
        if(empty($image)) {
            $width = !empty($width)?$width:200;
            $height = !empty($height)?$height:200;
            $image =  "no_image.jpg";
            //return "https://via.placeholder.com/{$width}x{$height}.png?text=Thumbnail+{$width}x{$height}";
        }
        $image = str_replace(MEDIA_NAME,'',$image);
        $image = ltrim($image,'/');
        $sourceImage = MEDIA_PATH . $image;

        if(!file_exists($sourceImage) || empty(getimagesize($sourceImage))){
            $width = !empty($width)?$width:200;
            $height = !empty($height)?$height:200;
            return "https://via.placeholder.com/{$width}x{$height}.png?text=Thumbnail+{$width}x{$height}";
            //$sourceImage = dirname(MEDIA_PATH) . DIRECTORY_SEPARATOR . "no_image.png";
        }
        $CI =& get_instance();
        if($width != 0 && $height != 0){
            $size = sprintf('-%dx%d', $width, $height);
            $part = explode('.', $image);
            $ext = '.'.end($part);
            $newImage = str_replace($ext,$size.$ext, $image);
            $newPathImage = MEDIA_PATH . 'thumb/' .$newImage;
            if ( !file_exists( $newPathImage ) ) {
                if(!is_dir(dirname($newPathImage))){
                    mkdir(dirname($newPathImage), 0755, TRUE);
                }

                // CONFIGURE IMAGE LIBRARY
                $CI->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = $sourceImage;
                $config['new_image'] = $newPathImage;
                $config['maintain_ratio'] = TRUE;
                $config['create_thumb'] = FALSE;
                $config['quality'] = "80%";
                $imageSize = getimagesize($sourceImage);
                $imageWidth = intval($imageSize[0]);
                $imageHeight = intval($imageSize[1]);
                $dim = ($imageWidth / $imageHeight) - ($width / $height);
                if($imageWidth > $width || $imageHeight > $height){
                    $config['master_dim'] = ($dim > 0) ? "height" : "width";
                    $config['height'] = $height;
                    $config['width'] = $width;
                }
                $CI->image_lib->initialize($config);
                if (!$CI->image_lib->resize()) {
                    log_message('error',"Error resize image: $sourceImage to $newPathImage =>" . $CI->image_lib->display_errors());
                }
                $CI->image_lib->clear();
                if(!empty($watermark)){
                    $watermarkImage = getWatermark($width,$height);
                    if(!empty($watermarkImage)){
                        $config_watermark['image_library']       = 'gd2';
                        $config_watermark['source_image']       = $newPathImage;
                        $config_watermark['wm_type']       = 'overlay';
                        $config_watermark['wm_opacity']     = 40;
                        //$config_watermark['wm_padding']     = 30;
                        $config_watermark['wm_vrt_alignment'] = 'middle';
                        $config_watermark['wm_hor_alignment'] = 'center';
                        $config_watermark['wm_overlay_path'] = getWatermark($width,$height);
                        $CI->image_lib->initialize($config_watermark);
                        $CI->image_lib->watermark();
                        $CI->image_lib->clear();
                    }
                }

                if($crop == true){
                    $image_config['image_library'] = 'gd2';
                    $image_config['source_image'] = $newPathImage;
                    $image_config['new_image'] = $newPathImage;
                    $image_config['quality'] = "80%";
                    $image_config['maintain_ratio'] = FALSE;
                    $image_config['width'] = $width;
                    $image_config['height'] = $height;
                    $imageSize = getimagesize($newPathImage);
                    $imageWidth = intval($imageSize[0]);
                    $imageHeight = intval($imageSize[1]);
                    $cropStartX = ( $imageWidth / 2) - ( $width /2 );
                    $cropStartY = ( $imageHeight/ 2) - ( $height/2 );
                    $image_config['x_axis'] = $cropStartX;
                    $image_config['y_axis'] = $cropStartY;
                    $CI->image_lib->initialize($image_config);
                    if (!$CI->image_lib->crop()) {
                        log_message('error',"Error crop image: $newPathImage =>" . $CI->image_lib->display_errors());
                    }
                }
            }
            return MEDIA_URL . 'thumb' . DIRECTORY_SEPARATOR . $newImage;
        }
        else {
            $newPathImage = MEDIA_PATH . 'thumb/' .$image;
            if(!is_dir(dirname($newPathImage))){
                mkdir(dirname($newPathImage), 0755, TRUE);
            }
            copy($sourceImage, $newPathImage);
            return MEDIA_URL.$image;
        }
    }
}

if (!function_exists('getWatermark')) {
    function getWatermark($width = '',$height= ''){
        $CI =& get_instance();
        $CI->load->model('setting_model');
        $settings = $CI->setting_model->getAll();
        $width = intval(250/2);
        $height = intval(300/2);
        $image = !empty($settings['watermark']) ? $settings['watermark'] : null;
        $image = ltrim($image,'/');
        if(!empty($image)){
            $source_image = MEDIA_PATH . $image;
            $size = sprintf('-%dx%d', $width, $height);
            $part = explode('.', $image);
            $ext = '.'.end($part);
            $newImage = str_replace($ext,$size.$ext, $image);
            $newPathImage = MEDIA_PATH . $newImage;

            if(!file_exists($newPathImage)) {
                $CI->load->library('image_lib');
                $config_watermark['image_library'] = 'gd2';
                $config_watermark['source_image'] = $source_image;
                $config_watermark['new_image'] = $newPathImage;
                $config_watermark['maintain_ratio'] = TRUE;
                $config_watermark['create_thumb'] = FALSE;
                $config_watermark['height'] = $height;
                $config_watermark['width'] = $width;
                $CI->image_lib->initialize($config_watermark);
                if (!$CI->image_lib->resize()) {
                    log_message('error',"Error watermark image: $newPathImage =>" . $CI->image_lib->display_errors());
                }
                $CI->image_lib->clear();
            }
            return $newPathImage;
        }else{
            return false;
        }
    }
}