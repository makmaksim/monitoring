
jQuery(document).ready(function($){
    
    var delete_elem = false;
    var delete_cancel = false;
    
    $('.edit_fields_cell_btn').click(function(){
        var id = $(this).data('id');
        var user_id = $(this).data('user_id');
        var group_id = $(this).data('group_id');
        $.ajax({
          type: "POST",
          url: "/home/get_cell_edit_form",
          dataType: "html",
          data: {'id' : id, 'user_id' : user_id, 'group_id' : group_id},
          success: function(data){
            $('body').append(data);
            $('body').find('#cell_edit_form').modal('show');
            $('body').on('hidden.bs.modal', '#cell_edit_form', function () {
                $("#cell_edit_form").remove();
            });
          }
        });
        return false;
    });
    
    $('.edit_fields_btn').click(function(){
       
        var user_id = $(this).data('user_id');
        $.ajax({
          type: "POST",
          url: "/home/get_fields_edit_form",
          dataType: "html",
          data: {'user_id' : user_id},
          success: function(data){
            $('body').append(data);
            $('body').find('#edit_fields').modal('show');
            $('body').on('hidden.bs.modal', '#edit_fields', function () {
                $("#edit_fields").remove();
            });
          }
        });
        return false;
    });
    
    $('.new_cell_btn').click(function(){
       
        var user_id = $(this).data('user_id');
        $.ajax({
          type: "POST",
          url: "/home/get_new_cell_form",
          dataType: "html",
          data: {'user_id' : user_id},
          success: function(data){
            $('body').append(data);
            $('body').find('#new_cell').modal('show');
            $('body').on('hidden.bs.modal', '#new_cell', function () {
                $("#new_cell").remove();
            });
          }
        });
        return false;
    });
    
    $('.edit_params_user_btn').click(function(){
       
        var user_id = $(this).data('user_id');
        $.ajax({
          type: "POST",
          url: "/home/get_edit_params_form",
          dataType: "html",
          data: {'user_id' : user_id},
          success: function(data){
            $('body').append(data);
            $('body').find('#params_user').modal('show');
            $('body').on('hidden.bs.modal', '#params_user', function () {
                $("#params_user").remove();
            });
          }
        });
        return false;
    });
    
    $('.remove_cell_btn').click(function(){
        $('#delete_modal').modal('show');
        var $this = $(this);
        $this.parent().find('a.cell_tab_link').css({  
                        'background' : '#FF8D8D'
                  });
        delete_elem = function(){      
            var id = $this.data('id');
            var user_id = $this.data('user_id');
            var elem = $this.parent().find('a.cell_tab_link').attr('href');
            $.ajax({
              type: "POST",
              url: "/home/remove_cell",
              dataType: "html",
              data: {'id' : id, 'user_id' : user_id},
              success: function(data){
                  $this.parent().fadeOut(800, function(){
                    $this.parent().remove();
                  });
                  $(elem).fadeOut(800, function(){
                   $(elem).remove();
                  });
              }
            });
            return false;
        }
        
        delete_cancel = function(){
            $this.parent().find('a.cell_tab_link').removeAttr('style');
            return false;
        }
        return false;
    });
    
    $('.remove_user_btn').click(function(){
        $('#delete_modal').modal('show');
        var $this = $(this);
        delete_elem = function(){   
            var user_id = $this.data('user_id');
            $.ajax({
              type: "POST",
              url: "/home/remove_user",
              dataType: "json",
              data: {'user_id' : user_id},
              success: function(data){
                  alert(data.mess);
              },
              error: function(){
                  alert('error');
              }
            });
            return false;
        }
        
        delete_cancel = function(){
            return false;
        }
        return false;
    });
    var cell_open = $.cookie('cell_open');
   
    if(cell_open){
        $('.nav-tabs a[href='+cell_open+']').tab('show');
    }else{
        $('.nav-tabs a[href=#usertab]').tab('show');
    }
    
    $('.nav-tabs a').on('click', function (e) {
        var user_id = $(this).data('user');
        $.cookie('cell_open', e.target.hash, { path: '/home/' + user_id});
    }); 
    
    $('.edit_comment').click(function(){
        var comment_block = $(this).parent().parent();
        comment_block.find('.edit_comment_form').slideDown(300);
        return false;
    });
    
    $('.delete_comment').click(function(){
        $('#delete_modal').modal('show');
        var $this = $(this);
        var parent = $this.parent().parent();
        parent.css({  
                        'background' : '#FF8D8D'
                  });
        delete_elem = function(){      
            var id = $this.data('id');
            var user_id = $this.data('user_id');
            $.ajax({
              type: "POST",
              url: "/home/remove_comment",
              dataType: "html",
              data: {'id' : id, 'user_id' : user_id},
              success: function(data){
                  parent.fadeOut(800, function(){
                    parent.remove();
                  });
              }
            });
            return false;
        }

        delete_cancel = function () {
            parent.removeAttr('style');
            return false;
        }
        return false;
    });
    
    $('.delete_file').click(function(){
        $('#delete_modal').modal('show');
        var $this = $(this);
        var parent = $this.parent();
        parent.css({  
                        'background' : '#FF8D8D'
                  });
        delete_elem = function(){      
            var id = $this.data('id');
            $.ajax({
              type: "POST",
              url: "/home/remove_file",
              dataType: "html",
              data: {'id' : id},
              success: function(data){
                  parent.fadeOut(800, function(){
                    parent.remove();
                  });
              }
            });
            return false;
        }

        delete_cancel = function () {
            parent.removeAttr('style');
            return false;
        }
    });
    
    $('.file_input_block input').change(function(){
        $('.upload_file_form').submit();
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