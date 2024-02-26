<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'document_name',
    'created_at'
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'predix_documents';

$join = [];
$where = [];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
    'id'
]);

$output  = $result['output'];
$rResult = $result['rResult'];


foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {

        $row[] = $aRow['document_name'];

        $row[] = $aRow['created_at'];


        $options = '<div class="tw-flex tw-items-center tw-space-x-3">';
        $options .= '<a href="' . admin_url('predix/view_template_document/' . $aRow['id']) . '" class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
        <i class="fa-regular fa-eye fa-lg"></i>
    </a>';

        $options .= '<a href="' . admin_url('predix/delete_template_document/' . $aRow['id']) . '"
    class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
        <i class="fa-regular fa-trash-can fa-lg"></i>
    </a>';

        $options .= '</div>';

        $row[]              = $options;
    }

    $output['aaData'][] = $row;
}
