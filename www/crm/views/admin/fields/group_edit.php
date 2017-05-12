<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="modal fade" id="edit_group_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?=lang('edit_form_title')?></h4>
      </div>
      <div class="modal-body">
        <form role="form" action="/admin/fields/edit_group" method="post" onsubmit="return send(this);return false;">
            <div class="errors"></div>
            <?=ViewInput::_text(array(
                'label' => lang('name'),
                'name' => 'name',
                'value' => $group->name,
                'required' => 1
            ))?>
            <?=ViewInput::_text(array(
                'label' => lang('postfix_text'),
                'name' => 'postfix',
                'value' => $group->postfix
            ), '', lang('postfix_text_title'))?>
            <?=ViewInput::_checkbox(array(
                'label' => lang('workmans'),
                'name' => 'workmans',
                'value' => $group->workmans
            ))?>
            <div class="form-group row">
            <label class="col-sm-3"></label>
            <div class="col-sm-9"><button type="submit" class="btn btn-primary"><?=lang('send_text')?></button></div>
          </div>
            <input type="hidden" name="group_id" value="<?=$group->group_id?>">
        </form>
        <?=get_scripts()?>
    </div>
    </div>
  </div>
</div>