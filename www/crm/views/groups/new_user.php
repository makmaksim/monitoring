<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="modal fade" id="new_user_form" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-body">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?=lang('add_user')?></h4>
        </div>
        <form method="post" action="/groups/add_user" onsubmit="return send_new_user(this);return false;">
            <div class="errors"></div>
            <div class="userdata">
                <?=ViewInput::_text(array(
                    'label' => lang('login'),
                    'name' => 'login'
                ))?>
                <?=ViewInput::_password(array(
                    'label' => lang('password'),
                    'name' => 'password'
                ))?>
                <?php foreach($fields as $field) : ?>
                    <?php if($perms->{$field->unique . '_rec'} && !$field->in_cell) : ?>
                        <?=ViewInput::get_input_for_user($field, new stdClass())?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php if($perms->control_cell && $count_field_cell) : ?>
                <div class="panel panel-default usercelldata" >
                    <div class="panel-heading">
                      <button type="button" class="btn btn-default btn-xs spoiler insert_cell_btn" data-toggle="collapse"><?=lang('create_cell')?></button>
                      <input type="hidden" id="insert_cell_hidden" name="insert_cell_hidden" value="0">
                    </div>
                    <div class="panel-collapse collapse out">
                      <div class="panel-body">
                        <?=ViewInput::_text(array(
                            'label' => lang('cell_name'),
                            'name' => 'cell_name',
                            'value' => $group->cell_name,
                            'required' => 1
                        ))?>
                        <?php foreach($fields as $field) : ?>
                            <?php if($perms->{$field->unique . '_rec'} && $field->in_cell) : ?>
                                <?=ViewInput::get_input_for_user($field, new stdClass())?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  </div>
            <?php endif; ?>
            <input type="hidden" name="group_id" value="<?=$group->group_id?>">
            <?=ViewInput::_get_send_button(array('label' => lang('send_text')))?>
        </form>
        <?=get_scripts()?>
        <script type="text/javascript">
            jQuery(document).ready(function($){
                $('.insert_cell_btn').click(function(){
                    
                    if($('#insert_cell_hidden').val() == 1){
                        $('#insert_cell_hidden').val(0);
                    }else{
                        $('#insert_cell_hidden').val(1);
                    }
                });
            });
            
            function send_new_user(form){
                <?php if(!empty(ViewInput::$scripts['ckeditor'])) : ?>
                for ( instance in CKEDITOR.instances ) {
                    CKEDITOR.instances[instance].updateElement();
                }
                <?php endif;?>
                setTimeout(function(){
                var errors = new Array();
                var url = jQuery(form).attr('action')
                jQuery(form).find('.userdata').find('.form-control').each(function(){
                    if(jQuery(this).data('required') && !jQuery(this).val()){
                        jQuery(this).addClass('not_valid');
                        errors.push('error')
                    }else{
                        jQuery(this).removeClass('not_valid');
                    }
                });
                
                if($('#insert_cell_hidden').val() == 1){
                    jQuery(form).find('.usercelldata').find('.form-control').each(function(){
                        if(jQuery(this).data('required') && !jQuery(this).val()){
                            jQuery(this).addClass('not_valid');
                            errors.push('error')
                        }else{
                            jQuery(this).removeClass('not_valid');
                        }
                    });
                }
                
                if(jQuery.inArray('error', errors) == -1){
                    
                    jQuery.ajax({
                      type: "POST",
                      url: url,
                      dataType: "json",
                      data: jQuery(form).serialize(),
                      success: function(data){
                          if(data.error){
                              alert(data.mess)
                              jQuery(form).find('.errors').html(data.mess);
                          }else{
                              location.reload();
                          }
                      },
                      error: function(){
                          alert('error');
                      }
                    });
                }
                }, 15);
                return false;
            }
            
            $('#login').keyup(function(){
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
        
        </script>
      </div>
    </div>
  </div>
</div>
