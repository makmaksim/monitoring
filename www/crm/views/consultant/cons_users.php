<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="cons_users_list">
<?php if(count($users_list_all)) : ?>
    <div class="cons_users_all">
        <?php foreach($users_list_all as $cons) : ?>
        <a href="#" class="users_list_open"><?=$cons->site_adress?> (<?=count($cons->users)?>)</a>
            <ol class="cons_ul" style="display: none;">
                <?php foreach($cons->users as $val) : ?>
                    <li><a href="#"><?=($val->cons_user_name) ? $val->cons_user_name : lang('user_text')?> - <?=$val->cons_user_geo?></a>
                    <?=($val->umc_cons_open) ? '<span style="color: #009A00;" class="glyphicon glyphicon-eye-open"></span>' : ''?></li>
                <?php endforeach; ?>
            </ol>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
    <div class="cons_users">
        <a href="#" class="users_list_open"><?=lang('users_list_text')?> (<?=count($users_list)?>)</a>
        <?php if(count($users_list)) : ?>
            <ol class="cons_ul">
                <?php foreach($users_list as $val) : ?>
                    <li><a class="cons_open_user" href="#" data-user="<?=$val->cons_user_id?>" data-name="<?=($val->cons_user_name) ? $val->cons_user_name : lang('user_text')?>" <?=($val->count_new_messages) ? 'style="color: red"':''?>><?=($val->cons_user_name) ? $val->cons_user_name : lang('user_text')?> - <?=$val->cons_user_geo?></a>
                    <?=($val->umc_cons_open) ? '<span style="color: #009A00" class="glyphicon glyphicon-eye-open"></span>' : ''?></li>
                <?php endforeach; ?>
            </ol>
        <?php endif; ?>
    </div>
</div>