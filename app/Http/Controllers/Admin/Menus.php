<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;

use App\Core\CoreController;

class Menus extends CoreController
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
        $data['main_content'] = view("admin.menus.index", $data)->render();
        return view('admin.layout', $data);
    }
    public function ajax_load(Request $request){
        $data['list_category'] = $allCategory = DB::table($this->_table)
        ->get();
        $html =  $this->load->view(TEMPLATE_PATH . 'menus/_ajax_load_data', $data, TRUE);
        echo $html;
    }

    public function ajax_load_menu(){
        $this->checkRequestPostAjax();
        $locationId = $this->input->post('location_id');
        $data = $this->_data->search(['location_id' => $locationId]);
        $this->listMenu($data, 0, $locationId);
        $this->returnJson($this->_listMenu);
        exit();
    }

    public function ajax_save_menu(){
        $this->checkRequestPostAjax();
        $menuLocation = $this->input->post('loc');
        $response = $this->input->post('s');
        $this->_data->delete(['location_id'=>$menuLocation]);
        if (is_array($response)) {
            $topmenusorder = 1;
            if(!empty($response)) foreach ($response as $key => $block) {
                $tmp['title'] = trim($block['label']);
                $tmp['class'] = trim($block['cls']);
                $tmp['data_id'] = trim($block['value']);
                $tmp['link'] = trim($block['link']);
                $tmp['order'] = $topmenusorder;
                $tmp['parent_id'] = 0;
                $tmp['location_id'] = $menuLocation;
                $menuid = $this->_data->saveMenu($tmp);
                if (!empty($block['children'])) {
                    $this->childsubmenus($menuid, $block['children'], $menuLocation);
                }
                $topmenusorder++;
            }
        }
        $this->_data->getAllMenu(true);
        echo 1;
        exit;
    }

    //-----------------------------------
    private function childsubmenus($menuid, $e, $menuLocation)
    {
        $topmenusorder = 1;
        foreach ($e as $key => $block) {
            $tmp['title'] = trim($block['label']);
            $tmp['class'] = trim($block['cls']);
            $tmp['data_id'] = trim($block['value']);
            $tmp['link'] = trim($block['link']);
            $tmp['order'] = $topmenusorder;
            $tmp['parent_id'] = $menuid;
            $tmp['location_id'] = $menuLocation;
            $menu = $this->_data->saveMenu($tmp);
            if (!empty($block['children'])) {
                $this->childsubmenus($menu, $block['children'], $menuLocation);
            }
            $topmenusorder++;
        }
    }

    private function listMenu($menu, $parent = 0,$locationId)
    {
        if(!empty($menu)) foreach ($menu as $row) {
            $row = (array) $row;
            if ($row['parent_id'] == $parent)
            {
                $this->_listMenu[] = array(
                    'id' => $row['id'],
                    'name' => $row['title'],
                    'class' => $row['class'],
                    'data_id' => $row['data_id'],
                    'link' => $row['link'],
                    'level' => $row['parent_id']);
                $this->listMenu($menu, $row['id'],$locationId);
            }
        }
    }
    private function getCategoryByType($all, $type){
        $data = [];
        if(!empty($all)) foreach ($all as $item){
            if($item->type === $type) $data[] = $item;
        }
        return $data;
    }
}
