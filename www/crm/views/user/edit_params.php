<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal fade" id="params_user" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-body">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?=lang('params_user')?></h4>
        </div>
        <form method="post" action="/home/edit_params_user">
            <?php if($perms->control_user) : ?>
                <?=ViewInput::_list(array(
                    'label' => lang('group_text'),
                    'name' => 'group_id',
                    'list' =>$groups,
                    'key' => 'group_id',
                    'val' => 'name',
                    'value' => $group->group_id
                ))?>
                <?=ViewInput::_text(array(
                    'label' => lang('username_text'),
                    'name' => 'new_login',
                    'value' => $user->username
                ), '', lang('username_title'))?>
                <?=ViewInput::_password(array(
                    'label' => lang('password_text'),
                    'name' => 'new_pass'
                ))?>
                <?=ViewInput::_text(array(
                    'label' => lang('vk_id_text'),
                    'name' => 'vk_id',
                    'value' => $user->vk_id
                ), '', lang('vk_id_title'))?>
            <?php elseif($editop == $user_id) : ?>
                <?=ViewInput::_text(array(
                    'label' => lang('username_text'),
                    'name' => 'new_login',
                    'value' => $user->username
                ))?>
                <?=ViewInput::_password(array(
                    'label' => lang('password_text'),
                    'name' => 'new_pass'
                ))?>
                <?=ViewInput::_text(array(
                    'label' => lang('vk_id_text'),
                    'name' => 'vk_id',
                    'value' => $user->vk_id
                ), '', lang('vk_id_title'))?>
            <?php endif; ?>
            <input type="hidden" name="user_id" id="user_id_hidden" value="<?=$user_id?>">
            <input type="hidden" name="href" id="href" value="">
            <?=ViewInput::_get_send_button(array('label' => lang('send_text')))?>
        </form>
        <?=get_scripts()?>
        <script type="text/javascript">
            jQuery(document).ready(function($){
                $('#href').val(location.href);
                
                $('#new_login').keyup(function(){
                    $this = $(this);
                    var username = $this.val();
                    var user_id = $('#user_id_hidden').val();
                    if(/^[a-zA-z]{1}[a-zA-Z1-9]{3,20}$/.test(username)){
                        $.ajax({
                          type: "POST",
                          url: "/home/isset_username",
                          dataType: "json",
                          data: {'username' : username, 'user_id' : user_id},
                          beforeSend: function(){
                              $this.css('background', 'url(/css/images/loading.gif) no-repeat center right 10px');
                          },
                          success: function(data){
                            $this.removeAttr('style');
                            if(data.issetUsername === true){
                                $this.addClass('not_valid');
                            }else{
                                $this.removeClass('not_valid');
                            }
                          }
                        });
                    }else{
                        $this.addClass('not_valid');
                    }
                });
            });
        </script>
      </div>
    </div>
  </div>
</div>