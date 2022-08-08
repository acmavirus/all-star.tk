<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;

use App\Core\CoreController;

class Page extends CoreController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($slug)
    {
        $data = [];
        $data['oneItem'] = $oneItem = DB::table('st_category')->where('slug', $slug)->get()[0];
        $data['SEO'] = (object)[
            'title' => $oneItem->title,
            'meta_title' => $oneItem->meta_title,
            'meta_description' => $oneItem->meta_description,
            'meta_keyword' => $oneItem->meta_keyword
        ];
        $data['main_content'] = view("default.page.index", $data)->render();
        return view('default.layout', $data);
    }
}
