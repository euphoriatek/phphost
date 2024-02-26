<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-6">
            <div class="panel_s">
               <div class="panel-body">
                  <h3><?php echo _l('sms_title'); ?></h3>
                  <br>
                  <div class="emailsmswrapper">
                  <form action="<?php print(admin_url('customemailandsmsnotifications/email_sms/sendEmailSms')) ?>" enctype='multipart/form-data' method="post">
                    <h5><?php echo _l('customer_or_leads'); ?></h5>
                    <select class="selectpicker"
		                  name="customer_or_leads"
		                  data-width="100%" id="customer_or_leads" onchange="show();">	      
	                    	<option value=""><?php echo _l('ceasn_none'); ?></option>
	                    	<option value="customers"><?php echo _l('ceasn_customers'); ?></option>
	                    	<option value="leads"><?php echo _l('ceasn_leads'); ?></option>
	                </select><br><br>
<hr>
					<div class="customers" id="customers" style="display: none;">
						<div class="form-group select-placeholder">
							<label for="clientid" class="control-label"><h5><?php echo _l('select_customer'); ?></h5></label>
							<select id="clientid" name="select_customer[]" multiple="true" data-live-search="true" data-width="100%" class="ajax-search<?php if(isset($invoice) && empty($invoice->clientid)){echo ' customer-removed';} ?>" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">

							<?php $selected = (isset($invoice) ? $invoice->clientid : '');

							if($selected == ''){
								$selected = (isset($customer_id) ? $customer_id: '');
							}

							if($selected != ''){
								$rel_data = get_relation_data('customer',$selected);
								$rel_val = get_relation_values($rel_data,'customer');
								echo '<option value="'.$rel_val['id'].'" selected>'.$rel_val['name'].'</option>';
							}?>
							
							</select>
						</div>
					</div>

	                <div id="leads" style="display: none;">
    	                <?php
	    	            $selected = [];
	                     //if (isset($customer_groups)) {
	                     //    foreach ($customer_groups as $group) {
	                     //        array_push($selected, $group['groupid']);
	                     //    }
	                     //}
	                     if (is_admin() || get_option('staff_members_create_inline_customer_groups') == '1') {
	                         echo render_select_with_input_group('select_lead[]', $leads, ['id', 'name'], 'select_lead', $selected, '<div class="input-group-btn"><a href="#" class="btn btn-default" data-toggle="modal" data-target="#customer_group_modal"><i class="fa fa-plus"></i></a></div>', ['multiple' => true, 'data-actions-box' => true], [], '', '', false);
	                     } else {
	                         echo render_select('select_lead[]', $leads, ['id', 'name'], 'select_lead', $selected, ['multiple' => true, 'data-actions-box' => true], [], '', '', false);
	                     }
    	                ?>       	
	                </div>
	                <br>
			        <h5><?php echo _l('template_select_title'); ?></h5>
			        <select class="selectpicker"
		                  name="template"
		                  data-actions-box="true"
		                  data-width="100%" id="tempaltes">
	                     	<option value="">Nothing Selected</option>
	                     <?php foreach ($templates as $template) { ?>
							<option value="<?php print($template['id']) ?>"><?php print($template['template_name']) ?></option>
		                 <?php } ?>
	                  </select>
						<br><br>

					  <div class="form-group">
					  	<h5><?php echo _l('subject'); ?> <i class="fa fa-question-circle" data-toggle="tooltip" data-title="Supports {contact_firstname}, {contact_lastname} & {client_company}" data-original-title="" title=""></i></h5>
						<input type="text" class="form-control" name="subject">
					  </div>
<br>
					  <h5><?php echo _l('write_your_notification'); ?></h5>
                      <script> function countChars(obj){ document.getElementById("charNum").innerHTML = '<i class="fa fa-calculator" aria-hidden="true"></i> '+obj.value.length; } </script>
	                  <textarea placeholder="<?php echo _l('sms_textarea_placeholder'); ?>" name="message" rows="10" onkeyup="countChars(this);" class="form-control" id="msg_content"></textarea>
	                <p id="charNum"><i class="fa fa-calculator" aria-hidden="true"></i> 0</p>

						<hr>
	                  <div>
	                  		<h5><?php echo _l('attachment_note'); ?></h5>
		                  <input name="file_mail" value="filemail" class="check_label radio" type="file">
	                  </div>
						
							
	                  <div class="check_div_mail"><hr>
					  <h5><?php echo _l('notification_type'); ?></h5>
		                  <input name="mail_or_sms" value="mail" class="check_label radio" type="radio" checked style="display:inline-block"> <span class="mail-or-sms-choice"><?php echo _l('send_as_email'); ?></span>
	                  </div>
					  <div class="check_div_sms">
		                  <input name="mail_or_sms" value="sms" class="check_label radio" type="radio" style="display:inline-block"> <span class="mail-or-sms-choice"><?php echo _l('send_as_sms'); ?></span>
					  </div>

					  <div class="check_div_mail"><hr>
	                  	<label for="custom_date"><?php echo _l('custom_date'); ?></label>
						<input type="date" class="form-control" name="custom_date" id="date">
						<br>
						<div id="custom_time_div">
    						<label for="custom_time"><?php echo _l('custom_time'); ?></label>
    						<input type="time" class="form-control timepicker" name="custom_time" id="custom_time">
						</div>
	                  </div>
	                  <hr><br>
	                  <button class="btn-tr btn btn-info invoice-form-submit transaction-submit"><?php echo _l('send'); ?></button>
                  </form>
                 </div>
               </div>
				
            </div>
         </div>
      </div>
   </div>
</div>
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
<?php init_tail(); ?>
<script type="text/javascript">

	function show(){
		var c_l = $('#customer_or_leads').val();
		
		if(c_l == 'customers'){
			$('#customers').show();
			$('#leads').hide();
		}else if(c_l == 'leads'){
			$('#leads').show();
			$('#customers').hide();
		}else{
			$('#leads').hide();
			$('#customers').hide();
		}
		
	}

	jQuery(document).ready(function($) {
		$('#tempaltes').change(function(e){
        	var template_info_url = "<?= base_url(CUSTOMEMAILANDSMSNOTIFICATIONS_MODULE.'/template/get_template_data'); ?>";
        	var template_id = $(this).val();
        	if (template_id === "") {
    			return false;
			}
			$.ajax({
				url: template_info_url,
				type: 'POST',
				dataType: 'json',
				data: {template_id:template_id},
				success:function(resJSON){
					$("#msg_content").html(resJSON[0].template_content);
				}
			});	
		});
		$('#custom_time_div').hide();
		 $('input[name="custom_date"]').change(function () {
            var customDate = $(this).val();
            if (customDate !== "") {
                $('#custom_time_div').show();
            } else {
                $('#custom_time_div').hide();
            }
        });
	});
</script>
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
</body>
</html>