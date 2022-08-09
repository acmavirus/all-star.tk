<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends Admin_Controller {

    public function __construct(){
        parent::__construct();
        //tải file ngôn ngữ
        //$this->lang->load('setting');
    }

    public function index()
    {
        $dataContent = file_get_contents('/home/settings.vuivetv.com/public_html/settings.cfg');
        $data = $dataContent ? json_decode($dataContent,true) : array();
        $data['heading_title'] = "Cấu hình hệ thống";
        $data['heading_description'] = 'Cấu hình chung';

        $data['list_db'] = $this->get_list_db();
        $dataPost = $this->input->post();
        if (!empty($dataPost)){
            if(file_put_contents('/home/settings.vuivetv.com/public_html/settings.cfg',json_encode($dataPost))) {
                $message['type'] = "success";
                $message['message'] = "Cập nhật thành công !";
            }else{
                $message['type'] = 'error';
                $message['message'] = "Cập nhật thất bại !";
            }
            $this->returnJson($message);
        }
        //$data['path1'] = glob(FCPATH.'application/views/templates/*',GLOB_ONLYDIR);
        $data['main_content'] = $this->load->view($this->template_path . $this->_controller . DIRECTORY_SEPARATOR . 'index', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function ajax_clear_cache_db(){
        shell_exec("curl http://vuive.live/debug/update_cache");
        shell_exec("curl http://ghiban.live/debug/update_cache");
        shell_exec("curl http://bongtron.live/debug/update_cache");
        $this->returnJson([
            'type' => 'success',
            'message' => 'Xóa cache database thành công !'
        ]);
    }

    public function ajax_clear_cache_image(){
        if($this->recursiveDelete(MEDIA_PATH . DIRECTORY_SEPARATOR . 'thumb'))
            $this->returnJson([
                'type' => 'success',
                'message' => 'Xóa cache ảnh thành công !'
            ]);
        else
            $this->returnJson([
                'type' => 'error',
                'message' => 'Xóa cache ảnh không thành công !'
            ]);
    }

    private function recursiveDelete($str) {
        if (is_file($str)) {
            return @unlink($str);
        }
        elseif (is_dir($str)) {
            $scan = glob(rtrim($str,'/').'/*');
            foreach($scan as $index=>$path) {
                $this->recursiveDelete($path);
            }
            return @rmdir($str);
        }
    }

    private function get_list_db(){
        $this->load->helper('directory');
        $map = directory_map(FCPATH.'db');
        $data = array();
        if(!empty($map)) foreach ($map as $item){
            $data[] = get_file_info(FCPATH.'db/'.$item);
        }
        usort($data, function($a, $b) {
            return ($a['date'] > $b['date']) ? -1 : 1;
        });
        return $data;
    }

    public function downloadFile(){
        $this->load->helper('download');
        $file = $this->input->get('file');
        $data = $this->load->file(FCPATH.'db/'.$file,true);
        force_download($file,$data);
    }

    public function ajax_backup_db(){
        if($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            // Load the DB utility class
            $this->load->dbutil();
            $prefs = array(
                'tables'        => array(),   // Array of tables to backup.
                'ignore'        => array(),                     // List of tables to omit from the backup
                'format'        => 'text',                       // gzip, zip, txt
                'filename'      => DB_DEFAULT_NAME."_".date('d_m_y').".sql",              // File name - NEEDED ONLY WITH ZIP FILES
                'add_drop'      => TRUE,                        // Whether to add DROP TABLE statements to backup file
                'add_insert'    => TRUE,                        // Whether to add INSERT data to backup file
                'newline'       => "\n"                         // Newline character used in backup file
            );
            // Backup your entire database and assign it to a variable
            $backup = $this->dbutil->backup($prefs);

            $this->load->helper('file');
            $data = write_file(FCPATH.'db/'.DB_DEFAULT_NAME."_".date('d_m_y_H_i').".sql", $backup);
            sleep(1);
            $message['type'] = "success";
            $message['message'] = 'Backup successful';
            $this->session->set_flashdata('message',$message);
            print json_encode($data);
        }
        exit;
    }

    public function ajax_restore_db(){
        if($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $dbFile = $this->input->post('db_name');
            $file = FCPATH.'db/'.$dbFile;

            $cmd = "mysql -h {$this->db->hostname} -u {$this->db->username} -p{$this->db->password} {$this->db->database} < $file";
            if(function_exists('shell_exec')) {
                print json_encode(['status' => 1,'msg' => 'Khôi phục data thành công !']);
                shell_exec($cmd);
            }else{
                print json_encode(['status' => 0,'msg' => 'Server chưa bật hàm shell_exec() !']);
            }
        }
        exit;
    }

    public function ajax_delete_db(){
        if($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $dbFile = $this->input->post('db_name');
            $file = FCPATH.'db/'.$dbFile;
            print json_encode(unlink($file));
        }
        exit;
    }
    public function sortdate( $a, $b ) {
        return $a["date"] - $b["date"];
    }

}