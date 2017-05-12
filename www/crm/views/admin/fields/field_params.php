<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="modal fade" id="field_params_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?=$field_params_text . ' ' . $field->name?> </h4>
      </div>
      <div class="modal-body">
        <form role="form" action="/admin/fields/edit_field_params" method="post">
          <?php switch($field->type){
              case 'list' : 
                $params = unserialize($field->params);?>
                  <div class="form-group row">
                    <label class="col-sm-2"><?=lang('field_params_text')?></label>
                    <div class="col-sm-10">
                        <div class="params_list">
                            <?php if(is_array($params)) : ?>
                            <?php foreach($params as $key => $val) : ?>
                                <div class="params_block row params_block_<?=$key?>">
                                    <div class="col-xs-1">#<?=$key?></div>
                                    <div class="col-xs-5">
                                        <input type="text" name="params[<?=$key?>][]" class="form-control" placeholder="<?=lang('form_list_item')?>" value="<?=$val[0]?>">
                                    </div>
                                    <div class="col-xs-5">
                                        <input type="text" name="params[<?=$key?>][]" class="form-control" placeholder="<?=lang('form_input_data')?>" value="<?=$val[1]?>">
                                    </div>
                                    <div class="col-xs-1 glyphicon glyphicon-remove remove_item_params btn" data-line="<?=$key?>">&nbsp;</div>
                                </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="btn btn-default add_data_in_list" style="width: 100%; margin-top: 15px;"><?=lang('add_data_text')?></div>
                            </div>
                        </div>
                    </div>
                  </div>
                  <script type="text/javascript">
                        var a = <?=($params) ? count($params) : 0?>;
                        jQuery(document).ready(function($){
                            $('.add_data_in_list').click(function(){
                                
                                var html = '<div class="col-xs-1">#' + a + '</div><div class="col-xs-5"><input type="text" name="params[' + a + '][]" class="form-control" placeholder="<?=lang('form_list_item')?>"></div><div class="col-xs-5"><input type="text" name="params[' + a + '][]" class="form-control" placeholder="<?=lang('form_input_data')?>"></div><div class="col-xs-1 glyphicon glyphicon-remove remove_item_params btn" data-line="' + a + '">&nbsp;</div>';
                                var paramsBlock = $('<div class="params_block row params_block_' + a + '" />').html(html).appendTo('.params_list');
                                a++;
                            });
                            
                            $('.params_list').on('click', '.remove_item_params', function(){
                                var line = $(this).data('line');
                                $('.params_block_' + line).fadeOut(200,function(){
                                    $('.params_block_' + line).remove();
                                });
                            });
                        });
                    </script>
                 <?php break;
              case 'text' :
                  echo ViewInput::_checkbox(array(
                            'label' => lang('field_param_name_unique'),
                            'name' => 'params',
                            'value' => $field->params
                        ));
                  break;
              case 'email' :
                  echo ViewInput::_checkbox(array(
                            'label' => lang('field_param_email_notice'),
                            'name' => 'params',
                            'value' => $field->params
                        ));
                  break;
              case 'user' :
                  echo ViewInput::_list(array(
                            'label' => lang('groups_text'),
                            'name' => 'params',
                            'list' => $groups,
                            'key' => 'group_id',
                            'val' => 'name',
                            'value' => $field->params
                        ));
                  break;
          } ?>
          <div class="form-group row">
            <label class="col-sm-3"></label>
            <div class="col-sm-9"><button type="submit" class="btn btn-primary"><?=$form_btn_send?></button></div>
          </div>
          <input type="hidden" name="field_id" value="<?=$field->field_id?>">
        </form>
    </div>
    </div>
  </div>
</div>