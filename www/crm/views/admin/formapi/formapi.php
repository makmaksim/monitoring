<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="" style="max-width: 980px;">
    <?=ViewInput::_get_button(array(
        'only' => true,
        'label' => lang('new_formapi_btn')
    ), ' id="new_formapi_btn"')?>
    <div class="formsapi_list" style="margin-top: 20px;">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <?php foreach($forms as $key => $form) : ?>
                <div class="panel panel-default panel_form_<?=$form->form_id?>">
                    <div class="panel-heading" role="tab" id="headingOne">
                      <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#block<?=$key?>" aria-expanded="true" aria-controls="collapseOne">
                            <?=$form->form_title?>
                        </a>
                        <button type="button" class="close remove_formapi" aria-label="Close" data-form="<?=$form->form_id?>"><span aria-hidden="true">&times;</span></button>
                      </h4>
                    </div>
                    <div id="block<?=$key?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                      <form method="post" action="/admin/formapi/edit_formapi" style="padding: 10px;">
                        <?=ViewInput::_text(array(
                            'label' => lang('form_input_name'),
                            'name' => 'form_title',
                            'value' => $form->form_title
                        ))?>
                        <div class="form-group row">
                            <label class="col-sm-3" for="form_id"><?=lang('form_id_label')?></label>
                            <div class="col-sm-9"><?=$form->form_id?></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3" for="api_key" title="<?=lang('api_key_title_text')?>"><?=lang('api_key_text')?></label>
                            <div class="col-sm-6"><input type="text" class="form-control api_key" placeholder="<?=lang('api_key_text')?>" name="api_key" value="<?=$form->api_key?>"></div>
                            <div class="col-sm-3"><input type="button" class="btn btn-default api_key_generate" name="api_key" value="<?=lang('api_key_generate_text')?>"></div>
                        </div>
                        <?=ViewInput::_checkbox(array(
                            'label' => lang('enable_api_text'),
                            'name' => 'status',
                            'value' => $form->status
                        ))?>
                        <?=ViewInput::_list(array(
                            'label' => lang('group_name'),
                            'name' => 'group_id',
                            'list' => $groups,
                            'key' => 'group_id',
                            'val' => 'name',
                            'value' => $form->group_id
                        ))?>
                        <?php if(!empty($form->fields)) : ?>
                        <div class="fields_list_formapi">
                            <?php foreach(unserialize($form->fields) as $val) : ?>
                                <div class="field_formapi_block row">
                                    <div class="col-md-11">
                                        <?=ViewInput::_list(array(
                                            'label' => lang('field_label'),
                                            'name' => 'field[]',
                                            'list' => $groups[$form->group_id]->fields,
                                            'key' => 'field_id',
                                            'val' => 'name',
                                            'value' => $val
                                        ))?>
                                    </div>
                                    <div class="col-md-1"><span class="btn glyphicon glyphicon-remove remove_field"></span></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <div class="fields_list_formapi">
                            <div class="field_formapi_block row">
                                
                                <div class="col-md-11">
                                    <?=ViewInput::_list(array(
                                        'label' => lang('field_label'),
                                        'name' => 'field[]',
                                        'list' => $groups[$form->group_id]->fields,
                                        'key' => 'field_id',
                                        'val' => 'name'
                                    ))?>
                                </div><div class="col-md-1"></div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div style="display: none;" id="field_html_hidden">
                                    <div class="field_formapi_block row">
                                        
                                        <div class="col-md-11">
                                            <?=ViewInput::_list(array(
                                                'label' => lang('field_label'),
                                                'name' => 'field[]',
                                                'list' => $groups[$form->group_id]->fields,
                                                'key' => 'field_id',
                                                'val' => 'name'
                                            ))?>
                                        </div>
                                        <div class="col-md-1"><span class="btn glyphicon glyphicon-remove remove_field"></span></div>
                                    </div>
                        </div>
                        <button type="button" class="btn btn-primary add_field" rel="add_field"><?=lang('add_field_btn')?></button>

                        <?=ViewInput::_get_send_button(array(
                            'only' => true,
                            'label' => lang('send_text'),
                            'name' => 'enable_api'
                        ))?>
                        <input type="hidden" name="form_id" value="<?=$form->form_id?>">
                      </form>
                    </div>
                  </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <p><?=lang('delete_text')?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default no_btn" data-dismiss="modal"><?=lang('no')?></button>
        <button type="button" class="btn btn-primary yes_btn" data-dismiss="modal"><?=lang('yes')?></button>
      </div>
    </div>
  </div>
</div>