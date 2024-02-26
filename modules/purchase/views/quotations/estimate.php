<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<!-- GOOGLE CHARTS -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<!-- GOOGLE MAPS -->
<script>(g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})
        ({key: "AIzaSyB41DRUbKWJHPxaFjMAwdrzWzbVKartNGg", v: "beta"});</script>
        <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<div id="wrapper">
	<div class="content">
 		<div class="row">
            <div class="col-md-6">
              
            </div>
            <!-- acf main by acf -->
            <?php include(module_dir_path("advance_custom_field", "includes/templates/acf_main.php")); ?>
          </div>
     
      
		<div class="row">
			<?php
			echo form_open($this->uri->uri_string(),array('id'=>'estimate-form','class'=>'_transaction_form'));
			if(isset($estimate)){
				echo form_hidden('isedit');
			}
			?>
          
       
			<div class="col-md-12">
				<?php $this->load->view('quotations/estimate_template'); ?>
        
			</div>
          
            
			<?php echo form_close(); ?>		
		</div>
	</div>
</div>
</div>
<?php init_tail(); ?>
</body>
</html>
<?php require 'modules/purchase/assets/js/estimate_js.php';?>