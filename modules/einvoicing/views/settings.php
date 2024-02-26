<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">

            <?php echo form_open(admin_url('einvoicing/settings'), ['id' => 'einvoicing-settings-form']); ?>
            <div class="col-md-12">
                <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
                    <?php echo $title; ?>
                </h4>
                <div class="panel_s">
                    <div class="panel-body">

                        <div class="col-md-6">
                            <?php echo render_input('settings[einvoicing_seller_electronic_address]', 'einvoicing_seller_electronic_address', get_option('einvoicing_seller_electronic_address')); ?>
                        </div>

                        <div class="col-md-6">
                            <?php echo render_input('settings[einvoicing_seller_company_id]', 'einvoicing_seller_company_id', get_option('einvoicing_seller_company_id')); ?>
                        </div>

                        <div class="col-md-6">
                            <?php echo render_input('settings[einvoicing_seller_electronic_address_scheme]', 'einvoicing_seller_electronic_address_scheme', get_option('einvoicing_seller_electronic_address_scheme')); ?>
                        </div>

                        <div class="col-md-6">
                            <?php echo render_input('settings[einvoicing_seller_company_id_scheme]', 'einvoicing_seller_company_id_scheme', get_option('einvoicing_seller_company_id_scheme')); ?>
                        </div>

                        <div class="btn-bottom-toolbar text-right">
                            <button type="submit" class="btn btn-primary"><?php echo _l('save'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
        <div class="btn-bottom-pusher"></div>
    </div>
</div>
<?php init_tail(); ?>
</body>

</html>
