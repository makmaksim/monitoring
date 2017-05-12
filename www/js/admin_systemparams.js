jQuery(document).ready(function($){
    $('#add_user_mail_event_btn').click(function(){
        var html = $('#user_events_maket').html();
        $('.sotr_events_new_user').append(html);
    });
    $('#add_cell_mail_event_btn').click(function(){
        var html = $('#cell_events_maket').html();
        $('.sotr_events_new_cell').append(html);
    });
    $('body').on('click', '.remove_mail_event', function(){
        $(this).parent().parent().remove();
    });
});