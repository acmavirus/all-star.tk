<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;

use App\Core\CoreController;

class Setting extends CoreController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $this->checkLogin(Auth::user());

        $data = [
            'user' => Auth::user(),
            'class' => $this->_class,
            'uri' => 'category'
        ];

        $data['data_seo'] = DB::table($this->_table)
        ->where('key_setting', 'data_seo')
        ->get();
        $json_string = json_encode($data['data_seo']);
        $result_array = json_decode($json_string, TRUE)[0];
        $data['data_seo'] = json_decode($result_array['value_setting']);

        $data['data_social'] = DB::table($this->_table)
        ->where('key_setting', 'data_social')
        ->get();
        $json_string = json_encode($data['data_social']);
        $result_array = json_decode($json_string, TRUE)[0];
        $data['data_social'] = json_decode($result_array['value_setting']);

        $data['data_email'] = DB::table($this->_table)
        ->where('key_setting', 'data_email')
        ->get();
        $json_string = json_encode($data['data_email']);
        $result_array = json_decode($json_string, TRUE)[0];
        $data['data_email'] = json_decode($result_array['value_setting']);

        $data['data_banner'] = DB::table($this->_table)
        ->where('key_setting', 'data_banner')
        ->get();
        $json_string = json_encode($data['data_banner']);
        $result_array = json_decode($json_string, TRUE)[0];
        $data['data_banner'] = json_decode($result_array['value_setting']);

        $data['data_301'] = DB::table($this->_table)
        ->where('key_setting', 'data_301')
        ->get();
        $json_string = json_encode($data['data_301']);
        $result_array = json_decode($json_string, TRUE)[0];
        $data['data_301'] = json_decode($result_array['value_setting']);

        $data['inner_link'] = DB::table($this->_table)
        ->where('key_setting', 'inner_link')
        ->get();
        $json_string = json_encode($data['inner_link']);
        $result_array = json_decode($json_string, TRUE)[0];
        $data['inner_link'] = json_decode($result_array['value_setting']);

        $data['main_content'] = view("admin.setting.index", $data)->render();
        return view('admin.layout', $data);
    }

    public function update_setting(Request $request) {
        $key_setting = $request['key_setting'];
        unset($request['key_setting']);
        
        $result = $this->toJson($request);
        if (DB::table($this->_table)->where('key_setting', $key_setting)->update(['value_setting'=>$result])) {
            $message['type'] = 'success';
            $message['message'] = "Cập nhật thành công !";
        } else {
            $message['type'] = 'error';
            $message['message'] = "Cập nhật thất bại !";
        }
        return response()->json($message);
    }

    private function toJson($request) {
        $collect = []; // empty array for collect customised inputs
        foreach($request->all() as $input_key => $input_value){ // split input one by one
        $collect[$input_key] = $input_value; };
        $result = json_encode($collect); //convert to json
        return $result;
    }
}
