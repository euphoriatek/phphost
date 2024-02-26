<?php defined('BASEPATH') or exit('No direct script access allowed');

class Page_builder extends AdminController
{
    public $baseDir;
    public $themeBaseDir;
    public $themeBaseUrl;
    public $mediaDirectoryPath;
    public $mediaDirectoryUrl;
    public $allowedImageExtensions = ['ico', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

    /**
     * @inheritDoc
     */
    public function __construct()
    {
        parent::__construct();

        $this->baseDir = module_dir_path(PAGE_BUILDER_MODULE_NAME, 'views/');

        $landingDir = page_builder_pages_path();

        $mediaFolder = $landingDir . '/media';
        $this->mediaDirectoryPath = FCPATH . $mediaFolder;
        $this->mediaDirectoryUrl = base_url($mediaFolder);

        list($themePath, $themeUrl) = page_builder_get_theme_path_url();
        $this->themeBaseDir = $themePath;
        $this->themeBaseUrl = $themeUrl;

        if (!has_permission('page_builder', '', 'edit')) {
            redirect(base_url());
        }
    }

    /**
     * Launch page editor into view
     *
     * @return void
     */
    public function builder()
    {
        $data['title'] = _l('page_builder_page_title');
        $options = page_builder_get_options();

        try {
            if (!is_dir($this->themeBaseDir)) {
                mkdir($this->themeBaseDir, 0755, true);
                //@todo Copy each start files
                xcopy($this->baseDir . '../assets/default_pages', $this->themeBaseDir);
                page_builder_remove_dir($this->themeBaseDir . '/assets');
            }
        } catch (\Throwable $th) {
            set_alert('danger', $th->getMessage());
            return redirect(admin_url());
        }

        $data['pages'] = page_builder_get_pages();
        $data['landingpagesBaseUrl'] = module_dir_url(PAGE_BUILDER_MODULE_NAME, 'views/');
        $data['mediaDirectoryUrl'] = $this->mediaDirectoryUrl;
        $data['pagesOptions'] = $options;

        $data['isRTL'] = (is_rtl() ? 'true' : 'false');
        $data['controllerUrl'] = admin_url(PAGE_BUILDER_MODULE_NAME);
        $data['pageActionUrl'] = $data['controllerUrl'] . '/page';
        $data['themeBaseUrl'] = $this->themeBaseUrl;
        $data['builderAssetPath'] = module_dir_url(PAGE_BUILDER_MODULE_NAME, 'assets');

        $this->load->view('builder', $data);
    }

    /**
     * Method to handle page actions.
     * It handly page copy, rename, save and delete.
     *
     * @param string $action The action to perform
     * @return void
     */
    public function page($action = '')
    {
        // Get input data from the POST request and sanitize
        $file   = $this->input->post('file', true) ?? '';
        $newfile = $this->input->post('newfile', true) ?? '';
        $formOptions = $this->input->post('options', true);
        $duplicate = $this->input->post('duplicate', true) === 'true';
        $startTemplateUrl = $this->input->post('startTemplateUrl', true) ?? '';
        $metadata = $this->input->post('metadata', true);

        // Don't do XSS here till we have the final html content
        $html   = $this->input->post('html', false) ?? '';

        // Get the starter template if provided
        if (!empty($startTemplateUrl)) {
            $startTemplateUrl = $this->sanitizeFileName($startTemplateUrl, true);
            $html = file_get_contents($startTemplateUrl);
        }

        // Purify HTML
        $html = page_builder_html_purify($html);

        // Validate html content size
        if (!empty($html)) {

            $fileSizeLimit = 1024 * 1024 * 2; //2 Megabytes max html file size
            if (strlen($html) > $fileSizeLimit)
                return $this->showError(_l('page_builder_content_exceed_file_size', '2mb'));
        }

        // File is required for all actions
        if (empty($file))
            return $this->showError(_l('page_builder_builder_filename_empty'));

        // Restrict writing to the theme base dir only or the media folder
        $file = $this->sanitizeFileName($file, true);
        $validFile = str_starts_with($file, $this->themeBaseDir) || str_starts_with($file, $this->mediaDirectoryPath);
        if (!$validFile) {

            return $this->showError(_l('page_builder_builder_wrong_filepath', [$file]));
        }

        if ($action) {

            // File manager actions: delete and rename
            switch ($action) {

                case 'rename':

                    // Require newfile 
                    if (empty($newfile))
                        return $this->showError(_l('page_builder_builder_newfilename_empty'));

                    // Sanitize file name and validate
                    $newfile = $this->sanitizeFileName($newfile, true);
                    $validNewFile = str_starts_with($newfile, $this->themeBaseDir) || str_starts_with($newfile, $this->mediaDirectoryPath);
                    if (empty($newfile) || !$validNewFile) {

                        return $this->showError(_l('page_builder_builder_wrong_filepath', [$newfile]));
                    }

                    // Rename the file
                    if (!$duplicate && $file !== $newfile) {

                        if (!file_exists($file) || !rename($file, $newfile)) {

                            return $this->showError(_l('page_builder_builder_error_renaming_file', [$file, $newfile]));
                        }
                    }

                    // Perform copy action
                    if ($duplicate) {

                        $dir = dirname($newfile);
                        if (!is_dir($dir)) mkdir($dir, 0755, true);

                        if (!file_exists($file) || !copy($file, $newfile))
                            return $this->showError(_l('page_builder_builder_error_copying_file', [$file, $newfile]));
                    }


                    // Update options i.e landingpage option
                    $options = page_builder_get_options();
                    if (!empty($formOptions) && !$duplicate) {

                        $markAsLanding = $formOptions['landingpage'] === 'yes';

                        // Get current landing page
                        $landingpage = $options['landingpage'] ?? '';

                        // Update
                        if ($markAsLanding) {
                            $landingpage = empty($newfile) ? $file : $newfile;
                        } else if ($landingpage == str_ireplace($this->themeBaseDir, '', $file)) {
                            $landingpage = '';
                        }
                        $options['landingpage'] = str_ireplace($this->themeBaseDir, '', $landingpage);
                        page_builder_save_options($options);
                    }

                    // Update page metadata
                    page_builder_metadata($file, [], $newfile);

                    // Return JSON
                    $actionMode = $duplicate ? _l('page_builder_builder_copied') : _l('page_builder_builder_renamed');
                    echo json_encode(['message' => _l('page_builder_builder_file_action', [$file, $actionMode, $newfile]), 'pagesOptions' => empty($options) ? '' : $options]);
                    exit;

                    break;

                case 'delete':

                    if (!file_exists($file) || !unlink($file)) {

                        return $this->showError(_l('page_builder_builder_error_deleting_file', [$file]));
                    }

                    //Remove metatdata
                    page_builder_metadata($file);

                    // Remove the directory also if no more files
                    $themePath = dirname($file);
                    $htmlFiles = page_builder_get_dir_html_files($themePath);
                    if (empty($htmlFiles) && $this->themeBaseDir != $themePath) {

                        page_builder_remove_dir($themePath);
                    }

                    echo _l('page_builder_builder_file_deleted', [$file]);
                    exit;
                    break;
                default:
                    return $this->showError(_l('page_builder_builder_invalid_action', [$action]));
            }
        } else {

            // Save page content to html file
            if (!$html)
                return $this->showError(_l('page_builder_builder_html_content_empty'));

            $dir = dirname($file);
            if (!is_dir($dir) && !mkdir($dir, 0755, true)) {

                return $this->showError(_l('page_builder_builder_folder_not_exist', [$dir]));
            }

            // Allow only .html extension here
            $pathInfo = pathinfo($file);
            if (empty($pathInfo['extension']) || $pathInfo['extension'] !== "html" || !str_starts_with($pathInfo['dirname'], $this->themeBaseDir))
                throw new \Exception("Error Processing Request", 1);

            $file = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . ".html";

            try {

                // Save content to file
                if (!file_put_contents($file, $html))
                    return $this->showError(_l('page_builder_builder_error_saving_file', [$file]));

                // Update metadata
                page_builder_metadata($file, $metadata);
            } catch (\Throwable $th) {

                return $this->showError($th->getMessage());
            }

            echo _l('page_builder_builder_file_saved', [$file]);
            exit;
        }
    }

    /**
     * Save common builder settings
     *
     * @return void
     */
    public function settings()
    {
        $settings = $this->input->post('settings', true);

        // Get whitelisted domains and clean
        $whitelist = $settings['whitelist'] ?? '';
        if ($whitelist) {
            $whitelist = explode(',', $whitelist);
            foreach ($whitelist as $key => $value) {
                $value = trim($value);
                $whitelist[$key] = $value;
                if (stripos($value, '/') !== false || !filter_var($value, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME))
                    unset($whitelist[$key]);
            }
            $whitelist = implode(',', $whitelist);
        }

        page_builder_save_options(['whitelist' => $whitelist]);

        echo json_encode(['message' => _l('page_builder_saved'), 'allowed_hosts' => page_builder_whitelisted_hosts()]);
        exit;
    }

    /**
     * Scan media folder for all media files to be display in builder media modal.
     *
     * @return void
     */
    public function media_scan()
    {

        $scandir = $this->mediaDirectoryPath;

        // If not exit, attempt creating and copy the default media files to the location.
        if (!is_dir($this->mediaDirectoryPath) && mkdir($this->mediaDirectoryPath, 0755, true)) {
            xcopy($this->baseDir . 'assets/media', $this->mediaDirectoryPath);
        }

        // Run the recursive function
        // This function scans the files folder recursively, and builds a large array

        $scan = function ($dir) use ($scandir, &$scan) {
            $files = [];

            // Is there actually such a folder/file?

            if (file_exists($dir)) {
                foreach (scandir($dir) as $f) {
                    if (!$f || $f[0] == '.') {
                        continue; // Ignore hidden files
                    }

                    if (is_dir($dir . '/' . $f)) {
                        // The path is a folder

                        $files[] = [
                            'name'  => $f,
                            'type'  => 'folder',
                            'path'  => str_replace($scandir, '', $dir) . '/' . $f,
                            'items' => $scan($dir . '/' . $f), // Recursively get the contents of the folder
                        ];
                    } else {
                        // It is a file

                        $files[] = [
                            'name' => $f,
                            'type' => 'file',
                            'path' => str_replace($scandir, '', $dir) . '/' . $f,
                            'size' => filesize($dir . '/' . $f), // Gets the size of this file
                        ];
                    }
                }
            }

            return $files;
        };

        $response = $scan($scandir);

        // Output the directory listing as JSON

        header('Content-type: application/json');
        echo json_encode([
            'name'  => '',
            'type'  => 'folder',
            'path'  => '',
            'items' => $response,
        ]);
        exit;
    }

    /**
     * Handle file upload from the media modal
     *
     * @return void
     */
    public function media_upload()
    {
        $fileName  = $_FILES['file']['name'];
        $extension = strtolower(substr($fileName, strrpos($fileName, '.') + 1));

        // check if extension is on allow list
        if (!in_array($extension, $this->allowedImageExtensions)) {
            return $this->showError(_l('page_builder_builder_file_not_allowed', [$extension]));
        }

        if (!is_dir($this->mediaDirectoryPath) && !mkdir($this->mediaDirectoryPath, 0755, true))
            return $this->showError(_l('page_builder_builder_error_creating_folder', [$this->mediaDirectoryPath]));

        try {
            $destination = $this->sanitizeFileName($this->mediaDirectoryPath . '/' . $fileName);
            if (!move_uploaded_file($_FILES['file']['tmp_name'], $destination))
                return $this->showError(_l('page_builder_builder_file_not_uploaded', [$destination]));
        } catch (\Throwable $th) {
            return $this->showError($th->getMessage());
        }

        if ($this->input->post('onlyFilename', true)) {
            echo $fileName;
        } else {
            echo $destination;
        }
    }

    /**
     * Validate and sanitize file name.
     * It cleans and validate safe extension
     *
     * @param string $file
     * @param boolean $appendThemeDir
     * @return mixed
     */
    private function sanitizeFileName($file, $appendThemeDir = false)
    {
        // Sanitize, remove double dot .. and remove get parameters if any
        $file = preg_replace('@\?.*$@', '', preg_replace('@\.{2,}@', '', preg_replace('@[^\/\\a-zA-Z0-9\-\._]@', '', $file)));

        $pathInfo  = pathinfo($file);
        $extension = $pathInfo['extension'] ?? '';
        $dir = $pathInfo['dirname'];
        $dir = (empty($dir) || $dir == '.' ? '/' : $dir . '/');
        $file = $dir . sanitize_filename($pathInfo['basename']);

        if ($appendThemeDir) {

            if ($extension === 'html') {

                $file = $this->themeBaseDir . $file;
            } else {
                // Media files. Must start with media dir ul
                if (str_starts_with($file, $this->mediaDirectoryUrl))
                    $file = str_ireplace($this->mediaDirectoryUrl, $this->mediaDirectoryPath, $file);
            }
        }

        $file = str_ireplace('//', '/', '/' . $file);

        // Check if extension is on allow list
        if ($extension !== 'html' && !in_array($extension, $this->allowedImageExtensions)) {
            return $this->showError(_l('page_builder_builder_file_not_allowed', [$extension]));
        }

        return $file;
    }

    /**
     * Display error with 500 header
     *
     * @param string $error
     * @return void
     */
    private function showError($error)
    {
        set_status_header(500, $error);
        echo $error;
        exit;
    }
}
