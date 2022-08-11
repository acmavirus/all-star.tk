<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        $data['heading_title'] = ucfirst($this->router->fetch_class());
        $data['heading_description'] = 'Tổng quan CMS';
        /*Breadcrumbs*/
        $this->breadcrumbs->push('Home', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*Breadcrumbs*/
        $data['main_content'] = $this->load->view(TEMPLATE_PATH.'dashboard/index', $data, TRUE);
        $this->load->view(TEMPLATE_MAIN, $data);
    }
}