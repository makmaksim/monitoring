<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="modal fade" id="edit_field_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?=lang('edit_form_name')?></h4>
      </div>
      <div class="modal-body">
        <form role="form" action="/admin/fields/edit_field" method="post" onsubmit="return send(this);return false;">
        <div class="errors"></div>
          <?=ViewInput::_text(array(
            'label' => lang('form_input_name'),
            'name' => 'name',
            'value' => $field->name,
            'required' => 1
          ))?>
          <?=ViewInput::_list_multiple(array(
            'label' => lang('group_name'),
            'name' => 'groups',
            'list' => $groups,
            'key' => 'group_id',
            'val' => 'name',
            'value' => $groups_vals,
            'required' => 1
          ), ' multiple size="5"')?>
          <?=ViewInput::_list(array(
            'label' => lang('form_input_type'),
            'name' => 'type',
            'list' => ViewInput::_types_input(),
            'value' => $field->type,
            'required' => 1
          ))?>
          <?=ViewInput::_text(array(
            'label' => lang('form_input_data'),
            'name' => 'data',
            'value' => $field->data
          ))?>
          <?=ViewInput::_checkbox(array(
            'label' => lang('field_in_cell'),
            'name' => 'in_cell',
            'value' => $field->in_cell
          ))?>
          <?=ViewInput::_checkbox(array(
            'label' => lang('required_text'),
            'name' => 'required',
            'value' => $field->required
          ))?>
          <div class="form-group row">
            <label class="col-sm-3"></label>
            <div class="col-sm-9"><button type="submit" class="btn btn-primary"><?=lang('send_text')?></button></div>
          </div>
          <input type="hidden" name="field_id" value="<?=$field->field_id?>">
        </form>
        <?=get_scripts()?>
    </div>
    </div>
  </div>
</div>