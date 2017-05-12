jQuery(document).ready(function($){
    $('select[name="params[type]"]').change(function(){
        var key = $(this).data('key');
        if($(this).val() != 0){
            $('.chart_list_fields' + key).slideDown(400);
        }else{
            $('.chart_list_fields' + key).slideUp(400);
        }
    });
    
    $('.remove_chart').click(function(){
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
                  url: "/admin/charts/remove_chart",
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