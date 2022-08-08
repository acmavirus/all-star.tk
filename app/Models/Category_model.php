<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category_model extends STEVEN_Model
{
    public $_list_category_child;
    public $_list_category_parent;
    public $_list_category_child_id;
    public $category_tree;
    private $table_post_cat;
    private $table_post_tag;

    public function __construct()
    {
        parent::__construct();
        $this->table = "category";
        $this->table_post_cat = "post_category";
        $this->table_post_tag = "post_tag";
        $this->column_order = array("$this->table.id", "title", "$this->table.is_status", "$this->table.created_time", "$this->table.updated_time");
        $this->column_search = array("title", "id");
        $this->order_default = array("$this->table.id" => "ASC");
    }

    public function _where_custom($args = array())
    {
        parent::_where_custom();
        extract($args);
        if (!empty($type)) $this->db->where("$this->table.type", $type);
        if (isset($parent_id)) $this->db->where("$this->table.parent_id", $parent_id);
        if (isset($is_robot)) $this->db->where("$this->table.is_robot", $is_robot);
    }

    public function _where_FE($args = array(), $is_count = false)
    {
        $page = 1;
        $limit = 10;
        extract($args);
        if ($is_count == true) $this->db->select('1');
        $this->db->distinct();
        if (empty($select)) {
            $this->db->select("$this->table.*");
        } else {
            $this->db->select($select);
        }

        $this->db->from($this->table);

        if (!empty($slug)) $this->db->where("$this->table.slug", $slug);
        if (!empty($type)) $this->db->where("$this->table.type", $type);
        if (!empty($parent_id)) $this->db->where("$this->table.parent_id", $parent_id);
        if (!empty($code)) $this->db->where("$this->table.code", $code);
        if (!empty($weekday)) $this->db->like("$this->table.weekday", $weekday);


        $offset = ($page - 1) * ($limit - 1);
        $this->db->limit($limit, $offset);
    }

    public function getDataFE($args = array(), $timeCache = 3600, $update_cache = false)
    {
        $keyCache = $this->table . "_getData_" . md5(json_encode($args));
        $data = $this->getCache($keyCache);
        if ($data === false || $update_cache == true) {
            $this->_where_FE($args);
            $query = $this->db->get();
            $data = $query->result();
            $this->setCache($keyCache, $data, $timeCache);
        }
        return $data;
    }

    public function _all_category($type = '', $updateCache = false)
    {
        $key = 'all_category_' . $type;
        $data = $this->getCache($key);
        if (empty($data) || $updateCache == true) {
            $this->db->select("id,code,title,parent_id,type,weekday,is_status,is_featured,slug,description");
            $this->db->from($this->table);
            $this->db->where("$this->table.is_status", 1);
            $this->db->order_by("$this->table.order", "ASC");
            if (!empty($type)) $this->db->where("$this->table.type", $type);
            $data = $this->db->get()->result();
            $this->setCache($key, $data, 60 * 60 * 2);
        }
        return $data;
    }


    public function getListRecursive($type, $parent_id = 0)
    {

        $all = $this->_all_category($type);
        $data = [];
        if (!empty($all)) foreach ($all as $key => $item) {
            if ($item->parent_id == $parent_id) {
                $tmp = $item;
                $tmp->list_child = $this->getListChild($all, $item->id);
                $data[] = $tmp;
            }
        }
        return $data;
    }
    public function getSpinTime(){
        $keyCache = "getSpinTime";
        $data = $this->getCache($keyCache);
        if (empty($data)){
            $dataCraw = file_get_contents(API_DATACENTER.'api/v1/result/getspintime');
            if (!empty($dataCraw)){
                $data = json_decode($dataCraw, true);
                $this->setCache($keyCache, $data, 15);
            }
        }

        return $data;
    }
    /*Đệ quy lấy record parent id*/
    public function _recursive_one_parent($id)
    {
        $all = $this->_all_category();
        if (!empty($all)) foreach ($all as $item) {
            if ($item->id == $id) {
                if ($item->parent_id == 0) return $item;
                else return $this->_recursive_one_parent($item->parent_id);
            }
        }
    }
    /*Đệ quy lấy record parent id*/

    /*Đệ quy lấy array list category con*/
    public function _recursive_child($all, $parentId = 0)
    {
        if (!empty($all)) foreach ($all as $key => $item) {
            if ($item->parent_id == $parentId) {
                $this->_list_category_child[] = $item;
                unset($all[$key]);
                $this->_recursive_child($all, $item->id);
            }
        }
    }

    /*Đệ quy lấy maps các ID cha*/
    public function _recursive_parent($all, $cateId = 0)
    {
        if (!empty($all)) foreach ($all as $key => $item) {
            if ($item->id == $cateId) {
                $this->_list_category_parent[] = $item;
                unset($all[$key]);
                $this->_recursive_parent($all, $item->parent_id);
            }
        }
    }

    /*Đệ quy lấy array list category con*/

    public function getListChild($all, $parentId = 0)
    {
        $data = array();
        if (!empty($all)) foreach ($all as $key => $item) {
            if ($item->parent_id == $parentId) {
                $data[] = $item;
            }
        }
        return $data;
    }
    /*Đệ quy lấy array list category  con*/

    /*Đệ quy lấy list các ID*/
    public function _recursive_child_id($all, $parentId = 0)
    {
        $this->_list_category_child_id[] = (int)$parentId;
        if (!empty($all)) foreach ($all as $key => $item) {
            if ($item->parent_id == $parentId) {
                $this->_list_category_child_id[] = (int)$item->id;
                unset($all[$key]);
                $this->_recursive_child_id($all, (int)$item->id);
            }
            $this->_list_category_child_id = array_unique($this->_list_category_child_id);
        }
    }

    /*Đệ quy lấy list các ID*/


    public function _queue_select($categories, $parent_id = 0, $char = '')
    {
        if (!empty($categories)) foreach ($categories as $key => $item) {
            if ($item->parent_id == $parent_id) {
                $tmp['title'] = $parent_id ? '  |--' . $char . $item->title : $char . $item->title;
                $tmp['id'] = $item->id;
                $tmp['thumbnail'] = $item->thumbnail;
                $this->category_tree[] = $tmp;
                unset($categories[$key]);
                $this->_queue_select($categories, $item->id, $char . '--');
            }
        }
    }

    /*Đệ quy lấy maps các ID cha*/

    public function getByIdCached($id, $updateCache = false)
    {
        $keyCache = $this->table . "_getByIdCached-$id";
        $data = $this->getCache($keyCache);
        if ($data === false || $updateCache == true) {
            $this->db->from($this->table);
            $this->db->where('id', $id);
            $data = $this->db->get()->row();
            $this->setCache($keyCache, $data);
        }
        return $data;
    }


    public function getBySlugCached($slug, $updateCache = false)
    {
        $keyCache = $this->table . "_getBySlugCached-" . md5($slug);
        $data = $this->getCache($keyCache);
        if ($data === false || $updateCache == true) {
            $this->db->from($this->table);
            $this->db->where('slug', $slug);
            $data = $this->db->get()->row();
            $this->setCache($keyCache, $data);
        }
        return $data;
    }

    public function getDataByCategoryType($allCategories, $type)
    {
        $dataType = [];
        if (!empty($allCategories)) foreach ($allCategories as $key => $item) {
            if ($item->type === $type) $dataType[] = $item;
        }
        return $dataType;
    }

    public function getAllCategoryByType($type, $parent_id = 0)
    {
        $keyCache = "getAllCategoryByType-$type-$parent_id";
        $data = $this->getCache($keyCache);
        if (empty($data)) {
            $this->db->from($this->table);
            $this->db->where([
                'type' => $type,
                'parent_id' => $parent_id
            ]);
            $data = $this->db->get()->result();
            $this->setCache($keyCache, $data);
        }
        return $data;
    }

    /*Lấy category cha*/
    public function getOneParent($id)
    {
        $params = [
            'lang_code' => $this->session->public_lang_code,
            'parent_id' => $id,
            'limit' => 1
        ];
        $data = $this->getData($params);
        return !empty($data) ? $data[0] : null;
    }

    public function getCategoryChild($id)
    {
        $key = "getCategoryChild_{$id}";
        $data = $this->getCache($key);
        if (empty($data)) {
            $this->db->from($this->table);
            $this->db->where([
                'parent_id' => $id,
                'is_status' => 1
            ]);
            $this->db->order_by('sort', 'asc');
            $data = $this->db->get()->result();
            $this->setCache($key, $data, 60 * 60);
        }
        return $data;
    }


    public function getDataGroupBy()
    {
        $this->db->select('type');
        $this->db->from($this->table);
        $this->db->group_by('type');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getCatByPostId($post_id, $timeCache = 120, $update_cache = false)
    {
        $keyCache = $this->table . "_getCatByPostId_" . $post_id;
        $data = $this->getCache($keyCache);
        if (empty($data) || $update_cache == true) {
            $this->db->select("$this->table.id, $this->table.title, $this->table.slug, $this->table.parent_id");
            $this->db->from($this->table);
            $this->db->join($this->table_post_cat, "$this->table.id = $this->table_post_cat.category_id");
            $this->db->where("$this->table_post_cat.post_id", $post_id);
            $data = $this->db->get()->row();
            $this->setCache($keyCache, $data, $timeCache);
        }

        return $data;
    }

    public function getTagByPostId($post_id, $update_cache = false)
    {
        $keyCache = $this->table . "_getTagByPostId_" . $post_id;
        $data = $this->getCache($keyCache);
        if (empty($data) || $update_cache == true) {
            $this->db->select("$this->table.id, $this->table.title, $this->table.slug");
            $this->db->from($this->table_post_tag);
            $this->db->join($this->table, "$this->table.id = $this->table_post_tag.tag_id", "left");
            $this->db->where("$this->table_post_tag.post_id", $post_id);
            $data = $this->db->get()->result();
            $this->setCache($keyCache, $data);
        }

        return $data;
    }

    public function getByCode($code, $updateCache = false)
    {
        $code = strtoupper($code);
        $key = "get_by_code_$code";
        $data = $this->getCache($key);
        if (empty($data) || $updateCache == true) {
            $this->db->select("*");
            $this->db->from($this->table);
            $this->db->where("$this->table.code", $code);
            $query = $this->db->get();
            $data = $query->row();
            $this->setCache($key, $data);
        }
        return $data;
    }
}
