<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Define global constants
defined('PERFEX_SAAS_MODULE_NAME') or define('PERFEX_SAAS_MODULE_NAME', 'perfex_saas');
defined('PERFEX_SAAS_MODULE_NAME_SHORT') or define('PERFEX_SAAS_MODULE_NAME_SHORT', 'ps');

/**@deprecated 0.0.6 */
defined('PERFEX_SAAS_TENANT_COLUMN') or define('PERFEX_SAAS_TENANT_COLUMN', 'perfex_saas_tenant_id');

defined('PERFEX_SAAS_ROUTE_ID') or define('PERFEX_SAAS_ROUTE_ID', 'ps');
defined('PERFEX_SAAS_FILTER_TAG') or define('PERFEX_SAAS_FILTER_TAG', 'psaas');
defined('APP_DB_DRIVER') or define('APP_DB_DRIVER', 'mysqli');
defined('PERFEX_SAAS_MAX_SLUG_LENGTH') or define('PERFEX_SAAS_MAX_SLUG_LENGTH', 20);

/** @var string Perfex CRM base upload folder with trailing slash */
defined('PERFEX_SAAS_UPLOAD_BASE_DIR') or define('PERFEX_SAAS_UPLOAD_BASE_DIR', 'uploads/');

// Tenant recognition modes
defined('PERFEX_SAAS_TENANT_MODE_PATH') or define('PERFEX_SAAS_TENANT_MODE_PATH', 'path');
defined('PERFEX_SAAS_TENANT_MODE_DOMAIN') or define('PERFEX_SAAS_TENANT_MODE_DOMAIN', 'custom_domain');
defined('PERFEX_SAAS_TENANT_MODE_SUBDOMAIN') or define('PERFEX_SAAS_TENANT_MODE_SUBDOMAIN', 'subdomain');

/** @var string[] List of options field that will should not be controlled by tenants i.e security fields */
defined('PERFEX_SAAS_ENFORCED_SHARED_FIELDS') or define('PERFEX_SAAS_ENFORCED_SHARED_FIELDS', ['allowed_files']);

/** @var string[] List of dangerous extensions */
defined('PERFEX_SAAS_DANGEROUS_EXTENSIONS') or define('PERFEX_SAAS_DANGEROUS_EXTENSIONS', [
    ".php", ".exe", ".sh", ".bat", ".cmd", ".js", ".vbs",
    ".py", ".pl", ".jsp", ".aspx", ".cgi", ".htaccess", ".ini", ".dll"
]);


defined('PERFEX_SAAS_CRON_PROCESS_MODULE') or define('PERFEX_SAAS_CRON_PROCESS_MODULE', 'module-update');
defined('PERFEX_SAAS_CRON_PROCESS_SINGLE_TENANT_MODULE') or define('PERFEX_SAAS_CRON_PROCESS_SINGLE_TENANT_MODULE', 'module-update-single-tenant');
defined('PERFEX_SAAS_CRON_PROCESS_PACKAGE') or define('PERFEX_SAAS_CRON_PROCESS_PACKAGE', 'package-update');

defined('PERFEX_SAAS_GLOBAL_ACTIVE_MODULES_OPTION_KEY') or define('PERFEX_SAAS_GLOBAL_ACTIVE_MODULES_OPTION_KEY', 'perfex_saas_registered_global_active_modules');

/** Route prefix */
defined('PERFEX_SAAS_ROUTE_NAME') or define('PERFEX_SAAS_ROUTE_NAME', 'saas');
