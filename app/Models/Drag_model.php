<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Drag_model extends STEVEN_Model {

    public $table;

    public function __construct() {
        parent::__construct();
        $this->table = "drag";
    }

    public function getDataDrag($type){
        $this->db->from($this->table);
        $this->db->where('type',$type);
        $this->db->order_by('order','asc');
        $query = $this->db->get()->result();
        return $query;
    }
}