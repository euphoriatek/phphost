<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head();?>
<link rel="stylesheet" type="text/css" id="acf-css" href="http://localhost/accountcrm/perfex_crm/modules/advance_custom_field/asset/css/acf_style.css" ><link href="http://localhost/accountcrm/perfex_crm/modules/ma/assets/css/custom.css?v=100"  rel="stylesheet" type="text/css" /><link href="http://localhost/accountcrm/perfex_crm/modules/ma/assets/plugins/Drawflow-master/docs/drawflow.min.css?v=100"  rel="stylesheet" type="text/css" /><link href="http://localhost/accountcrm/perfex_crm/modules/ma/assets/plugins/Drawflow-master/docs/beautiful.css?v=100"  rel="stylesheet" type="text/css" />

<div id="wrapper">
  <div class="content">
      <div class="panel_s">
        <div class="panel-body">
          <h4 class="customer-profile-group-heading"><?php echo _l($title); ?></h4>
            <?php echo form_hidden('csrf_token_hash', $this->security->get_csrf_hash()); ?>
          
            <?php echo form_open(admin_url('acf_flows/workflow_builder_save'),array('id'=>'workflow-form','autocomplete'=>'off')); ?>
            <?php echo form_hidden('campaign_id',(isset($campaign) ? $campaign->id : '') ); ?>
            <?php echo form_hidden('workflow',(isset($campaign) ? $campaign->workflow : '')); ?>
            <?php echo form_close(); ?>
          <div class="row wrapper">
          <div class="col-md-2 action-tab">
            <div class="drag-drawflow" draggable="true" ondragstart="drag(event)" data-node="flow_start">
              <span class="text-success glyphicon glyphicon-log-in"> </span><span class="text-success"> <?php echo _l('flow_start'); ?></span>
            </div>
            <?php if($field_type == "button"){?>
            <div class="drag-drawflow" draggable="true" ondragstart="drag(event)" data-node="condition">
              <span class="text-danger glyphicon glyphicon-fullscreen"> </span><span class="text-danger"> <?php echo _l('condition'); ?></span>
            </div>
            <?php } ?>
            <div class="drag-drawflow" draggable="true" ondragstart="drag(event)" data-node="action">
              <span class="text-info glyphicon glyphicon-retweet"> </span><span class="text-info"> <?php echo _l('action'); ?></span>
            </div>
          </div>
          <div class="col-md-10">
            <div id="drawflow" ondrop="drop(event)" ondragover="allowDrop(event)">
              <div class="btn-export" onclick="save_workflow(); return false;"><?php echo _l('save'); ?></div>
              <div class="btn-clear" onclick="editor.clearModuleSelected()">Clear</div>
            </div>
          </div>
        </div>
        </div>
      </div>
  </div>
</div>
<!-- </script> -->
<?php init_tail(); ?>
<script src="http://localhost/accountcrm/perfex_crm/modules/ma/assets/plugins/Drawflow-master/src/drawflow.js?v=100"></script>
<?php require 'assets/js/acf_flows/workflow_builder_js.php';?>

