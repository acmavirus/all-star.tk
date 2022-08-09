<?php
/**
 * Created by PhpStorm.
 * User: ducto
 * Date: 10/2/2018
 * Time: 11:43 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Page_model extends STEVEN_Model
{
    public $table_category;

    public function __construct()
    {
        parent::__construct();
        $this->table = "page";
        $this->column_order = array("$this->table.id", "$this->table.id", "title", "$this->table.is_status", "$this->table.created_time", "$this->table.updated_time");
        $this->column_search = array("title");
        $this->order_default = array("$this->table.id" => "ASC");
    }

    public function _where_custom($args = array())
    {
        parent::_where_custom();
        extract($args);
    }

    public function getByKey($meta_key, $updateCache = false)
    {
        $keyCache = "{$this->table}_getByKey_".$meta_key;
        $data = $this->getCache($keyCache);
        if(empty($data) || $updateCache == true){
            $this->db->from($this->table);
            $this->db->where('meta_key',$meta_key);
            $data = $this->db->get()->row();
            $this->setCache($keyCache,$data);
        }
        return $data;
    }
}