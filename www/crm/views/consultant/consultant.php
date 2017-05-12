<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="consultant_block">
    <div class="cb_row">
        <div class="cb_cell cb_cell_menu">
            <div class="cons_menu">
                <h4 style="text-align: center;"><?=lang('users_list_online')?></h4>
                <div class="cons_menu_inner"></div>
            </div>
        </div>
        <div class="cb_cell">
            <ul class="nav nav-tabs" role="tablist">
                <?php if(!empty($users_opened)) : ?>
                    <?php foreach($users_opened as $user) : ?>
                        <li role="presentation">
                            <a href="#<?=$user->cons_user_id?>" class="<?=$user->cons_user_id?> nav_tab_link" data-user="<?=$user->cons_user_id?>" aria-controls="<?=$user->cons_user_id?>" role="tab" data-toggle="tab" <?=($user->new_messages->count) ? 'style="color:red"' : ''?>>
                                <span class="name_<?=$user->cons_user_id?>"><?=($user->cons_user_name) ? $user->cons_user_name : lang('user_text')?></span>
                                <button type="button" class="close close_cons_user">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            <div class="tab-content">
                <?php if(!empty($users_opened)) : ?>
                    <?php foreach($users_opened as $user) : ?>
                        <div role="tabpanel" class="tab-pane active" id="<?=$user->cons_user_id?>">
                                    <div class="cons_user_inner">
                                        <?php require(dirname(__FILE__) . '/user_inner.php') ?>
                                    </div>
                                    <div class="cons_send_mess_block">
                                        <form method="post" action="">
                                            <textarea rows="2" id="umc_mess_input" class="form-control" placeholder="<?=lang('send_mess_placeholder')?>"></textarea>
                                        </form>
                                    </div>
                                <div class="cons_user_edit">
                                    <div class="before"><<</div>
                                    <form action="/consultant/rename_user" class="form-inline" id="rename_user_form_<?=$user->cons_user_id?>">
                                        <label for="rename_user_input_<?=$user->cons_user_id?>"><?=lang('new_name_label')?></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="rename_user_input_<?=$user->cons_user_id?>" placeholder="<?=lang('new_name_placeholder')?>">
                                            <div class="input-group-addon rename_user_send" style="cursor: pointer;" data-user="<?=$user->cons_user_id?>"><?=lang('send_text')?></div>
                                        </div>
                                    </form>
                                </div>
                        </div>
                        
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>