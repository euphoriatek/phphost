<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: E-Invoicing - European Invoicing
Description: The E-Invoicing - European Invoicing module for Perfex CRM enables seamless electronic invoicing within the European framework. It ensures efficient and compliant invoicing practices for businesses in Europe, adhering to European e-invoicing regulations.
Version: 1.1.0
Author: LenzCreative
Author URI: https://codecanyon.net/user/lenzcreativee/portfolio
Requires at least: 1.0.*
*/

define('EINVOICING_MODULE_NAME', 'einvoicing');

hooks()->add_action('admin_init', 'einvoicing_module_init_menu_items');
hooks()->add_action('admin_init', 'einvoicing_permissions');

include( __DIR__ . '/vendor/autoload.php');

/**
 * Load the module helper
 */
$CI = & get_instance();
$CI->load->helper(EINVOICING_MODULE_NAME . '/einvoicing'); //on module main file

function einvoicing_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
        'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete')
    ];

    register_staff_capabilities('einvoicing', $capabilities, _l('einvoicing'));
}

/**
 * Register activation module hook
 */
register_activation_hook(EINVOICING_MODULE_NAME, 'einvoicing_module_activation_hook');

function einvoicing_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(EINVOICING_MODULE_NAME, [EINVOICING_MODULE_NAME]);

/**
 * Init module menu items in setup in admin_init hook
 * @return null
 */
function einvoicing_module_init_menu_items()
{
    $CI = &get_instance();

    if (has_permission('einvoicing', '', 'view')) {
        $CI->app_menu->add_sidebar_menu_item('einvoicing', [
            'slug' => 'einvoicing',
            'name' => _l('einvoicing'),
            'position' => 6,
            'href'     => admin_url('einvoicing'),
            'icon' => 'fas fa-file-invoice'
        ]);
    }

    if (has_permission('einvoicing', '', 'view')) {
        $CI->app_menu->add_sidebar_children_item('einvoicing', [
            'slug' => 'einvoicing-view',
            'name' => _l('einvoicing'),
            'href' => admin_url('einvoicing/manage'),
            'position' => 11,
        ]);
    }

    if (is_admin()) {
        $CI->app_menu->add_sidebar_children_item('einvoicing', [
            'slug' => 'einvoicing-settings',
            'name' => _l('settings'),
            'href' => admin_url('einvoicing/settings'),
            'position' => 11,
        ]);
    }

}