<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
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
          <h4 class="no-margin font-bold"><?php echo _l($title); ?></h4>
          <hr />
          <div class="row">
            <div class="col-md-3">
              <?php echo render_date_input('from_date','from_date'); ?>
            </div>
            <div class="col-md-3">
              <?php echo render_date_input('to_date','to_date'); ?>
            </div>
          </div>
          <a href="#" data-toggle="modal" data-target="#journal_entry_bulk_actions" class="hide bulk-actions-btn table-btn" data-table=".table-journal-entry"><?php echo _l('bulk_actions'); ?></a>
          <table class="table table-journal-entry scroll-responsive">
           <thead>
              <tr>
                <th><span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="journal-entry"><label></label></div></th>
                 <th><?php echo _l('id'); ?></th>
                 <?php foreach ($get_unified_row_mapping as $key => $value) {?>
                 <th><?php echo _l($value['value']); ?></th>
                <?php } ?>
              </tr>
           </thead>
        </table>
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
