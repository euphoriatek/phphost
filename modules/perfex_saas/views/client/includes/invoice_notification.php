<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php

if (empty($invoice)) return;

$target = '';

$subscribe_url_endpoint = 'clients/packages/' . $invoice->slug . '/select';
$subscribe_url = APP_BASE_URL_DEFAULT . $subscribe_url_endpoint;

$invoice_pay_endpoint = "invoice/$invoice->id/$invoice->hash";
$invoice_pay_url = APP_BASE_URL_DEFAULT . $invoice_pay_endpoint;

if (perfex_saas_is_tenant()) {
    $target = 'target="_blank"';

    $client_bridge = perfex_saas_tenant_is_enabled('client_bridge');
    if ($client_bridge) {
        $target = '';
        $base_url = admin_url('billing/my_account?redirect=');
        $subscribe_url = $base_url . $subscribe_url_endpoint;
        $invoice_pay_url = $base_url . $invoice_pay_url;
    }
}
?>

<!-- Invoice notification -->
<div class="ps <?= perfex_saas_is_tenant() ? 'col-md-12' : ''; ?>">
    <?php if ($on_trial) : ?>
    <div class="alert alert-<?= $days_left > 0 ? 'warning' : 'danger'; ?>  tw-mt-5">

        <?php if ($days_left > 0) : ?>
        <?= perfex_saas_is_single_package_mode() ?
                    _l('perfex_saas_trial_invoice_not_single_pricing', [$invoice_days_left]) :
                    _l('perfex_saas_trial_invoice_not', [$invoice->name, _d($invoice->duedate), $invoice_days_left]);
                ?>
        <?php else : ?>
        <?= _l('perfex_saas_trial_invoice_over_not'); ?>
        <?php endif; ?>

        <a onclick="return confirm('<?= _l('perfex_saas_upgrade_confirm_text'); ?>')" href="<?= $subscribe_url; ?>"
            class="fs-5 text-danger" <?= $target; ?>>
            <?= _l('perfex_saas_click_here_to_subscribe'); ?>
        </a>
    </div>
    <?php endif; ?>

    <?php if (!$on_trial && $invoice->status != Invoices_model::STATUS_PAID) :
    ?>
    <div class="alert alert-danger tw-mt-5">
        <?= _l('perfex_saas_outstanding_invoice_not'); ?> <a href="<?= $invoice_pay_url; ?>"
            <?= $target; ?>><?= _l('perfex_saas_click_here_to_pay'); ?></a>
    </div>
    <?php endif
    ?>
</div>