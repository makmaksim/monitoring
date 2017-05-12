
jQuery(document).ready(function(){
    
    var delete_elem = false;
    var delete_cancel = false;
    
    $('.remove_cons_btn').click(function(){
        $('#delete_modal').modal('show');
        var $this = $(this);
        $this.parent().find('a.cell_tab_link').css({  
                        'background' : '#FF8D8D'
                  });

        delete_elem = function(){   
            var id = $this.data('id');
            var elem = $this.parent().find('a.cell_tab_link').attr('href');
            $.ajax({
              type: "POST",
              url: "/admin/consultant/remove_cons",
              dataType: "html",
              data: {'id' : id},
              success: function(data){
                  $this.parent().fadeOut(800, function(){
                    $this.parent().remove();
                  });
                  $(elem).fadeOut(800, function(){
                    $(elem).remove();
                  });
              }
            });
        }
        delete_cancel = function(){
            $this.parent().find('a.cell_tab_link').removeAttr('style');
        }
        return false;
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
    $('.api_key_generate').click(function(){
            var $this = $(this);
            $.ajax({
              type: "POST",
              url: "/admin/consultant/get_api_key",
              dataType: "html",
              success: function(data){
                  $this.parent().find('.api_key').val(data);
              }
            });
    });
});