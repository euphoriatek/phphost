<?php

# Version 1.0.0

$lang['predix'] = 'PrediX';
$lang['predix_chat'] = 'Chat';
$lang['predix_image_gen'] = 'Image Generator';
$lang['predix_transcription'] = 'Audio Transcription';
$lang['predix_translation'] = 'Audio Translation';
$lang['predix_settings'] = 'Settings';
$lang['predix_generate_image_label'] = 'Generate Image With Given Input';
$lang['predix_generate'] = 'Generate';
$lang['predix_generated_images'] = 'Generated Images';
$lang['predix_generated_images_question'] = 'Empower users with the ability to select the size of the images they can generate, giving them control over the process.';
$lang['predix_generate_image_number'] = 'Number Of Images To Generate';
$lang['predix_generate_image_number_helper'] = 'You can have control over the maximum number of images that a user can generate within a single session.';
$lang['predix_generate_image_size'] = 'Size Of Images To Generate';
$lang['predix_settings_open_ai_key'] = 'OpenAI Secret Key - <a href="https://platform.openai.com/account/api-keys" target="_blank">OpenAI API</a>';
$lang['predix_settings_text_limit'] = 'Chat Text Limit';
$lang['predix_settings_text_limit_question'] = 'To optimize operational costs, consider limiting the word count of OpenAI\'s chat responses.';
$lang['predix_settings_chat_model'] = 'Chat Model';
$lang['predix_settings_notification'] = 'Administrators are exempt from most limitations on these settings.';
$lang['predix_settings_predix_audio_transcription_model'] = 'Audio Transcription Model';
$lang['predix_settings_predix_audio_translation_model'] = 'Audio Translation Model';
$lang['predix_settings_predix_audio_translation_model_max_filesize'] = 'Audio Translation Maximum File Size in KB';
$lang['predix_settings_predix_audio_translation_model_allowed_extensions'] = 'Audio Translation Allowed Extensions';
$lang['predix_settings_predix_audio_transcription_model_max_filesize'] = 'Audio Transcription Maximum File Size in KB';
$lang['predix_settings_predix_audio_transcription_model_allow'] = 'Audio Transcription Maximum File Size in KB';
$lang['predix_settings_predix_audio_transcription_model_allowed_extensions'] = 'Audio Transcription Allowed Extensions';
$lang['predix_audio_translation_file_maximum_size'] = 'Audio File could not be attached because maximum size of file, please adjust file size on Predix->Settings';
$lang['predix_audio_translation_file_extensions_err'] = 'Audio File you are trying to upload is not a supported extension, please adjust extensions on Predix->Settings';
$lang['predix_generated_audio_translations'] = 'Generated Audio Translations';
$lang['predix_generated_translated_text'] = 'Translated Text';
$lang['predix_generated_transcribed_text'] = 'Transcribed Text';
$lang['predix_translate_button'] = 'Translate Audio To English';
$lang['predix_upload_audio_file'] = 'Upload Audio File';
$lang['predix_select_file_to_upload'] = 'Select File To Upload';
$lang['predix_transcript_button'] = 'Transcript';
$lang['predix_generated_audio_transcriptions'] = 'Generated Audio Transcriptions';
$lang['predix_chat_notification'] = 'Please setup correct OpenAI API Key in order to use PrediX services, PrediX->Settings->OpenAI Key';
$lang['predix_delete_chat_history'] = 'Delete Chat History';
$lang['predix_enter_your_message'] = 'Send a message....';
$lang['predix_send_message'] = 'Send';
$lang['predix_settings_use_streams'] = 'Use PHP Streams For Chat';
$lang['predix_settings_use_streams_tooltip'] = 'Sometime some servers have problem with PHP Streams and this will result in Chat not working, if this is the case please check NO';
$lang['predix_template_categories'] = 'Template Categories';
$lang['predix_templates'] = 'Templates';
$lang['predix_total_template_category_templates'] = 'Total Templates : %s';
$lang['predix_category_name'] = 'Category Name';
$lang['predix_category_description'] = 'Category Description';
$lang['predix_is_enabled'] = 'Is Enabled';
$lang['predix_add_template_category'] = 'Create Template Category';
$lang['predix_document_name'] = 'Document Name';
$lang['predix_document_description'] = 'Document Description';
$lang['predix_documents'] = 'Documents';
$lang['predix_template_category_failed_to_create'] = 'Failed to create template category';
$lang['predix_template_category_failed_to_update'] = 'Failed to update template category';
$lang['predix_create_template'] = 'Create Template';
$lang['predix_input_template_name'] = 'Template Name';
$lang['predix_input_template_category'] = 'Template Category';
$lang['predix_input_template_description'] = 'Template Description';
$lang['predix_input_template_custom_prompt'] = 'Custom Prompt';
$lang['predix_input_template_icon'] = 'Template Icon (fontawesome.com)';
$lang['predix_input_language_to_use'] = 'Response Language';
$lang['predix_input_creativity'] = 'Creativity';
$lang['predix_input_tone_of_voice'] = 'Tone of Voice';
$lang['predix_input_max_result_length'] = 'Max Result Length';
$lang['predix_generate_template_text'] = 'Generate Text';
$lang['predix_save_as_document'] = 'Save As Document';
$lang['predix_use_template'] = 'Use Template';
$lang['predix_settings_reply_agent_name'] = 'Ticket Reply Bot Name';
$lang['predix_settings_reply_agent_title'] = 'Ticket Reply Bot Title';
$lang['predix_settings_ticket_reply_agent'] = 'Enable Automatic Reply From Bot On Tickets';
$lang['predix_settings_ticket_assigned_staff'] = 'Assign Every Bot Reply To Staff :';
$lang['predix_settings_ticket_reply_agent_tooltip'] = 'If this option is checked as yes, then everytime a client opens a ticket bot will create a reply with possible solutions and ask client if needed for more information.';
$lang['predix_custom_input_name'] = 'Custom Input Name';
$lang['predix_custom_input_label'] = 'Custom Input Label';
$lang['predix_custom_input_field_type'] = 'Custom Input Field Type';
$lang['predix_custom_prompt_hint'] = 'Use <b>Custom Input Name</b> On <strong>Custom Prompt</strong> As A Merge Field For Example: John is thinking <b>{{user_thought}}</b>';