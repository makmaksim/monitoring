<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="modal fade" id="new_formapi_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?=lang('new_formapi_title')?></h4>
      </div>
      <div class="modal-body">
        <form role="form" action="/admin/formapi/save_new_formapi" method="post">
          <?=ViewInput::_text(array(
            'label' => lang('form_input_name'),
            'name' => 'name'
          ))?>
          <?=ViewInput::_checkbox(array(
            'label' => lang('form_input_status'),
            'name' => 'status'
          ))?>
          <?=ViewInput::_list(array(
            'label' => lang('group_name'),
            'name' => 'groups',
            'list' => $groups,
            'key' => 'group_id',
            'val' => 'name'
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