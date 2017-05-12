<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<form method="post" action="/admin/import/start_import" enctype="multipart/form-data">
<div class="row">
    <div class="col-md-4">
        <?=lang('import_comment')?><br><br>
        <div class="row">
            <label class="col-md-3"><?=lang('import_file_text')?></label>
            <div class="col-md-9">
                <input type="file" name="importfile">
            </div>
        </div><br>
        <?=ViewInput::_list(array(
            'label' => lang('group_label'),
            'name' => 'group_id',
            'list' => $groups,
            'key' => 'group_id',
            'val' => 'name'
        ))?>
        <div class="fields_list_import">
            <div class="field_import_block row">
                
                <div class="col-md-11">
                    <?=ViewInput::_list(array(
                        'label' => lang('field_label'),
                        'name' => 'field[]',
                        'list' => $fields,
                        'key' => 'unique',
                        'val' => 'name'
                    ))?>
                </div><div class="col-md-1"></div>
            </div>
        </div>
        <?=ViewInput::_text(array(
            'label' => lang('separator_label'),
            'name' => 'separator'
        ))?>
        <?=ViewInput::_get_button(array(
            'only' => true,
            'label' => lang('add_field_btn')
        ), ' id="add_field"')?>
        <?=ViewInput::_get_send_button(array(
            'only' => true,
            'label' => lang('send_text')
        ))?>
    </div>
</div>
</form>
<div style="display: none;" id="field_html_hidden">
            <div class="field_import_block row">
                
                <div class="col-md-11">
                    <?=ViewInput::_list(array(
                        'label' => lang('field_label'),
                        'name' => 'field[]',
                        'list' => $fields,
                        'key' => 'unique',
                        'val' => 'name'
                    ))?>
                </div>
                <div class="col-md-1"><span class="btn glyphicon glyphicon-remove remove_field"></span></div>
            </div>
</div>
