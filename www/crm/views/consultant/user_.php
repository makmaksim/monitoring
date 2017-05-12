<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
$url_history = explode('|', $user->url_history);
$url_history = array_unique($url_history);
$url_history =  array_filter($url_history);

$datetime1 = new DateTime($user->last_time);
$datetime2 = new DateTime(date('Y-m-d H:i:s'));
$interval = $datetime1->diff($datetime2);

?>
<div role="tabpanel" class="tab-pane active" id="<?=$user->cons_user_id?>">
    <div class="cons_user_inner">
        <div class="cons_user_info">
            <div> <?=lang('cons_active')?>: <?=date(lang('time_format'), strtotime($user->first_time))?> - <?=date(lang('time_format'), strtotime($user->last_time))?>; <?=lang('cons_user_status')?>: <?=(($interval->i * 60) > $this->config->item('cons_online_time')) ? '<span style="color: green">online</span>' : '<span style="color: red">offline</span>'?>
            </div>
            <div class="row">
                <div class="col-md-3"><?=lang('cons_opened_pages')?></div>
                <div class="col-md-9"><?=implode(' -> ', $url_history)?></div>
            </div>
            <div class="row">
                <div class="col-md-3"><?=lang('cons_user_geo')?></div>
                <div class="col-md-9"><?=$user->cons_user_geo?></div>
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
    </div>
    <div class="cons_send_mess_block">
        <form method="post" action="">
            <textarea rows="2" id="umc_mess_input" class="form-control" placeholder="<?=lang('send_mess_placeholder')?>"></textarea>
        </form>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function(){
            
            var h0 = $('body').height();
            var h1 = $('#<?=$user->cons_user_id?>').find('.cons_user_info').height();
            var h2 = $('#<?=$user->cons_user_id?>').find('.cons_send_mess_block').height();
            var my_h = h0 - h1 - h2 - 134 - h2;
            $('#<?=$user->cons_user_id?>').find('.cons_user_mess_block').height(my_h);
            $('#<?=$user->cons_user_id?>').find('.cons_user_mess_block').animate({ scrollTop: my_h}, "slow");

            $("#umc_mess_input").keypress(function(e){
                
                if(e.which == 13) {
                    var $this = $(this);
                    var message = $this.val();
                    if(message){
                        
                        $.ajax({
                            type: "POST",
                            url: "/consultant/send_message",
                            data: {'message' : message, 'user' : '<?=$user->cons_user_id?>'},
                            dataType: "html",
                            success: function(html){
                                $('.cons_user_inner').html(html);
                            }
                        });
                    }
                    return false;
                }
                
            });
        });
    </script>
</div>