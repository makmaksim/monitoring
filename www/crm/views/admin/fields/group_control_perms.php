<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="modal fade" id="group_control_perms" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?=lang('control_perms_title_form')?> <?=$group->name?></h4>
      </div>
      <div class="modal-body">
        <form role="form">
            <?=ViewInput::_checkbox(array(
                'label' => lang('control_user'),
                'name' => 'control_user',
                'value' => $group->control_user
            ), 'data-p_name="control_user"')?>
            <?=ViewInput::_checkbox(array(
                'label' => lang('control_cell'),
                'name' => 'control_cell',
                'value' => $group->control_cell
            ), 'data-p_name="control_cell"')?>
            <?=ViewInput::_checkbox(array(
                'label' => lang('control_export'),
                'name' => 'control_export',
                'value' => $group->control_export
            ), 'data-p_name="control_export"')?>
            <?=ViewInput::_checkbox(array(
                'label' => lang('control_chat'),
                'name' => 'control_chat',
                'value' => $group->control_chat
            ), 'data-p_name="control_chat"')?>
            <?=ViewInput::_checkbox(array(
                'label' => lang('control_chart'),
                'name' => 'control_chart',
                'value' => $group->control_chart
            ), 'data-p_name="control_chart"')?>
            <input type="hidden" id="from_group" value="<?=$group->group_id?>">
        </form>
        <script type="text/javascript">
            jQuery(document).ready(function($){
                $('#group_control_perms').find('label').click(function(){
                    var selector = $(this).attr('for');
                    var id = $('#' + selector).data('group');
                    var p_name = $('#' + selector).data('p_name');
                    var from_group = $('#from_group').val();
                    setTimeout(function(){
                  
                        if($('#' + selector).prop('checked')){
                            var p = 1;
                        }else{
                            var p = 0;
                        }

                        $.ajax({
                          type: "POST",
                          url: "/admin/fields/save_group_control_perms",
                          dataType: "html",
                          data: {'from_group' : from_group, 'p' : p, 'p_name' : p_name},
                          success: function(data){
//                              $('body').append(data);
                          },
                          error: function(){alert('error');
                              if($('#' + selector).prop('checked')){
                                  $('#' + selector).prop('checked', false);
                              }else{
                                  $('#' + selector).prop('checked', true);
                              }
                          }
                        });
                    },10);
                }); 
            });
        </script>
    </div>
    </div>
  </div>
</div>