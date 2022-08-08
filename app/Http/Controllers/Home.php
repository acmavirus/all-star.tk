<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;

use App\Core\CoreController;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class Home extends CoreController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $page = $request['page'];
        $limit = !empty($request['perpage']) && $request['perpage'] > 0 ? $request['perpage'] : 18;
        $data = [];
        $offset = ($page = 1) ? 0 : $page * $limit;
        $data['newest'] = DB::table('st_post')
            ->limit($limit)
            ->offset($offset)
            ->get();
        $data['popular'] = DB::table('st_post')
            ->inRandomOrder()
            ->limit(6)
            ->get();
        $data['main_content'] = view("default.home.index", $data)->render();
        return view('default.layout', $data);
    }
}