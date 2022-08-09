<?php
/**
 * Created by PhpStorm.
 * User: ducto
 * Date: 10/01/2018
 * Time: 11:31 SA
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Setting_model extends CI_Model
{
    private function _all_setting(){
        $dataSetting = file_get_contents(FCPATH . 'database' . DIRECTORY_SEPARATOR . 'settings.cfg');
        $data = $dataSetting ? json_decode($dataSetting, true) : array();
        return $data;
    }
    public function getAll(){
        $this->load->library('session');
        $data = '';
        if(CACHE_MODE == TRUE){
            $keyCache = '_all_setting';
            $data = $this->cache->get($keyCache);
        }
        if(empty($data)){
            $tmp = [];
            $dataSetting = $this->_all_setting();
            if (!empty($dataSetting)) foreach ($dataSetting as $key => $item) {
                if ($key === 'meta') {
                    $oneMeta = $item[$this->session->userdata('public_lang_code')];
                    if (!empty($oneMeta)) foreach ($oneMeta as $keyMeta => $value) {
                        $tmp[$keyMeta] = str_replace('"', '\'', $value);
                    }
                } else
                    $tmp[$key] = $item;
            }
            $data = $tmp;
        }

        if(CACHE_MODE == TRUE) $this->cache->save($keyCache,$data,3600);
        return $data;
    }

    public function getSetting($key){
        $data = $this->getAll();
        return !empty($data[$key]) ? $data[$key] : null;
    }
}