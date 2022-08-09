<?php
/**
 * Created by PhpStorm.
 * User: ducto
 * Date: 05/12/2017
 * Time: 4:24 CH
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class STEVEN_Controller extends CI_Controller
{
    public $template_path = '';
    public $template_main = '';
    public $templates_assets = '';
    public $settings = array();
    public $_controller;
    public $_method;
    public $_memcache;
    public $_message = array();

    public function __construct()
    {
        parent::__construct();

        //Load library
        $this->load->library(array('session', 'form_validation', 'user_agent'));
        $this->load->helper(array('cookie', 'data', 'security', 'url','title', 'directory', 'file', 'form', 'datetime', 'language', 'debug', 'text'));
        $this->config->load('languages');
        //Load database
        $this->load->database();

        $this->_controller = $this->router->fetch_class();
        $this->_method = $this->router->fetch_method();

        //load cache driver
        if (CACHE_MODE == TRUE) $this->load->driver('cache', array('adapter' => CACHE_ADAPTER, 'backup' => 'file', 'key_prefix' => CACHE_PREFIX_NAME));

    }

    public function setCache($key, $data, $timeOut = 3600)
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


    public function checkRequestGetAjax()
    {
        if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest'))
            die('Not Allow');;
    }

    public function checkRequestPostAjax()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST'
            || empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest')
        )
            die('Not Allow');
    }

    public function returnJson($data = null)
    {
        if ($this->config->item('csrf_protection') == TRUE) {
            $csrf = [
                'csrf_form' => [
                    'csrf_name' => $this->security->get_csrf_token_name(),
                    'csrf_value' => $this->security->get_csrf_hash()
                ]
            ];
            if (empty($data)) $data = $this->_message;
            $data = array_merge($csrf, (array)$data);
        }
        die(json_encode($data));
    }

    public function toSlug($doc)
    {
        $str = addslashes(html_entity_decode($doc));
        $str = $this->toNormal($str);
        $str = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $str);
        $str = preg_replace("/( )/", '-', $str);
        $str = str_replace('/', '', $str);
        $str = str_replace("\/", '', $str);
        $str = str_replace("+", "", $str);
        $str = str_replace(" - ", "-", $str);
        $str = strtolower($str);
        $str = stripslashes($str);
        return trim($str, '-');
    }

    public function toNormal($str)
    {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        return $str;
    }
    private function getRandomProxy(){
//        $keyCache = "my_proxy_random";
//        $data = $this->getCache($keyCache);
//        if(empty($data)){
            $url = "http://pubproxy.com/api/proxy?api=b2J3ZmlOT0RRQy9jRWlndWo0R0RtQT09&format=json&google=true&speed=25&last_check=120&limit=1";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL,$url);
            $result=curl_exec($ch);
            curl_close($ch);
            $proxy = json_decode($result);
            $tmp = [];
            if(!empty($proxy->data)) foreach ($proxy->data as $item){
                $tmp[] = $item->ipPort;
            }
            $data = $tmp;
            //$this->setCache($keyCache,$data,60*60*5);
//        }
        return $data;
    }

    public function cUrl($url, array $post_data = array(), $delete = false, $verbose = false, $ref_url = false, $cookie_location = false, $return_transfer = true)
    {
        $pointer = curl_init();
//        $proxy = $this->getRandomProxy();
        $proxy = [
            "178.63.239.228:3128","190.187.253.124:3128","69.197.181.202:3128","92.244.99.229:3128"
        ];
        curl_setopt($pointer, CURLOPT_URL, $url);
//        $oneProxy = $proxy[rand(0,count($proxy)-1)];
//        $oneProxy = "162.144.50.155:3838";
//        echo $oneProxy."\n";
//        if(!empty($proxy)) curl_setopt($pointer, CURLOPT_PROXY, $oneProxy);
        curl_setopt($pointer, CURLOPT_TIMEOUT, 10);
        curl_setopt($pointer, CURLOPT_RETURNTRANSFER, $return_transfer);
        curl_setopt($pointer, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.28 Safari/534.10");
        curl_setopt($pointer, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($pointer, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($pointer, CURLOPT_HEADER, false);
        curl_setopt($pointer, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($pointer, CURLOPT_AUTOREFERER, true);

        if ($cookie_location !== false) {
            curl_setopt($pointer, CURLOPT_COOKIEJAR, $cookie_location);
            curl_setopt($pointer, CURLOPT_COOKIEFILE, $cookie_location);
            curl_setopt($pointer, CURLOPT_COOKIE, session_name() . '=' . session_id());
        }

        if ($verbose !== false) {
            $verbose_pointer = fopen($verbose, 'w');
            curl_setopt($pointer, CURLOPT_VERBOSE, true);
            curl_setopt($pointer, CURLOPT_STDERR, $verbose_pointer);
        }

        if ($ref_url !== false) {
            curl_setopt($pointer, CURLOPT_REFERER, $ref_url);
        }

        if (count($post_data) > 0) {
            curl_setopt($pointer, CURLOPT_POST, true);
            curl_setopt($pointer, CURLOPT_POSTFIELDS, $post_data);
        }
        if ($delete !== false) {
            curl_setopt($pointer, CURLOPT_CUSTOMREQUEST, "DELETE");
        }

        $return_val = curl_exec($pointer);

        $http_code = curl_getinfo($pointer, CURLINFO_HTTP_CODE);

        if ($http_code == 404) {
            return false;
        }

        curl_close($pointer);

        unset($pointer);

        return $return_val;
    }

    public function callCURL($url, $data = array(), $type = "GET")
    {
        $resource = curl_init();
        curl_setopt($resource, CURLOPT_URL, $url);
//        curl_setopt($resource, CURLOPT_PROXY, "185.134.23.196:80");
        curl_setopt($resource, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($resource, CURLOPT_SSL_VERIFYPEER, false);

        if ($type == "POST") {
            curl_setopt($resource, CURLOPT_POST, true);
            curl_setopt($resource, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($resource, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($resource, CURLOPT_TIMEOUT, 40);
        $httpcode = curl_getinfo($resource, CURLINFO_HTTP_CODE);
        $result = curl_exec($resource);
        curl_close($resource);
        return $result;
    }

    public function encrypt_decrypt($action, $string)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'steven_secret_key';
        $secret_iv = 'steven_secret_iv';
        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }
}

class Admin_Controller extends STEVEN_Controller
{

    public function __construct()
    {
        parent::__construct();

        //set đường dẫn template
        $this->template_path = 'admin/';
        $this->template_main = $this->template_path . '_layout';
        $this->templates_assets = base_url() . 'public/admin/';

        //Language
        $this->switchLanguage($this->input->get('lang'));

        //tải thư viện
        $this->load->library(array('ion_auth', 'breadcrumbs'));
        //load helper
        $this->load->helper(array('authorization', 'image', 'format', 'link','button'));
        //Load config
        $this->config->load('seo');
        $this->config->load('permission');
        $this->config->load('domain');

        /*$configMinify['assets_dir'] = 'public/admin';
        $configMinify['assets_dir_css'] = 'public/admin/css';
        $configMinify['assets_dir_js'] = 'public/admin/js';
        $configMinify['css_dir'] = 'public/admin/css';
        $configMinify['js_dir'] = 'public/admin/js';
        $this->load->library('minify', $configMinify);
        $this->minify->enabled = FALSE;*/

        $this->check_auth();
    }

    public function check_auth()
    {
        //dd($this->session->userdata());
        if (
            ($this->_controller !== 'user' || ($this->_controller === 'user' && !in_array($this->_method, ['login', 'ajax_login','logout'])))
            && !$this->ion_auth->logged_in()) {
            //chưa đăng nhập thì chuyển về page login
            redirect(site_admin_url('user/login') . '?url=' . urlencode(current_url()), 'refresh');
        } else {
            if ($this->ion_auth->logged_in()) {
                //if ($this->session->admin_group_id === 2) redirect(site_url());
                if ($this->ion_auth->in_group(1) != true) {
                    if (!$this->session->admin_permission) {
                        $this->load->model('Groups_model', 'group');
                        $groupModel = new Groups_model();
                        $group = $groupModel->get_group_by_userid((int)$this->session->userdata('user_id'));
                        $data = $groupModel->getById($group->group_id);
                        if (!empty($data)) {
                            $this->session->admin_permission = json_decode($data->permission, true);
                            $this->session->admin_group_id = (int)$group->group_id;
                        }
                    }
                    if (!in_array($this->_controller, array('dashboard')) && $this->_method !== 'logout') {
                        if (!$this->session->admin_permission[$this->_controller]['view']) {//check quyen view
                            $this->load->view($this->template_main, ['main_content' => $this->load->view($this->template_path.'not_permission', [], TRUE)]);
                        }
                    }
                } else {
                    $this->session->admin_group_id = 1;//ID nhóm admin
                }
            }


        }
    }

    public function switchLanguage($lang_code = "")
    {
        $language_code = !empty($lang_code) ? $lang_code : $this->config->item('language_default');
        $this->session->set_userdata('admin_lang', $language_code);
        $languageFolder = $this->config->item('language_folder')[$language_code];
        $this->session->set_userdata('admin_lang_folder', $languageFolder);
        if (!empty($lang_code)) redirect($_SERVER['HTTP_REFERER']);
    }

    // add log action
    public function addLogAction($action, $note)
    {
        $this->load->model("Log_action_model");
        $logActionModel = new Log_action_model();
        $data['action'] = $action;
        $data['note'] = $note;
        $data['uid'] = $this->session->user_id;
        $logActionModel->save($data);
    }

}

class API_Controller extends yidas\rest\Controller
{
    const RESPONSE_SUCCESS = 200;
    const RESPONSE_EXIST = 201;
    const RESPONSE_REQUEST_ERROR = 400;
    const RESPONSE_LOGIN_ERROR = 401;
    const RESPONSE_LOGIN_DENIED = 403;
    const RESPONSE_NOT_EXIST = 404;
    const RESPONSE_LIMITED = 406;
    const RESPONSE_SERVER_ERROR = 500;

    public function __construct()
    {
        parent::__construct();
        $this->load->library(['form_validation','session']);
        $this->load->helper(array('security','debug','link'));
        $this->load->database();
        //$this->load->driver('cache');
        //log_message('error',"Log API => ".json_encode($_REQUEST));
        //$this->memcache_obj = new Memcache;
        //$this->memcache_obj->connect(MEM_HOST, MEM_PORT);
        $this->form_validation->set_error_delimiters('', '');
        //$this->checkAuth();

        if (CACHE_MODE == TRUE) $this->load->driver('cache', array('adapter' => CACHE_ADAPTER, 'backup' => 'file', 'key_prefix' => CACHE_PREFIX_NAME));

    }

    public function setCache($key, $data, $timeOut = 3600)
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

    private function checkAuth(){
        $arrAuth = [
            ['admin','admin'],
            ['user1','admin365!@#']
        ];
        $arrIP = ['127.0.0.1'];
        $ipClient = $this->input->ip_address();
        if(!in_array($this->request->getAuthCredentialsWithBasic(),$arrAuth) || !in_array($ipClient,$arrIP)){
            $dataJson = $this->pack([], self::RESPONSE_LOGIN_DENIED, 'Not permission !');
            return $this->response->json($dataJson);
        }
    }
    public function toSlug($doc)
    {
        $str = addslashes(html_entity_decode($doc));
        $str = $this->toNormal($str);
        $str = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $str);
        $str = preg_replace("/( )/", '-', $str);
        $str = str_replace('/', '', $str);
        $str = str_replace("\/", '', $str);
        $str = str_replace("+", "", $str);
        $str = strtolower($str);
        $str = stripslashes($str);
        return trim($str, '-');
    }

    public function toNormal($str)
    {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        return $str;
    }
    public function cUrl($url, array $post_data = array(), $delete = false, $verbose = false, $ref_url = false, $cookie_location = false, $return_transfer = true)
    {
        $return_val = false;
        $pointer = curl_init();

        curl_setopt($pointer, CURLOPT_URL, $url);
        curl_setopt($pointer, CURLOPT_TIMEOUT, 40);
        curl_setopt($pointer, CURLOPT_RETURNTRANSFER, $return_transfer);
        curl_setopt($pointer, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.28 Safari/534.10");
        curl_setopt($pointer, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($pointer, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($pointer, CURLOPT_HEADER, false);
        curl_setopt($pointer, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($pointer, CURLOPT_AUTOREFERER, true);

        if ($cookie_location !== false) {
            curl_setopt($pointer, CURLOPT_COOKIEJAR, $cookie_location);
            curl_setopt($pointer, CURLOPT_COOKIEFILE, $cookie_location);
            curl_setopt($pointer, CURLOPT_COOKIE, session_name() . '=' . session_id());
        }

        if ($verbose !== false) {
            $verbose_pointer = fopen($verbose, 'w');
            curl_setopt($pointer, CURLOPT_VERBOSE, true);
            curl_setopt($pointer, CURLOPT_STDERR, $verbose_pointer);
        }

        if ($ref_url !== false) {
            curl_setopt($pointer, CURLOPT_REFERER, $ref_url);
        }

        if (count($post_data) > 0) {
            curl_setopt($pointer, CURLOPT_POST, true);
            curl_setopt($pointer, CURLOPT_POSTFIELDS, $post_data);
        }
        if ($delete !== false) {
            curl_setopt($pointer, CURLOPT_CUSTOMREQUEST, "DELETE");
        }

        $return_val = curl_exec($pointer);

        $http_code = curl_getinfo($pointer, CURLINFO_HTTP_CODE);

        if ($http_code == 404) {
            return false;
        }

        curl_close($pointer);

        unset($pointer);

        return $return_val;
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

    public function deleteCache($key = null)
    {
        if (CACHE_MODE == TRUE) {
            if (!empty($key)) return $this->cache->delete($key);
            else return $this->cache->clean();
        } else return false;
    }
}