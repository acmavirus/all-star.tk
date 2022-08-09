<?php
/**
 * Created by PhpStorm.
 * User: ducto
 * Date: 18/12/2017
 * Time: 5:28 CH
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Menus_model extends STEVEN_Model
{
    public $listmenu;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'menus';
    }

    public function getMenu($location, $lang_code, $parent_id = 0,$clear_cache = FALSE){
        $data = '';
        $lang_code = $this->session->userdata('public_lang_code');
        if(CACHE_MODE == TRUE){
            $key = "_cache_menu_{$lang_code}_{$location}_{$parent_id}";
            $data = $this->cache->get($key);
        }
        if(empty($data) || $clear_cache == true){
            $this->db->select('*');
            $this->db->from($this->table);
            $this->db->where('location_id',$location);
            // $this->db->where('parent_id',$parent_id);
            $this->db->where('language_code',$lang_code);
            $query = $this->db->get();
            $data = $query->result_array();
        }
        if(CACHE_MODE == TRUE) $this->cache->save($key,$data,3600);
        return $data;
    }

    // hiển thị dữ liệu
    public function listmenu($menu, $parent = 0)
    {
        foreach ($menu as $key => $row) {
            if ($row['parent_id'] == $parent)
            {
                $this->listmenu[] = array(
                    'id' => intval($row['id']),
                    'name' => $row['title'],
                    'class' => $row['class'],
                    'type' => $row['type'],
                    'order' => $row['order'],
                    'link' => ($row['link'] === '/')?BASE_URL:($row['link'] === '#' ? $row['link'] : BASE_URL.$row['link']),
                    'level' => intval($row['parent_id']),
                    'parent' => intval($row['parent_id']));
                $this->listmenu($menu, $row['id']);
                unset($menu[$key]);
                
            }
        }
    }

    public function saveMenu($data){
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

}
