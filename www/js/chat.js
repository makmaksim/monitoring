var chat_mess_hover = false;
jQuery(document).ready(function($){
    var interval;
    var chat_users = function(){
        $.ajax({
          type: "POST",
          url: "/chat/get_chat_users",
          dataType: "json",
          data: {'opened' : $.cookie('chat_user_opened')},
          success: function(data){
            $('.chat_users').html(data.users);
            add_chat_mess(data.messages);  
            if(data.new_m){
                $('.chat_body').find('.new_m').show();
                var interval = setInterval(function(){
                    $('.chat_body').find('.new_m').animate({'opacity': 0.2}, 400).animate({'opacity': 1}, 400);
                }, 200);
                newConsMessages();
            }else{
                clearInterval(interval);
                $('.chat_body').find('.new_m').hide();
            }
          }
        });
    }
    chat_users();
    $('.chat_messages').hover(function(){
        chat_mess_hover = true;
    },function(){
        chat_mess_hover = false;
    });
    
    
    var update_chat = setInterval(function(){
        chat_users();
    }, 10000);
    
    $('.chat_head').click(function(){
        if($('.chat_body').hasClass('opened')){
            $('.chat_body').animate({'bottom' : '-375px'}, 200).removeClass('opened');
            $.cookie('chat_open',0, { path: '/'});
        }else{
            $('.chat_body').animate({'bottom' : '0px'}, 200).addClass('opened');
            $.cookie('chat_open',1, { path: '/'});
        }
    });
    
    $('.chat_body').resizable({
        stop: function( event, ui ) {
            $.cookie('chat_size', $('.chat_body').width(), { path: '/'});
        }
    });
    
    $('body').on('click', '.chat_user_open', function(){
        var user = $(this).data('user');
        $('body').find('.chat_user_open').removeClass('chat_user_opened');
        $(this).addClass('chat_user_opened');
        $(this).parent().removeAttr('style');
        $.cookie('chat_user_opened', user, { path: '/'});
        $.ajax({
          type: "POST",
          url: "/chat/read_mess",
          dataType: "json",
          data: {'user_id' : user},
          success: function(data){
            add_chat_mess(data.messages);
          }
        });
    });
    
    $('.chat_send').keyup(function(event){
        if(event.keyCode==13 && event.ctrlKey === true){
          var user_id = ($.cookie('chat_user_opened')) ? $.cookie('chat_user_opened') : 0;
          var mess = $(this).val();
          $(this).val('');
          $.ajax({
              type: "POST",
              url: "/chat/send_mess",
              dataType: "json",
              data: {'user_id' : user_id, 'message' : mess},
              success: function(data){
                add_chat_mess(data.messages);
              }
          });
          return false;
        }
    });
    
    $('.chat_send_btn').click(function(){
        var user_id = ($.cookie('chat_user_opened')) ? $.cookie('chat_user_opened') : 0;
          var mess = $('.chat_send').val();
          $('.chat_send').val('');
          $.ajax({
              type: "POST",
              url: "/chat/send_mess",
              dataType: "json",
              data: {'user_id' : user_id, 'message' : mess},
              success: function(data){
                  add_chat_mess(data.messages);
              }
          });
          return false;
    });
    
    $('body').on('click', '.chat_send_all', function(){
        $.ajax({
          type: "POST",
          url: "/chat/get_all_mess_form",
          dataType: "html",
          success: function(data){
            $('body').append(data);
            $('body').find('#all_mess').modal('show');
            $('body').on('hidden.bs.modal', '#all_mess', function(){
                $("#all_mess").remove();
            });
          }
        });
    });
});

function add_chat_mess(messages){
    if(!chat_mess_hover){
        jQuery('.chat_messages').html(messages);
            jQuery('.chat_messages').animate({
                    scrollTop: jQuery(".chat_messages_table").height()
                }, 0);
    }
}

function newConsMessages(){
                if (Notification.permission === "granted") {
                    var consNotification = new Notification('Новое сообщение в чате', {
                                tag : "cons_notice",
                                body : "У Вас новое сообщение в чате"
                            });
                } else {
                    Notification.requestPermission(function (permission) {
                        if(!('permission' in Notification)) {
                            Notification.permission = permission;
                        }
                        if (permission === "granted") {
                            var consNotification = new Notification('Новое сообщение в чате', {
                                tag : "cons_notice",
                                body : "У Вас новое сообщение в чате"
                            });
                        }
                    });
                }
            }