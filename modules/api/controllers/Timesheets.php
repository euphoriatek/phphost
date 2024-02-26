<?php
defined('BASEPATH') or exit('No direct script access allowed');

require __DIR__ . '/REST_Controller.php';

class Timesheets extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

 /**
     * @api {get} api/timesheets/ Get all data
     * @api {get} api/timesheets/{{ID}} Get data by ID
     * @apiName GetData
     *
     * @apiParam {id} id Data unique ID.
     *
     * @apiSuccess {Object} Data Information
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     [
     *         {
     *             "task_id": "2",
     *             "start_time": "10:00:00",
     *             "end_time": "12:00:00",
     *             "staff_id ": "2",
     *             "hourly_rate": "5.00",
     *             "note": "testing note",
     *         }
     *     ]
     *
     * @apiError DataNotFound The id of the data was not found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *         "status": false,
     *         "message": "No data were found"
     *     }
     */


     public function data_get($id = '')
     {
         $data = $this->Api_model->get_table('taskstimers', $id);
 
         if ($data) {
             $this->response($data, REST_Controller::HTTP_OK);
         } else {
             $this->response(['status' => FALSE, 'message' => 'No data were found'], REST_Controller::HTTP_NOT_FOUND);
         }
     }


       /**
     * @api {post} api/timesheets/ Post data
     * @apiName Post data
     *
     * @apiParamExample {Multipart Form} Request-Example:
     * 
     *       "task_id": "2",
     *       "start_time": "10:00:00",
     *       "end_time": "12:00:00",
     *       "staff_id ": "2",
     *       "hourly_rate": "5.00",
     *       "note": "testing note",
     *         
     * 
     * @apiSuccessExample Success-Response:
     * HTTP/1.1 200 OK
     *  {
     *      "status": true,
     *      "message": "Data Added Successfully"
     *  }
     *
     * @apiError DataNotAdded.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *  {
     *      "status": false,
     *      "error": "Data not Added"    
     *  }
     */

     public function data_post()
     {
         \modules\api\core\Apiinit::the_da_vinci_code('api');
         $data = $this->input->post();
 
         $this->form_validation->set_rules('task_id', 'Task', 'trim|required');
         $this->form_validation->set_rules('start_time', 'Start Time', 'trim|required');
         $this->form_validation->set_rules('end_time', 'End Time', 'required');
         $this->form_validation->set_rules('staff_id', 'Staff Member', 'required');
         $this->form_validation->set_rules('hourly_rate', 'Time (h)', 'required');
         $this->form_validation->set_rules('note', 'Note', 'required');
        
         if ($this->form_validation->run() == FALSE) {
             $message = array('status' => FALSE, 'error' => $this->form_validation->error_array(), 'message' => validation_errors());
             $this->response($message, REST_Controller::HTTP_NOT_FOUND);
         } else {
 
             $id = $this->Api_model->timesheets($data);
 
             if ($id > 0 && !empty($id)) {
                 $message = array('status' => TRUE, 'message' => 'Data Added Successfully');
                 $this->response($message, REST_Controller::HTTP_OK);
             } else {
                 $message = array('status' => FALSE, 'message' => 'Data Add Fail');
                 $this->response($message, REST_Controller::HTTP_NOT_FOUND);
             }
         }
     }


/**
     * @api {put} api/timesheets/{{UPDATE_ID}} Update data
     * @apiName Update data
     * @apiParam {id} unique ID for update data.
     *
     * @apiParamExample {json} Request-Example:
     * {
     *    "task_id": "2",
     *    "start_time": "07:00:00",
     *    "end_time": "09:00:00",
     *    "staff_id ": "2",
     *    "hourly_rate": "15.00",
     *    "note": "Timesheets Notes",
     *  }
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "status": true,
     *       "message": "Data Update Successful."
     *     }
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *  {
     *       "status": false,
     *       "message": "Data Not Acceptable OR Not Provided"
     *  }
     *
     * {
     *    "status": false,
     *    "message": "Data Update Fail."
     * }
     */


     public function data_put($id = '')
     {
         $_POST = json_decode($this->security->xss_clean(file_get_contents("php://input")), true);

         if (empty($_POST) || !isset($_POST)) {
             $message = array('status' => FALSE, 'message' => 'Data Not Acceptable OR Not Provided');
             $this->response($message, REST_Controller::HTTP_NOT_ACCEPTABLE);
         }
         $this->form_validation->set_data($_POST);
         if (empty($id) && !is_numeric($id)) {
             $message = array('status' => FALSE, 'message' => 'Invalid data or missing Send ID. please provide updated data ID.');
             $this->response($message, REST_Controller::HTTP_NOT_FOUND);
         } else {
             $_POST['id'] = $id;
             $update_data = $this->input->post();
             $data = $_POST;
             $output = $this->Api_model->timesheetUpdate($data);
             if ($output > 0 && !empty($output)) {
                 $message = array('status' => TRUE, 'message' => 'Data Update Successful.');
                 $this->response($message, REST_Controller::HTTP_OK);
             } else {
                 $message = array('status' => FALSE, 'message' => 'Data Update Fail.');
                 $this->response($message, REST_Controller::HTTP_NOT_FOUND);
             }
         }
     }



  /**
     * @api {delete} api/timesheets/{{DELETE_ID}} Delete data
     * @apiName Delete data
     *
     * @apiParam {id} unique ID for data Deletion.
     *
     * @apiSuccessExample Success-Response:
     * HTTP/1.1 200 OK
     *  {
     *      "status": true,
     *      "message": "Delete Successful."
     *  }
     *
     * @apiError DataNotAdded.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *  {
     *      "status": false,
     *      "message": "Delete Fail."
     *  }
     */

     public function data_delete($id = '')
    {
        $id = $this->security->xss_clean($id);

        if (empty($id) && !is_numeric($id)) {
            $message = array('status' => FALSE, 'message' => 'Invalid ID');
            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        } else {
            $this->load->model('api_model');
            $output = $this->api_model->timesheetDelete($id);

            if ($output === TRUE) {
                $message = array('status' => TRUE, 'message' => 'Delete Successful.');
                $this->response($message, REST_Controller::HTTP_OK);
            } else {
                $message = array('status' => FALSE, 'message' => 'Delete Fail.');
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    

}


    