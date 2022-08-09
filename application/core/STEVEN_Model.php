<?php
/**
 * Created by PhpStorm.
 * User: ducto
 * Date: 05/12/2017
 * Time: 4:24 CH
 */
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Smart model.
 */
class STEVEN_Model extends CI_Model
{
    public $table;
    public $primary_key;
    public $column_order;
    public $column_search;
    public $order_default;

    public $_dbprefix;

    public $_args = array();
    public $where_custom;

    public function __construct()
    {
        parent::__construct();
        $this->_dbprefix = $this->db->dbprefix;

        $this->table = strtolower(str_replace('_model', '', get_Class($this)));
        $this->primary_key = "$this->table.id";
        $this->column_order = array("$this->table.id", "$this->table.id", "title", "$this->table.is_status", "$this->table.updated_time", "$this->table.created_time");
        $this->column_search = array("title");
        $this->order_default = array("$this->table.created_time" => "DESC");


        //load cache driver
        if (CACHE_MODE == TRUE) $this->load->driver('cache', array('adapter' => CACHE_ADAPTER, 'backup' => 'file', 'key_prefix' => CACHE_PREFIX_NAME));

    }

    public function setCache($key, $data, $timeOut = 60)
    {
        if (CACHE_MODE == TRUE) {
            $this->cache->save($key, $data, $timeOut);
        }
    }

    public function getCache($key)
    {
        if (CACHE_MODE == TRUE) {
            return $this->cache->get($key);
        } else return false;
    }

    public function deleteCache($key = null)
    {
        if (CACHE_MODE == TRUE) {
            if (!empty($key)) return $this->cache->delete($key);
            else return $this->cache->clean();
        } else return false;
    }

    /*Hàm xử lý các tham số truyền từ Datatables Jquery*/
    public function _get_datatables_query()
    {
        $query = $this->input->post('query');
        if (!empty($query['generalSearch'])) {
            $keyword = $query['generalSearch'];
            $fieldSearch = '';
            foreach ($this->column_search as $i => $item) {
                if ($i == 0) {
                    $this->db->group_start();
                    $this->db->like($item, $keyword, 'both', false);
                    $this->db->or_like($item, $keyword, 'both', false);
                } else {
                    $this->db->or_like($item, $keyword, 'both', false);
                }

                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();

                /*if($i != 0) $fieldSearch .= ',';
                $fieldSearch .= $this->_dbprefix.$item;*/
            }
            //$this->db->select('MATCH ('.$fieldSearch.') AGAINST ('.$this->db->escape($keyword).' IN BOOLEAN MODE) AS score_search');
            //$this->db->or_where('MATCH ('.$fieldSearch.') AGAINST ('.$this->db->escape($keyword).' IN BOOLEAN MODE)', NULL, FALSE);
            //$this->db->order_by('score_search','DESC');
        }

        if ($this->input->post('sort')) {
            $sort = $this->input->post('sort');
            $this->db->order_by($sort['field'], $sort['sort']);
        }
    }

    public function _where_before($args = array(), $typeQuery = false)
    {
        $page = 1;
        $limit = 10;
        extract($args);
        //$this->db->distinct();
        if ($typeQuery === 'count' && empty($search)) $this->db->select('1');
        $this->db->from($this->table);

        if (isset($is_featured))
            $this->db->where("$this->table.is_featured", $is_featured);

        if (isset($is_status))
            $this->db->where("$this->table.is_status", $is_status);

        if (!empty($id))
            $this->db->where("$this->table.id", $id);

        if (!empty($in))
            $this->db->where_in("$this->table.id", $in);

        if (!empty($or_in))
            $this->db->or_where_in("$this->table.id", $or_in);

        if (!empty($not_in))
            $this->db->where_not_in("$this->table.id", $not_in);

        if (!empty($or_not_in))
            $this->db->or_where_not_in("$this->table.id", $or_not_in);


        $this->_get_datatables_query();

        if (!empty($search)) {
            //$this->db->select('MATCH (title) AGAINST (' . $this->db->escape($search) . ' IN NATURAL LANGUAGE MODE) AS score_search');
            $this->db->group_start();
            if(!empty($this->column_search)) foreach ($this->column_search as $k => $field){
                if($k == 0) $this->db->like($field, $search);
                else $this->db->or_like($field, $search);
            }
            $this->db->group_end();
            //$this->db->or_like("title", $search);
            //$this->db->where('MATCH (title) AGAINST (' . $this->db->escape($search) . ' IN NATURAL LANGUAGE MODE)', NULL, FALSE);
            //$this->db->group_end();
            //$this->db->order_by('score_search', 'DESC');
        }

        if ($typeQuery == false) {
            if (!empty($order) && is_array($order)) {
                foreach ($order as $k => $v)
                    $this->db->order_by($k, $v);
            }
            if($limit !== false){
                $offset = ($page - 1) * $limit;
                $this->db->limit($limit, $offset);
            }
        }
    }

    public function _where_custom($args = array())
    {
    }

    private function _where($args = array(), $typeQuery = null)
    {
        $this->_where_before($args, $typeQuery);
        $this->_where_custom($args);
    }

    /*
     * Lấy tất cả dữ liệu
     * */
    public function getAll($lang_code = null, $is_status = null)
    {
        $this->db->from($this->table);
        if (!empty($is_status)) $this->db->where("$this->table.is_status", $is_status);
        $query = $this->db->get();
        return $query->result();
    }


    /*
     * Đếm tổng số bản ghi
     * */
    public function getTotalAll($table = '')
    {
        if (empty($table)) $table = $this->table;
        $this->db->select('1');
        $this->db->from($table);
        return $this->db->count_all_results();
    }

    public function getTotal($args = [])
    {
        $args = array_merge(['select' => 1], $args);
        $this->_where($args, "count");
        $this->db->group_by("$this->table.id");
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function getData($args = array(), $returnType = "object")
    {
        $this->_where($args);
        $query = $this->db->get();
        if ($returnType !== "object") return $query->result_array(); //Check kiểu data trả về
        else return $query->result();
    }

    /*
     * Lấy dữ liệu một hàng ra
     * Truyền vào id
     * */
    public function getByField($field, $value, $select = '*')
    {
        $this->db->select($select);
        $this->db->from($this->table);
        $this->db->where("$this->table.$field", $value);
        $query = $this->db->get();
        return $query->row();
    }

    public function getById($id, $select = '*', $lang_code = null)
    {

        $this->db->select($select);
        $this->db->from($this->table);
        $this->db->where("$this->table.id", $id);

        $query = $this->db->get();
        return $query->row();
    }


    public function getPrevById($id, $select = '*', $lang_code = null)
    {

        $this->db->select($select);
        $this->db->from($this->table);

        $this->db->where("$this->table.id <", $id);
        $this->db->where("$this->table.is_status", 1);
        $this->db->order_by("$this->table.id", 'DESC');
        $query = $this->db->get();
        return $query->row();

    }

    public function getNextById($id, $select = '*', $lang_code = null)
    {

        $this->db->select($select);
        $this->db->from($this->table);
        $this->db->where("$this->table.id >", $id);
        $this->db->where("$this->table.is_status", 1);
        $this->db->order_by("$this->table.id", 'ASC');
        $query = $this->db->get();
        return $query->row();
    }


    public function checkExistByField($field, $value, $tablename = '')
    {
        $this->db->select('1');
        if ($tablename == '') {
            $tablename = $this->table;
        }
        $this->db->from($tablename);
        $this->db->where($field, $value);
        $query = $this->db->get();
        return $query->num_rows() > 0 ? true : false;
    }

    public function getSelect2($ids)
    {
        $this->db->select("$this->table.id, title AS text");
        $this->db->from($this->table);
        if (is_array($ids)) {
            $this->db->where_in("$this->table.id", $ids);
            $this->db->order_by("FIELD(id,".join(',',$ids).")");
        }
        else $this->db->where("$this->table.id", $ids);
        $query = $this->db->get();
        return $query->result();
    }


    public function save($data, $tablename = '')
    {
        if ($tablename == '') {
            $tablename = $this->table;
        }

        if (!$this->db->insert($tablename, $data)) {
            log_message('info', json_encode($data));
            log_message('error', json_encode($this->db->error()));
            return false;
        }
        $id = $this->db->insert_id();
        return !empty($id) ? $id : $this->db->affected_rows();
    }


    public function search($conditions = null, $limit = 500, $offset = 0, $tablename = '')
    {
        if ($tablename == '') {
            $tablename = $this->table;
        }
        if ($conditions != null) {
            $this->db->where($conditions);
        }

        $query = $this->db->get($tablename, $limit, $offset);

        return $query->result();
    }

    public function single($conditions, $tablename = '')
    {
        if ($tablename == '') {
            $tablename = $this->table;
        }
        $this->db->where($conditions);

        return $this->db->get($tablename)->row();
    }

    public function getDataAll($conditions = [], $tablename = '', $select = '*')
    {
        if (!empty($select)) $this->db->select($select);
        if ($tablename == '') {
            $tablename = $this->table;
        }
        if (!empty($conditions)) $this->db->where($conditions);

        return $this->db->get($tablename)->result();
    }

    public function insert($data, $tablename = '')
    {
        if ($tablename == '') {
            $tablename = $this->table;
        }
        $this->db->insert($tablename, $data);

        return $this->db->affected_rows();
    }


    public function insertMultiple($data, $tablename = '')
    {
        if ($tablename == '') {
            $tablename = $this->table;
        }
        $this->db->insert_batch($tablename, $data);

        return $this->db->affected_rows();
    }

    public function insertOnUpdate($data, $tablename = '')
    {
        if ($tablename == '') {
            $tablename = $this->table;
        }
        $data_update = [];
        if (!empty($data)) foreach ($data as $k => $val) {
            $data_update[] = $k . " = '" . addslashes($val) . "'";
        }

        $queryInsertOnUpdate = $this->db->insert_string($tablename, $data) . " ON DUPLICATE KEY UPDATE " . implode(', ', $data_update);
        if (!$this->db->query($queryInsertOnUpdate)) {
            log_message('info', json_encode($data));
            log_message('error', json_encode($this->db->error()));
            return false;
        }

        return true;
    }

    public function update($conditions, $data, $tablename = '')
    {
        if ($tablename == '') {
            $tablename = $this->table;
        }

        if (!$this->db->update($tablename, $data, $conditions)) {
            log_message('info', json_encode($conditions));
            log_message('info', json_encode($data));
            log_message('error', json_encode($this->db->error()));
            return false;
        }

        return true;
    }


    public function delete($conditions, $tablename = '')
    {
        if ($tablename == '') {
            $tablename = $this->table;
        }
        $this->db->where($conditions);
        if (!$this->db->delete($tablename)) {
            log_message('info', json_encode($conditions));
            log_message('info', json_encode($tablename));
            log_message('error', json_encode($this->db->error()));
        }

        return $this->db->affected_rows();
    }

    public function deleteArray($field, $value = array(), $tablename = '')
    {
        if ($tablename == '') {
            $tablename = $this->table;
        }
        $this->db->where_in($field, $value);
        if (!$this->db->delete($tablename)) {
            log_message('info', json_encode($tablename));
            log_message('error', json_encode($this->db->error()));
        }

        return $this->db->affected_rows();
    }

    public function count($conditions = null, $tablename = '')
    {
        if ($conditions != null) {
            $this->db->where($conditions);
        }

        if ($tablename == '') {
            $tablename = $this->table;
        }

        $this->db->select('1');
        return $this->db->get($tablename)->num_rows();
    }

    function array_group_by(array $data, $key) : array
    {
        if (!is_string($key) && !is_int($key) && !is_float($key) && !is_callable($key)) {
            trigger_error('array_group_by(): The key should be a string, an integer, a float, or a function', E_USER_ERROR);
        }

        $isFunction = !is_string($key) && is_callable($key);

        // Load the new array, splitting by the target key
        $grouped = [];
        foreach ($data as $value) {
            $groupKey = null;

            if ($isFunction) {
                $groupKey = $key($value);
            } else if (is_object($value)) {
                $groupKey = $value->{$key};
            } else {
                $groupKey = $value[$key];
            }

            $grouped[$groupKey][] = $value;
        }

        // Recursively build a nested grouping if more parameters are supplied
        // Each grouped array value is grouped according to the next sequential key
        if (func_num_args() > 2) {
            $args = func_get_args();

            foreach ($grouped as $groupKey => $value) {
                $params = array_merge([$value], array_slice($args, 2, func_num_args()));
                $grouped[$groupKey] = call_user_func_array(array($this, "array_group_by"), $params);
            }
        }

        return $grouped;
    }
}
