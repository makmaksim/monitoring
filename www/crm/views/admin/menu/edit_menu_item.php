<?php defined('BASEPATH') OR exit('No direct script access allowed'); var_dump($fields) ?>
<div class="modal fade" id="edit_menu_item" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-body">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?=lang('edit_menu_item_text')?></h4>
        </div>
        <form method="post" action="/admin/menu/edit_menu_item"  onsubmit="return send(this);return false;">
            <div class="errors"></div>
            <?=ViewInput::_text(array(
                'name' => 'name',
                'label' => lang('item_name_text'),
                'required' => TRUE,
                'value' => $item->name
            ))?>
            <?=ViewInput::_list(array(
                'name' => 'type',
                'label' => lang('types_item_text'),
                'list' => $types_item,
                'value' => $item->type
            ))?>
            <?=ViewInput::_list(array(
                'name' => 'parent_id',
                'label' => lang('parent_text'),
                'list' => $parents,
                'key' => 'menu_id',
                'val' => 'name',
                'value' => $item->parent_id
            ))?>
            <?=ViewInput::_list(array(
                'name' => 'group_id',
                'label' => lang('group_text'),
                'list' => $groups,
                'key' => 'group_id',
                'val' => 'name',
                'value' => $item->group_id
            ))?>
            <div class="group_fields">
                <?=ViewInput::_list_multiple(array(
                    'name' => 'fields',
                    'label' => lang('fileds_in_list_text'),
                    'list' => $fields,
                    'key' => 'field_id',
                    'val' => 'name',
                    'value' => unserialize($item->fields)
                ), 'size="' . count($fields) . '"')?>
            </div>
            <input type="hidden" name="menu_id" value="<?=$item->menu_id?>">
            <?=ViewInput::_get_send_button(array('label' => lang('send_text')))?>
        </form>
        <?=get_scripts()?>
        <script type="text/javascript">
            jQuery(document).ready(function($){
                $('#group_id').change(function(){
                    var id = $(this).val();
                    
                    $.ajax({
                      type: "POST",
                      url: "/admin/menu/get_fields_group",
                      dataType: "json",
                      data: {'id' : id},
                      success: function(data){
                        if(!data.error){
                            $('#fields').html('');
                            $('#fields').attr('size', 3);
                            for(var a in data.fields){
                                $('#fields').append('<option value="' + data.fields[a].field_id + '">' + data.fields[a].name + '</option>');
                            }
                            $('#fields').attr('size', data.fields.length);
                        }
                      }
                    });
                });
            });
        </script>
      </div>
    </div>
  </div>
</div>