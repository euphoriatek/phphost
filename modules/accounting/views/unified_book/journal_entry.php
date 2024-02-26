<?php init_head();?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="panel_s">
        <div class="panel-body">
          <?php echo form_open_multipart($this->uri->uri_string(),array('id'=>'journal-entry-form','autocomplete'=>'off')); ?>
          <h4 class="no-margin font-bold"><?php echo _l($title); ?></h4>
          <hr />
          <?php
          foreach ($unified_book as $key => $value) { if($key != "id"){ ?>
            <div class="form-group col-md-3">
              <label class="control-label"><?php echo $key;?></label>
              <input class="form-control" type="text" name="<?php echo $key;?>" value="<?php echo $value; ?>">
            </div>
          <?php }  }
          ?>
          <div class="row">
            <div class="col-md-12">    
              <div class="modal-footer">
                <button type="submit" class="btn btn-info journal-entry-form-submiter"><?php echo _l('submit'); ?></button>
              </div>
            </div>
          </div>
          <?php echo form_close(); ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
</body>
</html>