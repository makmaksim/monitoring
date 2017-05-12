<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="modal fade" id="cell_edit_form" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-body">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?=lang('cell_edit_form_title')?> <?=$fields['fields_data']->name?></h4>
        </div>
        
        <form method="post" action="/home/edit_cell" onsubmit="return send(this);return false;">
            <div class="errors"></div>
            <?=($perms->control_cell) ? ViewInput::_text(array(
                'label' => lang('cell_name'),
                'name' => 'cell_name',
                'value' => $fields['fields_data']->name,
                'required' => 1
            )) : ''?>
            <?php foreach($fields['fields_list'] as $field) : ?>
                <?php if($perms->{$field->unique . '_rec'}) : ?>
                    <?=ViewInput::get_input_for_user($field, $fields['fields_data'])?>
                <?php endif; ?>
            <?php endforeach; ?>
            <input type="hidden" name="user_id" value="<?=$user_id?>">
            <input type="hidden" name="cell_id" value="<?=$cell_id?>">
            <?=ViewInput::_get_send_button(array('label' => lang('send_text')))?>
        </form>
        <?=get_scripts()?>
      </div>
    </div>
  </div>
</div>
