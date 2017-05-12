jQuery(document).ready(function($){
    
    var delete_elem = false;
    var delete_cancel = false;
    
    $('.groups_list > ul').sortable({
        stop: function( event, ui ) {
            $('.groups_list > ul').sortable('disable');
            var list_ids = new Array();
            $('.groups_list > ul > li').each(function(){
                list_ids.push($(this).data('id'));
            });
            $.ajax({
                  type: "POST",
                  url: "/admin/fields/update_sort_group",
                  dataType: "html",
                  data: {'ids' : list_ids},
                  success: function(data){
                     $('.groups_list ul').sortable('enable');
                  },
                  error: function(){
                      alert('error');
                  }
                });
        }
    });
    
    $('.fields_list > ul, .fields_list_cell > ul').sortable({
        stop: function( event, ui ) {
            $('.fields_list > ul, .fields_list_ceil > ul').sortable('disable');
            var list_ids = new Array();
            ui.item.parent('ul').children('li').each(function(){
                list_ids.push($(this).data('id'));
            });
            
            $.ajax({
                  type: "POST",
                  url: "/admin/fields/update_sort_fields",
                  dataType: "html",
                  data: {'ids' : list_ids},
                  success: function(data){
                     $('.fields_list ul, .fields_list_ceil > ul').sortable('enable');
                  },
                  error: function(){
                      alert('error');
                  }
                });
        }
    });
    $('.edit_field').click(function(){
        var id = $(this).parent().data('id');
        $.ajax({
          type: "POST",
          url: "/admin/fields/get_edit_form_field",
          dataType: "html",
          data: {'id' : id},
          success: function(data){
            $('body').append(data);
            $('body').find('#edit_field_form').modal('show');
            $('body').on('hidden.bs.modal', '#edit_field_form', function () {
                $("#edit_field_form").remove();
            });
          }
        });
    });
    
    $('.delete_field').click(function(){
        $('#delete_modal').modal('show');
        var $this = $(this);
        var $style = $this.parent().attr('style');
        var selector = $this.data('selector');
        $('.' + selector).removeAttr('style');
        $('.' + selector).css({  
                        'background' : '#ff0000',
                        'color' : '#fff'
                  });
        delete_elem = function(){      
            
            var id = $this.parent().data('id');
            $.ajax({
              type: "POST",
              url: "/admin/fields/remove_field",
              dataType: "html",
              data: {'id' : id},
              success: function(data){
                  $('.' + selector).fadeOut(800, function(){
                    $('.' + selector).remove();
                  });
              }
            });
            return false;
        }
        
        delete_cancel = function(){    
            $('.' + selector).attr('style', $style);
            return false;
        }
        
        
    });
    
    $('.edit_group').click(function(){
        var id = $(this).parent().data('id');
        $.ajax({
          type: "POST",
          url: "/admin/fields/get_edit_form_group",
          dataType: "html",
          data: {'id' : id},
          success: function(data){
            $('body').append(data);
            $('body').find('#edit_group_form').modal('show');
            $('body').on('hidden.bs.modal', '#edit_group_form', function () {
                $("#edit_group_form").remove();
            });
          }
        });
    });
    
    $('.delete_group').click(function(){
        $('#delete_modal').modal('show');
        var $this = $(this);
        $this.parent().css({  
                        'background' : '#ff0000',
                        'color' : '#fff'
                  });
        delete_elem = function(){      
        
            var id = $this.parent().data('id');
            $.ajax({
              type: "POST",
              url: "/admin/fields/remove_group",
              dataType: "json",
              data: {'id' : id},
              success: function(data){
                  if(data.error){
                      $('.messages').html('<div class="mess_type_error">' + data.mess + '</div>');
                      $this.parent().removeAttr('style');
                  }else{
                      $this.parent().fadeOut(800, function(){
                        $this.parent().remove();
                      });
                      $('.messages').html('');
                  }
                  
              },error: function(){
                  alert('error');
                  $this.parent().removeAttr('style');
              }
            });   
            return false;        
        }
        delete_cancel = function(){
            $this.parent().removeAttr('style');
            return false;
        }
    });
    
    $('.get_perm_form').click(function(){
        var id = $(this).parent().data('id');
        $.ajax({
          type: "POST",
          url: "/admin/fields/get_perm_form",
          dataType: "html",
          data: {'id' : id},
          success: function(data){
            $('body').append(data);
            $('body').find('#edit_perm_form').modal('show');
            $('body').on('hidden.bs.modal', '#edit_perm_form', function () {
                $("#edit_perm_form").remove();
            });
          }
        });
    }); 
    
    $('.get_type_form').click(function(){
        var id = $(this).parent().data('id');
        $.ajax({
          type: "POST",
          url: "/admin/fields/get_field_params_form",
          dataType: "html",
          data: {'id' : id},
          success: function(data){
            $('body').append(data);
            $('body').find('#field_params_form').modal('show');
            $('body').on('hidden.bs.modal', '#field_params_form', function () {
                $("#field_params_form").remove();
            });
          }
        });
    }); 
    
    $('.cell_params').click(function(){
        var id = $(this).data('id');
        $.ajax({
          type: "POST",
          url: "/admin/fields/get_cell_params",
          dataType: "html",
          data: {'id' : id},
          success: function(data){
            $('body').append(data);
            $('body').find('#cell_params_form').modal('show');
            $('body').on('hidden.bs.modal', '#cell_params_form', function () {
                $("#cell_params_form").remove();
            });
          }
        });
    }); 
    
    $('.group_control_perms').click(function(){
        var id = $(this).parent().data('id');
        $.ajax({
          type: "POST",
          url: "/admin/fields/get_group_control_perms",
          dataType: "html",
          data: {'id' : id},
          success: function(data){
            $('body').append(data);
            $('body').find('#group_control_perms').modal('show');
            $('body').on('hidden.bs.modal', '#group_control_perms', function () {
                $("#group_control_perms").remove();
            });
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