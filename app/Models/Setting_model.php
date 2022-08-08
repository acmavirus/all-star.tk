<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_model extends STEVEN_Model {
    public $table;

    public function __construct()
    {
        parent::__construct();
        $this->table = 'setting';
    }

    public function get_setting_by_key($key_data, $update_cache = false) {
        $key = $this->table . "_setting_by_key_" . $key_data;
        $data = $this->getCache($key);
        if($data === false || $update_cache == true){
            $this->db->select('*');
            $this->db->from($this->table);
            $this->db->where('key_setting', $key_data);
            $data = $this->db->get()->row();
            $this->setCache($key,$data);
        }

        return $data;
    }

    public function getAllSettings($update_cache = false) {
        $key = $this->table . "_getAllSettings";
        $data = $this->getCache($key);
        if($data === false || $update_cache == true){
            $this->db->select('*');
            $this->db->from($this->table);
            $data = $this->db->get()->row();
            $this->setCache($key,$data);
        }
        return $data;
    }

}