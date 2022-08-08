<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use DB;

use App\Core\CoreController;

class Dashboard extends CoreController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function auth(Request $request)
    {
        $this->checkLogin(Auth::user());
        if (!empty($request) && Auth::attempt(['username' => request('username'), 'password' => request('password')], true)) {
            return response()->json([
                'message' => [
                    "status" => "success"
                ]
            ]);
        };
        return view('admin.auth', []);
    }

    public function index(Request $request)
    {
        $data = [
            'user' => Auth::user(),
            'class' => $this->_class
        ];
        return view('admin.layout', $data);
    }
}
