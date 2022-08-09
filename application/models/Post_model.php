<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post_model extends STEVEN_Model
{
    public $table_category;
    public $table_post_category;
    public $table_post_tag;

    public function __construct()
    {
        parent::__construct();
        $this->table = "post";
        $this->table_category       = "category";
        $this->table_post_category = "post_category";
        $this->table_post_tag = "post_tag";

        $this->column_order = array("$this->table.id", "$this->table.title", "$this->table.is_status", "$this->table.created_time", "$this->table.updated_time");
        $this->column_search = array('title');
        $this->order_default = array("$this->table.created_time" => 'desc');
    }

    public function _where_custom($args = array())
    {
        parent::_where_custom();
        extract($args);
        if (!empty($category_id)) {
            $this->db->join($this->table_post_category, "$this->table.id = $this->table_post_category.{$this->table}_id", "right");
            $this->db->where_in("$this->table_post_category.category_id", $category_id);
            $this->db->group_by("$this->table.id");
        }

        if(!empty($is_match_id))
            $this->db->where('match_id IS NOT NULL');

        if(!empty($type)) $this->db->where("$this->table.type", $type);
        if(isset($is_robot)) $this->db->where("$this->table.is_robot", $is_robot);
        if(isset($is_hot)) $this->db->where("$this->table.is_hot", $is_hot);
        if(!empty($is_displayed_time)) $this->db->where("UNIX_TIMESTAMP(displayed_time) <=", time());
    }

    public function _where_FE($args = array(), $is_count = false)
    {
        $page = 1;
        $limit = 10;
        extract($args);

        if ($is_count == true) $this->db->select('1');
        $this->db->distinct();
        if (empty($select)) {
            $this->db->select("$this->table.*,
            IF(st_post.displayed_time IS NULL,st_post.created_time,st_post.displayed_time) AS displayed_time");
        } else {
            $this->db->select($select);
        }

        $this->db->from($this->table);

        if (!empty($category_id)) {
            $this->db->select("$this->table_category.id AS category_id, $this->table_category.slug AS category_slug, $this->table_category.title AS category_title");
            $this->db->join($this->table_post_category, "$this->table.id = $this->table_post_category.{$this->table}_id", "right");
            $this->db->join("$this->table_category", "$this->table_post_category.category_id = $this->table_category.id", "right");
            $this->db->where_in("$this->table_post_category.category_id", $category_id);
            $this->db->group_by("$this->table.id");
        }

        if (!empty($is_primary_category)) {
            $this->db->select("$this->table_category.id as term_id, $this->table_category.slug as term_slug, $this->table_category.title as term_name");
            $this->db->join("$this->table_post_category", "$this->table.id = $this->table_post_category.post_id", "right");
            $this->db->join("$this->table_category", "$this->table_post_category.category_id = $this->table_category.id", "right");
            $this->db->where("$this->table_post_category.is_primary", true);
            $this->db->group_by("$this->table.id");
        }


        if (!empty($show_user)) {
            $this->db->select('username,fullname,email');
            $this->db->join("users", "$this->table.user_id = users.id", "left");
        }
        if (!empty($tag_id)) {
            $this->db->join($this->table_post_tag, "$this->table.id = $this->table_post_tag.post_id", "right");
            $this->db->where_in("$this->table_post_tag.tag_id", $tag_id);
        }
        if (!empty($post__not_in) && (is_array($post__not_in))) {
            $this->db->where_not_in("$this->table.id", $post__not_in);
        }

        $this->db->where("$this->table.is_status", true);

        //Check hẹn giờ mặc định và check time lấy bài
        if (!empty($displayed_time_less)) $this->db->where("UNIX_TIMESTAMP(displayed_time) <=", strtotime($displayed_time_less));
        else $this->db->where("UNIX_TIMESTAMP(displayed_time) <=", time());

        if (!empty($displayed_time_elder)) $this->db->where("UNIX_TIMESTAMP(displayed_time) >=", strtotime($displayed_time_elder));
        if(!empty($is_start_time)) {
            $this->db->where("start_time + INTERVAL 90 MINUTE >= NOW()", FALSE, FALSE);
        }
        if (isset($is_robot))
            $this->db->where("$this->table.is_robot", $is_robot);

        if (isset($is_featured))
            $this->db->where("$this->table.is_featured", $is_featured);

        if (isset($is_hot))
            $this->db->where("$this->table.is_hot", $is_hot);

        if (!empty($keyword))
            $this->db->like("$this->table.title", $keyword);

        if (!empty($type))
            $this->db->where("$this->table.type", $type);

        if (!empty($in))
            $this->db->where_in("$this->table.id", $in);

        if (!empty($or_in))
            $this->db->or_where_in("$this->table.id", $or_in);

        if (!empty($not_in))
            $this->db->where_not_in("$this->table.id", $not_in);

        if (!empty($or_not_in))
            $this->db->or_where_not_in("$this->table.id", $or_not_in);

        if ($is_count == false) {
            if (empty($order)) $order = array("$this->table.displayed_time" => "DESC");
            foreach ($order as $k => $v)
                $this->db->order_by($k, $v);

            if(empty($page)) $page = 1;
            $offset = ($page - 1) * $limit;
            $this->db->limit($limit, $offset);
        }
    }

    public function getTotalFE($args = [], $update_cache = false)
    {
        $keyCache = $this->table . "_getTotal_" . md5(json_encode($args));
        $data = $this->getCache($keyCache);
        if ($data === false || $update_cache == true) {
            $this->_where_FE($args, true);
            $this->db->group_by("$this->table.id");
            $query = $this->db->get();
            $data =  $query->num_rows();
            $this->setCache($keyCache, $data);
        }
        return $data;
    }

    public function getDataFE($args = array(), $update_cache = false)
    {
        $keyCache = $this->table . "_getData_" . md5(json_encode($args));
        $data = $this->getCache($keyCache);
        if ($data === false || $update_cache == true) {
            $this->_where_FE($args);
            $query = $this->db->get();
            $data = $query->result();
            $this->setCache($keyCache, $data);
        }
        return $data;
    }

    public function getBySlug($slug, $update_cache = false)
    {
        $keyCache = $this->table . "_getBySlug_" . md5($slug);
        $data = $this->getCache($keyCache);
        if ($data === false || $update_cache == true) {
            $this->db->from($this->table);
            $this->db->where("$this->table.slug", $slug);
            $data = $this->db->get()->row();
            $this->setCache($keyCache, $data);
        }
        return $data;
    }

    public function getByIdCached($id, $update_cache = false)
    {
        $keyCache = $this->table . "_getByIdCached_" . $id;
        $data = $this->getCache($keyCache);
        if ($data === false || $update_cache == true) {
            $this->db->from($this->table);
            $this->db->where("$this->table.id", $id);
            $data = $this->db->get()->row();
            $this->setCache($keyCache, $data);
        }
        return $data;
    }

    //lấy bài viết theo slug trong bảng st_category
    public function getPostByTaxonomy($args = array() ){
        $default_args = [
            'limit' => 10,
            'offset' => 0,
            'order_by' => 'displayed_time',
            'order' => 'DESC'
        ];

        extract($args);
        $this->db->select("$this->table.*,
        IF(st_post.displayed_time IS NULL, `st_post`.`created_time`, st_post.displayed_time) AS displayed_time,
        $this->table_category.id as term_id, $this->table_category.slug as term_slug, $this->table_category.title as term_name");
        /**@taxonomy: post, post_tag**/
        $this->db->join("$this->table_post_category", "$this->table.id = $this->table_post_category.post_id");
        $this->db->join("$this->table_category", "$this->table_post_category.category_id = $this->table_category.id");
        $this->db->where("$this->table_category.slug", $slug);
        $this->db->where("$this->table_category.type", $taxonomy);
        $this->db->where("UNIX_TIMESTAMP(displayed_time) <=", time());

        if (!empty($limit)) $default_args['limit'] = $limit;
        if (!empty($offset)) $default_args['offset'] = $offset;
        if (!empty($order_by)) $default_args['order_by'] = $order_by;
        if (!empty($order)) $default_args['order'] = $order;

        $this->db->group_by('post_id');
        $this->db->order_by( $default_args['order_by'], $default_args['order']);
        $data = $this->db->get($this->table, $default_args['limit'], $default_args['offset'])->result();
        return $data;
    }
    public function getSelect2Category($id, $is_primary = false) {
        $this->db->select("$this->table_post_category.category_id AS id, title AS text");
        $this->db->from($this->table_post_category);
        $this->db->join($this->table_category, "$this->table_post_category.category_id = $this->table_category.id","LEFT");
        $this->db->where($this->table_post_category . ".post_id", $id);
        if(!empty($is_primary)) $this->db->where("$this->table_post_category.is_primary", $is_primary);
        $data = $this->db->get()->result();
        return $data;
    }
    public function getSelect2Tag($id)
    {
        $this->db->select("$this->table_post_tag.tag_id AS id, title AS text");
        $this->db->from($this->table_post_tag);
        $this->db->join("category", "$this->table_post_tag.tag_id = category.id");
        $this->db->where($this->table_post_tag . ".post_id", $id);
        return $this->db->get()->result();
    }
    public function getChild($parent_id, $updateCache = false){
        $key = "{$this->table}_getChild_$parent_id";
        $data = $this->getCache($key);
        if($data === false || $updateCache == true){
            $data = array();
            if(!empty($this->_all_category())) foreach ($this->_all_category() as $key => $item){
                if($item->parent_id == (int) $parent_id){
                    $data[] = $item;
                }
            }
            $this->setCache($key,$data);
        }
        return $data;
    }

}