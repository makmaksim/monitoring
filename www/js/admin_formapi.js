    jQuery(document).ready(function($){
        
        var delete_elem = false;
        var delete_cancel = false;
        
        $('.add_field').click(function(){
            $(this).parent().find('.fields_list_formapi').append($(this).parent().find('#field_html_hidden').html());
        });
        $('body').on('click', '.remove_field', function(){
            $(this).parent().parent().remove();
        });
        
        $('#new_formapi_btn').click(function(){
            $.ajax({
              type: "POST",
              url: "/admin/formapi/get_new_formapi_form",
              dataType: "html",
              success: function(data){
                $('body').append(data);
                $('body').find('#new_formapi_form').modal('show');
                $('body').on('hidden.bs.modal', '#new_formapi_form', function () {
                    $("#new_formapi_form").remove();
                });
              }
            });
        });
        
        $('.remove_formapi').click(function(){
            $('#delete_modal').modal('show');
            var id = $(this).data('form');
            var selector = $('.panel_form_' + id).find('.panel-heading');
            selector.removeAttr('style');
            selector.css({  
                            'background' : '#ff0000',
                            'color' : '#fff'
                      });
            delete_elem = function(){                      
                $.ajax({
                  type: "POST",
                  url: "/admin/formapi/remove_form",
                  dataType: "html",
                  data: {'id' : id},
                  success: function(data){
                      selector.fadeOut(800, function(){
                        selector.parent().remove();
                      });
                  },
                  error: function(){
                      alert('error');
                  }
                });
            }

            delete_cancel = function () {
                selector.removeAttr('style');
            }
        });
        
        $('select[name=group_id]').change(function(){
            var id = $(this).val();
            $.ajax({
              type: "POST",
              url: "/admin/formapi/get_fields",
              dataType: "html",
              data: {'id' : id},
              success: function(data){
                  $('#field_html_hidden').html(data);
                  $('.fields_list_formapi').html(data);
              }
            });
        });
        
        $('.api_key_generate').click(function(){
            var $this = $(this);
            $.ajax({
              type: "POST",
              url: "/admin/formapi/get_api_key",
              dataType: "html",
              success: function(data){
                  $this.parent().parent().find('.api_key').val(data);
              }
            });
        });
            $('#delete_modal').find('.yes_btn').click(function(){
                delete_elem();
                delete_elem = false;
                delete_cancel = false;
            }); 
            $('body').on('hidden.bs.modal', '#delete_modal', function () {
                delete_cancel();
                delete_elem = false;
                delete_cancel = false;
            });
    });