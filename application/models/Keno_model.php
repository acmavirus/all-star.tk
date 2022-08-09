<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Keno_model extends STEVEN_Model
{
    protected $table_name;

    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'st_keno';
    }

    function updateKeno($period, $data, $update = 0) {
        $item = $this->db->from($this->table_name)->where('period', $period)->get()->row();
        if (empty($item)) {
            $this->db->insert($this->table_name, $data);
            return 1;
        } elseif($update==1) {
            $this->db->where('period', $period)->update($this->table_name, $data);
            return 2;
        }
        return false;
    }

    function getListKeno($offset = '', $limit = '', $date_from = '', $date_to = '') {
        $this->db->from($this->table_name);
        if (!empty($date_from) && !empty($date_to)) $this->db->where('displayed_time >= ', $date_from)->where('displayed_time <= ', $date_to);
        $this->db->order_by("displayed_time","DESC");
        $this->db->limit($limit, $offset);
        return $this->db->get()->result_array();
    }
}