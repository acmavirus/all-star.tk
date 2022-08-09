<?php
/**
 * Created by PhpStorm.
 * User: ducto
 * Date: 10/2/2018
 * Time: 11:43 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends STEVEN_Model
{
    public $table_user_group;
    public $table_user_favourite;

    public function __construct()
    {
        parent::__construct();
        $this->table = "users";
        $this->table_user_group = "users_groups";
        $this->table_user_favourite = "users_favourites";
        $this->column_order = array("$this->table.id", "$this->table.id", "$this->table.username", "$this->table.email", "$this->table.first_name", "$this->table.active", "$this->table.created_time", "$this->table.updated_time");
        $this->column_search = array("$this->table.username");
        $this->order_default = array("$this->table.created_time" => "DESC");
    }


    public function _where_custom($args = array())
    {
        parent::_where_custom();
        extract($args);
        if (!empty($group_id)) {
            $this->db->join($this->table_user_group, "$this->table.id = $this->table_user_group.user_id");
            $this->db->where_in("$this->table_user_group.group_id", $group_id);
        }
        if (isset($active)) {
            $this->db->where("$this->table.active", $active);
        }
    }

    public function check_oauth($field, $oauth)
    {
        $this->db->where($field, $oauth);

        $tablename = $this->table;

        $this->db->select('1');
        return $this->db->get($tablename)->num_rows();
    }

    public function getUserByField($key, $value)
    {
        $this->db->select('*');
        $this->db->where($this->table . '.' . $key, $value);
        $this->db->group_by("$this->table.id");
        return $this->db->get($this->table)->row();
    }

    public function getSelect2($ids){
        $this->db->select("$this->table.id, CONCAT(fullname, ' (', email, ')') AS text");
        $this->db->from($this->table);
        if(is_array($ids)) $this->db->where_in("$this->table.id",$ids);
        else $this->db->where("$this->table.id",$ids);

        $query = $this->db->get();
        return $query->result();
    }

    public function updateField($account_id, $key, $value)
    {
        $this->db->where($this->table . '.id', $account_id);
        $this->db->update($this->table, array($this->table . '.' . $key => $value));
        return true;
    }

    public function saveFavourite($user_id,$product_id){
        $data = [
            'account_id' => $user_id,
            'product_id' => $product_id
        ];
        if(!$this->save($data,$this->table_user_favourite)) return false;
        return true;
    }

    public function getDataIdFavourite($user_id){
        $params = [
            'account_id' => $user_id,
        ];
        return $this->getDataAll($params,$this->table_user_favourite);
    }
}