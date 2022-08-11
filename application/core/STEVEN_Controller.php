<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class STEVEN_Controller extends CI_Controller
{
    public $template_path = '';
    public $template_main = '';
    public $templates_assets = '';
    public $template_include = '';
    public $_controller;
    public $_method;
    public $_memcache;
    public $_settings;
    public $_settings_social;
    public $_message = array();

    public function __construct()
    {
        parent::__construct();

        //Load library
        $this->load->library(array('session', 'form_validation', 'user_agent'));
        $this->load->helper(array('cookie', 'data', 'security', 'url', 'directory', 'file', 'form', 'datetime', 'debug', 'text'));
        //$this->config->load('languages');
        //Load database
        $this->load->database();

        $this->_controller = $this->router->fetch_class();
        $this->_method = $this->router->fetch_method();
        //load cache driver
        if (CACHE_MODE == TRUE) $this->load->driver('cache', array('adapter' => CACHE_ADAPTER, 'backup' => 'file', 'key_prefix' => CACHE_PREFIX_NAME));
    }

    public function setCacheFile($timeOut = 1){
        if (CACHE_FILE_MODE == TRUE) {
            $this->output->cache($timeOut);
        }
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


    public function delete_cache_file($url = ''){
        if (empty($url)){
            $this->load->helper('file');
            $url = $this->input->get('url');
        }

        if(!empty($url)){
            $uri = str_replace(base_url(),'/',$url);
            $this->output->delete_cache($uri);
        }else{
            delete_files(FCPATH . 'application' . DIRECTORY_SEPARATOR . 'cache');
        }

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
        $str = str_replace('/', '-', $str);
        $str = str_replace("\/", '', $str);
        $str = str_replace("+", "", $str);
        $str = str_replace(" - ", "-", $str);
        $str = str_replace("---", "-", $str);
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
    public function array_group_by(array $arr, callable $key_selector)
    {
        $result = array();
        foreach ($arr as $i) {
            $key = call_user_func($key_selector, $i);
            $result[$key][] = $i;
        }
        return $result;
    }


}

class Admin_Controller extends STEVEN_Controller
{

    public function __construct()
    {
        parent::__construct();

        //set đường dẫn template
        define("TEMPLATE_PATH","admin/");
        define("TEMPLATE_MAIN",TEMPLATE_PATH . '_layout');
        define("TEMPLATE_ASSET",base_url('public/admin/'));

        //Language
        // $this->switchLanguage($this->input->get('lang'));


        //tải thư viện
        $this->load->library(array('ion_auth', 'breadcrumbs'));
        //load helper
        $this->load->helper(array('authorization', 'banner','image','format', 'link','button'));
        //Load config
        $this->config->load('seo');
        $this->config->load('permission');

        $this->check_auth();
    }

    public function check_auth()
    {
        if (
            ($this->_controller !== 'user' || ($this->_controller === 'user' && !in_array($this->_method, ['login', 'ajax_login','logout'])))
            && empty($this->ion_auth->logged_in())) {
            //chưa đăng nhập thì chuyển về page login
            redirect(site_admin_url('user/login') . '?url=' . urlencode(current_url()), 'refresh');
        } else {
            if ($this->ion_auth->logged_in()) {
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

                    if (!in_array($this->_controller, array('dashboard'))
                        && $this->_method !== 'logout'
                        && $this->_method !== 'profile'
                        && empty($_SERVER['HTTP_X_REQUESTED_WITH'])
                    ) {
                        if (empty($this->session->admin_permission[$this->_controller]['view'])) {//check quyen view
                            $this->load->view($this->template_main, ['main_content' => $this->load->view(TEMPLATE_PATH.'not_permission', [], TRUE)]);
                        }

                    }
                } else {
                    $this->session->admin_group_id = 1;//ID nhóm admin

                }
            }


        }
    }


    public function switchLanguage($lang_code = "") {
        $language_code = !empty($lang_code) ? $lang_code : $this->config->item('language_default');
        $this->session->set_userdata('admin_lang', $language_code);
        $languageFolder = $this->config->item('language_folder')[$language_code];
        $this->session->set_userdata('admin_lang_folder', $languageFolder);

        if (!empty($lang_code)) redirect($_SERVER['HTTP_REFERER']);
    }

    // add log action
    public function addLogAction($module,$data,$module_id,$note,$action)
    {
        $this->load->model("logs_model");
        $logActionModel = new Logs_model();
        $data_store = [
            'module' => $module,
            'ip' => $this->input->ip_address(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'module_id' => $module_id,
            'note' => $note,
            'action' => $action,
            'uid' => $this->session->user_id,
            'data' => json_encode($data)
        ];
        $logActionModel->save($data_store);
    }



}

class Public_Controller extends STEVEN_Controller
{

    public $schemas;
    public function __construct() {
        parent::__construct();
        //set đường dẫn template
        define("TEMPLATE_PATH","public/default/");
        define("TEMPLATE_MAIN",TEMPLATE_PATH . '_layout');
        define("TEMPLATE_ASSET",base_url('public/'));


        //tải thư viện
        $this->load->library(array('breadcrumbs','schema'));

        //load helper
        $this->load->helper(array('navmenus', 'banner', 'cookie','link', 'title', 'format', 'image', 'download'));

        if(MAINTAIN_MODE == TRUE) $this->load->view('public/coming_soon');

        $this->_settings        = getSetting('data_seo');
        $this->_settings_social = getSetting('data_social');
        $this->_settings_email  = getSetting('data_email');

        $this->_301_direction();
//        $this->check301Old();

        $configBreadcrumb['crumb_divider'] = $this->config->item('frontend_crumb_divider');
        $configBreadcrumb['tag_open'] = $this->config->item('frontend_tag_open');
        $configBreadcrumb['tag_close'] = $this->config->item('frontend_tag_close');
        $configBreadcrumb['crumb_open'] = $this->config->item('frontend_crumb_open');
        $configBreadcrumb['crumb_first_open'] = $this->config->item('frontend_crumb_first_open');
        $configBreadcrumb['crumb_last_open'] = $this->config->item('frontend_crumb_last_open');
        $configBreadcrumb['crumb_close'] = $this->config->item('frontend_crumb_close');
        $this->breadcrumbs->init($configBreadcrumb);
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if (DEBUG_MODE == TRUE && !in_array($this->_controller,['seo','player'])) {
            if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest')) {
                $this->load->add_package_path(APPPATH . 'third_party', 'codeigniter-debugbar');
                $this->output->enable_profiler(TRUE);
            }
        }

    }

    private function _301_direction(){
        $slug = str_replace(base_url(),"",current_url());
        $data_301 = getSetting('data_301');
        $data = explode("\n",$data_301->content);
        $content = [];
        if(!empty($data)) foreach ($data as $item){
            $rs = array_map('trim', explode("|",$item));
            if(!empty($rs[0] && !empty($rs[1])))
                $content[$rs[0]] = $rs[1];
        }
        if(!empty($content[$slug])) redirect($content[$slug],"location","301");
    }

    private function check301Old(){
        $slug = str_replace(base_url(),"",current_url());
        $this->load->model('post_model');
        $postModel = new Post_model();
        if($onePost = $postModel->getBySlug($slug)) redirect(getUrlPost($onePost),"location","301");
    }



    public function switchLanguage($lang_code = "")
    {
        $language_code = !empty($lang_code) ? $lang_code : $this->config->item('language_default');
        $this->session->set_userdata('public_lang_code', $language_code);
        $languageFolder = $this->config->item('public_lang_folder')[$language_code];
        $this->session->set_userdata('admin_lang_folder', $languageFolder);
        if (!empty($lang_code)) redirect($_SERVER['HTTP_REFERER']);
    }

    public function getUrlLogin()
    {
        $url = $this->zalo->getUrlLogin();
        return $url;
    }



    function alpha_numeric_space($str)
    {
        if (preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\+=\{\}\[\]\|;:"\<\>\.\?\\\]/', $str)) {
            $this->form_validation->set_message('alpha_numeric_space', '%s không được chứa ký tự đặc biệt');
            return false;
        }
        return true;
    }

    /**
     * @return array A CSRF key-value pair
     */
    public function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);
        return array($key => $value);
    }

    /**
     * @return bool Whether the posted CSRF token matches
     */
    public function _valid_csrf_nonce()
    {
        $csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
        if ($csrfkey && $csrfkey === $this->session->flashdata('csrfvalue')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function sendMail($to_mail, $subject, $contentHtml, $emailToCC = '', $emailToBCC = '')
    {
        try {
            $this->load->library('email');
            if (!empty($this->settings['protocol'])) {
                $this->email->protocol = $this->settings['protocol'];
                $this->email->smtp_host = $this->settings['smtp_host'];
                $this->email->smtp_user = $this->settings['smtp_user'];
                $this->email->smtp_port = $this->settings['smtp_port'];
            }
            if (!empty($this->settings['email_admin'])) {
                $from_mail = $this->settings['email_admin'];
            } else {
                $from_mail = $this->email->smtp_user;
            }
            $this->email->from($from_mail);
            $this->email->to($to_mail);
            if (!empty($emailToCC)) $this->email->cc($emailToCC);
            if (!empty($emailToBCC)) $this->email->bcc($emailToBCC);
            $this->email->subject($subject);
            $this->email->message($contentHtml);
            if ($this->email->send()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            $this->_message = array(
                'type' => 'danger',
                'message' => 'Co lỗi khi gửi mail'
            );
        }
    }
    public function callCURL($url, $data = array(), $type = "GET")
    {
        $resource = curl_init();
        curl_setopt($resource, CURLOPT_URL, $url);

        if ($type == "POST") {
            curl_setopt($resource, CURLOPT_POST, true);
            curl_setopt($resource, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($resource, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($resource,CURLOPT_TIMEOUT,40);
        $result = curl_exec($resource);
        curl_close($resource);
        return $result;
    }

}

class Crawler_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(array('session', 'form_validation'));
        $this->load->helper(array('security', 'url', 'form', 'debug', 'data'));
    }

    public function checkRequestGetAjax()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'GET')
            die('Not Allow');;
    }

    public function checkRequestPostAjax()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST')
            die('Not Allow');
    }

    public function returnJsonData($data)
    {
        header('Content-Type: application/json; charset=utf-8');
        die(json_encode($data));
    }

    public function toSlug($doc)
    {
        $str = addslashes(html_entity_decode($doc));
        $str = $this->toNormal($str);
        $str = str_replace(":", "-gio-", $str);
        $str = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $str);
        $str = preg_replace("/( )/", '-', $str);
        $str = str_replace('/', '-', $str);
        $str = str_replace("\/", '', $str);
        $str = str_replace("+", "", $str);
        $str = str_replace(" - ", "-", $str);
        $str = str_replace("---", "-", $str);
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

    public function copyFile($from,$to){
//        $url = 'https://static.bongda24h.vn/medias/standard/2020/6/27/nhan-dinh-bong-da-Leeds-vs-Fulham.jpg';
//        $img = MEDIA_PATH.'thumb/post/cai-ten-dac-biet.jpg';

        $ch = curl_init($from);
        $fp = fopen($to, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    }

    public function callCURL($url, $data = array(), $type = "GET")
    {
        $resource = curl_init();
        curl_setopt($resource, CURLOPT_URL, $url);

        if ($type == "POST") {
            curl_setopt($resource, CURLOPT_POST, true);
            curl_setopt($resource, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($resource, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($resource,CURLOPT_TIMEOUT,40);
        $result = curl_exec($resource);
        curl_close($resource);
        return $result;
    }
    public function cleanContent($content)
    {
        if(empty($content)) return false;
        $remove = [
          '/id=".*?"/',
          '/\!important/',
          '/<script.*?script>/',
          '/<iframe.*?\/iframe>/',
          '/<video.*?\/video>/',
          '/<img(?![^>]*(src="[^"]+"))[^>]*>/'
        ];
        $content = preg_replace($remove, '', $content);
        $content = strip_tags($content, '<img><h2><h3><h4><p><span><strong><br><table><th><tr><td><ol><ul><li>');
        /*xoa the font*/
        /*        $content = preg_replace('/<font.*?>(.*?)<\/font>/', '$1', $content);*/
        /*xoa class lazy*/
//        $content = preg_replace('/(class=".*?)lazy(.*?")/', '$1$2', $content);
        /*remove*/
        return $content;
    }
    public function downloadImage($link,$name = '', $folder = ''){
        $link = strtok($link, '?');
        $ext = pathinfo($link, PATHINFO_EXTENSION);
        if(empty($name)) $name = $this->toSlug(pathinfo($link, PATHINFO_FILENAME));
        $fileName = $folder . "/" . $name . '.' . (!empty($ext) ? $ext : 'png');
        if (file_exists(MEDIA_PATH . $fileName) == false) {
            if (!is_dir(dirname(MEDIA_PATH . $fileName))) {
                mkdir(dirname(MEDIA_PATH . $fileName), 0755, TRUE);
            }
            file_put_contents(MEDIA_PATH . $fileName, file_get_contents($link));
            return $fileName;
        } else return $fileName;
    }
}
