<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logs_model extends STEVEN_Model
{
    public $table_category;

    public function __construct()
    {
        parent::__construct();
        $this->table = "log_action";
        $this->column_order = array("id", "id", "action", "note", "uid", "created_time","updated_time");
        $this->column_search = array("note");
        $this->order_default = array("id" => "ASC");
    }

    public function _where_custom($args = array())
    {
        parent::_where_custom();
        extract($args);
    }
    
}