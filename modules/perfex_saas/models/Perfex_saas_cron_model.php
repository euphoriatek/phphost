<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Perfex_saas_cron_model extends App_model
{
    /**
     * Timeout limit in seconds
     *
     * @var integer
     */
    private $available_execution_time = 25;

    /**
     * Monitor of used seconds
     *
     * @var integer
     */
    private $start_time;

    private $cron_cache;

    /**
     * @inheritDoc
     */
    public function __construct()
    {
        parent::__construct();

        $max_time = (int)ini_get("max_execution_time");
        if ($max_time <= 0)
            $max_time = 60 * 60; //1 hour;

        $this->available_execution_time = $max_time - 5; //minus 5 seconds for cleanup
        $this->start_time = time();
    }

    /**
     * Init cron activites for both tenant and master routine
     *
     * @return void
     */
    public function init()
    {
        $this->cron_cache = $this->get_settings();

        // Run saas related cron task for tenants
        if (perfex_saas_is_tenant()) {
            try {

                // Check and run db upgrade if neccessary
                $this->run_database_upgrade();

                $tenant = perfex_saas_tenant();

                $should_run_setup_triggers = !empty($_GET['setup']) || perfex_saas_should_run_cron_triggers_for_tenant($tenant);
                if ($should_run_setup_triggers) {
                    // Update modules
                    perfex_saas_setup_modules_for_tenant();
                }
            } catch (\Throwable $th) {
                log_message('error', $th->getMessage());
            }
            return;
        }


        // Run master cron instance. From here, deployer is called and tenants cron triggered is called.
        try {

            // Run deployment of new instances
            perfex_saas_deployer();

            // Trigger cron for active instances
            $start_from_id = (int) ($this->cron_cache->last_proccessed_instance_id ?? 0);

            $this->perfex_saas_model->db->where('id >', $start_from_id)->where('status', 'active');
            $companies = $this->perfex_saas_model->companies();

            // Run cron for each instance and return the last processed instance id
            $last_proccessed_instance_id = $this->run_tenants_cron($companies);
            $this->cron_cache->last_proccessed_instance_id = $last_proccessed_instance_id;

            // Update cron cache
            $this->cron_cache->cron_last_success_runtime = time();

            if ($last_proccessed_instance_id === 0) {
                // Reset module activation pointer and other triggers since all company must have been processed
                $processes = [PERFEX_SAAS_CRON_PROCESS_MODULE, PERFEX_SAAS_CRON_PROCESS_SINGLE_TENANT_MODULE];
                $_cron_cache = $this->get_settings();
                foreach ($processes as $process) {

                    if (isset($this->cron_cache->{$process}))
                        unset($this->cron_cache->{$process});

                    if (isset($_cron_cache->{$process}))
                        unset($_cron_cache->{$process});
                }
                $this->save_settings($_cron_cache, false);

                $this->shared_license_file();
            }

            $this->save_settings($this->cron_cache);
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage());
        }
    }

    /**
     * Get cron cache
     *
     * @return object
     */
    public function get_settings($field = '')
    {
        $settings = perfex_saas_get_options('perfex_saas_cron_cache');
        $cron_cache = (object) (empty($settings) ? [] : json_decode($settings));
        if ($field) return $cron_cache->{$field} ?? '';
        return $cron_cache;
    }

    /**
     * Update cron cache
     *
     * @param array|object $settings
     * @return object
     */
    public function save_settings($settings, $merge = true)
    {
        $settings = $merge ? array_merge((array)$this->get_settings(), (array)$settings) : (array)$settings;
        perfex_saas_update_option('perfex_saas_cron_cache', json_encode($settings));
        return (object)$settings;
    }

    /**
     * Run cron for all tenants.
     * 
     * It uses Timeouter to detect timeout and return last processed id
     *
     * @param integer $start_from_id    The company id to start from.
     * @return integer The last processed company id
     */
    public function run_tenants_cron($companies)
    {
        $this->load->library(PERFEX_SAAS_MODULE_NAME . '/Timeouter');

        // Get all instance and run cron
        foreach ($companies as $company) {

            $time_elapsed = (time() - $this->start_time);

            try {

                // Start timeout
                Timeouter::limit($this->available_execution_time - $time_elapsed, 'Time out.');

                declare(ticks=1) {

                    try {
                        // Calculate total storage and update if neccessary
                        perfex_saas_update_tenant_storage_size($company);

                        $url = perfex_saas_tenant_base_url($company, 'cron/index', 'path');

                        // Simulate cron command: wget -q -O- http://saasdomain.com/demoinstance/ps/cron/index
                        $cron_result = perfex_saas_http_request($url, ['timeout' => 20]);

                        if (!$cron_result || (!empty($cron_result['error']) && !empty($cron_result['result']))) {

                            log_message("Error", "Cron: Error running cron on $url :" . $cron_result['error']);
                        }
                    } catch (\Throwable $th) {
                        log_message('error', "Cron job failure for $company->slug :" . $th->getMessage());
                    }
                }

                Timeouter::end();
            } catch (\Throwable $th) {

                Timeouter::end();
                return $company->id;
            }
        }

        return 0;
    }

    /**
     * Run perfex database upgrade.
     * This should be used for the tenant or master. Its advisable to run for only tenants 
     * and master admin should run db upgrade from the screen UI
     *
     * @return void
     */
    public function run_database_upgrade()
    {
        if ($this->app->is_db_upgrade_required($this->app->get_current_db_version())) {

            hooks()->do_action('pre_upgrade_database');

            if (perfex_saas_is_tenant()) {
                // Reset the database update info from tenant view
                hooks()->add_action('database_updated', function () {
                    update_option('update_info_message', '');
                }, PHP_INT_MAX);
            }

            // This call will redirect and code should not be placed after following line.
            $this->app->upgrade_database();
        }
    }

    /**
     * Method to make modules that uses file based validation available to all tenants.
     * Needed as some module does not use purchsae code in database. Thus not sharable through package shared settings.
     *
     * @return void
     */
    public function shared_license_file()
    {
        $modules = $this->app_modules->get();
        foreach ($modules as $module) {
            $directory = $module['path'] . 'libraries/';
            $file = $directory . '.licint';
            // We only want to do this if the module as actually been activated 
            // with a right license key by super admin
            if (file_exists($file) && is_writable($file)) {
                $date = date('d-m-Y', strtotime('+50 years')); // Get the date 50 years from now
                $date_enc = base64_encode($date);
                file_put_contents($file, $date_enc);
            }
        }
    }
}