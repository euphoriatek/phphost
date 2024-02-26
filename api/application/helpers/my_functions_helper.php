<?php
defined('BASEPATH') or exit('No direct script access allowed');

hooks()->add_action('app_admin_head','acf_css');
hooks()->add_action('app_admin_footer','acf_js');

function acf_css(){
    $CI        = &get_instance();
    $link1 = 'http://localhost/accountcrm/perfex_crm/modules/advance_custom_field/asset/css/acf_style.css';
    $html ='<link rel="stylesheet" type="text/css" id="acf-css" href="'.$link1.'" >';
    echo  $html;

}

function acf_js(){
    $CI        = &get_instance();
    $link1 = 'http://localhost/accountcrm/perfex_crm/modules/advance_custom_field/asset/js/acf_js.js';
    $html ='<script type="text/javascript" src="'.$link1.'"></script>';
    echo  $html;

}