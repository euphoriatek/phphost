<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Acf_flows_model extends App_Model
{
    
    /**
     * Change campaign published
     * @param  mixed $id     campaign id
     * @param  mixed $status status(0/1)
     */
    public function change_campaign_published($id, $status)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'acf_flows', [
            'published' => $status,
        ]);

        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * @param  integer
     * @param  string
     * @return array or string
     */
    public function get_lead_by_campaign($id, $return_type = 'leads'){
        $campaign = $this->get_campaign($id);
        $where = '';

        if($campaign->workflow != ''){
            $workflow = json_decode(json_decode($campaign->workflow), true);

            foreach($workflow['drawflow']['Home']['data'] as $data){
                if($data['class'] == 'flow_start'){
                    if(!isset($data['data']['lead_data_from']) || $data['data']['lead_data_from'] == 'segment'){
                        $where = $this->get_lead_by_segment($data['data']['segment'], 'where');
                    }else{
                        $where .= 'from_ma_form_id = '.$data['data']['form'];
                    }
                }
            }
        }   

        $this->db->where('campaign_id', $id);
        $lead_exception = $this->db->get(db_prefix().'ma_campaign_lead_exceptions')->result_array();
        $lead_exception_where = '';

        foreach($lead_exception as $lead){
            if($lead_exception_where == ''){
                $lead_exception_where = $lead['lead_id'];
            }else{
                $lead_exception_where .= ','.$lead['lead_id'];
            }
        }

        if($lead_exception_where != ''){
            if($where != ''){
                $where .= ' AND '.db_prefix().'leads.id not in ('.$lead_exception_where.')';
            }else{
                $where .= db_prefix().'leads.id not in ('.$lead_exception_where.')';
            }
        }

        if($where == ''){
            $where = '1=0';
        }

        if($return_type == 'leads'){
            $this->db->where($where);
            $leads = $this->db->get(db_prefix().'leads')->result_array();

            return $leads;
        }elseif($return_type == 'where'){
            return $where;
        }

        return false;
    }

    /**
     * Get campaign
     * @param  mixed $id campaign id (Optional)
     * @return mixed     object or array
     */
    public function get_campaign($id = '', $where = [], $count = false, $is_kanban = false)
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            $campaign = $this->db->get(db_prefix() . 'acf_flows')->row();

            return $campaign;
        }

        $this->db->where($where);
        if($is_kanban == false){
            $this->db->where('published', 1);
        }
        $this->db->order_by('name', 'asc');

        if($count == true){
            return $this->db->count_all_results(db_prefix() . 'acf_flows');
        }else{
            return $this->db->get(db_prefix() . 'acf_flows')->result_array();
        }
    }

    /**
     * @param  integer
     * @param  string
     * @param  string
     * @return mixed
     */
    public function get_object_by_campaign($campaign_id, $type = '', $return = 'id'){
        $campaign = $this->get_campaign($campaign_id);
        if ($campaign->workflow !== null) {
        $workflow = explode('\"'.$type.'\":\"',$campaign->workflow);
    	}
        $where = '';
        $object = [];
        if(isset($workflow[1])){
            foreach($workflow as $k => $data){
                if($k != 0){
                    $_workflow = explode('\"',$data);
                    if(isset($_workflow[1]) && !in_array($_workflow[0], $object)){
                        $object[] = $_workflow[0];
                    }
                }
            }
        }

        $data_return = [];
        if($return == 'object'){
            foreach($object as $id){
                switch ($type) {
                    case 'point_action':
                        $point_action = $this->get_point_action($id);
                        if($point_action){
                            $this->db->where('point_action_id', $id);
                            $this->db->where('campaign_id', $campaign_id);
                            $point_action->total = $this->db->count_all_results(db_prefix().'ma_point_action_logs');
                            $data_return[] = $point_action;
                        }
                        break;
                    case 'email':
                        $email_template = $this->get_email($id);
                        if($email_template){
                            $this->db->where('email_id', $id);
                            $this->db->where('campaign_id', $campaign_id);
                            $email_template->total = $this->db->count_all_results(db_prefix().'ma_email_logs');
                            $data_return[] = $email_template;
                        }
                        break;
                    case 'segment':
                        $segment = $this->get_segment($id);
                        if($segment){
                            $this->db->where('segment_id', $id);
                            $this->db->where('campaign_id', $campaign_id);
                            $segment->total = $this->db->count_all_results(db_prefix().'ma_lead_segments');
                            $data_return[] = $segment;
                        }
                        break;
                    case 'stage':
                        $stage = $this->get_stage($id);
                        if($stage){
                            $this->db->where('stage_id', $id);
                            $this->db->where('campaign_id', $campaign_id);
                            $stage->total = $this->db->count_all_results(db_prefix().'ma_lead_stages');

                            $data_return[] = $stage;
                        }
                        break;
                    case 'sms':
                        $sms = $this->get_sms($id);
                        if($sms){
                            $this->db->where('sms_id', $id);
                            $this->db->where('campaign_id', $campaign_id);
                            $sms->total = $this->db->count_all_results(db_prefix().'ma_sms_logs');
                            
                            $data_return[] = $sms;
                        }
                        break;
                    
                    default:
                        // code...
                        break;
                }
            }

            return $data_return;
        }
        
        return $object;
    }

    /**
     * Add new flow
     */
    public function add($data)
    {

        $this->db->insert(db_prefix() . 'acf_flows', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }
        return false;
    }

    public function get($type)
    {

        $this->db->select('*');
        $this->db->where("type", $type);
        $acf_flows = $this->db->get(db_prefix() . 'acf_flows')->result_array();
        // print_r($acf_flows);exit();
        return $acf_flows;
    }

    public function get_actions($id)
    {
        $this->db->select('*');
        $this->db->where('flow_id', $id);
        $actions = $this->db->get(db_prefix() . 'action')->result_array();
        return $actions[0];
    }
    
    public function add_flow_fields($data){
        $this->db->insert(db_prefix() . 'flows_fields', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }
        return false;
    }

    // public function get_flows($ref_id){
    //     $this->db->select('*');
    //     $this->db->where('ref_id', $ref_id);
    //     $data = $this->db->get(db_prefix() . 'flows_fields')->result_array();
    //     if ($data) {
    //        $this->db->select('*');
    //        $this->db->where('ref_id', $data[0]['flow_id']);
    //        $data = $this->db->get(db_prefix() . 'acf_flows')->result_array();
    //        return $data;
    //     }
    // }
    public function get_flows($ref_id) {
        $this->db->select('*');
        $this->db->from(db_prefix() . 'flows_fields');
        $this->db->where('ref_id', $ref_id);
        $this->db->join(db_prefix() . 'acf_flows', 'acf_flows.id = flows_fields.flow_id');
        
        $data = $this->db->get()->result_array();

        return $data;
    }

    /**
     * @param  array
     * @return boolean
     */
    public function workflow_builder_save($data){
        $workflowData = json_decode($data['workflow'], true);
        $conditionNode = null;
        $flowStartNode = null;
        foreach ($workflowData['drawflow']['Home']['data'] as $node) {
            if (isset($node['name']) && $node['name'] === 'condition') {
                $conditionNode = $node;
            }
            if (isset($node['name']) && $node['name'] === 'flow_start') {
                $flowStartNode = $node;
            }
        }
        $conditionValue = ($conditionNode !== null) ? $conditionNode['data']['condition'] : null;
        $flowDropdownValue = ($flowStartNode !== null) ? $flowStartNode['data']['flow_dropdown'] : null;
        if(isset($data['campaign_id']) && $data['campaign_id'] != ''){
            $this->db->where('id', $data['campaign_id']);
            $this->db->update(db_prefix() . 'acf_flows', ['workflow' => json_encode($data['workflow']), "condition" => $conditionValue, "flow_number" => $flowDropdownValue]);

            if ($this->db->affected_rows() > 0) {
                return true;
            }
        }

        return false;
    }

    public function getProposalsData()
    {
        $this->db->select('subtotal, total')->from(db_prefix() . 'proposals');
        $result = $this->db->get()->row_array();
        return $result;
    }
}

?>