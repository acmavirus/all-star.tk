<?php
/**
 * Created by PhpStorm.
 * User: ducto
 * Date: 9/29/2018
 * Time: 12:38 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Admin_Controller
{
    protected $_data;
    protected $_data_group;

    public function __construct()
    {
        parent::__construct();
        //tải thư viện
        $this->load->library(array('ion_auth'));
        //$this->lang->load('user');
        $this->load->model(['users_model', 'groups_model']);
        $this->_data = new Users_model();
        $this->_data_group = new Groups_model();
    }

    public function index()
    {
        $data['heading_title'] = "Quản lý thành viên";
        $data['heading_description'] = 'Danh sách thành viên';
        $data['main_content'] = $this->load->view($this->template_path . $this->_controller . DIRECTORY_SEPARATOR . $this->_method, $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function profile()
    {
        $data['heading_title'] = "Thông tin của tôi";
        $data['main_content'] = $this->load->view($this->template_path . $this->_controller . DIRECTORY_SEPARATOR . $this->_method, $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function activity()
    {
        $data['heading_title'] = "Hoạt động của tôi";
        $data['main_content'] = $this->load->view($this->template_path . $this->_controller . DIRECTORY_SEPARATOR . $this->_method, $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function login()
    {
        if ($this->ion_auth->logged_in() == true) redirect(site_admin_url());
        $this->load->view($this->template_path . 'user/login');
    }

    public function ajax_list()
    {
        $this->checkRequestPostAjax();
        $data = array();
        $pagination = $this->input->post('pagination');
        $page = $pagination['page'];
        $total_page = isset($pagination['pages']) ? $pagination['pages'] : 1;
        $limit = !empty($pagination['perpage']) && $pagination['perpage'] > 0 ? $pagination['perpage'] : 1;

        $queryFilter = $this->input->post('query');
        $params = [
            'group_id' => !empty($queryFilter['group_id']) ? $queryFilter['group_id'] : '',
            'page' => $page,
            'limit' => $limit
        ];
        if (isset($queryFilter['is_status']) && $queryFilter['is_status'] !== '')
            $params = array_merge($params, ['active' => $queryFilter['is_status']]);

        $listData = $this->_data->getData($params);
        if (!empty($listData)) foreach ($listData as $item) {
            $row = array();
            $row['checkID'] = $item->id;
            $row['id'] = $item->id;
            $row['username'] = $item->username;
            $row['fullname'] = $item->fullname;
            $row['is_status'] = $item->active;
            $row['updated_time'] = $item->updated_time;
            $row['created_time'] = $item->created_time;
            $data[] = $row;
        }

        $output = [
            "meta" => [
                "page" => $page,
                "pages" => $total_page,
                "perpage" => $limit,
                "total" => $this->_data->getTotal(),
                "sort" => "asc",
                "field" => "id"
            ],
            "data" => $data
        ];

        $this->returnJson($output);
    }

    public function ajax_login()
    {
        $this->checkRequestPostAjax();

        $identity = $this->input->post('identity');
        $rules[] = array(
            'field' => 'identity',
            'label' => str_replace(':', '', $this->lang->line('login_identity_label')),
            'rules' => filter_var($identity, FILTER_VALIDATE_EMAIL) ? 'trim|required|valid_email' : 'trim|required'
        );
        $rules[] = array(
            'field' => 'password',
            'label' => str_replace(':', '', $this->lang->line('login_password_label')),
            'rules' => 'trim|required'
        );
        if (GG_CAPTCHA_MODE == TRUE) {
            $rules[] = array(
                'field' => 'g-recaptcha-response',
                'label' => 'captcha',
                'rules' => 'required'
            );
        }

        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() != false) {

            if (GG_CAPTCHA_MODE == TRUE) {
                //Check config setting reCaptcha
                $this->load->library('recaptcha');
                $captchaResponse = $this->input->post('g-recaptcha-response');
                $remoteIp = $this->input->ip_address();
                if ($this->recaptcha->verify($captchaResponse, $remoteIp) == false) {
                    $message['type'] = 'error';
                    $message['message'] = "Bạn chưa xác thực Captcha !";
                    $this->returnJson($message);
                }
                //Check config setting reCaptcha
            }

            $remember = (bool)$this->input->post('remember');

            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
                //if the login is successful
                //Set session for Moxie Manager Image
                $this->session->set_userdata('logged_admin', true);
                $this->session->set_userdata('MyLoggedInKey', true);
                $this->session->set_userdata('CodeIgniterAuthenticator.environment', ENVIRONMENT);
                $this->session->set_userdata('moxiemanager.filesystem.rootpath', MEDIA_PATH);
                $this->session->set_userdata('moxiemanager.storage.path', MEDIA_PATH);
                //redirect them back to the home page
                $url_redirect = $this->input->post('url_redirect') ? $this->input->post('url_redirect') : site_admin_url();
                $message['type'] = 'success';
                $message['message'] = strip_tags($this->ion_auth->messages());
                $message['url_redirect'] = $url_redirect;
                $this->returnJson($message);
            } else {
                $message['type'] = 'error';
                $message['message'] = strip_tags($this->ion_auth->errors());
                $this->returnJson($message);
            }
        } else {
            $message['type'] = "warning";
            $message['message'] = "Vui lòng kiểm tra lại thông tin.";
            $valid = array();
            if (!empty($rules)) foreach ($rules as $item) {
                if (!empty(form_error($item['field']))) $valid[$item['field']] = form_error($item['field']);
            }
            $message['validation'] = $valid;
            $this->returnJson($message);
        }
    }

    public function logout()
    {
        $this->ion_auth->logout();
        redirect(site_admin_url('user/login'), 'refresh');
    }

    public function ajax_add()
    {
        $this->checkRequestPostAjax();
        $data = $this->_convertData();
        $identity = $data['username'];
        $password = $data['password'];
        $email = $data['email'];
        $group_id = $data['group_id'];
        unset($data['group_id']);
        if ($this->ion_auth->register($identity, $password, $email, $data, array('group_id' => $group_id)) != false) {
            $message['type'] = 'success';
            $message['message'] = "Thêm mới thành công !";
        } else {
            $message['type'] = 'error';
            $message['message'] = "Thêm mới thất bại !";
        }

        $this->returnJson($message);
    }

    public function ajax_edit()
    {
        $this->checkRequestPostAjax();
        $id = $this->input->post('id');
        if (!empty($id)) {
            $dataItem = $this->_data->getById($id);
            unset($dataItem->password);
            $output['data'] = $dataItem;
            $oneGroup = $this->ion_auth->get_users_groups($id)->row();
            $output['group'] = [['id' => $oneGroup->id, 'text' => $oneGroup->name]];
            $this->returnJson($output);
        }
    }

    public function ajax_update()
    {
        $this->checkRequestPostAjax();
        $data = $this->_convertData();
        if ($this->ion_auth->update($data['id'], $data)) {
            $this->ion_auth->remove_from_group(false, $data['id']);
            $this->ion_auth->add_to_group($data['group_id'], $data['id']);
            $message['type'] = 'success';
            $message['message'] = "Cập nhật thành công !";
        } else {
            $message['type'] = 'error';
            $message['message'] = "Cập nhật thất bại !";
        }
        $this->returnJson($message);
    }

    public function ajax_update_field()
    {
        $this->checkRequestPostAjax();
        $id = $this->input->post('id');
        $field = $this->input->post('field');
        $value = $this->input->post('value');
        $response = $this->_data->update(['id' => $id], [$field => $value]);
        if ($response != false) {
            $message['type'] = 'success';
            $message['message'] = "Cập nhật thành công !";
        } else {
            $message['type'] = 'error';
            $message['message'] = "Cập nhật thất bại !";
        }
        $this->returnJson($message);
    }

    public function ajax_delete()
    {
        $this->checkRequestPostAjax();
        $ids = $this->input->post('id');
        if ((is_array($ids) && array_search(1, $ids)) || $ids == 1) {
            $message['type'] = 'error';
            $message['message'] = "Bạn không có quyền xóa Admin !";
            $this->returnJson($message);
        } else {
            $response = $this->_data->deleteArray('id', $ids);
            if ($response != false) {
                $message['type'] = 'success';
                $message['message'] = "Xóa thành công !";
            } else {
                $message['type'] = 'error';
                $message['message'] = "Xóa thất bại !";
                log_message('error', $response);
            }
            $this->returnJson($message);
        }
    }

    public function ajax_load()
    {
        $this->checkRequestGetAjax();
        $term = $this->input->get("q");
        $params = [
            'search' => $term,
            'limit' => 100
        ];
        $list = $this->_data->getUserSelect2($params);
        $json = [];
        if (!empty($list)) foreach ($list as $item) {
            $item = (object)$item;
            $json[] = ['id' => $item->id, 'text' => $item->email . '(' . $item->fullname . ')'];
        }
        print json_encode($json);
        exit;
    }

    private function _validation()
    {
        $rules = array(
            array(
                'field' => 'fullname',
                'label' => 'Họ và tên',
                'rules' => 'trim'
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|valid_email' . ($this->input->post('id') === 0 ? '|is_unique[' . $this->_data->_dbprefix . 'users.email]' : ''),
                'errors' => array(
                    'is_unique' => '%s đã tồn tại. Vui lòng chọn %s khác.',
                )
            ),
            array(
                'field' => 'phone',
                'label' => 'Số điện thoại',
                'rules' => 'trim|regex_match[/^[0-9.-]{0,18}+$/]'
            ),
            array(
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'trim|required' . ($this->input->post('id') == 0 ? '|is_unique[' . $this->_data->_dbprefix . 'users.username]' : ''),
                'errors' => array(
                    'is_unique' => '%s đã tồn tại. Vui lòng chọn %s khác.',
                )
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim' . ($this->input->post('id') == 0 ? '|required' : '')
            ),
            array(
                'field' => 're-password',
                'label' => 'Re Password',
                'rules' => 'trim|matches[password]' . ($this->input->post('id') == 0 ? '|required' : '')
            ),
            array(
                'field' => 'group_id',
                'label' => 'Nhóm',
                'rules' => 'trim|required'
            )
        );
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
            $message['type'] = "warning";
            $message['message'] = "Vui lòng kiểm tra lại thông tin vừa nhập.";
            $valid = array();
            if (!empty($rules)) foreach ($rules as $item) {
                if (!empty(form_error($item['field']))) $valid[$item['field']] = form_error($item['field']);
            }
            $message['validation'] = $valid;
            $this->returnJson($message);
            exit;
        }
    }

    private function _convertData()
    {
        $this->_validation();
        $data = $this->input->post();
        if (!empty($data['active'])) $data['active'] = 1; else $data['active'] = 0;
        unset($data['re-password']);
        return $data;
    }
}