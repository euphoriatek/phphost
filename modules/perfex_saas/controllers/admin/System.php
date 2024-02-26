<?php defined('BASEPATH') or exit('No direct script access allowed');

use app\services\zip\Unzip;

class System extends AdminController
{

    public const PERFEX_SAAS_UPDATE_URL = 'https://perfextosaas.com/evanto.php?purchase_code=[PC]&action=[AC]&module=[MD]';

    /**
     * @inheritDoc
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->library('app_module_installer');
        $this->load->library('app_modules');
    }

    /**
     * Display index page
     */
    public function index()
    {
        // Check for permission
        if (!has_permission('perfex_saas_settings', '', 'view')) {
            return access_denied('perfex_saas_companies');
        }

        $purchase_code = get_option('perfex_saas_purchase_code');

        // Show list of comapnies
        $data['title'] = _l('perfex_saas_update_ext');
        $data['purchase_code'] = $purchase_code;
        $data['saas_module'] = $this->app_modules->get(PERFEX_SAAS_MODULE_NAME);
        $data['remote_modules'] = [];

        if (!empty($purchase_code)) {
            $url = $this->get_url($purchase_code, 2);
            $request = (object)perfex_saas_http_request($url, []);
            if (!empty($request->error))
                set_alert('error', $request->error);

            $response = (object)json_decode($request->response ?? '');
            $data['remote_modules'] = (object)($response->modules ?? []);
        }

        $this->load->view('settings/system', $data);
    }


    public function save_purchase_code()
    {
        if ($this->input->post()) {
            $status = 'danger';
            $message = '';

            try {
                $purchase_code = $this->input->post('purchase_code', true);
                if (empty($purchase_code))
                    throw new \Exception(_l("perfex_saas_invalid_purchase_code"), 1);

                // Validate code on server
                $url = $this->get_url($purchase_code);
                $request = (object)perfex_saas_http_request($url, []);
                if (!empty($request->error))
                    throw new \Exception($request->error, 1);

                $response = json_decode($request->response);

                $purchase = (object)($response->purchase ?? []);
                if (empty($purchase))
                    throw new \Exception(_l("perfex_saas_error_fetching_purchase_code_details"), 1);

                if (empty($purchase->buyer))
                    throw new \Exception(_l("perfex_saas_invalid_purchase_code"), 1);

                // Save purchase code in cache
                update_option('perfex_saas_purchase_code', $purchase_code);

                $status = 'success';
                $message = _l('updated_successfully', _l('perfex_saas_purchase_code'));
            } catch (\Throwable $th) {
                $message = $th->getMessage();
                $purchase_code = '';
                update_option('perfex_saas_purchase_code', $purchase_code);
            }

            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => $status, 'message' => $message, 'purchase_code' => $purchase_code]);
                exit;
            }

            set_alert($status, $message);

            return perfex_saas_redirect_back();
        }
    }

    public function get_module($module)
    {
        $moduleTemporaryDir = get_temp_dir() . time() . '/';
        if (!is_dir($moduleTemporaryDir))
            mkdir($moduleTemporaryDir, 0777, true);

        $purchase_code = get_option('perfex_saas_purchase_code');

        // Usage
        $remoteFileUrl = $this->get_url($purchase_code, 3, $module); // Replace with the actual remote file URL
        $downloadedModuleFile = $moduleTemporaryDir . $module . '.zip'; // Set the name for the downloaded file

        if (file_exists($downloadedModuleFile))
            unlink($downloadedModuleFile);

        // Initialize CURL session for HEAD request
        $ch = curl_init($remoteFileUrl);

        // Set CURL options for HEAD request
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);

        // Execute CURL session for HEAD request
        curl_exec($ch);

        // Capture the response code and content type
        $responseCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

        // Close CURL session for HEAD request
        curl_close($ch);

        $response = ['success' => false, 'error' => ''];

        if ($responseCode === 200 && strpos($contentType, 'application/zip') !== false) {
            // It's a file download response

            // Initialize CURL session for GET request to download the file
            $ch = curl_init($remoteFileUrl);

            // Set CURL options for GET request to download the file
            $fp = fopen($downloadedModuleFile, 'w');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_HEADER, false);

            // Execute CURL session for GET request to download the file
            curl_exec($ch);

            // Close CURL session and file pointer
            curl_close($ch);
            fclose($fp);


            try {

                $unzip = new Unzip();

                $unzip->extract($downloadedModuleFile, $moduleTemporaryDir);

                if ($this->app_module_installer->check_module($moduleTemporaryDir) === false) {
                    $response['message'] = _l('perfex_saas_module_not_found');
                } else {
                    $unzip->extract($downloadedModuleFile, APP_MODULES_PATH);
                    $response['success'] = true;
                }

                delete_files($moduleTemporaryDir);
                delete_dir($moduleTemporaryDir);

                $this->app_modules->upgrade_database($module);
                if ($this->app_modules->is_active($module)) {
                    $this->app_modules->activate($module);
                }

                $response['message'] = _l('perfex_saas_module_installed_successfully');
            } catch (Exception $e) {
                $response['message'] = $e->getMessage();
            }
        } else {
            // It's not a file download response, directly output JSON text
            $_response = perfex_saas_http_request($remoteFileUrl, []);
            $_response = json_decode($_response['response'], true) ?? [];
            $response = ['success' => false, 'message' => $_response['message'] ?? 'Unkown error'];
        }

        if ($this->input->is_ajax_request()) {
            echo json_encode($response);
            exit;
        }

        set_alert($response['success'] ? 'success' : 'danger', $response['message']);

        return perfex_saas_redirect_back();
    }

    public function activate($module_name)
    {
        $this->app_modules->activate($module_name);
        return perfex_saas_redirect_back();
    }

    public function deactivate($module_name)
    {
        $this->app_modules->deactivate($module_name);
        return perfex_saas_redirect_back();
    }

    private function get_url($purchase_code, $action = 1, $module = '')
    {
        return str_replace(['[PC]', '[AC]', '[MD]'], [$purchase_code, $action, $module], self::PERFEX_SAAS_UPDATE_URL);
    }
}