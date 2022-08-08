<?php
defined('BASEPATH') or exit('No direct script access allowed');

class STEVEN_Model extends CI_Model
{
    public $table;
    public $primary_key;
    public $column_order;
    public $column_search;
    public $order_default;

    public $_controller;
    public $_method;
    public $_dbprefix;

    public $_args = array();
    public $where_custom;

    public function __construct()
    {
        parent::__construct();
        $this->_dbprefix = $this->db->dbprefix;
        $this->_controller = $this->router->fetch_class();
        $this->_method = $this->router->fetch_method();

        $this->table = strtolower(str_replace('_model', '', get_Class($this)));
        $this->primary_key = "$this->table.id";
        $this->column_order = array("$this->table.id", "$this->table.id", "title", "$this->table.is_status", "$this->table.updated_time", "$this->table.created_time");
        $this->column_search = array("title");
        $this->order_default = array("$this->table.created_time" => "DESC");

        $this->_controller = $this->router->fetch_class();
        $this->_method = $this->router->fetch_method();

        $this->load->library('console');

        //load cache driver
        if (CACHE_MODE == TRUE) $this->load->driver('cache', array('adapter' => CACHE_ADAPTER, 'backup' => 'file', 'key_prefix' => CACHE_PREFIX_NAME));

    }

    public function setCache($key, $data, $timeOut = 3600)
    {
        if (CACHE_MODE == TRUE) {
            if($data === null) $timeOut = 60*1;
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

    public function callAPI($method, $url, $data){
        $curl = curl_init();

        switch ($method){
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'APIKEY: 111111111111111111111',
            'Content-Type: application/json',
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        // EXECUTE:
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            log_message('error', "cURL Error #:" . $err);
            return false;
        } else {
            return json_decode($response);
        }
    }

    public function getDataApi($url, $params, $method = 'GET'){
        $url = API_DATACENTER."api/v1/$url";
        if(DEBUG_MODE == TRUE){
            $this->console->info("API: $url");
            $this->console->warning("Warning Cache from getDataAPIv2: $url => Check $this->_controller / $this->_method");
        }
        $data = $this->callAPI($method,$url,$params);
        return !empty($data->data) ? $data->data : null;
    }

    public function getDataAPIXoso($urlAPI, $postField = [])
    {
        $data = json_encode($postField);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $urlAPI,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            log_message('error', "cURL Error #:" . $err);
            return false;
        } else {
            return json_decode($response);
        }
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
            }
            
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
        if ($typeQuery === 'count' && empty($search)) $this->db->select('1');
        $this->db->from($this->table);


        if (isset($is_featured))
            $this->db->where("$this->table.is_featured", $is_featured);

        if (isset($is_status))
            $this->db->where("$this->table.is_status", $is_status);

        if (!empty($keyword))
            $this->db->like("$this->table.title", $keyword);

        if (!empty($search)){
            foreach ($this->column_search as $i => $item) {
                if ($i == 0) {
                    $this->db->group_start();
                    $this->db->like($item, $search, 'both', false);
                    $this->db->or_like($item, $search, 'both', false);
                } else {
                    $this->db->or_like($item, $search, 'both', false);
                }

                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
        }

        if (!empty($id))
            $this->db->where("$this->table.id", $id);

        if (!empty($post_id))
            $this->db->where("$this->table.post_id", $post_id);

        if (!empty($slug)){
            $this->db->where('slug',$slug);
        }

        if (!empty($in))
            $this->db->where_in("$this->table.id", $in);

        if (!empty($or_in))
            $this->db->or_where_in("$this->table.id", $or_in);

        if (!empty($not_in))
            $this->db->where_not_in("$this->table.id", $not_in);

        if (!empty($or_not_in))
            $this->db->or_where_not_in("$this->table.id", $or_not_in);

        if (!empty($created_time))
            $this->db->like("$this->table.created_time", $created_time);

        if (!empty($author))
            $this->db->where("$this->table.author", $author);

        if (!empty($offset))
            $this->db->offset($offset);


        $this->_get_datatables_query();

        if ($typeQuery == false) {
            if (!empty($order) && is_array($order)) {
                foreach ($order as $k => $v)
                    $this->db->order_by($k, $v);
            }
            $offset = ($page - 1) * $limit;
            $this->db->limit($limit, $offset);
        }
    }

    public function _where_custom($args = array()) {
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

    public function getDataByField($field, $value, $select = '*')
    {
        $key = 'getDataByField_'.$field.$value.$this->table;
        $data = $this->getCache($key);
        if(empty($data)){
            $this->db->select($select);
            $this->db->from($this->table);
            $this->db->where("$this->table.$field", $value);
            $data = $this->db->get()->row();
            $this->setCache($key,$data,60*60);
        }
        return $data;
    }

    public function getByField($field, $value, $select = '*')
    {
        $this->db->select($select);
        $this->db->from($this->table);
        $this->db->where("$this->table.$field", $value);
        $query = $this->db->get()->row();
        return $query;
    }

    public function getById($id, $select = '*') {
        $this->db->select($select);
        $this->db->from($this->table);
        $this->db->where("$this->table.id", $id);
        $query = $this->db->get()->row();
        return $query;
    }



    public function getDataBySlug($slug, $select = '*') {

        $this->db->select($select);
        $this->db->from($this->table);
        $this->db->where("$this->table.slug", $slug);
        $this->db->where("$this->table.is_status", 1);
        $query = $this->db->get()->row();
        return $query;
    }


    public function getDataPreview ($id) {
        $this->db->select('*');
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
        if (is_array($ids)) $this->db->where_in("$this->table.id", $ids);
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
        $this->db->order_by('id','desc');

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

}
