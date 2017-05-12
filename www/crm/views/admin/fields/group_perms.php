<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="modal fade" id="edit_perm_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?=lang('perm_form_title')?> <?=$g->name?></h4>
      </div>
      <div class="modal-body">
        <table class="perms_table">
            <thead>
                <tr>
                    <th></th>
                    <th><?=lang('read')?></th>
                    <th><?=lang('rec')?></th>
                </tr>
            </thead>
            <tbody>
                
                <?php foreach($groups as $group) : ?>
                    <tr>
                        <td colspan="3" style="text-align: center; font-weight: bold;"><?=lang('groups_text')?></td>
                    </tr>
                    <tr class="groups_p">
                        <td><?=$group->name?></td>
                        <td><?=ViewInput::_checkbox(array(
                            'only' => true,
                            'name' => 'group_read' . $group->group_id,
                            'value' => $g->{$group->unique}
                        ), 'data-group="' . $group->group_id . '"' )?></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="font-weight: bold;"><?=lang('fields_text')?></td>
                    </tr>
                    <?php foreach($group->fields as $field) : ?>
                        <tr class="fields_p">
                            <td><?=$field->name?></td>
                            <td>
                                <input type="hidden" name="<?='field_read' . $field->field_id?>" value="0">
                                <input type="checkbox" name="<?='field_read' . $field->field_id?>" class="checkbox checkbox<?=$field->field_id?>_read" id="<?='field_read' . $field->field_id?>" value="<?=$g->{$field->unique . '_read'}?>" <?=($g->{$field->unique . '_read'}) ? 'checked="checked"' : ''?> data-field="<?=$field->field_id?>" data-permtype="_read">
                                <label data-selector="checkbox<?=$field->field_id?>_read"></label>                            
                            </td>
                            <td>
                                <input type="hidden" name="<?='field_rec' . $field->field_id?>" value="0">
                                <input type="checkbox" name="<?='field_rec' . $field->field_id?>" class="checkbox checkbox<?=$field->field_id?>_rec" id="<?='field_rec' . $field->field_id?>" value="<?=$g->{$field->unique . '_rec'}?>" <?=($g->{$field->unique . '_rec'}) ? 'checked="checked"' : ''?> data-field="<?=$field->field_id?>" data-permtype="_rec">
                                <label data-selector="checkbox<?=$field->field_id?>_rec"></label>                            
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
        <script type="text/javascript">
            jQuery(document).ready(function($){
                $('.groups_p').find('label').click(function(){
                    var selector = $(this).attr('for');
                    var id = $('#' + selector).data('group');
                    var from_group = $('#from_group').val();
                    setTimeout(function(){
                  
                        if($('#' + selector).prop('checked')){
                            var p = 1;
                        }else{
                            var p = 0;
                        }

                        $.ajax({
                          type: "POST",
                          url: "/admin/fields/save_perms_group",
                          dataType: "html",
                          data: {'id' : id, 'from_group' : from_group, 'p' : p},
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
                $('.fields_p').find('label').click(function(){
                    var $this = $(this);
                    var selector = $this.data('selector');
                    var id = $this.parent().find('.' + selector).data('field');
                    var type = $this.parent().find('.' + selector).data('permtype');
                    var from_group = $('#from_group').val();
//                    setTimeout(function(){
                    var prop = 1;
                        if($this.parent().find('.' + selector).prop('checked')){
                            var prop = 0;
                        }

                        $.ajax({
                          type: "POST",
                          url: "/admin/fields/save_perms_fields",
                          dataType: "html",
                          data: {'id' : id, 'from_group' : from_group, 'p' : prop, 'type' : type},
                          success: function(data){
                              $('.' + selector).each(function(){
                                  $(this).prop('checked', prop);
                              });
                          },
                          error: function(){
                              $('.' + selector).each(function(){
                                  $(this).prop('checked', $this.parent().find('.' + selector).prop('checked'));
                              });
                          }
                        });
//                    },10);
                }); 
                
            });
        </script>
        <input type="hidden" id="from_group" value="<?=$g->group_id?>">
    </div>
    </div>
  </div>
</div>