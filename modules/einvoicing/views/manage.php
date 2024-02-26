<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
                    <?php echo $title; ?>
                </h4>
                <div class="panel_s">
                    <div class="col-md-12 panel-body">
                        <?php render_datatable([
                            _l('invoice_dt_table_heading_number'),
                            _l('invoice_dt_table_heading_amount'),
                            _l('invoice_total_tax'),
                            _l('invoice_dt_table_heading_date'),
                            _l('invoice_dt_table_heading_client'),
                            _l('project'),
                            _l('invoice_dt_table_heading_duedate'),
                            _l('invoice_dt_table_heading_status'),
                            _l('options'),
                        ], 'einvoicing-list'); ?>

                    </div>
                </div>
            </div>
        </div>

        <div class="btn-bottom-pusher"></div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(document).ready(function() {
        "use strict";
        $(function() {
            initDataTable('.table-einvoicing-list', window.location.href, [0], [0], [], [0, 'desc']);
        });
    });
</script>
</body>
</html>
