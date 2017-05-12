<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="modal fade" id="cell_params_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?=lang('cell_params_title_text') . ' ' . $group->name?> </h4>
      </div>
      <div class="modal-body">
        <form role="form" action="/admin/fields/save_cell_params" method="post">
          <?=ViewInput::_text(array(
            'label' => lang('form_cell_names_text'),
            'name' => 'params[cell_name]',
            'value' => $group->cell_name
          ))?>
          <div class="form-group row">
            <label class="col-sm-3"></label>
            <div class="col-sm-9"><button type="submit" class="btn btn-primary"><?=lang('form_btn_send')?></button></div>
          </div>
          <input type="hidden" name="group_id" value="<?=$group->group_id?>">
        </form>

        <?php if(!empty($fields)) : ?>
            <b><?=lang('available_fields')?></b>
            <table style="width: 100%;">
            <?php foreach($fields as $field) : ?>
                <tr><td><?=$field->name?></td><td>{<?=$field->unique?>}</td></tr>
            <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
    </div>
  </div>
</div>