<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?php if(!empty($users)) : ?>
    <ul>
        <li style="padding: 5px 0 0 0;"><span class="chat_user_open <?=$opened_all?>" data-user="">Общий чат</span></li>
        <li style="padding: 0 0 5px 0;cursor: pointer;"><span class="chat_send_all" data-user="">Отправить всем</span></li>
        <?php foreach($users as $group) : ?>
            <li><b><?=$group['group_name']?></b>
                <?php if(!empty($group['list'])) : ?>
                    <ul>
                        <?php foreach($group['list'] as $user) : ?>
                            <?php if($autor != $user->user_id) : ?>
                            <li <?=($user->not_read > 0 && !$user->opened) ? 'style="color: red;"' : ''?>>
                                <span class="chat_status
                                <?php if($user->status == 0) echo 'chat_offline';
                                      elseif($user->status == 1) echo 'chat_onoff';
                                      elseif($user->status == 2) echo 'chat_online';
                                ?>
                                
                                "></span>
                                <span class="chat_user_open <?=$user->opened?>" data-user="<?=$user->user_id?>"><?=$user->username?></span>
                            </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
