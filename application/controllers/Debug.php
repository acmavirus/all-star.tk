<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Debug extends Public_Controller
{
    protected $_data_category;
    protected $_data_post;
    protected $_data_match;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['category_model', 'post_model','match_model']);
        $this->_data_post = new Post_model();
        $this->_data_category = new Category_model();
        $this->_data_match = new Match_model();
    }

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
        $this->delete_cache_file(base_url());
        exit;
    }

    public function update_cache_home(){
        $this->delete_cache_file(base_url());
        exit;
    }

    public function update_cache_cate($slug){
        $this->delete_cache_file(base_url());
        exit;
    }

    public function update_cache_detail($id){
        $oneItem = $this->_data_post->get_post_by_id($id,true);
        $this->delete_cache_file(getUrlPost($oneItem));
        exit;
    }

    public function test(){
        $allPost = $this->_data_post->getDataAll('','','id, content');
        if(!empty($allPost)) foreach ($allPost as $item){
            $content = $item->content;
            $content = $this->executeImage($content, $item);
        }
    }

    private function executeImage($content, $itemPost){
        preg_match_all("~<img.*src\s*=\s*[\"']([^\"']+)[\"'][^>]*>~i", $content, $matches);

        if(!empty($matches[1])) foreach ($matches[1] as $item){
            $urlImg = "https://asoikeo.com" . $item;
//            echo $urlImg ."<br> \n";
            if($this->checkImgExist($urlImg) == false) {
//                echo "/src='".addcslashes($item, '/')."'/i";
                echo "IMAGE error \n";
                $content = preg_replace("/<img(.*)src=(\\'|\")(".addcslashes($item, '/').".*)(\\'|\")>/i","",$content);
                if($this->_data_post->update(['id' => $itemPost->id],['content' => $content])) echo "Updated contend id $itemPost->id ! \n";
            }
        }

        /*Xử lý ảnh chưa có title*/
        /*preg_match("/title=\"(.*?)\"/", $content, $matchesNoTitle);
        dd($matchesNoTitle);*/
        return $content;
    }

    private function checkImgExist($image){
        $ch = curl_init($image);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
        curl_close($ch);
        if( $httpCode == 200 ){
            if(empty($size)) return false;
            return true;
        }
        return false;
    }
}

