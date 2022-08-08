<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\Admin\Dashboard;
use App\Http\Controllers\Admin\Setting;
use App\Http\Controllers\Admin\Users;
use App\Http\Controllers\Admin\Media;
use App\Http\Controllers\Admin\Menus;
use App\Http\Controllers\Admin\Category;
use App\Http\Controllers\Admin\Post;
use App\Http\Controllers\Admin\Onpage;

use App\Http\Controllers\Home;
use App\Http\Controllers\Page;
use App\Http\Controllers\Post_Controller;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::get('/admin', [Dashboard::class, 'auth'], function (Request $request) {return $request;});
Route::get('/admin/dashboard', [Dashboard::class, 'index'], function (Request $request) {return $request;});
Route::get('/admin/users', [Users::class, 'index'], function (Request $request) {return $request;});
Route::get('/admin/media', [Media::class, 'index'], function (Request $request) {return $request;});

Route::group(['prefix'=>'/admin/category'],function(){
    Route::get('/', [Category::class, 'category'], function (Request $request) {return $request;});
    Route::get('/page', [Category::class, 'page'], function (Request $request) {return $request;});
    Route::get('/post', [Category::class, 'post'], function (Request $request) {return $request;});
    Route::post('/ajax_list', [Category::class, 'ajax_list'], function (Request $request) {return $request;});
    Route::post('/ajax_delete', [Category::class, 'ajax_delete'], function (Request $request) {return $request;});
    Route::post('/ajax_load', [Category::class, 'ajax_load'], function (Request $request) {return $request;});
    Route::post('/ajax_add', [Category::class, 'ajax_add'], function (Request $request) {return $request;});
    Route::post('/ajax_edit', [Category::class, 'ajax_edit'], function (Request $request) {return $request;});
    Route::post('/ajax_update', [Category::class, 'ajax_update'], function (Request $request) {return $request;});
});
Route::group(['prefix'=>'/admin/post'],function(){
    Route::get('/', [Post::class, 'index'], function (Request $request) {return $request;});
    Route::post('/ajax_list', [Post::class, 'ajax_list'], function (Request $request) {return $request;});
    Route::post('/ajax_delete', [Post::class, 'ajax_delete'], function (Request $request) {return $request;});
    Route::post('/ajax_load', [Post::class, 'ajax_load'], function (Request $request) {return $request;});
    Route::post('/ajax_add', [Post::class, 'ajax_add'], function (Request $request) {return $request;});
    Route::post('/ajax_edit', [Post::class, 'ajax_edit'], function (Request $request) {return $request;});
    Route::post('/ajax_update', [Post::class, 'ajax_update'], function (Request $request) {return $request;});
});
Route::group(['prefix'=>'/admin/onpage'],function(){
    Route::get('/', [Onpage::class, 'index'], function (Request $request) {return $request;});
    Route::get('/page', [Onpage::class, 'page'], function (Request $request) {return $request;});
    Route::get('/post', [Onpage::class, 'post'], function (Request $request) {return $request;});
    Route::post('/ajax_list', [Onpage::class, 'ajax_list'], function (Request $request) {return $request;});
    Route::post('/ajax_delete', [Onpage::class, 'ajax_delete'], function (Request $request) {return $request;});
    Route::post('/ajax_load', [Onpage::class, 'ajax_load'], function (Request $request) {return $request;});
    Route::post('/ajax_add', [Onpage::class, 'ajax_add'], function (Request $request) {return $request;});
    Route::post('/ajax_edit', [Onpage::class, 'ajax_edit'], function (Request $request) {return $request;});
    Route::post('/ajax_update', [Onpage::class, 'ajax_update'], function (Request $request) {return $request;});
});
Route::group(['prefix'=>'/admin/setting'],function(){
    Route::get('/', [Setting::class, 'index'], function (Request $request) {return $request;});
    Route::post('/update_setting', [Setting::class, 'update_setting'], function (Request $request) {return $request;});

});
Route::group(['prefix'=>'/admin/menus'],function(){
    Route::get('/', [Menus::class, 'index'], function (Request $request) {return $request;});
    Route::post('/ajax_load', [Setting::class, 'ajax_load'], function (Request $request) {return $request;});
});
/*
|--------------------------------------------------------------------------
| DEFAULT
|--------------------------------------------------------------------------
*/
Route::get('/page/{slug}', [Page::class, 'index'], function ($slug) {return $slug;});
Route::get('/puid-{slug}.html', [Post_Controller::class, 'detail'], function ($slug) {return $slug;});
Route::get('/', [Home::class, 'index'], function (Request $request) {return $request;});