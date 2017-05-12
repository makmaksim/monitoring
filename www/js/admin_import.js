jQuery(document).ready(function($){
        $('#add_field').click(function(){
            $('.fields_list_import').append($('#field_html_hidden').html());
        });
        $('body').on('click', '.remove_field', function(){
            $(this).parent().parent().remove();
        });
    });