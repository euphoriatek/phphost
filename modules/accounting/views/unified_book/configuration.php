<?php defined('BASEPATH') or exit('No direct script access allowed');
$acc_account_relations = get_option('acc_account_relations');
?>
<?php init_head();?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="panel_s">
        <div class="panel-body">
          <ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked customer-tabs" role="tablist">
            <?php
            foreach($tab as $key => $gr){
              ?>
              <li class="<?php if($key == 0){echo 'active ';} ?>setting_tab_<?php echo html_entity_decode($key); ?>">
              <a data-group="<?php echo html_entity_decode($gr); ?>" href="<?php echo admin_url('accounting/unified_book?group='.$gr); ?>">
                <?php if ($gr == 'unified_book') {
                    echo '<i class="fa fa-th" aria-hidden="true"></i>';
                }elseif ($gr == 'configuration') {
                    echo '<i class="fa fa-book" aria-hidden="true"></i>';
                } ?>
                <?php echo _l($gr); ?>
              </a>
            </li>
            <?php } ?>
          </ul>
          <?php echo form_open(admin_url('accounting/update_unified_book'),array('id'=>'general-settings-form')); ?>
          <div class="div_content">
              <div class="row <?php if($acc_account_relations == 0){echo 'hide';} ?>" id="div_acc_account_relations">
                <div class="col-md-6">
                  <?php echo render_select_nested('document_module',$document_module,array('key','name', ''),'document_module',array(),array(),array(),'','',false); ?>
                </div>
                <div class="col-md-6">
                  <div class="form-group" app-field-wrapper="relation_variable">
                  <label for="relation_variable" class="control-label">Relation Variable</label>
                  <select required="" type="relation_variable" class="form-control" id="relation_variable" name="relation_variable"></select>
                  </div>
                </div>
                 <div class="col-md-6">
                  <label><?php echo _l("row_number"); ?></label>
                  <input type="number" name="row_number" class="form-control">
                </div>
                <div class="col-md-12">
                <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
                </div>
                <?php echo form_close(); ?>
              </div>
          <!-- Display saved mappings -->
          <?php if (!empty($unified_book_mapping)) : ?>
              <h4 class="no-margin font-bold"><?php echo _l('mappings'); ?></h4>
              <table class="table table-striped">
                  <thead>
                      <tr>
                          <th><?php echo _l('relation_variable'); ?></th>
                          <th><?php echo _l('row_number'); ?></th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php foreach ($unified_book_mapping as $mapping) : ?>
                          <tr>
                              <td><?php echo $mapping['value']; ?></td>
                              <td><?php echo $mapping['row_number']; ?></td>
                              <td>  <a href="<?php echo admin_url('accounting/delete_unified_mapping/'.$mapping['value']); ?>">
                                <?php echo _l('delete'); ?>
                            </a></td>
                          </tr>
                      <?php endforeach; ?>
                  </tbody>
              </table>
          <?php endif; ?>

          <!-- Rest of your HTML code -->

          </div>
          <?php echo form_close(); ?>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade bulk_actions" id="journal_entry_bulk_actions" tabindex="-1" role="dialog" data-table=".table-journal-entry">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?php echo _l('bulk_actions'); ?></h4>
         </div>
         <div class="modal-body">
            <?php if(has_permission('accounting_journal_entry','','detele')){ ?>
               <div class="checkbox checkbox-danger">
                  <input type="checkbox" name="mass_delete" id="mass_delete">
                  <label for="mass_delete"><?php echo _l('mass_delete'); ?></label>
               </div>
            <?php } ?>
      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
         <a href="#" class="btn btn-info" onclick="bulk_action(this); return false;"><?php echo _l('confirm'); ?></a>
      </div>
   </div>
   <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<?php init_tail(); ?>
</body>
</html>
<script type="text/javascript">
  function capitalizeFirstLetter(str) {
    var i, frags = str.split('_');
    for (i=0; i<frags.length; i++) {
      frags[i] = frags[i].charAt(0).toUpperCase() + frags[i].slice(1);
    }
    return frags.join(' ');
  }
function change_val(data) {
  const elementBlank = document.getElementById('relation_variable');
  elementBlank.innerHTML = '';

  if (data) {
    if (data === "unified_general" || data === "unified_company" || data === "unified_customers") {
      var setting;
      if (data === "unified_general") {
        setting = ['company_logo', 'company_logo_dark', 'favicon', 'companyname', 'main_domain', 'rtl_support_admin', 'rtl_support_client', 'allowed_files'];
      } else if (data === "unified_company"){
        setting = ['invoice_company_name', 'invoice_company_address', 'invoice_company_city', 'company_state', 'invoice_company_country_code', 'invoice_company_postal_code', 'invoice_company_phonenumber', 'company_vat', 'company_info_format'];
      }else if (data === "unified_customers"){
        setting = ['clients_default_theme', 'customer_default_country', 'visible_customer_profile_tabs', 'company_is_required', 'company_requires_vat_number_field', 'allow_registration', 'customers_register_require_confirmation', 'allow_primary_contact_to_manage_other_contacts', 'enable_honeypot_spam_validation','allow_primary_contact_to_view_edit_billing_and_shipping','only_own_files_contacts','allow_contact_to_delete_files','use_knowledge_base','knowledge_base_without_registration','show_estimate_request_in_customers_area','default_contact_permissions','customer_info_format'];
      }

      var key_name = data;
      var parts = key_name.split('_');

      if (parts.length > 1) {
        var wordAfterUnderscore = parts[1];
        const selectElement = document.getElementById('relation_variable');
        setting.forEach(option => {
          const newOption = document.createElement('option');
          newOption.value = option + '_' + wordAfterUnderscore;
          newOption.text = capitalizeFirstLetter(option);
          selectElement.appendChild(newOption);
        });
      }
    } else {
      var key_name = data;
      var hostname = window.location.hostname;
      var url = "https://"+hostname+"/accounting/get_relation_variable";

      $.ajax({
        url: url,
        type: 'GET',
        data: { 'data': data },
        dataType: 'json',
        success: function (data) {
          if (data.data) {
            var dynamicOptions = data.data;
            var parts = key_name.split('_');

            if (parts.length > 1) {
              var wordAfterUnderscore = parts[1];
              const selectElement = document.getElementById('relation_variable');

              dynamicOptions.forEach(option => {
                const newOption = document.createElement('option');
                newOption.value = option + '_' + wordAfterUnderscore;
                newOption.text = capitalizeFirstLetter(option);
                selectElement.appendChild(newOption);
              });
            }
          }
        },
        error: function (request, error) {
          console.log(error);
        }
      });
    }
  } else {
    const selectElement = document.getElementById('relation_variable');
    selectElement.innerHTML = '';
  }
}
  change_val("unified_invoices");
</script>