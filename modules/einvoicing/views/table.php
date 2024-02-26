<?php
defined('BASEPATH') or exit('No direct script access allowed');

$CI = &get_instance();
$CI->load->model('invoices_model');

$project_id = $CI->input->post('project_id');

$aColumns = [
    'number',
    'total',
    'total_tax',
    'YEAR(date) as year',
    'date',
    get_sql_select_client_company(),
    db_prefix() . 'projects.name as project_name',
    'duedate',
    db_prefix() . 'invoices.status',
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'invoices';

$join = [
    'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'invoices.clientid',
    'LEFT JOIN ' . db_prefix() . 'currencies ON ' . db_prefix() . 'currencies.id = ' . db_prefix() . 'invoices.currency',
    'LEFT JOIN ' . db_prefix() . 'projects ON ' . db_prefix() . 'projects.id = ' . db_prefix() . 'invoices.project_id',
];

$where  = [];
$filter = [];

if (!has_permission('invoices', '', 'view')) {
    $userWhere = 'AND ' . get_invoices_where_sql_for_staff(get_staff_user_id());
    array_push($where, $userWhere);
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
    db_prefix() . 'invoices.id',
    db_prefix() . 'invoices.clientid',
    db_prefix() . 'currencies.name as currency_name',
    'project_id',
    'hash',
    'recurring',
    'deleted_customer_name',
]);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $numberOutput = '';

    // If is from client area table
    if ($project_id) {
        $numberOutput = '<p>' . format_invoice_number($aRow['id']) . '</a>';
    } else {
        $numberOutput = '<p>' . format_invoice_number($aRow['id']) . '</a>';
    }

    if ($aRow['recurring'] > 0) {
        $numberOutput .= '<br /><span class="label label-primary inline-block tw-mt-1"> ' . _l('invoice_recurring_indicator') . '</span>';
    }

    $numberOutput .= '<div class="row-options">';

    $numberOutput .= '<a href="' . site_url('invoice/' . $aRow['id'] . '/' . $aRow['hash']) . '" target="_blank">' . _l('view') . '</a>';
    if (has_permission('invoices', '', 'edit')) {
        $numberOutput .= ' | <a href="' . admin_url('invoices/invoice/' . $aRow['id']) . '">' . _l('edit') . '</a>';
    }
    $numberOutput .= '</div>';

    $row[] = $numberOutput;

    $row[] = app_format_money($aRow['total'], $aRow['currency_name']);

    $row[] = app_format_money($aRow['total_tax'], $aRow['currency_name']);

    $row[] = _d($aRow['date']);

    if (empty($aRow['deleted_customer_name'])) {
        $row[] = '<a href="' . admin_url('clients/client/' . $aRow['clientid']) . '">' . $aRow['company'] . '</a>';
    } else {
        $row[] = $aRow['deleted_customer_name'];
    }

    $row[] = '<a href="' . admin_url('projects/view/' . $aRow['project_id']) . '">' . $aRow['project_name'] . '</a>';
    ;

    $row[] = _d($aRow['duedate']);

    $row[] = format_invoice_status($aRow[db_prefix() . 'invoices.status']);

    $options = '<div class="tw-flex tw-items-center tw-space-x-3">';
    $options .= '<a href="' . admin_url('einvoicing/generate_einvoice/' . $aRow['id']) . '" class="btn btn-info">
        Generate E-Invoice
    </a>';
    $options .= '</div>';
    $row[]              = $options;

    $output['aaData'][] = $row;
}