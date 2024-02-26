<?php

defined('BASEPATH') or exit('No direct script access allowed');

add_option("acf_onn", "1", 1);
update_option("acf_onn", "1");


if (!$CI->db->table_exists(db_prefix() . 'advance_custom_fields')) {
    
    $CI->db->query('CREATE TABLE `' . db_prefix() . "advance_custom_fields` (
  `id` int(11) NOT NULL,
  `fieldto` varchar(255) NOT NULL,
  `rel_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `data` text NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `type` varchar(255) NOT NULL,
  `options` varchar(255) NOT NULL,
  `display_inline` tinyint(1) NOT NULL DEFAULT '1',
  `field_order` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  `show_on_pdf` int(11) NOT NULL,
  `show_on_ticket_form` int(11) NOT NULL,
  `only_admin` tinyint(1) NOT NULL DEFAULT '0',
  `show_on_table` tinyint(1) NOT NULL DEFAULT '0',
  `show_on_client_portal` int(11) NOT NULL,
  `disalow_client_to_edit` int(11) NOT NULL,
  `default_value` varchar(255) NOT NULL
  
) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');

    
}