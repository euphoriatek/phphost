<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- GOOGLE CHARTS -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<!-- GOOGLE MAPS -->
 <script>(g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})
        ({key: "AIzaSyB41DRUbKWJHPxaFjMAwdrzWzbVKartNGg", v: "beta"});</script>
        <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
        
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">
        <?php if (isset($lead)) {
    if (!empty($lead->name)) {
        $name = $lead->name;
    } elseif (!empty($lead->company)) {
        $name = $lead->company;
    } else {
        $name = _l('lead');
    }
    echo '#' . $lead->id . ' - ' . $name;
} else {
    echo _l('add_new', _l('lead_lowercase'));
}

if (isset($lead)) {
    echo '<div class="tw-ml-4 -tw-mt-2 tw-inline-block">';
    if ($lead->lost == 1) {
        echo '<span class="label label-danger">' . _l('lead_lost') . '</span>';
    } elseif ($lead->junk == 1) {
        echo '<span class="label label-warning">' . _l('lead_junk') . '</span>';
    } else {
        if (total_rows(db_prefix() . 'clients', [
          'leadid' => $lead->id, ])) {
            echo '<span class="label label-success">' . _l('lead_is_client') . '</span>';
        }
    }
    echo '</div>';
}
?>
    </h4>
    
    <div class="row module-custom-field">
        <div class="col-md-6"></div>
        <!-- acf main by acf -->
        <?php include(module_dir_path("advance_custom_field", "includes/templates/acf_main.php")); ?>
    </div>
                    
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <?php if (isset($lead)) {
    echo form_hidden('leadid', $lead->id);
} ?>
            <div class="top-lead-menu">
                <?php if (isset($lead)) { ?>
                <div class="horizontal-scrollable-tabs preview-tabs-top panel-full-width-tabs mbot20">
                    <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                    <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                    <div class="horizontal-tabs">
                        <ul class="nav-tabs-horizontal nav nav-tabs<?php if (!isset($lead)) {
    echo ' lead-new';
} ?>" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#tab_lead_profile" aria-controls="tab_lead_profile" role="tab"
                                    data-toggle="tab">
                                    <?php echo _l('lead_profile'); ?>
                                </a>
                            </li>
                            <?php if (isset($lead)) { ?>
                            <?php if (count($mail_activity) > 0 || isset($show_email_activity) && $show_email_activity) { ?>
                            <li role="presentation">
                                <a href="#tab_email_activity" aria-controls="tab_email_activity" role="tab"
                                    data-toggle="tab">
                                    <?php echo hooks()->apply_filters('lead_email_activity_subject', _l('lead_email_activity')); ?>
                                </a>
                            </li>
                            <?php } ?>
                            <li role="presentation">
                                <a href="#tab_proposals_leads"
                                    onclick="initDataTable('.table-proposals-lead', admin_url + 'proposals/proposal_relations/' + <?php echo $lead->id; ?> + '/lead','undefined', 'undefined','undefined',[6,'desc']);"
                                    aria-controls="tab_proposals_leads" role="tab" data-toggle="tab">
                                    <?php echo _l('proposals');
                        if ($total_proposals > 0) {
                            echo ' <span class="badge">' . $total_proposals . '</span>';
                        }
                        ?>
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#tab_tasks_leads"
                                    onclick="init_rel_tasks_table(<?php echo $lead->id; ?>,'lead','.table-rel-tasks-leads');"
                                    aria-controls="tab_tasks_leads" role="tab" data-toggle="tab">
                                    <?php echo _l('tasks');
                        if ($total_tasks > 0) {
                            echo ' <span class="badge">' . $total_tasks . '</span>';
                        }
                        ?>
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#attachments" aria-controls="attachments" role="tab" data-toggle="tab">
                                    <?php echo _l('lead_attachments');
                        if ($total_attachments > 0) {
                            echo ' <span class="badge">' . $total_attachments . '</span>';
                        }
                        ?>
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#lead_reminders"
                                    onclick="initDataTable('.table-reminders-leads', admin_url + 'misc/get_reminders/' + <?php echo $lead->id; ?> + '/' + 'lead', undefined, undefined,undefined,[1, 'asc']);"
                                    aria-controls="lead_reminders" role="tab" data-toggle="tab">
                                    <?php echo _l('leads_reminders_tab');
                           if ($total_reminders > 0) {
                               echo ' <span class="badge">' . $total_reminders . '</span>';
                           }
                           ?>
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#lead_notes" aria-controls="lead_notes" role="tab" data-toggle="tab">
                                    <?php echo _l('lead_add_edit_notes');
                        if ($total_notes > 0) {
                            echo ' <span class="badge">' . $total_notes . '</span>';
                        }
                        ?>
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#lead_activity" aria-controls="lead_activity" role="tab" data-toggle="tab">
                                    <?php echo _l('lead_add_edit_activity'); ?>
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#conversation" aria-controls="conversation" role="tab" data-toggle="tab">
                                    <?php echo _l('conversation'); ?>
                                </a>
                            </li>
                            <?php if (is_gdpr() && (get_option('gdpr_enable_lead_public_form') == '1' || get_option('gdpr_enable_consent_for_leads') == '1')) { ?>
                            <li role="presentation">
                                <a href="#gdpr" aria-controls="gdpr" role="tab" data-toggle="tab">
                                    <?php echo _l('gdpr_short'); ?>
                                </a>
                            </li>
                            <?php } ?>
                            <?php } ?>
                            <?php hooks()->do_action('after_lead_lead_tabs', $lead ?? null); ?>
                        </ul>
                    </div>
                </div>
                <?php } ?>
            </div>
            <!-- Tab panes -->
            <div class="tab-content">
                <!-- from leads modal -->
                <div role="tabpanel" class="tab-pane active" id="tab_lead_profile">
                    <?php $this->load->view('admin/leads/profile'); ?>
                </div>
                <?php if (isset($lead)) { ?>
                <?php if (count($mail_activity) > 0 || isset($show_email_activity) && $show_email_activity) { ?>
                <div role="tabpanel" class="tab-pane" id="tab_email_activity">
                    <?php hooks()->do_action('before_lead_email_activity', ['lead' => $lead, 'email_activity' => $mail_activity]); ?>
                    <?php foreach ($mail_activity as $_mail_activity) { ?>
                    <div class="lead-email-activity">
                        <div class="media-left">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <div class="media-body">
                            <h4 class="bold no-margin lead-mail-activity-subject">
                                <?php echo $_mail_activity['subject']; ?>
                                <br />
                                <small
                                    class="text-muted display-block mtop5 font-medium-xs"><?php echo _dt($_mail_activity['dateadded']); ?></small>
                            </h4>
                            <div class="lead-mail-activity-body">
                                <hr />
                                <?php echo $_mail_activity['body']; ?>
                            </div>
                            <hr />
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <?php } ?>
                    <?php hooks()->do_action('after_lead_email_activity', ['lead_id' => $lead->id, 'emails' => $mail_activity]); ?>
                </div>
                <?php } ?>
                <?php if (is_gdpr() && (get_option('gdpr_enable_lead_public_form') == '1' || get_option('gdpr_enable_consent_for_leads') == '1' || (get_option('gdpr_data_portability_leads') == '1') && is_admin())) { ?>
                <div role="tabpanel" class="tab-pane" id="gdpr">
                    <?php if (get_option('gdpr_enable_lead_public_form') == '1') { ?>
                    <a href="<?php echo $lead->public_url; ?>" target="_blank" class="mtop5">
                        <?php echo _l('view_public_form'); ?>
                    </a>
                    <?php } ?>
                    <?php if (get_option('gdpr_data_portability_leads') == '1' && is_admin()) { ?>
                    <?php
                  if (get_option('gdpr_enable_lead_public_form') == '1') {
                      echo ' | ';
                  }
                  ?>
                    <a href="<?php echo admin_url('leads/export/' . $lead->id); ?>">
                        <?php echo _l('dt_button_export'); ?>
                    </a>
                    <?php } ?>
                    <?php if (get_option('gdpr_enable_lead_public_form') == '1' || (get_option('gdpr_data_portability_leads') == '1' && is_admin())) { ?>
                    <hr class="-tw-mx-3.5" />
                    <?php } ?>
                    <?php if (get_option('gdpr_enable_consent_for_leads') == '1') { ?>
                    <h4 class="no-mbot">
                        <?php echo _l('gdpr_consent'); ?>
                    </h4>
                    <?php $this->load->view('admin/gdpr/lead_consent'); ?>
                    <hr />
                    <?php } ?>
                </div>
                <?php } ?>
                <div role="tabpanel" class="tab-pane" id="lead_activity">
                    <div>
                        <div class="activity-feed">
                            <?php foreach ($activity_log as $log) { ?>
                            <div class="feed-item">
                                <div class="date">
                                    <span class="text-has-action" data-toggle="tooltip"
                                        data-title="<?php echo _dt($log['date']); ?>">
                                        <?php echo time_ago($log['date']); ?>
                                    </span>
                                </div>
                                <div class="text">
                                    <?php if ($log['staffid'] != 0) { ?>
                                    <a href="<?php echo admin_url('profile/' . $log['staffid']); ?>">
                                        <?php echo staff_profile_image($log['staffid'], ['staff-profile-xs-image pull-left mright5']);
                              ?>
                                    </a>
                                    <?php
                              }
                              $additional_data = '';
                              if (!empty($log['additional_data'])) {
                                  $additional_data = unserialize($log['additional_data']);
                                  echo ($log['staffid'] == 0) ? _l($log['description'], $additional_data) : $log['full_name'] . ' - ' . _l($log['description'], $additional_data);
                              } else {
                                  echo $log['full_name'] . ' - ';
                                  if ($log['custom_activity'] == 0) {
                                      echo _l($log['description']);
                                  } else {
                                      echo _l($log['description'], '', false);
                                  }
                              }
                              ?>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-12">
                            <?php echo render_textarea('lead_activity_textarea', '', '', ['placeholder' => _l('enter_activity')], [], 'mtop15'); ?>
                            <div class="text-right">
                                <button id="lead_enter_activity"
                                    class="btn btn-primary"><?php echo _l('submit'); ?></button>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="conversation">
                    <div>
                        <div class="activity-feed">
                            <table class="table table-leads customizable-table number-index-2 dataTable no-footer">
                                <thead>
                                    <tr>
                                        <td>Email's Subject</td>
                                        <td>Date</td>
                                        <td>Action</td>
                                    </tr>
                                </thead>
                                <tbody>
                                        <?php foreach ($conversation as $key => $value) {
                                            $value['profile'] = staff_profile_image($value['sender_staff_id'], ['mr-2 rounded-circle']);
                                            $value['module_dir_url'] = module_dir_url(MAILBOX_MODULE);
                                            $value['date_sent'] = _dt($value['date_sent']);
                                            $value['view_url'] = admin_url().'mailbox/reply/'.$value['mail_outbox_id'].'/reply/outbox';
                                            $value['get_staff_email_by_id'] = get_staff_email_by_id($value['sender_staff_id']);
                                            if ($value['has_attachment'] > 0) {
                                                foreach ($value['attachments'] as $key => $values) {
                                                    // $value['get_mime_class'] = get_mime_class($value['type']);
                                                    $value['attachments'][$key]['get_mime_class'] = get_mime_class($values['file_type']);
                                                }
                                            }
                                        ?>
                                           <tr>
                                           <td><?php echo $value['subject']; ?></td>
                                           <td><?php echo $value['date_sent']; ?></td>
                                           <td>
                                           <button class="btn btn-primary" type="button" onclick="send_mail_modal(<?php echo htmlspecialchars(json_encode($value), ENT_QUOTES, 'UTF-8') . ', \'' . $module_dir_url . '\''; ?>)">PreView</button>
                                            <button onclick="delete_mail_conversation(<?php echo $value['id']; ?>, this)" class="btn btn-danger">Delete</button></td>
                                           </tr>
                                        <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="tab_proposals_leads">
                    <?php if (has_permission('proposals', '', 'create')) { ?>
                    <a href="<?php echo admin_url('proposals/proposal?rel_type=lead&rel_id=' . $lead->id); ?>"
                        class="btn btn-primary mbot25"><?php echo _l('new_proposal'); ?></a>
                    <?php } ?>
                    <?php if (total_rows(db_prefix() . 'proposals', ['rel_type' => 'lead', 'rel_id' => $lead->id]) > 0 && (has_permission('proposals', '', 'create') || has_permission('proposals', '', 'edit'))) { ?>
                    <a href="#" class="btn btn-primary mbot25" data-toggle="modal"
                        data-target="#sync_data_proposal_data"><?php echo _l('sync_data'); ?></a>
                    <?php $this->load->view('admin/proposals/sync_data', ['related' => $lead, 'rel_id' => $lead->id, 'rel_type' => 'lead']); ?>
                    <?php } ?>
                    <?php
                  $table_data = [
                   _l('proposal') . ' #',
                   _l('proposal_subject'),
                   _l('proposal_total'),
                   _l('proposal_date'),
                   _l('proposal_open_till'),
                   _l('tags'),
                   _l('proposal_date_created'),
                   _l('proposal_status'), ];
                  $custom_fields = get_custom_fields('proposal', ['show_on_table' => 1]);
                  foreach ($custom_fields as $field) {
                      array_push($table_data, [
                       'name'     => $field['name'],
                       'th_attrs' => ['data-type' => $field['type'], 'data-custom-field' => 1],
                    ]);
                  }
                  $table_data = hooks()->apply_filters('proposals_relation_table_columns', $table_data);
                  render_datatable($table_data, 'proposals-lead', [], [
                      'data-last-order-identifier' => 'proposals-relation',
                      'data-default-order'         => get_table_last_order('proposals-relation'),
                  ]);
                  ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="tab_tasks_leads">
                    <?php init_relation_tasks_table(['data-new-rel-id' => $lead->id, 'data-new-rel-type' => 'lead']); ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="lead_reminders">
                    <a href="#" data-toggle="modal" class="btn btn-default"
                        data-target=".reminder-modal-lead-<?php echo $lead->id; ?>"><i class="fa-regular fa-bell"></i>
                        <?php echo _l('lead_set_reminder_title'); ?></a>
                    <hr />
                    <?php render_datatable([ _l('reminder_description'), _l('reminder_date'), _l('reminder_staff'), _l('reminder_is_notified')], 'reminders-leads'); ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="attachments">
                    <?php echo form_open('admin/leads/add_lead_attachment', ['class' => 'dropzone mtop15 mbot15', 'id' => 'lead-attachment-upload']); ?>
                    <?php echo form_close(); ?>
                    <?php if (get_option('dropbox_app_key') != '') { ?>
                    <hr />
                    <div class=" pull-left">
                        <?php if (count($lead->attachments) > 0) { ?>
                        <a href="<?php echo admin_url('leads/download_files/' . $lead->id); ?>" class="bold">
                            <?php echo _l('download_all'); ?> (.zip)
                        </a>
                        <?php } ?>
                    </div>
                    <div class="tw-flex tw-justify-end tw-items-center tw-space-x-2">
                        <button class="gpicker">
                            <i class="fa-brands fa-google" aria-hidden="true"></i>
                            <?php echo _l('choose_from_google_drive'); ?>
                        </button>
                        <div id="dropbox-chooser-lead"></div>
                    </div>
                    <div class=" clearfix"></div>
                    <?php } ?>
                    <?php if (count($lead->attachments) > 0) { ?>
                    <div class="mtop20" id="lead_attachments">
                        <?php $this->load->view('admin/leads/leads_attachments_template', ['attachments' => $lead->attachments]); ?>
                    </div>
                    <?php } ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="lead_notes">
                    <?php echo form_open(admin_url('leads/add_note/' . $lead->id), ['id' => 'lead-notes']); ?>
                    <div class="form-group">
                        <textarea id="lead_note_description" name="lead_note_description" class="form-control"
                            rows="4"></textarea>
                    </div>
                    <div class="lead-select-date-contacted hide">
                        <?php echo render_datetime_input('custom_contact_date', 'lead_add_edit_datecontacted', '', ['data-date-end-date' => date('Y-m-d')]); ?>
                    </div>
                    <div class="radio radio-primary">
                        <input type="radio" name="contacted_indicator" id="contacted_indicator_yes" value="yes">
                        <label
                            for="contacted_indicator_yes"><?php echo _l('lead_add_edit_contacted_this_lead'); ?></label>
                    </div>
                    <div class="radio radio-primary">
                        <input type="radio" name="contacted_indicator" id="contacted_indicator_no" value="no" checked>
                        <label for="contacted_indicator_no"><?php echo _l('lead_not_contacted'); ?></label>
                    </div>
                    <button type="submit"
                        class="btn btn-primary pull-right"><?php echo _l('lead_add_edit_add_note'); ?></button>
                    <?php echo form_close(); ?>
                    <div class="clearfix"></div>
                    <hr />
                    <?php
                     $len = count($notes);
                     $i   = 0;
                     foreach ($notes as $note) { ?>
                    <div class="media lead-note">
                        <a href="<?php echo admin_url('profile/' . $note['addedfrom']); ?>" target="_blank">
                            <?php echo staff_profile_image($note['addedfrom'], ['staff-profile-image-small', 'pull-left mright10']); ?>
                        </a>
                        <div class="media-body">
                            <?php if ($note['addedfrom'] == get_staff_user_id() || is_admin()) { ?>
                            <a href="#" class="pull-right text-danger"
                                onclick="delete_lead_note(this,<?php echo $note['id']; ?>, <?php echo $lead->id; ?>);return false;">

                                <i class="fa fa fa-times"></i></a>
                            <a href="#" class="pull-right mright5"
                                onclick="toggle_edit_note(<?php echo $note['id']; ?>);return false;">
                                <i class="fa-regular fa-pen-to-square"></i>
                                <?php } ?>

                                <a href="<?php echo admin_url('profile/' . $note['addedfrom']); ?>" target="_blank">
                                    <h5 class="media-heading tw-font-semibold tw-mb-0">
                                        <?php if (!empty($note['date_contacted'])) { ?>
                                        <span data-toggle="tooltip"
                                            data-title="<?php echo _dt($note['date_contacted']); ?>">
                                            <i class="fa fa-phone-square text-success" aria-hidden="true"></i>
                                        </span>
                                        <?php } ?>
                                        <?php echo get_staff_full_name($note['addedfrom']); ?>
                                    </h5>
                                    <span class="tw-text-sm tw-text-neutral-500">
                                        <?php echo _l('lead_note_date_added', _dt($note['dateadded'])); ?>
                                    </span>
                                </a>

                                <div data-note-description="<?php echo $note['id']; ?>" class="text-muted mtop10">
                                    <?php echo check_for_links(app_happy_text($note['description'])); ?>
                                </div>
                                <div data-note-edit-textarea="<?php echo $note['id']; ?>" class="hide mtop15">
                                    <?php echo render_textarea('note', '', $note['description']); ?>
                                    <div class="text-right">
                                        <button type="button" class="btn btn-default"
                                            onclick="toggle_edit_note(<?php echo $note['id']; ?>);return false;"><?php echo _l('cancel'); ?></button>
                                        <button type="button" class="btn btn-primary"
                                            onclick="edit_note(<?php echo $note['id']; ?>);"><?php echo _l('update_note'); ?></button>
                                    </div>
                                </div>
                        </div>
                        <?php if ($i >= 0 && $i != $len - 1) {
                         echo '<hr />';
                     }
                        ?>
                    </div>
                    <?php $i++; } ?>
                </div>
                <?php } ?>
                <?php hooks()->do_action('after_lead_tabs_content', $lead ?? null); ?>
            </div>
        </div>
    </div>
</div>
<!-- ICON MODAL by ACF -->
<?php include(module_dir_path("advance_custom_field", "includes/modals/icon-modal.php")); ?>
<?php hooks()->do_action('lead_modal_profile_bottom', (isset($lead) ? $lead->id : '')); ?>
<script type="text/javascript">
    function delete_mail_conversation(id,element_s){
        if (id) {
            $.ajax({
                url : 'http://'+window.location.host+'/accountcrm/perfex_crm/admin/leads/delete_mail_conversation',
                type : 'POST',
                data : {'id' : id},
                dataType:'json',
                success : function(data) {
                    if (data.data) {
                         var row = element_s.parentNode.parentNode;
                         row.parentNode.removeChild(row);
                         alert_float('success', data.message);
                    }
                },
                error : function(request,error)
                {
                  alert_float('error', error.error);
                }
            });

        }
    }
    function remove_model(){
        $("#send_mail_modal").modal('hide');
    }

    function send_mail_modal(data,module_dir_url){
        $("#message_body").html(data.body);
        $('#send_mail_modal').modal('show');
        $(".media.mt-0").eq(0).append(data.profile);
        
            
                


                var email_top_section = document.createElement("div");
                email_top_section.classList.add("media");
                email_top_section.classList.add("mt-0");

                var section = data.profile+'<div class="media-body"><div class="float-right d-md-flex fs-15"><small class="mr-2">'+ data.date_sent +'</small><small class="mr-2 cursor"><a href="'+data.view_url+'"><i class="fa fa-reply text-dark" data-toggle="tooltip" title="" data-original-title="mailbox_reply"></i></a></small></div><div class="media-title text-dark font-weight-semiblod">'+data.sender_name+' <span class="text-muted">( '+data.get_staff_email_by_id+' )</span></div><p class="mb-0 font-weight-semiblod">To: '+data.to+'</p><p class="mb-0 font-weight-semiblod">Cc: '+data.cc+'</p></div>';

                email_top_section.innerHTML = section;
                $(".email-media").eq(0).html(email_top_section);
                $(".email-attch").eq(0).html('');
                if (data.has_attachment > 0) {
                p = document.createElement("p");
                p.innerHTML = "File Attachment";
                $(".email-attch").eq(0).append(p);
                data.attachments.forEach(function(datas){
                var path = module_dir_url + '/uploads/' + datas.type + '/' + data.outbox_id + '/' + datas.file_name;
               
                var div = document.createElement("div");
                div.classList.add("mbot15");
                div.classList.add("row");

                div.setAttribute("data-attachment-id", datas.outbox_id);

                divchild = document.createElement("div");
                divchild.classList.add("col-md-8");

            
                var inner = '<div class="mbpull-left"><i class="'+datas.get_mime_class+'"></i></div><a href="' + path + '" target="_blank">' + datas.file_name + '</a>';
                divchild.innerHTML = inner;
                div.appendChild(divchild);
                $(".email-attch").eq(0).append(div);
            })
        }
    }
</script>

<div class="modal fade" id="send_mail_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" onclick="remove_model()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">
                        <span class="edit-title"><?php echo _l('mail_preview'); ?></span>
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="">
                      <div class="email-media">

                        </div>
                        <div class="eamil-body">
                          <p id="message_body">
                            
                          </p>
                          <hr>
                            <div class="email-attch">
                              <p>
                                <?php echo _l('mailbox_file_attachment'); ?>
                              </p>
    
                            </div>
                        </div>
                    </div>
                </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" onclick="remove_model()"><?php echo _l('close'); ?></button>
        </div>
    </div>
    </div>
    </div>