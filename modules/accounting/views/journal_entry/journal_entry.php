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
		  <div class="row">
            <div class="col-md-6">
              <h4 class="no-margin font-bold"><?php echo _l($title); ?></h4>
            </div>
            <!-- acf main by acf -->
            <?php include(module_dir_path("advance_custom_field", "includes/templates/acf_main.php")); ?>
          </div>
          <?php $arrAtt = array();
                $arrAtt['data-type']='currency';
                ?>
          <?php echo form_open_multipart($this->uri->uri_string(),array('id'=>'journal-entry-form','autocomplete'=>'off')); ?>
          <hr />
          <div class="row">
            <div class="col-md-6">
              <?php $value = (isset($journal_entry) ? _d($journal_entry->journal_date) : _d(date('Y-m-d'))); ?>
              <?php echo render_date_input('journal_date','journal_date',$value); ?>
            </div>
            <div class="col-md-6">
              <?php $value = (isset($journal_entry) ? $journal_entry->number : $next_number); ?>
              <?php echo render_input('number','number',$value,'number'); ?>
            </div>
          </div>
          <div id="journal_entry_container"></div>
          <div class="col-md-8 col-md-offset-4">
         <table class="table text-right">
            <tbody>
                <tr>
                  <td></td>
                  <td class="text-right bold"><?php echo _l('debit'); ?></td>
                  <td class="text-right bold"><?php echo _l('credit'); ?></td>
                </tr>
               <tr>
                  <td><span class="bold"><?php echo _l('invoice_total'); ?> :</span>
                  </td>
                  <td class="total_debit">
                    <?php $value = (isset($journal_entry) ? $journal_entry->amount : 0); ?>
                    <?php echo app_format_money($value, $currency->name); ?>
                  </td>
                  <td class="total_credit">
                    <?php $value = (isset($journal_entry) ? $journal_entry->amount : 0); ?>
                    <?php echo app_format_money($value, $currency->name); ?>
                  </td>
               </tr>
            </tbody>
         </table>
        </div>
          <?php echo form_hidden('journal_entry'); ?>
          <?php echo form_hidden('amount'); ?>
          <div class="row">
            <div class="col-md-12">
              <p class="bold"><?php echo _l('dt_expense_description'); ?></p>
              <?php $value = (isset($journal_entry) ? $journal_entry->description : ''); ?>
              <?php echo render_textarea('description','',$value,array(),array(),'','tinymce'); ?>
            </div>
          </div>
		  <!-- RENDER ACF Fields by ACF -->
          <?php echo get_advance_custom_fields_html('journal_entry', $journal_entry->id); ?>
          <div class="row">
            <div class="col-md-12">    
              <div class="modal-footer">
                <button type="button" class="btn btn-info journal-entry-form-submiter"><?php echo _l('submit'); ?></button>
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
<!-- ICON MODAL by ACF -->
<?php include(module_dir_path("advance_custom_field", "includes/modals/icon-modal.php")); ?>
<?php require 'modules/accounting/assets/js/journal_entry/journal_entry_js.php';?>