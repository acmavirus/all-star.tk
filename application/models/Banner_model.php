<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Banner_model extends STEVEN_Model
{
    public $table;

    public function __construct()
    {
        parent::__construct();
        $this->table = "banner";
        $this->column_order = array("$this->table.id",);
        $this->column_search = array("title");
        $this->order_default = array("$this->table.id" => "ASC");
    }


    public function _where_custom($args = array())
    {
        parent::_where_custom();
        extract($args);
        if (!empty($type)) $this->db->where("$this->table.type", $type);
    }

    public function getDataBanner($location)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        if (!empty($location))
            $this->db->where('location', $location);
        $this->db->where('is_status', 1);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get()->result_array();
        return $query;
    }
}
