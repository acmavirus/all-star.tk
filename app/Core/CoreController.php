<?php

namespace App\Core;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

use DB;

class CoreController extends BaseController
{
    protected $_class;
    protected $_db_prefix;
    protected $_table;

    public function __construct()
    {
        $this->_class = strtolower(class_basename(get_class($this)));
        $this->_db_prefix = "st_";
        $this->_table = $this->_db_prefix.$this->_class;
    }

    public function checkLogin($auth)
    {
        if ($this->_class == 'dashboard' && !empty($auth)) {
            header("Location: ".url("admin/dashboard"));
            die();
        };
        if ($this->_class !== 'dashboard' && empty($auth)) {
            header("Location: ".url("admin"));
            die();
        };
    }
}
