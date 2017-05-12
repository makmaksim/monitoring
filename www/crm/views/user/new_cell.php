<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal fade" id="new_cell" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-body">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?=lang('fields_add_cell_title_form')?></h4>
        </div>
        <form method="post" action="/home/new_cell" onsubmit="return send(this);return false;">
            <div class="errors"></div>
            <?=ViewInput::_text(array(
                'label' => lang('cell_name'),
                'name' => 'cell_name',
                'value' => $group->cell_name,
                'required' => 1
            ))?>
            <?php foreach($fields['in_cell']['fields_list'] as $field) : ?>
                <?=ViewInput::get_input_for_user($field, $fields['not_cell']['fields_data'])?>
            <?php endforeach; ?>
            <input type="hidden" name="user_id" value="<?=$user_id?>">
            <input type="hidden" name="href" id="href" value="">
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