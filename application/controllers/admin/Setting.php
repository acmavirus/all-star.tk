<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends Admin_Controller {

    protected $_setting;

    public function __construct(){
        parent::__construct();

        $this->load->model('setting_model');
        $this->_setting  = new Setting_model();
    }

    public function index()
    {
        $data_seo = $this->_setting->get_setting_by_key('data_seo');
        $data_301 = $this->_setting->get_setting_by_key('data_301');
        $data_social = $this->_setting->get_setting_by_key('data_social');
        $data_email = $this->_setting->get_setting_by_key('data_email');
        $data['data_seo'] = !empty($data_seo) ? json_decode($data_seo->value_setting) : '';
        $data['data_301'] = !empty($data_301) ? json_decode($data_301->value_setting) : '';
        $data['data_social'] = !empty($data_social) ? json_decode($data_social->value_setting) : '';
        $data['data_email'] = !empty($data_email) ? json_decode($data_email->value_setting) : '';

        $data['main_content'] = $this->load->view(TEMPLATE_PATH . $this->_controller . DIRECTORY_SEPARATOR . 'index', $data, TRUE);
        $this->load->view(TEMPLATE_MAIN, $data);
    }

    public function update_setting()
    {
        $this->checkRequestPostAjax();
        $data = $this->input->post();
        $key_setting = $data['key_setting'];
        unset($data['key_setting']);
        if (!empty($data)) {
            $param_store = [
                'value_setting' => json_encode($data),
                'key_setting' => $key_setting,
                'title' => $key_setting,
            ];
            $checkSetting = $this->_setting->get_setting_by_key($key_setting);
            if (!empty($checkSetting)) {
                unset($param_store['key_setting']);
               $this->_setting->update(['id'=>$checkSetting->id],$param_store);
            }else{
               $this->_setting->save($param_store);
            }
        }

        $data_mess = [
            'message' => 'Update thành công',
            'type' => 'success'
        ];
        die(json_encode($data_mess));
    }

    public function delete_cache_file($url = ''){
        if (empty($url)){
            $this->load->helper('file');
            $url = $this->input->get('url');
        }

        if(!empty($url)){
            $uri = str_replace(base_url(),'/',$url);
            if($this->output->delete_cache($uri)){
               $this->returnJson([
                'type' => 'success',
                'message' => 'Xóa cache database thành công !'
            ]);
            }
        }else{
            if(delete_files(FCPATH . 'application' . DIRECTORY_SEPARATOR . 'cache')){
                $this->returnJson([
                    'type' => 'success',
                    'message' => 'Xóa cache database thành công !'
                ]);
            }
        }

    }
    public function ajax_clear_cache_db(){
        $this->deleteCache();
        $this->returnJson([
            'type' => 'success',
            'message' => 'Xóa cache database thành công !'
        ]);
    }
}