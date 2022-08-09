<?php
/**
 * Created by PhpStorm.
 * User: ducto
 * Date: 10/2/2018
 * Time: 11:43 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Groups_model extends STEVEN_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = "groups";
        $this->table_relation = 'users_groups';
        $this->column_order = array("$this->table.id", "$this->table.id", "title", "$this->table.is_status", "$this->table.created_time", "$this->table.updated_time");
        $this->column_search = array("title");
        $this->order_default = array("$this->table.created_time" => "DESC");

    }

    public function _where_custom($args = array())
    {
        parent::_where_custom();
        extract($args);

        if (isset($is_status)) {
            $this->db->where("$this->table.is_status", $is_status);
        }
    }

    public function get_group_by_userid($id)
    {
        $this->db->from($this->table_relation);
        $this->db->where('user_id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_all_group()
    {
        $this->db->from($this->table);
        $query = $this->db->get();
        return $query->result();
    }
}