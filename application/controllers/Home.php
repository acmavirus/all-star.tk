<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = [];
        $data['main_content'] = $this->load->view(TEMPLATE_PATH . 'home/index', $data, TRUE);
        $this->load->view(TEMPLATE_MAIN, $data);
    }
}
