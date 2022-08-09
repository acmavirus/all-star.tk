<?php
/**
 * Created by PhpStorm.
 * User: ducto
 * Date: 10/2/2018
 * Time: 11:43 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Category_model extends STEVEN_Model
{
    public $_list_category_child;
    public $_list_category_parent;
    public $_list_category_child_id;
    public $category_tree;
    public function __construct(){
        parent::__construct();
        $this->table            = "category";
        $this->column_order     = array("$this->table.id", "$this->table.id", "title", "$this->table.is_status", "$this->table.created_time", "$this->table.updated_time");
        $this->column_search    = array("title");
        $this->order_default    = array("$this->table.id" => "ASC");

    }
    public function _where_custom($args = array()){
        parent::_where_custom();
        extract($args);
        if(!empty($type)) $this->db->where("$this->table.type", $type);
        if (!empty($parent_id)) $this->db->where("$this->table.parent_id",$parent_id);

    }

    public function _all_category($type = '', $updateCache = false){
        $key = '_all_category_'.$type;
        $data = $this->getCache($key);
        if(empty($data) || $updateCache == true) {
            $params = [];
            if(!empty($type)) $params['type'] = $type;
            $data = $this->getDataAll($params);
            $this->setCache($key,$data,60*60*24);
        }
        return $data;

    }

    public function getListRecursive($type, $parent_id = 0){
        $all = $this->_all_category($type);
        $data = [];
        if(!empty($all)) foreach ($all as $key => $item){
            if($item->parent_id == $parent_id){
                $tmp = $item;
                $tmp->list_child = $this->getListChild($all,$item->id);
                $data[] = $tmp;
            }
        }
        return $data;
    }

    /*Đệ quy lấy record parent id*/
    public function _recursive_one_parent($all, $id){
        if(!empty($all)) foreach ($all as $item){
            if($item->id == $id){
                if($item->parent_id == 0) return $item;
                else return $this->_recursive_one_parent($all, $item->parent_id);
            }
        }
    }
    /*Đệ quy lấy record parent id*/

    /*Đệ quy lấy array list category con*/
    public function _recursive_child($all, $parentId = 0){
        if(!empty($all)) foreach ($all as $key => $item){
            if($item->parent_id == $parentId){
                $this->_list_category_child[] = $item;
                unset($all[$key]);
                $this->_recursive_child($all, $item->id);
            }
        }
    }
    /*Đệ quy lấy array list category con*/

    /*Đệ quy lấy array list category con*/
    public function getListChild($all, $parentId = 0){
        $data = array();
        if(!empty($all)) foreach ($all as $key => $item){
            if($item->parent_id == $parentId){
                $data[] = $item;
            }
        }
        return $data;
    }
    /*Đệ quy lấy array list category  con*/

    /*Đệ quy lấy list các ID*/
    public function _recursive_child_id($all, $parentId = 0){
        $this->_list_category_child_id[] = (int)$parentId;
        if(!empty($all)) foreach ($all as $key => $item){
            if($item->parent_id == $parentId){
                $this->_list_category_child_id[] = (int) $item->id;
                unset($all[$key]);
                $this->_recursive_child_id($all, (int) $item->id);
            }
            $this->_list_category_child_id = array_unique($this->_list_category_child_id);
        }
    }
    /*Đệ quy lấy list các ID*/

    /*Đệ quy lấy maps các ID cha*/
    public function _recursive_parent($all, $cateId = 0){
        if(!empty($all)) foreach ($all as $key => $item){
            if($item->id === $cateId){
                $this->_list_category_parent[] = $item;
                unset($all[$key]);
                $this->_recursive_parent($all, $item->parent_id);
            }
        }
    }

    public function _queue_select($categories, $parent_id = 0, $char = ''){
        if(!empty($categories)) foreach ($categories as $key => $item)
        {
            if ($item->parent_id == $parent_id)
            {
                $tmp['title'] = $parent_id ? '  |--'.$char.$item->title : $char.$item->title;
                $tmp['id'] = $item->id;
                $tmp['thumbnail'] = $item->thumbnail;
                $this->category_tree[] = $tmp;
                unset($categories[$key]);
                $this->_queue_select($categories,$item->id,$char.'--');
            }
        }
    }

    public function getListChildByDOW($parentId = 0,$dayOfWeek,$select=null){
        $all = $this->_all_category();
        $data = array();
        if(!empty($all)) foreach ($all as $key => $item){
            if($item->parent_id == $parentId && in_array(($dayOfWeek),json_decode($item->day_prize))){
                (!empty($select)) ? $data[] = $item->$select : $data[] = $item;
            }
        }
        return $data;
    }

    /*Đệ quy lấy maps các ID cha*/

    public function getByIdCached($id, $updateCache = false){
        $keyCache = $this->table . "getByIdCached".$id;
        $data = $this->getCache($keyCache);
        if(empty($data) || $updateCache == true) {
            $this->db->from($this->table);
            $this->db->where('id',$id);
            $data = $this->db->get()->row();
            $this->setCache($keyCache,$data);
        }
        return $data;
    }

    public function getSelect2ByLeagueId($tournament_id){
        $this->db->select('tournament_id AS id, title AS text');
        $this->db->from($this->table);
        $this->db->where("$this->table.tournament_id", $tournament_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getLeagueById($tournament_id){
        $this->db->select('id,title');
        $this->db->from($this->table);
        $this->db->where("$this->table.tournament_id", $tournament_id);
        $query = $this->db->get();
        return $query->row();
    }

    public function getDataByCategoryType($allCategories, $type){
        $dataType = [];
        if(!empty($allCategories)) foreach ($allCategories as $key => $item){
            if($item->type === $type) $dataType[] = $item;
        }
        return $dataType;
    }

    public function getLeagueFeatured(){
        $dataType = [];
        if(!empty($this->_all_category())) foreach ($this->_all_category() as $key => $item){
            if($item->is_featured == 1) $dataType[] = $item->tournament_id;
        }
        return $dataType;
    }

    public function getRandomId($type = null){
        if(empty($type)) $type = $this->session->userdata('type');
        $this->db->select('id');
        $this->db->from($this->table);
        $this->db->where('type', $type);
        $this->db->order_by('id', 'RANDOM');
        $this->db->limit(1);
        $query = $this->db->get();
        $data = $query->result();
        $result = [];
        if(!empty($data)) foreach ($data as $item) $result[] = $item->id;
        return $result;
    }

    // get data group by
    public function getDataGroupBy()
    {
        $this->db->select('type');
        $this->db->from($this->table);
        $this->db->group_by('type');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function slugToId($slug){
        $this->db->select("$this->table.id,slug");
        $this->db->from($this->table);
        $this->db->where("slug",$slug);
        $data = $this->db->get()->row();
        return !empty($data)?$data->id:null;
    }

    public function getAllCategoryByType($type,$parent_id = 0){
        $this->db->from($this->table);
        if(!empty($lang_code)) $this->db->where([
            'type' =>$type,
            'language_code' => $lang_code,
            'parent_id' => $parent_id
        ]);
        $query = $this->db->get();
        return $query->result();
    }

    /*Lấy category cha*/
    public function getOneParent($item){
        if ($item->parent_id == 0)
            $oneParent = $item;
        else {
            $oneParent = $this->getById($item->parent_id);
        }
        return $oneParent;
    }


    public function getCategoryChild($id, $lang_code){
        $this->db->from($this->table);
        $this->db->where([
            'language_code' => $lang_code,
            'parent_id' => $id,
            'is_status' => 1
        ]);
        $query = $this->db->get();
        return $query->result();
    }

    /*Lấy id thứ tự sắp xếp cuối cùng*/
    public function getLastOrder($idParent = 0){
        $this->db->select('order');
        $this->db->from($this->table);
        $this->db->where([
            'type' => $this->session->userdata('type'),
            'parent_id' => $idParent,
        ]);
        $this->db->order_by('order','DESC');
        $this->db->limit(1);
        $data = $this->db->get()->row();
        if(!empty($data)) return $data->order;
        return 0;
    }
    public function getByCode($code){
        $allCategories = $this->_all_category();
        if(!empty($allCategories)) foreach ($allCategories as $key => $item){
            if(strtolower($item->code) === strtolower($code)) return $item;
        }
        return false;
    }
}