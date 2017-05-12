<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="modal fade" id="all_mess" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-body">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Отправить группам</h4>
        </div>

        <form method="post" action="<?=base_url()?>chat/send_all_mess">
            <div class="errors"></div>
            <?=ViewInput::_list_multiple(array(
                'label' => 'Группы',
                'name' => 'groups',
                'list' => $groups,
                'key' => 'group_id',
                'val' => 'name'
            ))?>
            <?=ViewInput::_textarea(array(
                'label' => 'Сообщение',
                'name' => 'message'
            ))?>
            <input type="hidden" name="href" id="href" value="">
            <?=ViewInput::_get_send_button(array('label' => lang('send_text')))?>
        </form>
        <script type="text/javascript">
            jQuery(document).ready(function($){
                $('#href').val(location.href)
            });
        </script>
      </div>
    </div>
  </div>
</div>
