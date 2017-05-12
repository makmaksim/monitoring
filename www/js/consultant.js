
jQuery(document).ready(function($){
    $(".cb_cell a:last").tab('show');
//    прокрутка сообщений к последнему 
    var scrollMessages = function(id){
        var mh = $('#' + id).find('.cons_user_mess_block').height();
        var h0 = $('body').height();
        var h1 = $('#' + id).find('.cons_user_info').height();
        var h2 = $('#' + id).find('.cons_send_mess_block').height();
        var my_h = h0 - h1 - h2 - 134 - h2;
        $('#' + id).find('.cons_user_mess_block').height(my_h);
        $('#' + id).find('.cons_user_mess_block').animate({ scrollTop: mh}, 0);
    }
    
//    обновление списка сообщений пользователя
    var updateMessagesList = function(id){
        var interval = setInterval(function(){
            $.ajax({
                type: "POST",
                url: "/consultant/get_user_inner",
                dataType: "html",
                data: {'user' : id},
                success: function(html){
                    $('#'+id).find('.cons_user_inner').html(html);
                    scrollMessages(id);
                }
            });
        }, $CONSUPDATETIME);
        return interval;
    }
//    загрузка списка пользователей 
    $.ajax({
        type: "GET",
        url: "/consultant/get_cons_users",
        dataType: "html",
        success: function(html){
            $('.cons_menu_inner').removeAttr('style');
            $('.cons_menu_inner').html(html);
        }
    });
    
//    обновление списка пользователей
    var get_cons_top = function(){
        var interval = setInterval(function(){
            $.ajax({
                          type: "GET",
                          url: "/consultant/get_cons_users",
                          dataType: "html",
                          success: function(html){
                              $('.cons_menu_inner').removeAttr('style');
                              $('.cons_menu_inner').html(html);
                          }
                        });
        }, $CONSUPDATETIME);
        return interval;
    }
    var interval = get_cons_top();
    
//    запуск скриптов пользователей при загрузке страницы
    $('.nav-tabs li').each(function(){
        var id = $(this).find('a').attr('aria-controls');
        eval("$('#"+ id +"').find('#umc_mess_input').keypress(function(e){if(e.which == 13) {var $this = $(this);var message = $this.val();sendMessage($this, '"+ id +"', message);return false;}});");
        scrollMessages(id);
        updateMessagesList(id);
    });
    
//    при наведении на список пользователей перезагрузка отменяется
    $('.cons_menu').hover(function(){
        clearInterval(interval);
    },function(){
        interval = get_cons_top();
    });
    
    $('body').on('click', '.users_list_open', function(){
        $(this).parent().parent().find('.cons_ul').slideUp(200);
        $(this).parent().find('.cons_ul').slideDown(200);
        return false;
    });
    
//    при открытии пользователя или клике по табу, все сообщения помечаем как прочитанные
    $('body').on('click', '.nav_tab_link, .cons_open_user', function(){
        var id = $(this).data('user');
        var $this = $(this);
        $.ajax({
            type: "POST",
            url: "/consultant/set_read_status_messages",
            dataType: "html",
            data: {'user' : id},
            success: function(html){
                $this.removeAttr('style');
            }
        });
    });
//    добавляем таб пользователя
    $('body').on('click', '.cons_open_user', function(){
        var id = $(this).data('user');
        var name = $(this).data('name');
        if($('#'+id).length > 0) return false;
        $.ajax({
            type: "POST",
            url: "/consultant/get_user_inner",
            dataType: "html",
            data: {'user' : id},
            success: function(html){
                $('<li role="presentation"><a href="#'+id+'" aria-controls="'+id+'" role="tab" data-toggle="tab">'+name+'<button type="button" class="close close_cons_user"><span aria-hidden="true">×</span></button></a></li>').appendTo('.nav-tabs');
                $('<div role="tabpanel" class="tab-pane active" id="'+id+'"><div class="cons_user_inner"></div><div class="cons_send_mess_block"><form method="post" action=""><textarea rows="2" id="umc_mess_input" class="form-control"></textarea></form></div></div>').appendTo('.tab-content');
                $('#'+id).find('.cons_user_inner').html(html);
                $(".cb_cell a:last").tab('show');
                eval("$('#"+ id +"').find('#umc_mess_input').keypress(function(e){if(e.which == 13) {var $this = $(this);var message = $this.val();sendMessage($this, '"+ id +"', message);return false;}});");
                scrollMessages(id);
                updateMessagesList(id);
            }
        });
        return false;
    });
    
//    отправка сообщения
    var sendMessage = function($this, id, message){
                                if(message){
                                    $.ajax({
                                        type: "POST",
                                        url: "/consultant/send_message",
                                        data: {'message' : message, 'user' : id},
                                        dataType: "html",
                                        success: function(html){
                                            $('#' + id).find('.cons_user_inner').html(html);
                                            $this.val('');
                                            scrollMessages(id);
                                        }
                                    });
                                }
                                return false;
    }        
    
//    закрываем пользователя (удаляем таб)
    $('body').on('click', '.close_cons_user', function(){
        var id = $(this).parent('a').attr('href');
        $(this).parent().parent().remove();
        $(id).remove();
        $.ajax({
            type: "POST",
            url: "/consultant/user_close",
            dataType: "html",
            data: {'id' : id}
        });
        $(".cb_cell a:last").tab('show');
    });
    
//    переименовываем пользователя
    $('body').on('click', '.rename_user_send', function(){
        var id = $(this).data('user');
        var action = $('body').find('#rename_user_form_'+id).attr('action');
        var newName = $('body').find('#rename_user_form_'+id).find('#rename_user_input_'+id).val();
        $.ajax({
            type: "POST",
            url: action,
            dataType: "json",
            data: {'user' : id, 'new_name' : newName},
            success: function(data){
                if(!data.error){
                    $('body').find('#rename_user_form_'+id).find('#rename_user_input_'+id).val('');
                    $('body').find('.name_'+id).html(newName);
                }
            }
        });
    });
    
    $('body').on('click', '.cons_user_edit .before', function(){
        var block = $(this).parent();
        if(block.hasClass('open')){
            block.animate({'right' : -400}, 300).removeClass('open');
        }else{
            block.animate({'right' : 0}, 300).addClass('open');
        }
    });
});