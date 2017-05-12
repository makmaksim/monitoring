<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
$url_history = explode('|', $user->url_history);
$url_history = array_unique($url_history);
$url_history =  array_filter($url_history);

$datetime1 = new DateTime($user->last_time);
$datetime2 = new DateTime(date('Y-m-d H:i:s'));
$interval = $datetime1->diff($datetime2);
?>

        <div class="cons_user_info">
            <div>
                <?=lang('site_adress')?>: <?=$user->site_adress?>;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <?=lang('cons_active')?>: <?=date(lang('time_format'), strtotime($user->first_time))?> - <?=date(lang('time_format'), strtotime($user->last_time))?>;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                
                <?=lang('cons_user_status')?>: <?=(($interval->i * 60) < $this->config->item('cons_online_time')) ? '<span style="color: green">online</span>' : '<span style="color: red">offline</span>'?>;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <?=lang('cons_module_status')?>: <?=($user->umc_cons_open) ? '<span style="color: green">' . lang('cons_module_open') . '</span>' : '<span style="color: red">' . lang('cons_module_closed') . '</span>'?>;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <?=lang('cons_user_geo')?>: <?=$user->cons_user_geo?>;
            </div>
            <div class="row">
                <div class="col-md-2"><?=lang('cons_opened_pages')?></div>
                <div class="col-md-10"><?=implode(' -> ', $url_history)?></div>
            </div>
        </div>
        <div class="cons_user_mess_block">
            <?php if(count($user->messages)) : ?>
                <ol>
                    <?php foreach($user->messages as $mess) : ?>
                        <li>
                            <div class="mess_head">
                                <?php if(!$mess->from_to) : ?>
                                    <?=($user->cons_user_name) ? $user->cons_user_name : lang('user_text')?>
                                <?php else : ?>
                                    <?=lang('you_text')?>
                                <?php endif; ?>
                                <?=date(lang('datetime_format'), strtotime($mess->datetime))?>
                            </div>
                            <div class="mess_body cons_mess_<?=$mess->from_to?>">
                                <?=$mess->message?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ol>
            <?php endif; ?>
        </div>
        <?php if($user->new_messages->count) : ?>
            <script type="text/javascript">
                jQuery(function($){
                    $('.<?=$user->cons_user_id?>').css('color', 'red');
                });
            </script>
        <?php endif; ?>