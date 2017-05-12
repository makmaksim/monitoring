<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 $user_name = ''; 
 if(!empty($fields['not_cell'])){
    foreach($fields['not_cell']['fields_list'] as $field){
        if($field->type == 'text' && $field->params == 1)
            $user_name = ViewInput::get_field_data($fields['not_cell']['fields_data'], $field);
    }
 }
?>
<div class="page_title"><?=($user_name) ? $user_name : lang('user_title')?> <span class="glyphicon glyphicon-eye-open tooltop_title user_status_<?=(isset($user_status) && $user_status->status) ? $user_status->status : 0 ; ?>" title="<?=(isset($user_status) && $user_status->last_active) ? lang('last_active_text') . date(lang('datetime_format'), strtotime($user_status->last_active)): lang('last_active_text') . lang('last_active_text_undefined') ; ?>"></span></div>


    <div class="fields_cell_block">
        <ul id="myTab" class="nav nav-tabs">
            <li role="presentation">
                <a href="#usertab" class="cell_tab_link" data-toggle="tab" data-user="<?=$user_id?>">
                    <?=($user_name) ? $user_name : lang('user_title')?>
                </a>
            </li>
        <?php if(!empty($fields['in_cell']['fields_data']) && $count_field_cell) : ?>
            <?php $a=0; foreach($fields['in_cell']['fields_data'] as $key => $val) : $a++; ?>
                <li role="presentation">
                    <?php if($perms->control_cell) : ?>
                        <button type="button" class="remove_cell_btn close" data-id="<?=$key?>" data-user_id="<?=$user_id?>">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    <?php endif; ?>
                    <a href="#tab<?=$key?>" class="cell_tab_link" data-toggle="tab" data-user="<?=$user_id?>"><?=$val->name?></a>
                </li>
            <?php endforeach; ?>
        <?php endif ?>
        <?php if($perms->control_cell && $count_field_cell) : ?>
            <li role="presentation" style="text-align: center;">
                <a class="glyphicon glyphicon-plus new_cell_btn"  data-user_id="<?=$user_id?>" style="padding: 10px 15px; cursor: pointer;font-size: 12px;opacity: .4;color: #000;"></a>
            </li>
        <?php endif ?>
        </ul>
        
        <div class="tab-content">
            <?php if(!empty($fields['not_cell'])) : ?>
                <div class="fields_block tab-pane fade in" id="usertab">
                    <?php if(!empty($fields['not_cell']['fields_rec'])) : ?>
                        <span class="glyphicon glyphicon-pencil edit_fields_btn" data-user_id="<?=$user_id?>"></span>
                    <?php endif; ?>
                    <?php if($editop == $user_id || $perms->control_user) : ?>
                        <span class="glyphicon glyphicon-cog edit_params_user_btn" data-user_id="<?=$user_id?>"></span>
                    <?php endif; ?>
                    <?php if($perms->control_user && $editop != $user_id) : ?>
                        <label class="glyphicon glyphicon-trash remove_user_btn" data-user_id="<?=$user_id?>" title="<?=lang('remove_user')?>"></label>
                    <?php endif; ?>
                        <div class="row">
                            <div class="col-md-3"><label><?=lang('group_text')?></label></div>
                            <div class="col-md-9"><?=$group->name?></div>
                        </div>
                    <?php foreach($fields['not_cell']['fields_list'] as $field) : ?>
                        <div class="row" style="<?=$field->data?>">
                            <div class="col-md-3"><label><?=$field->name?></label></div>
                            <div class="col-md-9"><?=ViewInput::get_field_data($fields['not_cell']['fields_data'], $field)?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if(!empty($fields['in_cell']['fields_data']) && $count_field_cell) : ?>
            <?php $a=0; foreach($fields['in_cell']['fields_data'] as $key => $val) : $a++; ?>
                <div class="tab-pane fade in" id="tab<?=$key?>">
                <div class="row">
                    <div class="col-md-10">
                    <div class="fields_in_cell">
                        <span class="glyphicon glyphicon-pencil edit_fields_cell_btn" data-id="<?=$key?>" data-user_id="<?=$user_id?>"></span>
                        <?php foreach($val as $k_field => $v_field) : ?>
                            <?php if(isset($fields['in_cell']['fields_list'][$k_field])) : ?>
                                <div class="row" style="<?=$fields['in_cell']['fields_list'][$k_field]->data?>">
                                    <div class="col-md-3"><label><?=$fields['in_cell']['fields_list'][$k_field]->name?></label></div>
                                    <div class="col-md-9">
                                        <?=ViewInput::get_field_data($fields['in_cell']['fields_data'][$key], $fields['in_cell']['fields_list'][$k_field])?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <div class="comments_block">
                        <?php if(!empty($fields['in_cell']['comments'][$key])) : ?>
                            <h4><?=lang('comments_text')?></h4>
                            <div class="comments_list">
                                <?php foreach($fields['in_cell']['comments'][$key] as $val) : ?>
                                    <div class="comment">
                                        <div class="comment_title">
                                            <span><?=$val->autor_name?></span> 
                                            <span><?=date(lang('datetime_format'), strtotime($val->date))?></span>
                                            <?php if($val->autor == $editop) : ?>
                                                <span class="glyphicon glyphicon-pencil edit_comment"></span>
                                                <span class="glyphicon glyphicon-trash delete_comment" data-id="<?=$val->comment_id?>" data-user_id="<?=$user_id?>"></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="comment_text"><?=$val->comment?></div>
                                        <div class="edit_comment_form" style="display: none;">
                                            <form method="post" action="/home/edit_comment">
                                                <?=ViewInput::_textarea(array(
                                                    'only' => true,
                                                    'label' => lang('comment_text_label'),
                                                    'name' => 'comment_text',
                                                    'value' => $val->comment
                                                ))?>
                                                <?=ViewInput::_get_send_button(array(
                                                    'only' => true,
                                                    'label' => lang('send_text')
                                                ), ' onclick="if(!jQuery(this).parent().find(\'#comment_text\').val()) return false;" style="margin: 10px 0"')?>
                                                <?=ViewInput::_get_button(array(
                                                    'only' => true,
                                                    'label' => lang('cancel_text')
                                                ), ' onclick="jQuery(this).parent().parent().slideUp(300); return false;" style="margin: 10px 0"')?>
                                                <input type="hidden" name="user_id" value="<?=$user_id?>">
                                                <input type="hidden" name="comment_id" value="<?=$val->comment_id?>">
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <h4><?=lang('add_comment_label')?></h4>
                        <form method="post" action="/home/add_comment">
                            <?=ViewInput::_textarea(array(
                                'only' => true,
                                'label' => lang('comment_text_label'),
                                'name' => 'comment_text'
                            ))?>
                            <?=ViewInput::_get_send_button(array(
                                'only' => true,
                                'label' => lang('send_text')
                            ), ' onclick="if(!jQuery(this).parent().find(\'#comment_text\').val()) return false;" style="margin: 10px 0"')?>
                            <input type="hidden" name="user_id" value="<?=$user_id?>">
                            <input type="hidden" name="cell_id" value="<?=$key?>">
                        </form>
                    </div>
                </div>
                <div class="col-md-2">
                    <h4 style="text-align: center;"><?=lang('files_label')?></h4>
                    <?php if(isset($fields['in_cell']['files'][$key])) : ?>
                        
                        <div class="files_list">
                            <?php foreach($fields['in_cell']['files'][$key] as $val) : ?>
                                <div class="file_block">
                                    <?php if($val->autor == $editop) : ?>
                                        <span class="glyphicon glyphicon-trash delete_file" data-id="<?=$val->file_id?>" data-user_id="<?=$user_id?>"></span>
                                    <?php endif; ?>
                                    <?php if($val->is_image) : ?>
                                        <a href="<?=get_type_icon($val, $user_id)?>" target="_blank">
                                    <?php else : ?>
                                        <a href="/home/download_file?id=<?=$val->file_id?>&amp;user_id=<?=$user_id?>">
                                    <?php endif; ?>
                                            <img src="<?=get_type_icon($val, $user_id)?>" alt="">
                                            <span class="file_name"><?=$val->orig_name?></span>
                                        </a>
                                    
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <form method="post" action="/home/upload_file" enctype="multipart/form-data" class="upload_file_form">
                        <div class="file_input_block">+<input type="file" name="file"></div>
                        <input type="hidden" name="user_id" value="<?=$user_id?>">
                        <input type="hidden" name="cell_id" value="<?=$key?>">
                    </form>
                </div>
                </div>
                </div>
            <?php endforeach; ?>
            <?php endif ?>
        </div>
        
    </div>








<!--подтверждение удаления-->

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











