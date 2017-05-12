<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal fade" id="dynamic_field_edit" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-body">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Дата звонка</h4>
        </div>
        <form method="post" action="/groups/save_dynamic_field">
            <div class="errors"></div>
            <?=ViewInput::get_input_for_user($field, $field->data)?>
            <input type="hidden" name="href" id="href" value="">
            <input type="hidden" name="user_id" id="user_id" value="<?=$field->data->user_id?>">
            <input type="hidden" name="cell_id" id="user_id" value="<?=$field->data->cell_id?>">
            <input type="hidden" name="field" id="field" value="<?=$field->unique?>">
            <?=ViewInput::_get_send_button(array('label' => lang('send_text')))?>
        </form>
        <?=get_scripts()?>
        <script type="text/javascript">
            jQuery(document).ready(function($){
                $('#href').val(location.href)
            });
        </script>
      </div>
    </div>
  </div>
</div>