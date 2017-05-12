<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="page_title"><?=$menu_item->name?></div>

<div class="form filter_form navbar-collapse" style="margin-bottom: 15px;">
    <div class="form-inline navbar-nav">
        <form class="filter_form">
        <?php if($perms->control_user) : ?>
            <div class="form-group"><button type="button" class="btn btn-primary add_user_btn" data-group_id="<?=$menu_item->group_id?>"><?=lang('add_user')?></button></div>
        <?php endif; ?>
            <label class="form-group">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
            <label class="form-group"><?=lang('filter_label')?></label>
            <div class="form-group">
                <?=ViewInput::_list(array(
                    'only' => true,
                    'name' => 'filter_field',
                    'label' => lang('field_text'),
                    'list' => $fields,
                    'key' => 'field_id',
                    'val' => 'name',
                    'value' => $this->input->get('filter_field')
                ))?>
            </div>
            <div class="form-group filter_val">
                <?=$filter_val?>
            </div>
            <?=ViewInput::_get_send_button(array(
                'only' => true,
                'label' => lang('filter_button')
            ))?>
            <?=ViewInput::_get_button(array(
                'only' => true,
                'label' => lang('filter_button_clean')
            ), 'onclick="location.href=\'' . $group_href . '\'"')?>
            <?php if($perms->control_export) : ?>
                <a  href="<?=$export_link?>" target="_blank" class="btn btn-success"><?=lang('export_text')?></a>
            <?php endif; ?>
        </form>
    </div>
    <?=$count_in_page?>
</div>
<div class="users_list">
    <table class="table table-hover table-bordered table-responsive table-striped table-condensed" id="userlist_table">
        <thead>
            <tr>
                    <th></th>
                    <th></th>
                <?php foreach($fields as $field) : ?>
                        <th>
                            <span class="clean_sort glyphicon <?php if($field->order == 2){
                                echo 'glyphicon glyphicon-sort-by-attributes-alt';
                            }elseif($field->order == 1){
                                echo 'glyphicon glyphicon-sort-by-attributes';
                            } ?>" title="<?=lang('clean_sort_text')?>"></span>&nbsp;
                            <span class="sort_btn" data-order="<?=$field->order?>" data-field="<?=$field->unique?>"><?=$field->name?></span></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $user) : ?>
                <tr class="user_tr_<?=$user->user_id?>">
                    <td>
                        <div class="dropdown">
                            <span class="glyphicon glyphicon-menu-hamburger dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"></span>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                <li>&nbsp;
                                    <span class="glyphicon glyphicon-pencil"></span>
                                    <span class="btn edit_fields_btn" data-user_id="<?=$user->user_id?>"><?=lang('edit_user_text')?></span>
                                </li>
                                <?php if($editop == $user->user_id || $perms->control_user) : ?>
                                    <li>&nbsp;
                                        <span class="glyphicon glyphicon-cog"></span>
                                        <span class="btn edit_params_user_btn" data-user_id="<?=$user->user_id?>"><?=lang('params_user_text')?></span>
                                    </li>
                                <?php endif; ?>
                                <?php if($perms->control_user) : ?>
                                    <li>&nbsp;
                                        <span class="glyphicon glyphicon-trash"></span>
                                        <span class="btn remove_user_btn" data-user_id="<?=$user->user_id?>"><?=lang('remove_user_text')?></span>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </td>
                    <td>
                        <a href="/home/<?=$user->user_id?>" class="glyphicon glyphicon-eye-open tooltop_title user_status_<?=($user->status) ? $user->status : 0 ; ?>" target="_blank" title="<?=($user->last_active) ? lang('last_active_text') . date(lang('datetime_format'), strtotime($user->last_active)): lang('last_active_text') . lang('last_active_text_undefined') ; ?>"></a>
                    </td>
                    <?php foreach($fields as $field) : ?>
                            <td style="<?=$field->data?>" 
                                <?php if($perms->{$field->unique . '_rec'}) : ?> 
                                    class="dynamic_field_edit" data-field="<?=$field->unique?>" data-user="<?=$user->user_id?>" data-cell="<?=$user->cell_id?>"
                                <?php endif; ?>        
                            >
                                <?php if(($field->in_cell && $user->count_cell) || !$field->in_cell) : ?>
                                    <?php if($field->params == 1) : ?>
                                        <a href="/home/<?=$user->user_id?>"><?=ViewInput::get_field_data($user, $field)?></a>
                                    <?php else : ?>
                                        <?=ViewInput::get_field_data($user, $field)?>   
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?=$pagination?>
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