<?php
/**
 * Created by PhpStorm.
 * User: ducto
 * Date: 10/2/2018
 * Time: 11:43 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Reviews_model extends STEVEN_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = "reviews";
    }

    public function checkExistByArgs ($args){
        $this->db->select('1');
        $this->db->from($this->table);
        $this->db->where($args);
        $query = $this->db->get();
        return $query->num_rows() > 0 ? true : false;
    }


    public function getRate ($args){
        $url = str_replace(base_url(),"",$args['url']);
        $this->db->select('COUNT(1) AS count_vote, CEIL (ROUND (AVG(rate),1)) AS avg');
        $this->db->where("url",$url);
        $query = $this->db->get($this->table);
        $data = $query->row();
        return $data;
    }
}