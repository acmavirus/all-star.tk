<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    $config['num_links'] = 3;
    $config['enable_query_strings'] = TRUE;
    $config['use_page_numbers'] = TRUE;
    /*SET PARAM PAGE*/
    $config['page_query_string'] = FALSE;
    $config['query_string_segment'] = 'page';
    /*SET PARAM PAGE*/
    $config['reuse_query_string'] = TRUE;

    $config['full_tag_open'] = '<ul class="pagination">';
    $config['full_tag_close'] = '</ul>';
    $config['first_tag_open'] = '<li class="page-item">';
    $config['first_tag_close'] = '<li class="page-item">';
    $config['next_tag_open'] = '<li>';
    $config['next_tag_close'] = '<li class="page-item">';
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '<li class="page-item">';
    $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="javascript:;" title="Trang hiện tại">';
    $config['cur_tag_close'] = '</a><li class="page-item">';
    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '<li class="page-item">';
    $config['last_tag_open'] = '<li class="last">';
    $config['last_tag_close'] = '<li class="page-item">';
    $config['first_link'] = '&laquo;';
    $config['last_link'] = '&raquo;';
    $config['prev_link'] = '<span aria-hidden="true">&lsaquo;</span>';
    $config['next_link'] = '<span aria-hidden="true">&rsaquo;</span>';
    $config['display_pages'] = true;