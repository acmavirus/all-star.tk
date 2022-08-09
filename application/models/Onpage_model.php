<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Onpage_model extends STEVEN_Model {
    public $table;

    public function __construct() {
        parent::__construct();
        $this->table = "onpage";
        $this->column_order = array("$this->table.id",);
        $this->column_search = array("title");
        $this->order_default = array("$this->table.id" => "ASC");
    }

    public function _where_custom($args = array()){
        parent::_where_custom();
        extract($args);
    }

    public function getBySlug($url, $updateCache = false)
    {
        $key = "get_by_url_$url";
        $data = $this->getCache($key);
        if (empty($data) || $updateCache == true) {
            $this->db->select("*");
            $this->db->from($this->table);
            $this->db->where("$this->table.url", $url);
            $query = $this->db->get();
            $data = $query->row();
            $this->setCache($key, $data);
        }
        return $data;
    }

}