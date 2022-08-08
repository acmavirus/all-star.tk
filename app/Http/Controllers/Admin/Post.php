<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;

use App\Core\CoreController;

class Post extends CoreController
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
            'uri' => 'post'
        ];
        $data['main_content'] = view("admin.post.index", $data)->render();
        return view('admin.layout', $data);
    }

    public function ajax_list(Request $request)
    {
        $data = array();
        $pagination = $request['pagination'];
        $page = $pagination['page'];
        $total_page = isset($pagination['pages']) ? $pagination['pages'] : 1;
        $limit = !empty($pagination['perpage']) && $pagination['perpage'] > 0 ? $pagination['perpage'] : 10;

        $queryFilter = $request['query'];
        $type = (!empty($_GET['uri'])) ? $_GET['uri'] : $this->_class;
        $offset = ($page = 1) ? 0 : $page * $limit;
        $listAll = DB::table($this->_table)
            ->where('type', $type)
            ->offset($offset)
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->get();
        $total = DB::table($this->_table)
            ->where('type', $type)
            ->count();
        $data = array();
        if (!empty($listAll)) foreach ($listAll as $item) {
            $row = array();
            $row['checkID'] = $item->id;
            $row['id'] = $item->id;
            $row['title']        = $item->title;
            $row['is_featured']  = $item->is_featured;
            $row['is_robot'] = $item->is_robot;
            $row['is_status']    = $item->is_status;
            $row['updated_time'] = $item->updated_time;
            $row['created_time'] = $item->created_time;
            $data[] = $row;
        }

        return response()->json([
            "meta" => [
                "page"      => $page,
                "pages"     => $total_page,
                "perpage"   => $limit,
                "total"     => $total,
                "sort"      => "desc",
                "field"     => "id"
            ],
            "data" =>  $data
        ]);
    }

    public function ajax_delete(Request $request)
    {
        $data = array();
        $ids = $request['id'];
        $response = DB::table($this->_table)
            ->delete($ids);

        if ($response != false) {
            DB::table($this->_table."_category")
            ->where('post_id', $ids)
            ->delete();
            $message['type'] = 'success';
            $message['message'] = "Xóa thành công !";
        } else {
            $message['type'] = 'error';
            $message['message'] = "Xóa thất bại !";
        }
        return response()->json($message);
    }

    public function ajax_add(Request $request)
    {
        $data = $this->_convertData($request);
        $type = (!empty($_GET['uri'])) ? $_GET['uri'] : $$this->_class;
        if (DB::table($this->_table)->insertGetId([
            'title' => $data['title'],
            'description' => $data['description'],
            'content' => $data['content'],
            'meta_title' => $data['meta_title'],
            'slug' => $data['slug'],
            'meta_description' => $data['meta_description'],
            'meta_keyword' => $data['meta_keyword'],
            'layout' => $data['layout'],
            'is_status' => $data['is_status'],
            'thumbnail' => $data['thumbnail'],
            'type' => $data['type'],
            'is_robot' => $data['is_robot'],
        ])) {
            $message['type'] = 'success';
            $message['message'] = "Thêm mới thành công !";
        } else {
            $message['type'] = 'error';
            $message['message'] = "Thêm mới thất bại !";
        }
        return response()->json($message);
    }

    public function ajax_edit(Request $request)
    {
        $id = $request['id'];
        if (!empty($id)) {
            $output['data_info'] = DB::table($this->_table)
                ->where('id', $id)
                ->first();
            $output['data_category'] = [];
            return response()->json($output);
        }
    }

    public function ajax_update(Request $request)
    {
        $data = $this->_convertData($request);
        $id = $request['id'];
        if (DB::table($this->_table)->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'content' => $data['content'],
            'meta_title' => $data['meta_title'],
            'slug' => $data['slug'],
            'meta_description' => $data['meta_description'],
            'meta_keyword' => $data['meta_keyword'],
            'is_status' => $data['is_status'],
            'thumbnail' => $data['thumbnail'],
            'type' => $data['type'],
            'is_robot' => $data['is_robot'],
        ], ['id' => $id])) {
            $message['type'] = 'success';
            $message['message'] = "Cập nhật thành công !";
        } else {
            $message['type'] = 'error';
            $message['message'] = "Cập nhật thất bại !";
        }
        return response()->json($message);
    }

    private function _convertData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'slug' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 404);
        };
        $data = $request;
        $type = (!empty($_GET['uri'])) ? $_GET['uri'] : $this->_class;
        if (!empty($type)) $data['type'] = $type;
        else $data['type'] = 'post';
        if (!empty($data['is_status'])) $data['is_status'] = 1;
        else $data['is_status'] = 0;
        if (isset($data['is_robot'])) $data['is_robot'] = 1;
        else $data['is_robot'] = 0;
        unset($data['id']);
        unset($data['_token']);
        return $data;
    }
}
