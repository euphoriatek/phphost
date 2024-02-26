<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php echo form_open_multipart($this->uri->uri_string(), ['id'=>'mailbox_compose_form']); ?>
<div class="clearfix mtop20"></div>
<div class="row">
  <div class="col-md-12">
    <div class="form-group">      
    <i class="fa fa-question-circle mbpull-left" data-toggle="tooltip" data-title="<?php echo _l('mailbox_multi_email_split'); ?>"></i>
    <?php
        $to      = '';
        $cc      = '';
        $subject = '';
        $body    = '';
    ?>
    <?php if (isset($mail)) {
        $to      = $mail->to;
        $cc      = $mail->cc;
        $subject = $mail->subject;
        $body    = $mail->body;
    }
    ?>
    <?php echo render_input('to', 'mailbox_to', $to); ?>
    <?php echo render_input('cc', 'CC', $cc); ?>
    <?php echo render_input('subject', 'mailbox_subject', $subject); ?>
	<?php
        $CI = &get_instance();
        $CI->db->select()
            ->from(db_prefix().'staff')
            ->where(db_prefix().'staff.mail_password !=', '');
        $staffs = $CI->db->get()->result_array();
		
		$myid = get_staff_user_id();
		
        $CI->db->select()
            ->from(db_prefix().'staff')
            ->where('staffid', $myid );
        $currentusers = $CI->db->get()->result_array();
		
        foreach ($currentusers as $currentuser) {
            $mail_signature = $currentuser['mail_signature'];
        }
		
		
    ?>
    <hr />
    <?php 
        if ($mail_signature !== null) {
            echo render_textarea('body', '', $body.$mail_signature, [], [], '', 'tinymce tinymce-compose');
        } else {
            echo render_textarea('body', '', $body, [], [], '', 'tinymce tinymce-compose');
        }
    ?>    
    </div>
    <div class="attachments">
      <div class="attachment">
        <div class="mbot15">
          <div class="form-group">
            <label for="attachment" class="control-label"><?php echo _l('ticket_add_attachments'); ?></label>
            <div class="input-group">
              <input type="file" extension="<?php echo str_replace('.', '', get_option('ticket_attachments_file_extensions')); ?>" filesize="<?php echo file_upload_max_size(); ?>" class="form-control" name="attachments[0]" accept="<?php echo get_ticket_form_accepted_mimes(); ?>">
              <span class="input-group-btn">
                <button class="btn btn-success add_more_attachments p8-half" data-max="<?php echo get_option('maximum_allowed_ticket_attachments'); ?>" type="button"><i class="fa fa-plus"></i></button>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="btn-group mbpull-left">
      <a href="<?php echo admin_url().'mailbox'; ?>" class="btn btn-warning close-send-template-modal"><?php echo _l('cancel'); ?></a>       
    </div>
    <div class="modal fade" id="sales_item_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">
                        <span class="edit-title"><?php echo _l('assign_lead'); ?></span>
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-warning affect-warning hide">
                                <?php echo _l('changing_items_affect_warning'); ?>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                 <div class="form-group">
                                  <div id="leads">
                          <?php
                        $selected = [];
                           if (is_admin() || get_option('staff_members_create_inline_customer_groups') == '1') {
                               echo render_select_with_input_group('select_lead[]', $leads, ['id', 'name'], 'select_lead', $selected, '<div class="input-group-btn"><a href="#" class="btn btn-default" data-toggle="modal" data-target="#customer_group_modal"><i class="fa fa-plus"></i></a></div>', ['multiple' => true, 'data-actions-box' => true], [], '', '', false);
                           } else {
                               echo render_select('select_lead[]', $leads, ['id', 'name'], 'select_lead', $selected, ['multiple' => true, 'data-actions-box' => true], [], '', '', false);
                           }
                          ?>        
                      </div>
                                </div>
                            </div>
                    </div>
                    <div class="clearfix mbot15"></div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo _l('submit'); ?></button>
        </div>
    </div>
    </div>
    </div>
    <div class="pull-right">
      <button class="btn btn-info" type="button" data-toggle="modal" data-target="#sales_item_modal"><?php echo _l('assign_to_leads'); ?></button>   
      <?php if (!isset($mail)) {?>   
      <button type="submit" name="sendmail" value="draft" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>" class="btn btn-primary">
        <i class="fa fa-file menu-icon"></i> <?php echo _l('mailbox_save_draft'); ?></button>
      <?php } ?>
      <button type="submit" name="sendmail" value="outbox" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>" class="btn btn-info">
          <i class="fa fa-paper-plane menu-icon"></i>
          <?php echo _l('mailbox_send'); ?>          
        </button>
    </div>
</div>
</div>
<?php echo form_close(); ?>




<div class="modal fade" id="customer_group_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button group="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title"><?php echo _l('customer_group_edit_heading'); ?></span>
                    <span class="add-title"><?php echo _l('add_new', _l('lead_lowercase')); ?></span>
                </h4>
            </div>
            <?php echo form_open('admin/leads/lead', ['id' => 'customer-group-modal']); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php echo render_input('name', 'lead_group_name'); ?>
            <?php echo render_input('email', 'lead_group_email'); ?>
                        <?php echo form_hidden('id'); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button group="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button group="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<script>
    window.addEventListener('load',function(){
       appValidateForm($('#customer-group-modal'), {
        name: 'required',
    email: 'required'
    }, manage_customer_groups);
       $('#customer_group_modal').on('show.bs.modal', function(e) {
        var invoker = $(e.relatedTarget);
        var group_id = $(invoker).data('id');
        $('#customer_group_modal .add-title').removeClass('hide');
        $('#customer_group_modal .edit-title').addClass('hide');
        $('#customer_group_modal input[name="id"]').val('');
        $('#customer_group_modal input[name="name"]').val('');
        // is from the edit button
        if (typeof(group_id) !== 'undefined') {
            $('#customer_group_modal input[name="id"]').val(group_id);
            $('#customer_group_modal .add-title').addClass('hide');
            $('#customer_group_modal .edit-title').removeClass('hide');
            $('#customer_group_modal input[name="name"]').val($(invoker).parents('tr').find('td').eq(0).text());
        }
    });
   });
    function manage_customer_groups(form) {
        var data = $(form).serialize();
        var url = form.action;
    var formData = new URLSearchParams(data);
        var nameValue = formData.get('name');
        $.post(url, data).done(function(response) {
            response = JSON.parse(response);
            if (response.success == true) {
                if($.fn.DataTable.isDataTable('.table-customer-groups')){
                    $('.table-customer-groups').DataTable().ajax.reload();
                }
                if($('body').hasClass('dynamic-create-groups') && typeof(response.id) != 'undefined') {
          console.log(data);
                    var groups = $('select[name="select_lead[]"]');
                    groups.prepend('<option value="'+response.id+'">'+nameValue+'</option>');
                    groups.selectpicker('refresh');
                }
                alert_float('success', response.message);
            }
            $('#customer_group_modal').modal('hide');
        });
        return false;
    }
</script>