<?php

/**
 * Created by PhpStorm.
 * User: ducto
 * Date: 9/29/2018
 * Time: 12:38 PM
 */

defined('BASEPATH') or exit('No direct script access allowed');

class Home extends STEVEN_Controller
{
	public function index()
	{
		$this->load->view('default/layout', []);
	}
}
