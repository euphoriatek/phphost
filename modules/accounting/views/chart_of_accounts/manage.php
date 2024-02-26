<?php init_head();?>

<!-- GOOGLE CHARTS -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<!-- GOOGLE MAPS -->
<script>(g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})
        ({key: "AIzaSyB41DRUbKWJHPxaFjMAwdrzWzbVKartNGg", v: "beta"});</script>
        <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>

<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="panel_s">
        <div class="panel-body">
          <h4 class="no-margin font-bold"><?php echo _l($title); ?></h4>
          <hr />
          <div>
            <a href="#" class="btn btn-info add-new-account mbot15 <?php if(!has_permission('accounting_chart_of_accounts', '', 'create')){echo 'hide';} ?>"><?php echo _l('add'); ?></a>
            <a href="<?php echo admin_url('accounting/accounts_import'); ?>" class="btn btn-success mbot15 <?php if(!has_permission('accounting_chart_of_accounts', '', 'create')){echo 'hide';} ?>"><?php echo _l('import_account'); ?></a>
          </div>
          <div class="row">
            <div class="col-md-3">
              <?php echo render_select('ft_account',$accounts,array('id','name', 'account_type_name'),'acc_account', '', array('multiple' => true, 'data-actions-box' => true), array(), '', '', false); ?>
            </div>
            <div class="col-md-3">
              <?php echo render_select('ft_parent_account',$accounts,array('id','name', 'account_type_name'),'parent_account', '', array('multiple' => true, 'data-actions-box' => true), array(), '', '', false); ?>
            </div>
            <div class="col-md-3">
              <?php echo render_select('ft_type',$account_types,array('id','name'),'type', '', array('multiple' => true, 'data-actions-box' => true), array(), '', '', false); ?>
            </div>
            <div class="col-md-3">
              <?php echo render_select('ft_detail_type',$detail_types,array('id','name'),'detail_type', '', array('multiple' => true, 'data-actions-box' => true), array(), '', '', false); ?>
            </div>
            <div class="col-md-3">
              <?php $active = [ 
                    1 => ['id' => 'all', 'name' => _l('all')],
                    2 => ['id' => 'yes', 'name' => _l('is_active_export')],
                    3 => ['id' => 'no', 'name' => _l('is_not_active_export')],
                  ]; 
                  ?>
                  <?php echo render_select('ft_active',$active,array('id','name'),'staff_dt_active', 'yes', array(), array(), '', '', false); ?>
            </div>
          </div>
          <hr>
          <a href="#" data-toggle="modal" data-target="#accounts_bulk_actions" class="hide bulk-actions-btn table-btn" data-table=".table-accounts"><?php echo _l('bulk_actions'); ?></a>
          <table class="table table-accounts">
            <thead>
              <th><span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="accounts"><label></label></div></th>
              <th><?php echo _l('account_number'); ?></th>
              <th><?php echo _l('name'); ?></th>
              <th><?php echo _l('parent_account'); ?></th>
              <th><?php echo _l('type'); ?></th>
              <th><?php echo _l('detail_type'); ?></th>
              <th><?php echo _l('primary_balance'); ?></th>
              <th><?php echo _l('bank_balance'); ?></th>
              <th><?php echo _l('staff_dt_active'); ?></th>
              <th><?php echo _l('options'); ?></th>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $arrAtt = array();
      $arrAtt['data-type']='currency';
?>
<div class="modal fade" id="account-modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        
         <div class="row module-custom-field">
        <div class="col-md-6"><h4 class="modal-title"><?php echo _l('acc_account')?></h4></div>
        <!-- acf main by acf -->
        <?php include(module_dir_path("advance_custom_field", "includes/templates/acf_main.php")); ?>
    </div>
       
       
      </div>
      <?php echo form_open_multipart(admin_url('accounting/account'),array('id'=>'account-form'));?>
      <?php echo form_hidden('id'); ?>
      <?php echo form_hidden('update_balance'); ?>
      
      <div class="modal-body">
          <?php echo render_select('account_type_id',$account_types,array('id','name'),'account_type','',array(),array(),'','',false); ?>
          <?php echo render_select('account_detail_type_id',$detail_types,array('id','name'),'detail_type','',array(),array(),'','',false); ?>
          <p><i class="detail_type_note"><?php echo html_entity_decode($detail_types[0]['note']); ?></i></p>
        <?php echo render_input('name','name'); ?>
        <?php if(get_option('acc_enable_account_numbers') == 1){
           echo render_input('number','number'); 
        } ?>
        
        <!-- RENDER ACF Fields by ACF -->
       <?php echo get_advance_custom_fields_html('project', $rel_id_custom_field); ?> 
        
        <?php echo render_select('parent_account',$accounts,array('id','name'),'parent_account'); ?>
        <div class="row hide" id="div_balance">
          <div class="col-md-6">
          <?php echo render_input('balance','balance','','text', $arrAtt); ?>
          </div>
          <div class="col-md-6">
          <?php echo render_date_input('balance_as_of','as_of'); ?>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <p class="bold"><?php echo _l('dt_expense_description'); ?></p>
            <?php echo render_textarea('description','','',array(),array(),'','tinymce'); ?>
          </div>
        </div>
      

      </div>
      
      
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-info btn-submit"><?php echo _l('submit'); ?></button>
      </div>
      <?php echo form_close(); ?>  
    </div>
  </div>
</div>


<div class="modal fade bulk_actions" id="accounts_bulk_actions" tabindex="-1" role="dialog" data-table=".table-accounts">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?php echo _l('bulk_actions'); ?></h4>
         </div>
         <div class="modal-body">
            <?php if(has_permission('accounting_chart_of_accounts','','edit')){ ?>
               <div class="checkbox checkbox-info">
                  <input type="checkbox" name="mass_activate" id="mass_activate">
                  <label for="mass_activate"><?php echo _l('mass_activate'); ?></label>
               </div>
            <?php } ?>
            <?php if(has_permission('accounting_chart_of_accounts','','edit')){ ?>
               <div class="checkbox checkbox-info">
                  <input type="checkbox" name="mass_deactivate" id="mass_deactivate">
                  <label for="mass_deactivate"><?php echo _l('mass_deactivate'); ?></label>
               </div>
            <?php } ?>
            <?php if(has_permission('accounting_chart_of_accounts','','detele')){ ?>
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
<?php require 'modules/accounting/assets/js/chart_of_accounts/manage_js.php'; ?>
