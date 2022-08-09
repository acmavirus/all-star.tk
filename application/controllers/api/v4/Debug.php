<?php
/**
 * Created by PhpStorm.
 * User: ducto
 * Date: 9/29/2018
 * Time: 12:38 PM
 */

defined('BASEPATH') or exit('No direct script access allowed');

class Debug extends STEVEN_Controller
{
    protected $_data_category;
    protected $_data;

    public function cache(){
        $allCache = $this->cache->cache_info();
        echo "<ul>";
        if(!empty($allCache)) foreach ($allCache as $key => $item){
            $delete = "<a target='_blank' href='".base_url('debug/delete_cache?key='.$key)."'>Delete cache</a>";
            echo "<li>$key => $delete</li>";
        }
        echo "</ul>";
    }

    public function delete_cache_file($url = ''){
        if (empty($url)){
            $this->load->helper('file');
            $url = $this->input->get('url');
        }

        if(!empty($url)){
            $uri = str_replace(base_url(),'/',$url);
            if($this->output->delete_cache($uri)) echo 'Delete cache'.$uri."<br>";
            else  echo "$uri has been deleted !<br>";
        }else{
            if(delete_files(FCPATH . 'application' . DIRECTORY_SEPARATOR . 'cache')) die("Delete all page statistic success !");
            else  die("Delete all page statistic error !");
        }

    }

    public function delete_cache(){
        $key = $this->input->get('key');
        $key = str_replace(CACHE_PREFIX_NAME,'',$key);
        if(!empty($key)) {
            if($this->deleteCache($key)) die('Delete success !');
            else  die('Delete error !');
        }else{
            die('Not key => error !');
        }
    }

    public function update_cache(){
        $down= date('N');
        if(date('H') == 16) {
            $this->deleteCacheDOW(3,$down);
        }

        if(date('H') == 17) {
            $this->deleteCacheDOW(2,$down);
        }

        if(date('H') == 18) {
            $this->deleteCacheDOW(1,$down);
        }
        exit;
    }

    private function deleteCacheDOW($parentId,$dayOfWeek){
        $this->load->model("category_model");
        $this->_data_category = new Category_model();
        $all = $this->_data_category->_all_category();
        $this->updateCacheCategory($parentId);
        $data = array();
        if(!empty($all)) foreach ($all as $key => $item){
            if($item->parent_id == $parentId && in_array(($dayOfWeek),json_decode($item->day_prize,true))){
                $this->updateCacheCategory($item->id);
            }
        }
        return $data;
    }

    private function updateCacheCategory($categoryId){
        $this->load->model("result_model");
        $resultModel = new Result_model();
        /*upcache home*/
        $resultModel->getByCategory($categoryId,1,1,true);
        /*upcache mien*/
        $resultModel->getByCategory($categoryId,1,7,true);
        /*upcache dayofweek*/
        $resultModel->getByCategoryDayOfWeek($categoryId,date('N'),1,7,'',true);
        /*upcache detail*/
        $resultModel->getFromDayToDay($categoryId,date('Y-m-d'),date('Y-m-d'),true);
        $resultModel->getResultNearest(date('Y-m-d'),$categoryId,1,0,true);
    }

    public function allCache(){
        $listCache = get_filenames(FCPATH . 'application' . DIRECTORY_SEPARATOR . 'cache');
        dd($listCache);
    }
}