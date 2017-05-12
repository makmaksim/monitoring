<?php defined('BASEPATH') OR exit('No direct script access allowed');?>


<div class="cons_block">
    <ul id="myTab" class="nav nav-tabs">
        <?php foreach($cons_list as $key => $cons) : ?>
            <li role="presentation" class="<?=($key == 0) ? 'active' : ''?>">
                <button type="button" class="remove_cons_btn close" data-id="<?=$cons->cons_id?>">
                    <span aria-hidden="true">&times;</span>
                </button>
                <a href="#cons<?=$cons->cons_id?>" class="cell_tab_link" data-toggle="tab"><?=$cons->site_adress?></a>
            </li>
        <?php endforeach; ?>
        <li role="presentation" style="text-align: center;">
            <a class="" style="padding: 10px 15px; cursor: pointer;font-size: 12px;opacity: .4;color: #000;">
                <span class="glyphicon glyphicon-plus " data-toggle="modal" data-target="#new_cons_form"></span>
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <?php foreach($cons_list as $key => $cons) : ?>
            <div class="tab-pane fade in  <?=($key == 0) ? 'active' : ''?>" id="cons<?=$cons->cons_id?>" style="padding: 10px 20px;">
                <form method="post" action="/admin/consultant/edit_cons" id="cons_form_<?=$cons->cons_id?>">
                    <div class="form-group row">
                        <label class="col-sm-3"><?=lang('cons_id_label')?></label>
                        <div class="col-sm-9"><?=$cons->cons_id?></div>
                    </div>
                    <?=ViewInput::_text(array(
                        'label' => lang('site_adress'),
                        'name' => 'site_adress',
                        'required' => 1,
                        'value' => $cons->site_adress
                    ))?>
                    <div class="form-group row">
                        <label class="col-sm-3" title="<?=lang('api_key_title_text')?>"><?=lang('api_key_label')?><span class="red">*</span></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control api_key" placeholder="<?=lang('api_key_text')?>" name="api_key" value="<?=$cons->api_key?>" data-required="1">
                                <span class="input-group-addon btn api_key_generate" name="api_key"><?=lang('api_key_generate_text')?></span>
                            </div>
                        </div>
                    </div>
                    <div class="cons_operators_list">
                        <?php foreach($cons->users_list as $user) : ?>
                                <div class="form-group row cons_operator">
                                    <label class="col-sm-3"><?=lang('user_label')?><span class="red">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control operator_auto" placeholder="<?=lang('user_label')?>" data-required="1" value="<?=$user['name']?>">
                                        <input type="hidden" class="user_id_hidden" name="user_id[]"  data-required="1" value="<?=$user['user_id']?>">
                                    </div>
                                    <div class="col-md-1"><span class="btn glyphicon glyphicon-remove remove_operator"></span></div>
                                </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3"></label>
                        <div class="col-sm-9">
                            <?=ViewInput::_get_send_button(array('label' => lang('send_text'), 'only' => TRUE))?>

                            <input type="button" value="<?=lang('add_cons')?>" class="btn btn-primary add_operator" data-form="#cons_form_<?=$cons->cons_id?>">
                        </div>
                    </div>
                    <input type="hidden" name="cons_id" value="<?=$cons->cons_id?>">
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="modal fade" id="new_cons_form" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-body">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?=lang('new_cons_form_title')?></h4>
        </div>
        <form method="post" action="/admin/consultant/add_cons" id="new_cons_form" onsubmit="return send(this);return false;">
            <div class="errors"></div>
            <?=ViewInput::_text(array(
                'label' => lang('site_adress'),
                'name' => 'site_adress',
                'required' => 1
            ))?>
            <div class="cons_operators_list">
                <div class="form-group row cons_operator">
                    <label class="col-sm-3"><?=lang('user_label')?><span class="red">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control operator_auto" placeholder="<?=lang('user_label')?>" data-required="1">
                        <input type="hidden" class="user_id_hidden" name="user_id[]"  data-required="1">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3"></label>
                <div class="col-sm-9">
                    <?=ViewInput::_get_send_button(array('label' => lang('send_text'), 'only' => TRUE))?>
                    <input type="button" value="<?=lang('add_cons')?>" class="btn btn-primary add_operator" data-form="#new_cons_form">
                </div>
            </div>
        </form>
        <script type="text/javascript">
            jQuery(document).ready(function(){
                $('.add_operator').click(function(){
                    var form = $(this).data('form');
                    var operatorInput = $('<div class="form-group row cons_operator" />').html($('.cons_operator_input').html());
                    $(form).find('.cons_operators_list').append(operatorInput);
                    operatorInput.find('.operator_auto').autocomplete({
                        source: function(request, response){
                            $.ajax({
                                type: "POST",
                                url: "/home/get_user_autocomplete",
                                dataType: "json",
                                data:{
                                    limit: 10, 
                                    text: request.term 
                                },
                                success: function(data){
                                    response($.map(data, function(item){
                                        return {
                                            label: item.name,                                    
                                            value: item.name,                                    
                                            user_id: item.user_id                                    
                                        }
                                    }));
                                }
                            });
                        },
                        select: function(event, ui){
                            $(this).parent().find('.user_id_hidden').val(ui.item.user_id);
                        },
                        minLength: 2 
                    });
                });
                
                $('body').on('click', '.remove_operator', function(){
                    $(this).parent().parent().remove();
                });
                
                $('body').find(".operator_auto").each(function(){
                    $(this).autocomplete({
                        source: function(request, response){
                            $.ajax({
                                type: "POST",
                                url: "/home/get_user_autocomplete",
                                dataType: "json",
                                data:{
                                    limit: 10, 
                                    text: request.term 
                                },
                                success: function(data){
                                    response($.map(data, function(item){
                                        return {
                                            label: item.name,                                    
                                            value: item.name,                                    
                                            user_id: item.user_id                                    
                                        }
                                    }));
                                }
                            });
                        },
                        select: function(event, ui){
                            $(this).parent().find('.user_id_hidden').val(ui.item.user_id);
                        },
                        minLength: 2 
                    });
                });
                
            });
        </script>
      </div>
    </div>
  </div>
</div>

<div class="cons_operator_input" style="display: none;">
        <label class="col-sm-3"><?=lang('user_label')?><span class="red">*</span></label>
        <div class="col-sm-8">
            <input type="text" class="form-control operator_auto" placeholder="<?=lang('user_label')?>" data-required="1">
            <input type="hidden" name="user_id[]" class="user_id_hidden"  data-required="1">
        </div>
        <div class="col-md-1"><span class="btn glyphicon glyphicon-remove remove_operator"></span></div>
</div>


<div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <p><?=lang('delete_text')?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default no_btn" data-dismiss="modal"><?=lang('no')?></button>
        <button type="button" class="btn btn-primary yes_btn" data-dismiss="modal"><?=lang('yes')?></button>
      </div>
    </div>
  </div>
</div>