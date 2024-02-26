<?php
$CI          = & get_instance();
$custom_fields = get_table_custom_fields('dmn');
$CI->db->query("SET sql_mode = ''");
$aColumns = ['title',db_prefix() . 'dmn.description','staffid', db_prefix() . 'projects.name',db_prefix() . 'dmn.dateadded',];
$sIndexColumn = 'id';
$sTable       = db_prefix() . 'dmn';
$join = ['LEFT JOIN ' . db_prefix() . 'projects ON ' . db_prefix() . 'projects.id = ' . db_prefix() . 'dmn.project_id',];
$where        = [];
$filter = [];
foreach ($custom_fields as $key => $field) {
    $selectAs = (is_cf_date($field) ? 'date_picker_cvalue_' . $key : 'cvalue_' . $key);
    array_push($customFieldsColumns, $selectAs);
    array_push($aColumns, 'ctable_' . $key . '.value as ' . $selectAs);
    array_push($join, 'LEFT JOIN '.db_prefix().'customfieldsvalues as ctable_' . $key . ' ON '.db_prefix().'clients.userid = ctable_' . $key . '.relid AND ctable_' . $key . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $key . '.fieldid=' . $field['id']);
}
$join = hooks()->apply_filters('dmn_table_sql_join', $join);
$staffIds = [];
if ($CI->input->post('assigned')) {
    array_push($staffIds, $CI->input->post('assigned'));
}
if (count($staffIds) > 0) {
    array_push($filter, 'AND '.db_prefix().'dmn.staffid IN (' . implode(', ', $staffIds) . ')');
}
$projectIds = [];
if ($CI->input->post('project')) {
    array_push($projectIds, $CI->input->post('project'));
}
if (count($projectIds) > 0) {
    array_push($filter, 'AND '.db_prefix().'dmn.project_id IN (' . implode(', ', $projectIds) . ')');
}
if (count($filter) > 0) {
    array_push($where, 'AND (' . prepare_dt_filter($filter) . ')');
}
if ($CI->input->post('my_dmn')) {
    array_push($where, 'AND '.db_prefix().'dmn.staffid = ' . get_staff_user_id());
}
$aColumns = hooks()->apply_filters('dmn_table_sql_columns', $aColumns);
if (count($custom_fields) > 4) {
    @$CI->db->query('SET SQL_BIG_SELECTS=1');
}
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [db_prefix() . 'dmn.id']);
$output  = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'title') {
            $hrefAttr = 'href="' . admin_url('dmn/index/' . $aRow['id']) . '" onclick="init_dmn_modal(' . $aRow['id'] . ');return false;"';
            $_data = '<a href="' . admin_url('dmn/preview/' . $aRow['id']) . '" >' . $_data . '</a>';
            $_data .= '<div class="row-options">';
            $_data .= ' <a href="' . admin_url('dmn/dmn_create/' . $aRow['id']) . '">' . _l('dmn_edit') . '</a>';
            if (has_permission('dmn', '', 'delete')) {
                $_data .= ' | <a href="' . admin_url('dmn/delete/' . $aRow['id']) . '" class="text-danger _delete">' . _l('dmn_delete') . '</a>';
            }
            $_data .= '</div>';
        } 
        elseif ($aColumns[$i] == db_prefix() . 'dmn.description' || $aColumns[$i] == 'description') {
            $_data = $_data;
        }
        elseif ($aColumns[$i] == 'staffid') {
            if($_data > 0) {
                $oStaff = $this->ci->staff_model->get($_data);
                $_data = staff_profile_image($oStaff->staffid, array('img', 'img-responsive', 'staff-profile-image-small', 'pull-left')) . '<a href="' . admin_url('profile/' . $oStaff->staffid) . '">' . $oStaff->firstname . ' ' . $oStaff->lastname . '</a><br>';
            }else{
                $_data = '';
            }
        }
        elseif ($aColumns[$i] == db_prefix() . 'dmn.dateadded' || $aColumns[$i] == 'dateadded') {
            $_data = _dt($_data);
        }
        else{
            $_data = $_data;
        }
        $row[] = $_data;
    }
    ob_start();
    ?>
    <?php
    $progress = ob_get_contents();
    ob_end_clean();
    $row[]              = $progress;
    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}