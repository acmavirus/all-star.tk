<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reviews extends Public_Controller
{

    protected $_reviews;
    public function __construct()
    {
        parent::__construct();
        //tải model
        $this->load->model(['reviews_model']);
        $this->_reviews = new Reviews_model();
    }

    public function ajax_vote()
    {
        $this->checkRequestPostAjax();
        $data = $this->input->post();
        $ip  = $this->input->ip_address();
        $exits = $this->_reviews->checkExistByArgs([
            'ip' => $ip,
            'url' => str_replace(base_url(), "", $data['url'])
        ]);


        if ($exits) {
            $message['type'] = 'warning';
            $message['message'] = 'Bạn đã đánh giá cho bài viết rồi';
        } else {
            $params  = [
                'url' => str_replace(base_url(), "", $data['url']),
                'rate' => $data['rate'],
                'ip' => $ip,

            ];
            if ($this->_reviews->save($params, $this->_reviews->table)) {
                $message['type'] = 'success';
                $message['message'] = 'Bạn đã vote ' . $data['rate'] . ' sao cho bài viết !!';
            } else {
                $message['error'] = 'error';
                $message['message'] = 'Có lỗi xảy ra';
            }
        }
        $rate = $this->_reviews->getRate(
            [
                'url' => $data['url']
            ]
        );
        $message['vote']['avg'] = $rate->avg;
        $message['vote']['count'] = $rate->count_vote;
        $this->returnJson($message);
    }
}
