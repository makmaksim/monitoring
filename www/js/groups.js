
jQuery(document).ready(function($){
    $('.add_user_btn').click(function(){
        var group_id = $(this).data('group_id');
        $.ajax({
          type: "POST",
          url: "/groups/get_new_user_form",
          dataType: "html",
          data: {'group_id' : group_id},
          success: function(data){
            $('body').append(data);
            $('body').find('#new_user_form').modal('show');
            $('body').on('hidden.bs.modal', '#new_user_form', function () {
                $("#new_user_form").remove();
            });
          }
        });
    });
    
    $('body').on('click', '.spoiler', function() {
        $(this).parent().next().collapse('toggle');
    });
    
    $('#filter_field').change(function(){
        var id = $(this).val();
        $('#filter_group_id').val(id);
        var group_id = $(this).data('group_id');
        $.ajax({
          type: "POST",
          url: "/groups/get_field_search",
          dataType: "html",
          data: {'id' : id},
          success: function(data){
            $('.filter_val').html(data);
          }
        });
    });
    
    $('.sort_btn').click(function(){
        var order = $(this).data('order');
        if(order == 1){
           order = 2 ;
        }else{
            order = 1;
        }
        var field = $(this).data('field');
        $.cookie('sort_field', field, { path: '/groups'});
        $.cookie('sort_order', order, { path: '/groups'});
        location.reload();
    });
    
    $('.clean_sort').click(function(){
        $.cookie('sort_field', 0);
        $.cookie('sort_order', 0);
        location.reload();
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
    });
    
    $('.remove_user_btn').click(function(){
        var $this = $(this);
        var user_id = $this.data('user_id');
        $('.user_tr_' + user_id).css({
            color: '#fff',
            background: '#ff0000'
        });
        $('#delete_modal').modal('show').find('.yes_btn').click(function(){   
            
            $.ajax({
              type: "POST",
              url: "/home/remove_user",
              dataType: "json",
              data: {'user_id' : user_id},
              success: function(data){
                  $('.user_tr_' + user_id).fadeOut(200, function(){
                      $('.user_tr_' + user_id).remove();
                  });
              }
            });
        });
        $('#delete_modal').find('.no_btn').click(function(){   
            $('.user_tr_' + user_id).removeAttr('style');
        });
        $('body').on('hidden.bs.modal', '#delete_modal', function () {
            $('.user_tr_' + user_id).removeAttr('style');
        });
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
    });
    
    $('.dynamic_field_edit').dblclick(function(){
        var user_id = $(this).data('user');
        var cell_id = $(this).data('cell');
        var field = $(this).data('field');
        $.ajax({
          type: "POST",
          url: "/groups/dynamic_field_edit",
          dataType: "html",
          data: {'user_id' : user_id, 'field' : field, 'cell_id' : cell_id},
          success: function(data){
            $('body').append(data);
            $('body').find('#dynamic_field_edit').modal('show');
            $('body').on('hidden.bs.modal', '#dynamic_field_edit', function () {
                $("#dynamic_field_edit").remove();
            });
          }
        });
    });
});



//----------------------------------------------------- 
// Фиксированный заголовок у таблицы 
//----------------------------------------------------- 
// by ManHunter / PCL (www.manhunter.ru) 
//----------------------------------------------------- 
fix_header={ 
  'fixed_el': null, 
  'new_table': null, 
  
  bind : function(el, eventName, callback) { 
    if (el) { 
      if (el.addEventListener) { 
        el.addEventListener(eventName, callback, false); 
      } 
      else if (el.attachEvent) { 
        el.attachEvent("on" + eventName, callback); 
      } 
    } 
  }, 
  
  get_position: function(el) { 
    var offsetLeft = 0, offsetTop = 0; 
    do { 
      offsetLeft += el.offsetLeft; 
      offsetTop  += el.offsetTop; 
    } 
    while (el = el.offsetParent); 
    return {x:offsetLeft, y:offsetTop}; 
  }, 
  
  chk_position: function() { 
    var doc = document.documentElement; 
    var body = document.body; 
  
    if (typeof(window.innerWidth) == 'number') { 
      my_width = window.innerWidth; 
      my_height = window.innerHeight; 
    } 
    else if (doc && (doc.clientWidth || doc.clientHeight)) { 
      my_width = doc.clientWidth; 
      my_height = doc.clientHeight; 
    } 
    else if (body && (body.clientWidth || body.clientHeight)) { 
      my_width = body.clientWidth; 
      my_height = body.clientHeight; 
    } 
  
    if (doc.scrollTop) { dy=doc.scrollTop; } else { dy=body.scrollTop; } 
  
    var coord=fix_header.get_position(fix_header.fixed_el); 
  
    // Заголовок таблицы еще на экране или таблица уже не на экране 
    if (coord.y>dy || (coord.y+fix_header.fixed_el.clientHeight)<dy) { 
      fix_header.new_table.style.left='-9999px'; 
    } 
    // Заголовок уже прокручен вверх 
    else { 
      fix_header.new_table.style.left= 
        fix_header.fixed_el.getBoundingClientRect().left+'px'; 
    } 
  }, 
  
  fix: function (id) { 
    var tmp,st; 
    var ftable=document.getElementById(id); 
    if (ftable) { 
      if (this.new_table!=null) { 
        if (this.new_table.parentNode!=undefined) { 
          this.new_table.parentNode.removeChild(this.new_table); 
        } 
        this.new_table=null; 
      } 
      else { 
        this.bind(window,'scroll',this.chk_position); 
        this.bind(window,'resize',this.chk_position); 
      } 
  
      this.fixed_el=ftable; 
  
      tmp=ftable.getElementsByTagName('thead'); 
      if (tmp) { 
        var fthead=tmp[0]; 
  
        new_table=document.createElement('table'); 
  
        for(var i in this.fixed_el.style) { 
          if (this.fixed_el.style[i]!='') { 
            try { 
              new_table.style[i]=this.fixed_el.style[i]; 
            } 
            catch (e) {}; 
          } 
        } 
  
        new_table.id='fixed_'+id; 
        new_table.rules='all'; 
        new_table.border='1'; 
        new_table.style.position='fixed'; 
        new_table.style.left='-9999px'; 
        new_table.style.top='0px'; 
  
        var cln = fthead.cloneNode(true); 
        var cth=cln.getElementsByTagName('th'); 
        var fth=fthead.getElementsByTagName('th'); 
  
        for(var i=0; i<fth.length; i++) { 
          cth[i].style.width=(fth[i].clientWidth + 1)+'px'; 
          cth[i].style.paddingLeft='0'; 
          cth[i].style.paddingRight='0'; 
        } 
        new_table.appendChild(cln); 
  
        this.fixed_el.parentNode.appendChild(new_table); 
        this.new_table=new_table; 
        this.chk_position(); 
      } 
    } 
  } 
}; 

fix_header.fix('userlist_table');