<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* Copyright © 2015 г. Коротков Е.А.

Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми «Программное Обеспечение»), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение копий Программного Обеспечения, а также лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:

Запрещено изменять (модернизировать, удалять, скрывать любыми средствами) ссылку на Веб-сайт разработчика данного Програмного Обеспечения.
Данное Программное Обеспечение является бесплатным только на стадии разработки, пользователь имеет право бесплатного пользования только версиями данного Программное Обеспечение без пометки "final" и "release".Запрещено продавать копии, а также модернизированные версии данного Програмного Обеспечения
Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.

ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ ГАРАНТИИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ, НО НЕ ОГРАНИЧИВАЯСЬ ИМИ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО КАКИМ-ЛИБО ИСКАМ, ЗА УЩЕРБ ИЛИ ПО ИНЫМ ТРЕБОВАНИЯМ, В ТОМ ЧИСЛЕ, ПРИ ДЕЙСТВИИ КОНТРАКТА, ДЕЛИКТЕ ИЛИ ИНОЙ СИТУАЦИИ, ВОЗНИКШИМ ИЗ-ЗА ИСПОЛЬЗОВАНИЯ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫХ ДЕЙСТВИЙ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ. */
 
function get_scripts(){
    $html = '';
    if(!empty(ViewInput::$scripts['mask_tel'])) : 
            $html .= '<script src="' . base_url() . 'js/mask.js" type="text/javascript"></script>
            <script type="text/javascript">
                jQuery(document).ready(function($){
                    $(\'body\').find("' . implode(', ', ViewInput::$scripts['mask_tel']) . '").mask("+7 (999) 999-99-99");
                });
            </script>';
         endif;
         if(!empty(ViewInput::$scripts['datepicker'])) : 
            $html .= '<script type="text/javascript">
                jQuery(document).ready(function($){
                    ' . lang('datepicker') . '
                    $(\'body\').find("' . implode(', ', ViewInput::$scripts['datepicker']) . '").datepicker();
                });
            </script>';
         endif;
         if(!empty(ViewInput::$scripts['datetime'])) : 
            $html .= '<script type="text/javascript">
                jQuery(document).ready(function($){
                    ' . lang('datetime') . '
                    $(\'body\').find("' . implode(', ', ViewInput::$scripts['datetime']) . '").datetimepicker({
                        controlType: "select",
                        oneLine: true
                    });
                });
            </script>';
         endif;
         if(!empty(ViewInput::$scripts['autoload_user'])) :
            $html .= '<script type="text/javascript">
                jQuery(document).ready(function($){
                    $(\'body\').find("' . implode(', ', ViewInput::$scripts['autoload_user']) . '").autocomplete({
                        source: function(request, response){
                            var group = $(this.element).data("group");
                            $.ajax({
                                type: "POST",
                                url: "/home/get_user_autocomplete",
                                dataType: "json",
                                data:{
                                    limit: 10, 
                                    text: request.term,
                                    group_id: group
                                },
                                success: function(data){
                                    response($.map(data, function(item){
                                        return {
                                            label: item.name                                    
                                        }
                                    }));
                                }
                            });
                        },
                        minLength: 2 
                    });
                });
            </script>';
        endif; 
        if(!empty(ViewInput::$scripts['password'])) :
            $html .= '<script type="text/javascript">
                    jQuery(document).ready(function($) {
                        function str_rand() {
                            var result       = "";
                            var words        = "0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
                            var max_position = words.length - 1;
                                for( i = 0; i < 10; ++i ) {
                                    position = Math.floor ( Math.random() * max_position );
                                    result = result + words.substring(position, position + 1);
                                }
                            return result;
                        }
                        $(".showPassword").mousedown(function(){
                            var inputPsw = $("' . ViewInput::$scripts['password'] . '");
                            if (inputPsw.attr("type") == "password") {
                                inputPsw.attr("type", "text");
                                $(this).removeClass("glyphicon-eye-open").addClass("glyphicon-eye-close");
                            } else {
                                inputPsw.attr("type", "password");
                                $(this).removeClass("glyphicon-eye-close").addClass("glyphicon-eye-open");
                            }
                        });
                        $(".generatePassword").click(function() {
                            $("' . ViewInput::$scripts['password'] . '").attr("type", "text").val(str_rand());
                        });
                    });
                    </script>';
        endif;
        
        if(!empty(ViewInput::$scripts['pagination'])) :
            $html .= '<script type="text/javascript">
                            jQuery(document).ready(function($){
                                $(".count_in_page").change(function(){
                                    var count = $(this).val();
                                    if(count <= 0){
                                        var input = $(\'<input type="text" class="input_cip form-control form-group">\').appendTo($(this).parent());
                                        var button = $(\'<input type="button"  class="btn_cip btn btn-primary form-group" value="' . lang('send_text') . '">\').appendTo($(this).parent());
                                    }else{
                                        $.ajax({
                                            type: "POST",
                                            url: "/groups/set_count_in_page",
                                            dataType: "html",
                                            data:{
                                                "count" : count 
                                            },
                                            success: function(data){
                                                location.reload();
                                            }
                                        });
                                    }
                            
                                });
        
                                $("body").on("click", ".btn_cip", function(){
            
                                var count = $(this).parent().find(".input_cip").val();
                                    $.ajax({
                                        type: "POST",
                                        url: "/groups/set_count_in_page",
                                        dataType: "html",
                                        data:{
                                            "count" : count
                                        },
                                        success: function(data){
                                            location.reload();
                                        }
                                    });
                                });
                            });
            </script>';
        endif;
        
        if(!empty(ViewInput::$scripts['ckeditor'])) : 
        $html .= '<script src="/js/ckeditor/ckeditor.js" type="text/javascript"></script>
            <script type="text/javascript">
                jQuery(document).ready(function($){';
                     foreach(ViewInput::$scripts['ckeditor'] as $val) : 
                       $html .= 'var ' . $val . ' = CKEDITOR.replace("' . $val . '");
                                    if (' . $val . ') {
                                        CKEDITOR.remove(' . $val . ');
                                    }';
                    endforeach;
               $html .=' });
            </script>';
        endif;
        
        $html .= '<script type="text/javascript">
            
            jQuery(document).ready(function($){
                $(\'label, .tooltop_title\').tooltip();
            });
            
            function send(form){';
        if(!empty(ViewInput::$scripts['ckeditor'])) : 
                $html .= 'for ( instance in CKEDITOR.instances ) {
                    CKEDITOR.instances[instance].updateElement();
                }';
        endif;   
        $html .= 'setTimeout(function(){
                    var errors = new Array();
                    var url = jQuery(form).attr(\'action\')
                    jQuery(form).find(\'.form-control\').each(function(){
                        if(jQuery(this).data(\'required\') && !jQuery(this).val()){
                            jQuery(this).addClass(\'not_valid\');
                            errors.push(\'error\')
                        }else{
                            jQuery(this).removeClass(\'not_valid\');
                        }
                    });

                    if(jQuery.inArray(\'error\', errors) == -1){
                        var loader = jQuery("<img src=\"/css/images/loading.gif\"\>");
                        jQuery.ajax({
                          type: "POST",
                          url: url,
                          dataType: "json",
                          data: jQuery(form).serialize(),
                          beforeSend: function(){
                            
                            jQuery(form).parent(".modal-body").find(".modal-title").append(loader);
                          },
                          success: function(data){
                            loader.remove();
//                          $("body").append(data)
                              if(data.error){
                                  jQuery(form).find(\'.errors\').html(data.mess);
                              }else{
                                  location.reload();
                              }
                          },
                          error: function(){
                              alert(\'error\');
                          }
                        });
                    }
                    }, 15);
                return false;
            }
        
            function newConsMessages(count_new){
                if (Notification.permission === "granted") {
                    var consNotification = new Notification(\'' . lang('notice') . '\', {
                        tag : \'cons_notice\',
                        body : count_new + \' ' . lang('cons_new_messages') . '\'
                    });
                    consNotification.onclick = function(){
                        open(\'/consultant\', "Wnd");
                    }
                } else {
                    Notification.requestPermission(function (permission) {
                        if(!(\'permission\' in Notification)) {
                            Notification.permission = permission;
                        }
                        if (permission === "granted") {
                            var consNotification = new Notification(\'' . lang('notice') . '\', {
                                tag : \'cons_notice\',
                                body : count_new + \' ' . lang('cons_new_messages') . '\'
                            });
                            consNotification.onclick = function(){
                                open(\'/consultant\', "Wnd");
                            }
                        }
                    });
                }
            }
            
            function getNewConsMess(){
                var newShow = false;
                var interval = setInterval(function(){
                    jQuery.ajax({
                        type: "GET",
                        url: "/consultant/get_cont_new_mess",
                        dataType: "json",
                        success: function(data){
                            if(data.newMess === true){
                                if(newShow === false){
                                    newConsMessages(data.count_new);
                                    jQuery("#cons_header_link").css("color", "red");
                                }
                                newShow = true;
                            }else{
                                jQuery("#cons_header_link").removeAttr("style");
                                newShow = false;
                            }
                        }
                    });
                }, $CONSUPDATETIME);
            }
            </script>';
        return $html;
}

function get_user_auto($field){
    if($field->type == 'text' && $field->params == 1) :
            $html = '<script type="text/javascript">
                jQuery(document).ready(function($){
                    $(\'body\').find("#' . $field->unique . '").autocomplete({
                        source: function(request, response){
                            $.ajax({
                                type: "POST",
                                url: "/home/get_user_autocomplete",
                                dataType: "json",
                                data:{
                                    limit: 10, 
                                    text: request.term 
                                },
                                success: function(data){
                                    response($.map(data, function(item){
                                        return {
                                            label: item.name                                    
                                        }
                                    }));
                                }
                            });
                        },
                        minLength: 2 
                    });
                });
            </script>';
            return $html;
        endif; 
        return false;
}

