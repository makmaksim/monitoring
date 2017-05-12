<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Copyright © 2015 г. Коротков Е.А.

Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми «Программное Обеспечение»), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение, сублицензирование и/или продажу копий Программного Обеспечения, а также лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:

Запрещено изменять (модернизировать, удалять, скрывать любыми средствами) нижнюю часть Программного Обеспечения (footer) в административной панели и на front-end
Данное Программное Обеспечение является бесплатным только на стадии разработки, пользователь имеет право бесплатного пользования только версиями данного Программное Обеспечение без пометки "final" и "release"
Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.

ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ ГАРАНТИИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ, НО НЕ ОГРАНИЧИВАЯСЬ ИМИ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО КАКИМ-ЛИБО ИСКАМ, ЗА УЩЕРБ ИЛИ ПО ИНЫМ ТРЕБОВАНИЯМ, В ТОМ ЧИСЛЕ, ПРИ ДЕЙСТВИИ КОНТРАКТА, ДЕЛИКТЕ ИЛИ ИНОЙ СИТУАЦИИ, ВОЗНИКШИМ ИЗ-ЗА ИСПОЛЬЗОВАНИЯ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫХ ДЕЙСТВИЙ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ. */
?>

<ul id="myTab" class="nav nav-tabs">
    <li role="presentation" class="active">
        <a href="#email_params" class="cell_tab_link" data-toggle="tab" aria-expanded="true"><?=lang('email_params_tab')?></a>
    </li>
    <li role="presentation">
        <a href="#vk_params" class="cell_tab_link" data-toggle="tab" aria-expanded="true"><?=lang('vk_params_tab')?></a>
    </li>
    <li role="presentation">
        <a href="#email_template" class="cell_tab_link" data-toggle="tab" aria-expanded="true"><?=lang('email_template_tab')?></a>
    </li>
    
</ul>
<div class="tab-content">
    <div class="fields_block tab-pane fade active in" id="email_params">
        <form method="post" action="/admin/systemparams/edit_email_params" style="padding: 10px;">
            <div class="row">
                <div class="col-md-6">
                    <h4><?=lang('mail_system_params')?></h4>
                    <?=ViewInput::_checkbox(array(
                        'name' => 'send_system_messages',
                        'label' => lang('send_system_messages'),
                        'value' => $umc_mail['send_system_messages']
                    ))?>
                    <?=ViewInput::_text(array(
                        'name' => 'mail_sender',
                        'label' => lang('sender'),
                        'value' => $umc_mail['mail_sender']
                    ), '', lang('sender_title'))?>
                    <?=ViewInput::_text(array(
                        'name' => 'smtp_user',
                        'label' => lang('smtp_user'),
                        'value' => $umc_mail['smtp_user']
                    ))?>
                    <?=ViewInput::_list(array(
                        'name' => 'protocol',
                        'label' => lang('email_protocol'),
                        'list' => array('mail' => 'mail', 'smtp' => 'smtp'),
                        'value' => $umc_mail['protocol']
                    ))?>
                    <?=ViewInput::_list(array(
                        'name' => 'mailtype',
                        'label' => lang('mailtype'),
                        'list' => array('html' => 'HTML', 'text' => 'Text'),
                        'value' => $umc_mail['mailtype']
                    ))?>
                    <?=ViewInput::_text(array(
                        'name' => 'smtp_pass',
                        'label' => lang('smtp_pass'),
                        'value' => $umc_mail['smtp_pass']
                    ))?>
                    <?=ViewInput::_list(array(
                        'name' => 'smtp_crypto',
                        'label' => lang('smtp_crypto'),
                        'list' => array(0 => lang('no'), 'tls' => 'tls', 'ssl' => 'ssl'),
                        'value' => $umc_mail['smtp_crypto']
                    ))?>
                    <?=ViewInput::_text(array(
                        'name' => 'smtp_host',
                        'label' => lang('smtp_host'),
                        'value' => $umc_mail['smtp_host']
                    ))?>
                    <?=ViewInput::_text(array(
                        'name' => 'smtp_port',
                        'label' => lang('smtp_port'),
                        'value' => $umc_mail['smtp_port']
                    ))?>
                    <?=ViewInput::_get_send_button(array(
                        'label' => lang('save_text')
                    ))?>
                </div>
                <div class="col-md-6">
                    <h4><?=lang('mail_system_actions')?></h4>
                    <?=ViewInput::_checkbox(array(
                        'name' => 'mail_edit_fields',
                        'label' => lang('mail_send_edit_user'),
                        'value' => $umc_mail['mail_edit_fields']
                    ), '', lang('mail_send_edit_user_title'))?>
                    <?=ViewInput::_checkbox(array(
                        'name' => 'mail_new_cell',
                        'label' => lang('mail_new_cell'),
                        'value' => $umc_mail['mail_new_cell']
                    ))?>
                    <?=ViewInput::_checkbox(array(
                        'name' => 'mail_delete_cell',
                        'label' => lang('mail_delete_cell'),
                        'value' => $umc_mail['mail_delete_cell']
                    ))?>
                    <?=ViewInput::_checkbox(array(
                        'name' => 'mail_add_comment',
                        'label' => lang('mail_add_comment'),
                        'value' => $umc_mail['mail_add_comment']
                    ))?>
                    <?=ViewInput::_checkbox(array(
                        'name' => 'mail_edit_comment',
                        'label' => lang('mail_edit_comment'),
                        'value' => $umc_mail['mail_edit_comment']
                    ))?>
                    <?=ViewInput::_checkbox(array(
                        'name' => 'mail_comments_delete',
                        'label' => lang('mail_comments_delete'),
                        'value' => $umc_mail['mail_comments_delete']
                    ))?>
                    <?=ViewInput::_checkbox(array(
                        'name' => 'mail_upload_files',
                        'label' => lang('mail_upload_files'),
                        'value' => $umc_mail['mail_upload_files']
                    ))?>
                    <h4><?=lang('mail_system_actions_sotr')?></h4>
                    <div class="sotr_events_new_user_block">
                        <label><?=lang('mail_send_new_user')?></label>
                        <div class="sotr_events_new_user">
                            <?php foreach($umc_mail['mail_event_user'] as $key => $val) : ?>
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <?=ViewInput::_list(array(
                                        'only' => TRUE,
                                        'name' => 'user_group_from[]',
                                        'key' => 'group_id',
                                        'val' => 'name',
                                        'list' => $groups,
                                        'label' => lang('email_group_from'),
                                        'value' => $key
                                    ))?>
                                </div>
                                <div class="col-md-1">
                                    =>
                                </div>
                                <div class="col-md-4">
                                    <?=ViewInput::_list(array(
                                        'only' => TRUE,
                                        'name' => 'user_group_to[]',
                                        'key' => 'group_id',
                                        'val' => 'name',
                                        'list' => $groups,
                                        'label' => lang('email_group_to'),
                                        'value' => $val
                                    ))?>
                                </div>
                                <div class="col-md-1">
                                    <span class="btn glyphicon glyphicon-remove remove_mail_event"></span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?=ViewInput::_get_button(array(
                            'only' => TRUE,
                            'label' => lang('add_text')
                        ), 'id="add_user_mail_event_btn"')?>
                    </div>
                    <div class="sotr_events_new_user_block">
                        <label><?=lang('mail_new_cell')?></label>
                        <div class="sotr_events_new_cell">
                            <?php foreach($umc_mail['mail_event_cell'] as $key => $val) : ?>
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <?=ViewInput::_list(array(
                                        'only' => TRUE,
                                        'name' => 'cell_group_from[]',
                                        'key' => 'group_id',
                                        'val' => 'name',
                                        'list' => $groups,
                                        'label' => lang('email_group_from'),
                                        'value' => $key
                                    ))?>
                                </div>
                                <div class="col-md-1">
                                    =>
                                </div>
                                <div class="col-md-4">
                                    <?=ViewInput::_list(array(
                                        'only' => TRUE,
                                        'name' => 'cell_group_to[]',
                                        'key' => 'group_id',
                                        'val' => 'name',
                                        'list' => $groups,
                                        'label' => lang('email_group_to'),
                                        'value' => $val
                                    ))?>
                                </div>
                                <div class="col-md-1">
                                    <span class="btn glyphicon glyphicon-remove remove_mail_event"></span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?=ViewInput::_get_button(array(
                            'only' => TRUE,
                            'label' => lang('add_text')
                        ), 'id="add_cell_mail_event_btn"')?>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="fields_block tab-pane fade in" id="vk_params">
        <form method="post" action="/admin/systemparams/edit_vk_params" style="padding: 10px;">
            <div class="row">
                <div class="col-md-6">
                    <?=ViewInput::_checkbox(array(
                        'name' => 'enable_vk_messages',
                        'label' => lang('enable_vk_messages'),
                        'value' => $vk_params['enable_vk_messages']
                    ))?>
                    <?=ViewInput::_text(array(
                        'name' => 'api_id',
                        'label' => lang('vk_api_id'),
                        'value' => $vk_params['api_id']
                    ))?>
                    <?=ViewInput::_text(array(
                        'name' => 'secret_key',
                        'label' => lang('vk_secret_key'),
                        'value' => $vk_params['secret_key']
                    ))?>
                    <?=ViewInput::_get_send_button(array(
                        'label' => lang('save_text')
                    ))?>
                </div>
                <div class="col-md-6">
                    <h4><?=lang('mail_system_actions')?></h4>
                    <?=ViewInput::_checkbox(array(
                        'name' => 'vk_edit_fields',
                        'label' => lang('mail_send_edit_user'),
                        'value' => $vk_params['vk_edit_fields']
                    ), '', lang('mail_send_edit_user_title'))?>
                    <?=ViewInput::_checkbox(array(
                        'name' => 'vk_new_cell',
                        'label' => lang('mail_new_cell'),
                        'value' => $vk_params['vk_new_cell']
                    ))?>
                    <?=ViewInput::_checkbox(array(
                        'name' => 'vk_delete_cell',
                        'label' => lang('mail_delete_cell'),
                        'value' => $vk_params['vk_delete_cell']
                    ))?>
                    <?=ViewInput::_checkbox(array(
                        'name' => 'vk_add_comment',
                        'label' => lang('mail_add_comment'),
                        'value' => $vk_params['vk_add_comment']
                    ))?>
                    <?=ViewInput::_checkbox(array(
                        'name' => 'vk_edit_comment',
                        'label' => lang('mail_edit_comment'),
                        'value' => $vk_params['vk_edit_comment']
                    ))?>
                    <?=ViewInput::_checkbox(array(
                        'name' => 'vk_comments_delete',
                        'label' => lang('mail_comments_delete'),
                        'value' => $vk_params['vk_comments_delete']
                    ))?>
                    <?=ViewInput::_checkbox(array(
                        'name' => 'vk_upload_files',
                        'label' => lang('mail_upload_files'),
                        'value' => $vk_params['vk_upload_files']
                    ))?>
                </div>
            </div>
        </form>
        <small><?=lang('vk_notice')?></small>
    </div>
    <div class="fields_block tab-pane fade in" id="email_template">
        <form method="post" action="/admin/systemparams/edit_email_system" style="padding: 10px;">
            <?=ViewInput::_editor(array(
                'name' => 'email_template_system',
                'label' => lang('email_template_system'),
                'value' => $system_template
            ))?>
            <?=ViewInput::_get_send_button(array(
                'label' => lang('save_text')
            ))?>
        </form>
    </div>
</div>
<div id="user_events_maket" class="hidden">
    <div class="row form-group">
                                <div class="col-md-4">
                                    <?=ViewInput::_list(array(
                                        'only' => TRUE,
                                        'name' => 'user_group_from[]',
                                        'key' => 'group_id',
                                        'val' => 'name',
                                        'list' => $groups,
                                        'label' => lang('email_group_from')
                                    ))?>
                                </div>
                                <div class="col-md-1">
                                    =>
                                </div>
                                <div class="col-md-4">
                                    <?=ViewInput::_list(array(
                                        'only' => TRUE,
                                        'name' => 'user_group_to[]',
                                        'key' => 'group_id',
                                        'val' => 'name',
                                        'list' => $groups,
                                        'label' => lang('email_group_to')
                                    ))?>
                                </div>
                                <div class="col-md-1">
                                    <span class="btn glyphicon glyphicon-remove remove_mail_event"></span>
                                </div>
                            </div>
</div>
<div id="cell_events_maket" class="hidden">
    <div class="row form-group">
                                <div class="col-md-4">
                                    <?=ViewInput::_list(array(
                                        'only' => TRUE,
                                        'name' => 'cell_group_from[]',
                                        'key' => 'group_id',
                                        'val' => 'name',
                                        'list' => $groups,
                                        'label' => lang('email_group_from')
                                    ))?>
                                </div>
                                <div class="col-md-1">
                                    =>
                                </div>
                                <div class="col-md-4">
                                    <?=ViewInput::_list(array(
                                        'only' => TRUE,
                                        'name' => 'cell_group_to[]',
                                        'key' => 'group_id',
                                        'val' => 'name',
                                        'list' => $groups,
                                        'label' => lang('email_group_to')
                                    ))?>
                                </div>
                                <div class="col-md-1">
                                    <span class="btn glyphicon glyphicon-remove remove_mail_event"></span>
                                </div>
                            </div>
</div>