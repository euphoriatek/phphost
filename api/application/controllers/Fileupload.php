<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Fileupload extends App_Controller
{
     public function __construct()
    {
    	parent::__construct();
        $this->load->model('file_model');
        $this->load->helper('file');
        $this->load->helper('invoices_helper');
        $this->load->helper('upload_helper');
    }
    public function index()
    {

    	$data['id'] = $_GET['id'];
    	// $data =array("invoice_id" => 91, "file_name" => "test");
    	// $id = $this->file_model->add($data);
    	// print_r($data['id']);exit;
        $this->load->view('admin/File_upload', $data);
        
    }

    public function upload_item_image() {

         if (!file_exists(rtrim('assets', '/') . '/invoice_items/')) {
            mkdir(rtrim('assets', '/') . '/invoice_items/', 0777, true);
        }
        $upload_path = "assets/invoice_items/";

        $file_paths = array();
      

        $random_number = 0;

        if ($random_number != 0) { // Removed unnecessary closing parenthesis
            $random_number = $random_number;
        } else {
            $random_number = rand(10000, 99999);
        }

        // echo "<pre>";
        // print_r($_FILES['files']['tmp_name']);exit();
        // foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {
            $original_filename = $_FILES['files']['name'];
            $filename = str_replace(' ', '_', $original_filename);
            $file_extension = pathinfo($filename, PATHINFO_EXTENSION);
            $allowed_extensions = array('png', 'jpg', 'jpeg');
            // Check if the file extension is allowed
            if (!in_array(strtolower($file_extension), $allowed_extensions)) {
                echo 'Error: Only PNG, JPG, and JPEG files are allowed.';
                return;
            }
            $location = $upload_path . $filename;
            //
           
            if (move_uploaded_file($_FILES['files']['tmp_name'], $location)) {

               
                if (is_image($location)) {
                    create_img_invoice_thumb($upload_path, $filename, '180', '140');
                }
                $file_paths[] = $location;
                $insertData = array(
                    'invoice_id' => $this->input->post('invoice_id'),
                    'file_name' => $filename,
                );
                // Insert data into the 'item_images' table
                $this->db->insert(db_prefix() . 'invoice_attachments', $insertData);
                
               
            } else {
                echo 'Error uploading file: ' . $filename;
                return;
            }
        // }
        // get images against random_number
        // $images_random = item_images_load_against_random_number($random_number);
        // Return JSON response with file paths
        echo json_encode(['random_number' => $random_number, 'images_random' => $random_number]);
    }
    
}