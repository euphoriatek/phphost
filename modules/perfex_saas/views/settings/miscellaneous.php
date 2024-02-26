<?php

defined('BASEPATH') or exit('No direct script access allowed');

$CI = &get_instance();
?>

<div class="tw-flex tw-flex-col">
    <!-- redirection after url -->
    <?php $key = 'perfex_saas_after_first_instance_redirect_url'; ?>
    <?= render_input('settings[' . $key . ']', perfex_saas_input_label_with_hint($key), get_option($key)); ?>

    <?php
    $key = 'perfex_saas_enable_deploy_splash_screen';
    render_yes_no_option($key, _l($key));
    ?>

    <?php
    $key = 'perfex_saas_deploy_splash_screen_theme';
    $value = get_option($key);
    $splash_themes = [
        ['key' => 'verbose', 'label' => _l('perfex_saas_deploy_splash_screen_theme_verbose')],
        ['key' => 'simple', 'label' => _l('perfex_saas_deploy_splash_screen_theme_simple')],
    ]; ?>
    <?= render_select('settings[' . $key . ']', $splash_themes, ['key', ['label']], _l($key) . perfex_saas_form_label_hint($key . '_hint'), $value, [], [], '', '', false); ?>

</div>