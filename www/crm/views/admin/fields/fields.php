<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="groups_list datas_list">

<ul>
    <?php foreach($groups as $group) : ?>
        <li data-id="<?=$group->group_id?>" class="col-sm-4">
            <span class="glyphicon glyphicon-pencil edit_group" aria-hidden="true"></span>
            <span class="glyphicon glyphicon-cog group_control_perms" aria-hidden="true"></span>
            <span class="glyphicon glyphicon-list-alt get_perm_form" aria-hidden="true"></span>
            <span class="glyphicon glyphicon glyphicon-trash delete_group" aria-hidden="true"></span>
            <span class="group_name"><?=$group->name?>&nbsp;&nbsp;&nbsp;<span class="small">{<?=$group->unique?>}</span></span>
            <?php if(!empty($group->fields_list)) : ?>
            <div class="fields_list">
            <h6><?=lang('list_fields_group')?></h6>
            <ul>
                <?php foreach($group->fields_list as $field) : ?>
                    <?php if(!$field->in_cell) : ?>
                    <li style="<?=$field->data?>" data-id="<?=$field->field_id?>" class="<?=$field->unique?>">
                        <span class="glyphicon glyphicon-pencil edit_field" aria-hidden="true"></span>
                        <span class="glyphicon glyphicon-cog get_type_form" aria-hidden="true" data-type="<?=$field->type?>"></span>
                        <span class="glyphicon glyphicon-trash delete_field" aria-hidden="true" data-selector="<?=$field->unique?>"></span>
                        <span class="field_name"><?=$field->name?><?=($field->required) ? '<span class="red">*</span>' : ''?>&nbsp;&nbsp;&nbsp;<span class="small">{<?=$field->unique?>}</span></span>
                    </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
            <div class="fields_list_cell">
            <h6><span class="glyphicon glyphicon-cog cell_params" aria-hidden="true" data-id="<?=$group->group_id?>"></span> <?=lang('list_fields_group_cell')?></h6>
            <ul>
                <?php foreach($group->fields_list as $field) : ?>
                    <?php if($field->in_cell) : ?>
                    <li style="<?=$field->data?>" data-id="<?=$field->field_id?>" class="<?=$field->unique?>">
                        <span class="glyphicon glyphicon-pencil edit_field" aria-hidden="true"></span>
                        <span class="glyphicon glyphicon-cog get_type_form" aria-hidden="true" data-type="<?=$field->type?>"></span>
                        <span class="glyphicon glyphicon-trash delete_field" aria-hidden="true" data-selector="<?=$field->unique?>"></span>
                        <span class="field_name"><?=$field->name?><?=($field->required) ? '<span class="red">*</span>' : ''?>&nbsp;&nbsp;&nbsp;<span class="small">{<?=$field->unique?>}</span></span>
                    </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>
</div>

<div style="clear: both;">
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_group_form">
  <?=lang('group_btn')?>
</button>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_field_form">
  <?=lang('form_btn')?>
</button>
</div>

<!--редактируем поле-->

<div class="modal fade" id="add_field_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?=lang('form_name')?></h4>
      </div>
      <div class="modal-body">
        <form role="form" action="/admin/fields/new_field" method="post" onsubmit="return send(this);return false;">
            <div class="errors"></div>
          <?=ViewInput::_text(array(
            'label' => lang('form_input_name'),
            'name' => 'name',
            'required' => 1
          ))?>
          <?=ViewInput::_list_multiple(array(
            'label' => lang('group_name'),
            'name' => 'group_id',
            'list' => $groups,
            'key' => 'group_id',
            'val' => 'name',
            'required' => 1
          ), ' size="5"')?>
          <?=ViewInput::_list(array(
            'label' => lang('form_input_type'),
            'name' => 'type',
            'list' => ViewInput::_types_input(),
            'required' => 1
          ))?>
          <?=ViewInput::_text(array(
            'label' => lang('form_input_data'),
            'name' => 'data'
          ))?>
          <?=ViewInput::_checkbox(array(
            'label' => lang('field_in_cell'),
            'name' => 'in_cell'
          ))?>
          <?=ViewInput::_checkbox(array(
            'label' => lang('required_text'),
            'name' => 'required'
          ))?>
          <div class="form-group row">
            <label class="col-sm-3"></label>
            <div class="col-sm-9"><button type="submit" class="btn btn-primary"><?=lang('send_text')?></button></div>
          </div>
        </form>
    </div>
    </div>
  </div>
</div>

<!--редактируем группу-->
<div class="modal fade" id="add_group_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?=lang('form_title')?></h4>
      </div>
      <div class="modal-body">
        <form role="form" action="/admin/fields/new_group" method="post" onsubmit="return send(this);return false;">
            <div class="errors"></div>
          <?=ViewInput::_text(array(
            'name' => 'name',
            'label' => lang('name'),
            'required' => 1
          ))?>
          <?=ViewInput::_text(array(
                'label' => lang('postfix_text'),
                'name' => 'postfix'
            ), '', lang('postfix_text_title'))?>
          <?=ViewInput::_checkbox(array(
            'label' => lang('workmans'),
            'name' => 'workmans'
          ))?>
          <div class="form-group row">
            <label class="col-sm-3"></label>
            <div class="col-sm-9"><button type="submit" class="btn btn-primary"><?=lang('form_btn_send')?></button></div>
          </div>
        </form>
    </div>
    </div>
  </div>
</div>

<!--подтверждение удаления-->

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