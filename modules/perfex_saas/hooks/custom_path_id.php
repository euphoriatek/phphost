<?php

defined('BASEPATH') or exit('No direct script access allowed');

if ($is_tenant) return;

// Add path id to saas custom config file
hooks()->add_filter('before_settings_updated', function ($data) {

    $field = 'perfex_saas_route_id';
    if (!empty($data['settings'][$field])) {
        $path_id = strtolower(slug_it($data['settings'][$field]));

        if (!empty($path_id) && PERFEX_SAAS_ROUTE_ID !== $path_id) {

            unset($data['settings'][$field]);

            // Add to file definition or update
            $dest = APPPATH . 'config/my_saas_config.php';
            $signature = 'defined(\'PERFEX_SAAS_ROUTE_ID\') or define(\'PERFEX_SAAS_ROUTE_ID\', \'' . $path_id . '\');';

            if (!file_exists($dest)) {
                $can_save = perfex_saas_file_put_contents($dest, "<?php defined('BASEPATH') or exit('No direct script access allowed');\n$signature");
            } else {
                $content = file_get_contents($dest);
                $definition = str_replace("'$path_id'", "'" . PERFEX_SAAS_ROUTE_ID . "'",  $signature);
                if (strpos($content, $definition) !== false)
                    $can_save = replace_in_file($dest, $definition, $signature);
                else
                    $can_save = perfex_saas_file_put_contents($dest, "$content\n$signature");
            }

            if ($can_save)
                $data['settings'][$field] = $path_id;
        }
    }

    return $data;
});
