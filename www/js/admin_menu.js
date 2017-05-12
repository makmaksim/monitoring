jQuery(document).ready(function($){
    
    var delete_elem = false;
    var delete_cancel = false;
    
    $('#add_menu_item_btn').click(function(){
        $.ajax({
          type: "POST",
          url: "/admin/menu/get_new_item_form",
          dataType: "html",
          success: function(data){
            $('body').append(data);
            $('body').find('#add_menu_item').modal('show');
            $('body').on('hidden.bs.modal', '#add_menu_item', function () {
                $("#add_menu_item").remove();
            });
          }
        });
    });
    
    $('.menu_list, .menu_cildren_list').sortable({
        stop: function( event, ui ) {
            var $this = $(this);
            $this.sortable('disable');
            var list_ids = new Array();
            $this.children('li').each(function(){
                list_ids.push($(this).data('id'));
            });
            $.ajax({
                  type: "POST",
                  url: "/admin/menu/update_sort_items",
                  dataType: "html",
                  data: {'ids' : list_ids},
                  success: function(data){
                     $this.sortable('enable');
                  },
                  error: function(){
                      alert('error');
                  }
                });
        }
    });
    
    $('.edit_item').click(function(){
        var id = $(this).parent().data('id');
        $.ajax({
          type: "POST",
          url: "/admin/menu/get_edit_item_form",
          dataType: "html",
          data: {'id' : id},
          success: function(data){
            $('body').append(data);
            $('body').find('#edit_menu_item').modal('show');
            $('body').on('hidden.bs.modal', '#edit_menu_item', function () {
                $("#edit_menu_item").remove();
            });
          }
        });
    });
    
    $('.remove_item').click(function(){
        $('#delete_modal').modal('show');
        var $this = $(this).parent();
        $this.css({  
            'background' : '#ff0000',
            'color' : '#fff'
        });
        delete_elem = function(){ 
            var id = $this.data('id');
            $.ajax({
              type: "POST",
              url: "/admin/menu/remove_item",
              dataType: "json",
              data: {'id' : id},
              success: function(data){
                  if(!data.error){
                    $this.fadeOut(200, $this.remove());
                  }else{
                      $('.messages').html('<div class="mess_type_error">' + data.mess + '</div>');
                      $this.removeAttr('style');
                  }
              }
            });
            return false;
        }
        
        delete_cancel = function(){    
            $this.removeAttr('style');
            return false;
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