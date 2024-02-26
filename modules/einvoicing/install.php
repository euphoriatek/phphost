<?php

defined('BASEPATH') or exit('No direct script access allowed');

add_option('einvoicing_seller_company_id', '');
add_option('einvoicing_seller_electronic_address', '');
add_option('einvoicing_seller_electronic_address_scheme', '0088');
add_option('einvoicing_seller_company_id_scheme', '0183');

$CI->db->query("INSERT INTO `".db_prefix()."customfields` (`fieldto`,`name`, `type`, `options`, `default_value`, `field_order`, `bs_column`, `slug`) VALUES ('customers', 'Electronic Address', 'input','','','','12','customers_electronic_address');");
$CI->db->query("INSERT INTO `".db_prefix()."customfields` (`fieldto`,`name`, `type`, `options`, `default_value`, `field_order`, `bs_column`, `slug`) VALUES ('customers', 'Electronic Address Scheme', 'input','','0002','','12','customers_electronic_address_scheme');");
