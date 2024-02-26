<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Acf_flows extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ma/Ma_model');
        $this->load->model('Acf_flows_model');
    }

    public function index(){
        $this->load->view('admin/acf_flows/workflow_node/actions.php');

    }

    /**
     * campaign management
     * @return view
     */
    public function campaigns(){
        $data['title'] = _l('campaigns');

        $data['group'] = $this->input->get('group');

        if($data['group'] == ''){
            $data['group'] = 'list';
        }

        if ($data['group'] == 'chart') {
            $data['data_campaign_pie'] = $this->ma_model->get_data_campaign_pie_chart($data);
            $data['data_campaign_column'] = $this->ma_model->get_data_campaign_column_chart($data);
        }

        $data['categories'] = [];
        
        $data['view'] = 'admin/acf_flows/workflow_node/' . $data['group'];
        
        $this->load->view('admin/acf_flows/workflow_builder', $data);
    }

    /**
     * campaign table
     * @return json
     */
    public function campaign_table(){
        if ($this->input->is_ajax_request()) {
           
            $select = [
                'name',
                'published',
            ];

            $where = [];

            // Filter by custom groups


            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'acf_flows';
            $join         = [
        ];
            $result       = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['id']);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
                $row   = [];
                $categoryOutput = '<span>'.$aRow['name'].'</span>';

                $categoryOutput .= '<div class="row-options">';
                $categoryOutput .= '<a href="' . admin_url('acf_flows/campaign_detail/' . $aRow['id']) . '">' . _l('view') . '</a>';

                if (has_permission('ma_campaigns', '', 'edit')) {
                    $categoryOutput .= ' | <a href="' . admin_url('ma/campaign/' . $aRow['id']) . '">' . _l('edit') . '</a>';
                }

                if (has_permission('ma_campaigns', '', 'delete')) {
                    $categoryOutput .= ' | <a href="' . admin_url('ma/delete_campaign/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                }

                $categoryOutput .= '</div>';
                $row[] = $categoryOutput;
                // $row[] = ma_get_category_name($aRow['category']);

                $checked = '';
                if ($aRow['published'] == 1) {
                    $checked = 'checked';
                }

                $_data = '<div class="onoffswitch">
                    <input type="checkbox" ' . ((!has_permission('ma_campaigns', '', 'edit') && !is_admin()) ? 'disabled' : '') . ' data-switch-url="' . admin_url() . 'acf_flows/change_campaign_published" name="onoffswitch" class="onoffswitch-checkbox" id="c_' . $aRow['id'] . '" data-id="' . $aRow['id'] . '" ' . $checked . '>
                    <label class="onoffswitch-label" for="c_' . $aRow['id'] . '"></label>
                </div>';

                // For exporting
                $_data .= '<span class="hide">' . ($checked == 'checked' ? _l('is_active_export') : _l('is_not_active_export')) . '</span>';
                $row[] = $_data;

                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
        }
    }

    /**
     * view campaign
     * @return view
     */
    public function campaign_detail($id){
        $this->Acf_flows_model->get_lead_by_campaign($id);
        $data['campaign'] = $this->Acf_flows_model->get_campaign($id);
        $data['point_actions'] = $this->Acf_flows_model->get_object_by_campaign($id, 'point_action', 'object');
        $data['emails'] = $this->Acf_flows_model->get_object_by_campaign($id, 'email', 'object');
        $data['sms'] = $this->Acf_flows_model->get_object_by_campaign($id, 'sms', 'object');

        $data['stages'] = $this->Acf_flows_model->get_object_by_campaign($id, 'stage', 'object');
        $data['segments'] = $this->Acf_flows_model->get_object_by_campaign($id, 'segment', 'object');

        $data['title'] = _l('campaign');

        $this->load->view('admin/acf_flows/campaign_detail', $data);
    }

    /**
     * change campaign published
     * @param  integer
     * @param  string
     */
    public function change_campaign_published($id, $status)
    {
        if (has_permission('ma_campaigns', '', 'edit')) {
            if ($this->input->is_ajax_request()) {
                $this->Acf_flows_model->change_campaign_published($id, $status);
            }
        }
    }


    /**
     * create flow entry
     * @param  name
     * @param  string
     */
    public function create_acf_flow()
    {
        if ($this->input->post()) {
        	$data = $this->input->post();
        	$this->Acf_flows_model->add($data);
        }
    }

    public function get_acf_flow()
    {
        $type = $this->input->get('type');
        $data = $this->Acf_flows_model->get($type);
        echo json_encode($data);
        die();
    }

    public function create_flows_fields(){
        $json_data = $this->input->raw_input_stream;
        if (!empty($json_data)) {
            $data = json_decode($json_data, true);
            $this->Acf_flows_model->add_flow_fields($data);
        }
    }

    public function perform_action(){
        $ref_id = $this->input->get('ref_id');
        $to_id = $this->input->get('to_id');
        $data = $this->Acf_flows_model->get_flows($ref_id);
        if ($data) {
           if ($data[0]['flow_number'] == 2) {
               $notified = add_notification([
                        'description'     => 'Notification send!!!!',
                        'touserid'        => get_staff_user_id(),
                        'fromuserid'      => get_staff_user_id(),
                        'link'            => 'proposals/list_proposals/' . $to_id,
                        'additional_data' => serialize([
                            "updated_successfully",
                        ]),
                    ]);
           }
           if ($data[0]['flow_number'] == 3){
                $proposalsData = $this->Acf_flows_model->getProposalsData();
                if ($proposalsData){
                    $subtotal = $proposalsData['subtotal'];
                    $total = $proposalsData['total'];
                    $dynamicOperations = array(
                        array('variable1' => $subtotal, 'variable2' => $total, 'operation' => 'add'),
                        array('variable1' => $subtotal, 'variable2' => $total, 'operation' => 'subtract'),
                        array('variable1' => $subtotal, 'variable2' => $total, 'operation' => 'multiply'),
                        array('variable1' => $subtotal, 'variable2' => $total, 'operation' => 'divide'),
                    );
                    $resultsDynamic = $this->performOperationsDynamic($dynamicOperations);
                    echo "<table border='2'>";
                    echo "<tr><th>Variable 1</th><th>Variable 2</th><th>Operation</th><th>Result</th></tr>";
                    foreach($dynamicOperations as $key => $operation){
                        $variable1 = $operation['variable1'];
                        $variable2 = $operation['variable2'];
                        $operationType = $operation['operation'];
                        $result = $resultsDynamic[$key];
                        echo "<tr><td>$variable1</td><td>$variable2</td><td>$operationType</td><td>$result</td></tr>";
                    }
                    echo "</table>";
                }
            }
        }
    }
    /**
     * workflow builder
     * @return view
     */
    public function workflow_builder($id){
        $type = $this->input->get('type');
        $data['campaign'] = $this->Acf_flows_model->get_campaign($id);

        $data['title'] = _l('workflow_builder');

        $data['is_edit'] = true;
        $data['field_type'] = $type;
        $this->load->view('admin/acf_flows/workflow_builder', $data);
    }

    /**
     * get workflow node html
     * @return view
     */
    public function get_workflow_node_html(){
        $data = $this->input->post();
        switch ($data['type']) {
            case 'flow_start':
                $data['segments'] = $this->Ma_model->get_segment('', 'published = 1');
                $data['forms'] = $this->Ma_model->get_forms();
                break;
            case 'sms':
                $data['sms'] = $this->Ma_model->get_sms();
                break;
            case 'email':
                $data['emails'] = $this->Ma_model->get_email();
                break;
            case 'action':
                $data['segments'] = $this->Ma_model->get_segment('', 'published = 1');
                $data['stages'] = $this->Ma_model->get_stage();
                $data['point_actions'] = $this->Ma_model->get_point_action();
                break;
            default:
                // code...
                break;
        }
       switch ($data['url_type']) {
            case "button":
            case "text":
                $flow = array(2, 3, 4, 5, 6, 7, 8, 12, 14, 15, 16);
                break;
            case "icon":
                $flow = array(14);
                break;
            case "date":
                $flow = array(1, 2, 4, 6, 7);
                break;
            case "file":
                $flow = array(1, 2, 5, 9, 15, 17);
                break;
            case "table":
                $flow = array(2, 6, 3);
                break;
            case "chart":
                $flow = array(1, 2, 3, 4, 12);
                break;
            case "progress_bar":
                $flow = array(14);
                break;
            case "fileviewer_pdf":
                $flow = array(10, 11);
                break;
            default:
                $flow = array();
        }

        $data['flows'] = $flow;
        $this->load->view('admin/acf_flows/workflow_node/'.$data['type'], $data);
    }

    function get_actions(){
        $id = $this->input->get('flow_id');
        $data = $this->Acf_flows_model->get_actions($id);
        echo json_encode($data);
        die();
    }

    /**
     * workflow builder save
     * @return redirect
     */
    public function workflow_builder_save(){
        $data = $this->input->post();
        $data['workflow'] = $this->input->post('workflow', false);
        $success = $this->Acf_flows_model->workflow_builder_save($data);
        if($success){
            $message = _l('updated_successfully', _l('workflow'));
        }
        echo '<script>window.history.go(-2);</script>';
        exit;
        // redirect(admin_url('proposals/proposal'));
    }


    public function performOperationsDynamic($operations)
    {
        $results = array();
        foreach ($operations as $operation){
            $variable1 = $operation['variable1'];
            $variable2 = $operation['variable2'];
            $operationType = $operation['operation'];
            switch ($operationType) {
                case 'add':
                    $results[] = $variable1 + $variable2;
                    break;
                case 'subtract':
                    $results[] = $variable1 - $variable2;
                    break;
                case 'multiply':
                    $results[] = $variable1 * $variable2;
                    break;
                case 'divide':
                    $results[] = ($variable2 != 0) ? $variable1 / $variable2 : null;
                    break;
                default:
                    $results[] = null;
            }
        }
        return $results;
    }

}

