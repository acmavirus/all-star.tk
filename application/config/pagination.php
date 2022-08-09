<?php

defined('BASEPATH') OR exit('No direct script access allowed');
$config['num_links'] = 4;
$config['last_link'] = '››';
$config['first_link'] ='‹‹';
$config['enable_query_strings'] = TRUE;
$config['use_page_numbers'] = TRUE;
/*SET PARAM PAGE*/
$config['page_query_string'] = FALSE;
$config['query_string_segment'] = 'page';
/*SET PARAM PAGE*/
$config['reuse_query_string'] = TRUE;
$config['full_tag_open'] = '<ul class="pagination justify-content-center">';
$config['full_tag_close'] = '</ul>';
$config['first_tag_open'] = '<li class="page-item">';
$config['first_tag_close'] = '</li>';
$config['next_tag_open'] = '<li class="page-item">';
$config['next_tag_close'] = '</li>';
$config['prev_tag_open'] = '<li class="page-item">';
$config['prev_tag_close'] = '</li>';
$config['cur_tag_open'] = '<li class="page-item active"><a href="javascript:;" class="page-link">';
$config['cur_tag_close'] = '</a></li>';
$config['num_tag_open'] = '<li class="page-item">';
$config['num_tag_close'] = '</li>';
$config['last_tag_open'] = '<li class="page-item">';
$config['last_tag_close'] = '</li>';

$config['prev_link'] = '<span aria-hidden="true"><i class="fas fa-long-arrow-alt-left"></i></span>';
$config['next_link'] = '<span aria-hidden="true"><i class="fas fa-long-arrow-alt-right"></i></span>';
$config['display_pages'] = true;
