<?php

/**
 * Ensures that the module init file can't be accessed directly, only within the application.
 */
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Advance Custom Field
Description: Advance Custom Field Module by MZ
Version: 1.1.0
Requires at least: 2.3.*
*/


/**
* Register activation module hook
*/
define('ACF_MODULE_NAME', 'advance_custom_field');

register_activation_hook(ACF_MODULE_NAME, 'acf_module_activation_hook');
function acf_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

register_deactivation_hook(ACF_MODULE_NAME, 'acf_module_deactivation_hook');
function acf_module_deactivation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/deactivate.php');
    
}

// register_uninstall_hook(ACF_MODULE_NAME, 'acf_module_uninstall_hook');
function acf_module_uninstall_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/uninstall.php');
    
}
