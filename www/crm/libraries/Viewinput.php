<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Copyright © 2015 г. Коротков Е.А.

Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми «Программное Обеспечение»), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение копий Программного Обеспечения, а также лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:

Запрещено изменять (модернизировать, удалять, скрывать любыми средствами) ссылку на Веб-сайт разработчика данного Програмного Обеспечения.
Данное Программное Обеспечение является бесплатным только на стадии разработки, пользователь имеет право бесплатного пользования только версиями данного Программное Обеспечение без пометки "final" и "release".Запрещено продавать копии, а также модернизированные версии данного Програмного Обеспечения
Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.

ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ ГАРАНТИИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ, НО НЕ ОГРАНИЧИВАЯСЬ ИМИ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО КАКИМ-ЛИБО ИСКАМ, ЗА УЩЕРБ ИЛИ ПО ИНЫМ ТРЕБОВАНИЯМ, В ТОМ ЧИСЛЕ, ПРИ ДЕЙСТВИИ КОНТРАКТА, ДЕЛИКТЕ ИЛИ ИНОЙ СИТУАЦИИ, ВОЗНИКШИМ ИЗ-ЗА ИСПОЛЬЗОВАНИЯ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫХ ДЕЙСТВИЙ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ. */
 
/**
* класс вывода html полей информации
*/


class ViewInput{
    
    static $scripts = array(
                          'mask_tel'   => array(),
                          'ckeditor'   => array(),
                          'datepicker' => array()
                      );
    
//    список типов полей
    public static function _types_input(){
        $array =  array(
        
            'text' => lang('text_field'),
            'textarea' => lang('textarea_field'),
            'editor' => lang('editor_field'),
            'tel' => lang('phone_field'),
            'email' => lang('email_field'),
            'list' => lang('list_field'),
            'date' => lang('date_field'),
            'datetime' => lang('datetime_field'),
            'user' => lang('user_field'),
            'checkbox' => lang('checkbox_field')
        );

        return $array;
    }
    
    public static function _text($data, $params = '', $title = ''){
        if(isset($data['value'])){
            $value = htmlspecialchars($data['value']);
        }else{
            $value = '';
        }
        $id = preg_replace('/[^\w]/', '', $data['name']);
        $required = (isset($data['required'])) ? ' data-required="' . $data['required'] . '" ' : ' data-required="0" ';
        
        $required_star = '';
        if(isset($data['required']) && $data['required']){
            $required_star = '<span class="red">*</span>';
        }
        
        if(isset($data['only'])){
            $html = '<input type="text" class="form-control" id="' . $id . '" placeholder="' . htmlspecialchars($data['label']) . '" name="' . $data['name'] . '" ' . $required . ' value="' . $value . '">';
        }else{
            
            $html = '<div class="form-group row">
                <label class="col-sm-3" for="' . $id . '" title="' . $title . '">' . $data['label'] . $required_star . '</label>
                <div class="col-sm-9"><input type="text" class="form-control" id="' . $id . '" placeholder="' . htmlspecialchars($data['label']) . '" name="' . $data['name'] . '" ' . $required . ' value="' . $value . '"></div>
              </div>';
        }
        
        return $html;
    }
    
    public static function _user($data){
        $value = '';
        if(isset($data['value'])){
            $value = htmlspecialchars($data['value']);
        }
        $id = preg_replace('/[^\w]/', '', $data['name']);
        if(isset($data['id_key']) && $data['id_key']){
            $rand = $data['id_key'];
        }else{
            $rand = rand(0,100);
        }
        $id .= $rand;
        $required = (isset($data['required'])) ? ' data-required="' . $data['required'] . '" ' : ' data-required="0" ';
        
        $required_star = '';
        if(isset($data['required']) && $data['required']){
            $required_star = '<span class="red">*</span>';
        }
        
        $field_user = '';
        if(isset($data['field_user'])){
            $field_user = $data['field_user'];
        }
        
        ViewInput::$scripts['autoload_user'][] = '#' . $id;
        
        if(isset($data['only'])){
            $html = '<input type="text" class="form-control" id="' . $id . '" placeholder="' . $data['label'] . '" name="' . $data['name'] . '" ' . $required . ' value="' . $value . '" data-group="' . (int)$data['params'] . '">';
        }else{
            
            $html = '<div class="form-group row">
                <label class="col-sm-3" for="' . $id . '">' . $data['label'] . $required_star . '</label>
                <div class="col-sm-9"><input type="text" class="form-control" id="' . $id . '" placeholder="' . $data['label'] . '" name="' . $data['name'] . '" ' . $required . ' value="' . $value . '" data-group="' . (int)$data['params'] . '"></div>
              </div>';
        }
        
        return $html;
    }
    
    public static function _password($data){
        if(isset($data['value'])){
            $value = $data['value'];
        }else{
            $value = '';
        }
        $id = preg_replace('/[^\w]/', '', $data['name']);
        ViewInput::$scripts['password'] = '#' . $id;
        
        if(isset($data['only'])){
            $html = '<div class="input-group"><input type="password" class="form-control" id="' . $id . '" placeholder="' . $data['label'] . '" name="' . $data['name'] . '" value="' . $value . '"><a href="#" class="showPassword glyphicon glyphicon-eye-open input-group-addon""></a><a href="#" class="generatePassword input-group-addon"">' . lang('generate_text') . '</a></div>';
        }else{
            
            $html = '<div class="form-group row">
                <label class="col-sm-3" for="' . $id . '">' . $data['label'] . '</label>
                <div class="col-sm-9"><div class="input-group"><input type="password" class="form-control" id="' . $id . '" placeholder="' . $data['label'] . '" name="' . $data['name'] . '" value="' . $value . '"><span class="showPassword glyphicon glyphicon-eye-open input-group-addon btn"></span><span class="generatePassword input-group-addon btn"">' . lang('generate_text') . '</span></div></div>
              </div>';
        }
        
        return $html;
    }
    
    public static function _tel($data){
        
        $value = '';
        if(isset($data['value'])){
            $value = $data['value'];
        }
        $id = preg_replace('/[^\w]/', '', $data['name']);
        if(isset($data['id_key']) && $data['id_key']){
            $rand = $data['id_key'];
        }else{
            $rand = rand(0,100);
        }
        $id .= $rand;
        $required = (isset($data['required'])) ? ' data-required="' . $data['required'] . '" ' : ' data-required="0" ';
        
        $required_star = '';
        if(isset($data['required']) && $data['required']){
            $required_star = '<span class="red">*</span>';
        }
        
        ViewInput::$scripts['mask_tel'][] = '#' . $id;
                
        if(isset($data['only'])){
            $html = '<input type="tel" class="form-control" id="' . $id . '" placeholder="' . $data['label'] . '" name="' . $data['name'] . '" ' . $required . ' value="' . $value . '">';
        }else{
            
            $html = '<div class="form-group row">
                <label class="col-sm-3" for="' . $id . '">' . $data['label'] . $required_star . '</label>
                <div class="col-sm-9"><input type="tel" class="form-control" id="' . $id . '" placeholder="' . $data['label'] . '" name="' . $data['name'] . '" ' . $required . ' value="' . $value . '"></div>
              </div>';
        }
        
        return $html;
        
    }
    
    public static function _date($data){
        
        $value = '';
        if(isset($data['value']) && $data['value']){
            $value = date(lang('date_format'), strtotime($data['value']));
        }
        
        $id = preg_replace('/[^\w]/', '', $data['name']);
        $required = (isset($data['required'])) ? ' data-required="' . $data['required'] . '" ' : ' data-required="0" ';
        
        $required_star = '';
        if(isset($data['required']) && $data['required']){
            $required_star = '<span class="red">*</span>';
        }
        
        if(isset($data['id_key']) && $data['id_key']){
            $rand = $data['id_key'];
        }else{
            $rand = rand(0,100);
        }
        
        $id .= $rand;
        
        ViewInput::$scripts['datepicker'][] = '#' . $id;
                
        if(isset($data['only'])){
            $html = '<input type="text" class="form-control" id="' . $id . '" placeholder="' . $data['label'] . '" name="' . $data['name'] . '" ' . $required . ' value="' . $value  . '">';
        }else{
            
            $html = '<div class="form-group row">
                <label class="col-sm-3" for="' . $id . '">' . $data['label'] . $required_star . '</label>
                <div class="col-sm-9"><input type="text" class="form-control" id="' . $id . '" placeholder="' . $data['label'] . '" name="' . $data['name'] . '" ' . $required . '  value="' . $value . '"></div>
              </div>';
        }
        
        return $html;
        
    }
    
    public static function _datetime($data){
        
        $value = '';
        if(isset($data['value']) && $data['value']){
            $value = date(lang('datetime_format'), strtotime($data['value']));
        }
        
        $required = (isset($data['required'])) ? ' data-required="' . $data['required'] . '" ' : ' data-required="0" ';
        
        $required_star = '';
        if(isset($data['required']) && $data['required']){
            $required_star = '<span class="red">*</span>';
        }
        $id = preg_replace('/[^\w]/', '', $data['name']);
        if(isset($data['id_key']) && $data['id_key']){
            $rand = $data['id_key'];
        }else{
            $rand = rand(0,100);
        }
        
        $id .= $rand;
        
        ViewInput::$scripts['datetime'][] = '#' . $id;
                
        if(isset($data['only'])){
            $html = '<input type="text" class="form-control" id="' . $id . '" placeholder="' . $data['label'] . '" name="' . $data['name'] . '" ' . $required . ' value="' . $value  . '">';
        }else{
            
            $html = '<div class="form-group row">
                <label class="col-sm-3" for="' . $id . '">' . $data['label'] . $required_star . '</label>
                <div class="col-sm-9"><input type="text" class="form-control" id="' . $id . '" placeholder="' . $data['label'] . '" name="' . $data['name'] . '" ' . $required . ' value="' . $value . '"></div>
              </div>';
        }
        
        return $html;
        
    }
    
    public static function _email($data){
    
        if(isset($data['value'])){
            $value = $data['value'];
        }else{
            $value = '';
        }
        $id = preg_replace('/[^\w]/', '', $data['name']);
        $required = (isset($data['required'])) ? ' data-required="' . $data['required'] . '" ' : ' data-required="0" ';
        
        $required_star = '';
        if(isset($data['required']) && $data['required']){
            $required_star = '<span class="red">*</span>';
        }
        
        if(isset($data['only'])){
            $html = '<input type="email" class="form-control" id="' . $id . '" placeholder="' . $data['label'] . '" name="' . $data['name'] . '" ' . $required . ' value="' . $value . '">';
        }else{
            
            $html = '<div class="form-group row">
                <label class="col-sm-3" for="' . $id . '">' . $data['label'] . $required_star . '</label>
                <div class="col-sm-9"><input type="email" class="form-control" id="' . $id . '" placeholder="' . $data['label'] . '" name="' . $data['name'] . '" ' . $required . ' value="' . $value . '"></div>
              </div>';
        }
        
        return $html;
        
    }
    
    public static function _checkbox($data, $params = '', $title = ''){
        $id = preg_replace('/[^\w]/', '', $data['name']);
        if(isset($data['id_key']) && $data['id_key']){
            $rand = $data['id_key'];
        }else{
            $rand = rand(0,100);
        }
        $id .= $rand;
        $checked = '';
        if(isset($data['value']) && $data['value'])
            $checked = 'checked="checked"';      
        
        if(isset($data['only'])){
            $html = '<input type="hidden" name="' . $data['name'] . '" value="0"><input type="checkbox" name="' . $data['name'] . '" class="checkbox" id="' . $id. '" value="1" ' . $checked . ' ' . $params . '><label for="' . $id. '"></label>';
        }else{
            
            $html = '<div class="form-group row">
                <label class="col-sm-3" for="' . $data['name'] . '" title="' . $title . '">' . $data['label'] . '</label>
                <div class="col-sm-9">
                    <input type="hidden" name="' . $data['name'] . '" value="0">
                    <input type="checkbox" name="' . $data['name'] . '" class="checkbox" id="' . $id . '" value="1" ' . $checked . ' ' . $params . '>
                    
                    <label for="' . $id . '"></label></div>
              </div>';
        }
        
        return $html;
        
    }
    
    public static function _textarea($data){
    
        if(isset($data['value'])){
            $value = $data['value'];
        }else{
            $value = '';
        }
        $id = preg_replace('/[^\w]/', '', $data['name']);
        $required = (isset($data['required'])) ? ' data-required="' . $data['required'] . '" ' : ' data-required="0" ';
        
        $required_star = '';
        if(isset($data['required']) && $data['required']){
            $required_star = '<span class="red">*</span>';
        }
        
        if(isset($data['only'])){
            $html = '<textarea rows="5" class="form-control" id="' . $id . '" placeholder="' . $data['label'] . '" name="' . $data['name'] . '" ' . $required . '>' . $value . '</textarea>';
        }else{
            
            $html = '<div class="form-group row">
                <label class="col-sm-3" for="' . $id . '">' . $data['label'] . $required_star . '</label>
                <div class="col-sm-9"><textarea rows="5" class="form-control" id="' . $id . '" placeholder="' . $data['label'] . '" name="' . $data['name'] . '" ' . $required . ' >' . $value . '</textarea></div>
              </div>';
        }
        
        return $html;
        
    }
    
    public static function _editor($data){
        $value = '';
        if(isset($data['value'])){
            $value = $data['value'];
        }
        $id = preg_replace('/[^\w]/', '', $data['name']);
        if(isset($data['id_key']) && $data['id_key']){
            $rand = $data['id_key'];
        }else{
            $rand = rand(0,100);
        }
        
        $id .= $rand;
        
        $required = (isset($data['required'])) ? ' data-required="' . $data['required'] . '" ' : ' data-required="0" ';
        
        $required_star = '';
        if(isset($data['required']) && $data['required']){
            $required_star = '<span class="red">*</span>';
        }
        
        ViewInput::$scripts['ckeditor'][] = $id;

        if(isset($data['only'])){
            $html = '<textarea class="form-control" id="' . $id . '" placeholder="' . $data['label'] . '" name="' . $data['name'] . '" ' . $required . '>' . $value . '</textarea>';
        }else{
            
            $html = '<div class="form-group row">
                <label class="col-sm-3" for="' . $id . '">' . $data['label'] . $required_star . '</label>
                <div class="col-sm-9"><textarea class="form-control" id="' . $id . '" placeholder="' . $data['label'] . '" name="' . $data['name'] . '" ' . $required . '>' . $value . '</textarea></div>
              </div>';
        }
        
        return $html;
        
    }
    
//    select для ручной вставки
    
    public static function _list($data, $params = ''){
        
        $value = '';
        if(isset($data['value'])){
            $value = $data['value'];
        }
        
        $id = preg_replace('/[^\w]/', '', $data['name']);

        $required = (isset($data['required'])) ? ' data-required="' . $data['required'] . '" ' : ' data-required="0" ';
        
        $required_star = '';
        if(isset($data['required']) && $data['required']){
            $required_star = '<span class="red">*</span>';
        }
        
        if(isset($data['key'])){
            if(isset($data['only'])){
                $html = '<select name="' . $data['name'] . '" class="form-control" id="' . $id. '" ' . $params . ' ' . $required . '>
                            <option value="0" selected="selected">' . $data['label'] . '</option>';
                            foreach($data['list'] as $val){
                                if($val->{$data['key']} == $value){
                                    $selected = 'selected="selected"';
                                }else{
                                    $selected = '';
                                }
                                $html .= '<option value="' . $val->{$data['key']}  . '" ' . $selected . '>' . $val->{$data['val']} . '</option>';
                            }
                
                $html .='</select>';
            }else{
                
                $html = '<div class="form-group row">
                    <label for="' . $id . '" class="col-sm-3">' . $data['label'] . $required_star . '</label>
                    <div class="col-sm-9">
                        <select name="' . $data['name'] . '" class="form-control" id="' . $id . '" ' . $params . ' ' . $required . '>
                            <option value="0" selected="selected">' . $data['label'] . '</option>';
                            foreach($data['list'] as $val){
                                if($val->{$data['key']} == $value){
                                    $selected = 'selected="selected"';
                                }else{
                                    $selected = '';
                                }
                                $html .= '<option value="' . $val->{$data['key']} . '" ' . $selected . '>' . $val->{$data['val']} . '</option>';
                            }
                
                $html .='</select>
                    </div>
                  </div>';
            }
        }else{
            if(isset($data['only'])){
                $html = '<select name="' . $data['name'] . '" class="form-control" id="' . $id . '" ' . $params . ' ' . $required . '>
                            <option value="0" selected="selected">' . $data['label'] . '</option>';
                            foreach($data['list'] as $key => $val){
                                if($key == $value){
                                    $selected = 'selected="selected"';
                                }else{
                                    $selected = '';
                                }
                                $html .= '<option value="' . $key . '" ' . $selected . '>' . $val . '</option>';
                            }
                
                $html .='</select>';
            }else{
                
                $html = '<div class="form-group row">
                    <label for="' . $id . '" class="col-sm-3">' . $data['label'] . $required_star . '</label>
                    <div class="col-sm-9">
                        <select name="' . $data['name'] . '" class="form-control" id="' . $id . '" ' . $params . ' ' . $required . '>
                            <option value="0" selected="selected">' . $data['label'] . '</option>';
                            foreach($data['list'] as $key => $val){
                                if($key == $value){
                                    $selected = 'selected="selected"';
                                }else{
                                    $selected = '';
                                }
                                $html .= '<option value="' . $key . '" ' . $selected . '>' . $val . '</option>';
                            }
                
                $html .='</select>
                    </div>
                  </div>';
            }
        }
        
        return $html;
        
    }
    
//    select для вывода данных из типа поля list
    
    public static function _list_type($data, $params = ''){
        
        $value = '';
        if(isset($data['value'])){
            $value = $data['value'];
        }
        $id = preg_replace('/[^\w]/', '', $data['name']);
        $required = (isset($data['required'])) ? ' data-required="' . $data['required'] . '" ' : ' data-required="0" ';
        
        $required_star = '';
        if(isset($data['required']) && $data['required']){
            $required_star = '<span class="red">*</span>';
        }
            if(isset($data['only'])){
                $html = '<select name="' . $data['name'] . '" class="form-control" id="' . $id . '" ' . $params . ' ' . $required . '>';
                            foreach($data['list'] as $key => $val){
                                if($key == $value){
                                    $selected = 'selected="selected"';
                                }else{
                                    $selected = '';
                                }
                                $html .= '<option value="' . $key . '" ' . $selected . '>' . $val[0] . '</option>';
                            }
                
                $html .='</select>';
            }else{
                
                $html = '<div class="form-group row">
                    <label for="' . $id . '" class="col-sm-3">' . $data['label'] . $required_star . '</label>
                    <div class="col-sm-9">
                        <select name="' . $data['name'] . '" class="form-control" id="' . $id . '" ' . $params . ' ' . $required . '>';
                            foreach($data['list'] as $key => $val){
                                if($key == $value){
                                    $selected = 'selected="selected"';
                                }else{
                                    $selected = '';
                                }
                                $html .= '<option value="' . $key . '" ' . $selected . '>' . $val[0] . '</option>';
                            }
                
                $html .='</select>
                    </div>
                  </div>';
            }
        
        
        return $html;
        
    }
    
    public static function _list_multiple($data, $params = ''){
        
        $value = array();
        if(isset($data['value'])){
            $value = $data['value'];
        }
        $id = preg_replace('/[^\w]/', '', $data['name']);
        $required = (isset($data['required'])) ? ' data-required="' . $data['required'] . '" ' : ' data-required="0" ';
        
        $required_star = '';
        if(isset($data['required']) && $data['required']){
            $required_star = '<span class="red">*</span>';
        }
        
        if(isset($data['key'])){
            if(isset($data['only'])){
                $html = '<select name="' . $data['name'] . '[]" class="form-control" id="' . $id . '" ' . $params . ' multiple ' . $required . '>';
                            foreach($data['list'] as $val){
                                if(in_array($val->{$data['key']}, $value)){
                                    $selected = 'selected="selected"';
                                }else{
                                    $selected = '';
                                }
                                $html .= '<option value="' . $val->{$data['key']}  . '" ' . $selected . '>' . $val->{$data['val']} . '</option>';
                            }
                
                $html .='</select>';
            }else{
                
                $html = '<div class="form-group row">
                    <label for="' . $id . '" class="col-sm-3">' . $data['label'] . $required_star . '</label>
                    <div class="col-sm-9">
                        <select name="' . $data['name'] . '[]" class="form-control" id="' . $id . '" ' . $params . ' multiple ' . $required . '>';
                            foreach($data['list'] as $val){
                                if(in_array($val->{$data['key']}, $value)){
                                    $selected = 'selected="selected"';
                                }else{
                                    $selected = '';
                                }
                                $html .= '<option value="' . $val->{$data['key']} . '" ' . $selected . '>' . $val->{$data['val']} . '</option>';
                            }
                
                $html .='</select>
                    </div>
                  </div>';
            }
        }else{
            if(isset($data['only'])){
                $html = '<select name="' . $data['name'] . '[]" class="form-control" id="' . $id . '" ' . $params . ' multiple ' . $required . '>';
                            foreach($data['list'] as $key => $val){
                                if(in_array($key, $value)){
                                    $selected = 'selected="selected"';
                                }else{
                                    $selected = '';
                                }
                                $html .= '<option value="' . $key . '" ' . $selected . '>' . $val . '</option>';
                            }
                
                $html .='</select>';
            }else{
                
                $html = '<div class="form-group row">
                    <label for="' . $id . '" class="col-sm-3">' . $data['label'] . $required_star . '</label>
                    <div class="col-sm-9">
                        <select name="' . $data['name'] . '[]" class="form-control" id="' . $id . '" ' . $params . ' multiple ' . $required . '>';
                            foreach($data['list'] as $key => $val){
                                if(in_array($key, $value)){
                                    $selected = 'selected="selected"';
                                }else{
                                    $selected = '';
                                }
                                $html .= '<option value="' . $key . '" ' . $selected . '>' . $val . '</option>';
                            }
                
                $html .='</select>
                    </div>
                  </div>';
            }
        }
        
        return $html;
        
    }
    
    public static function _get_send_button($data, $params = ''){
        
        if(isset($data['only'])){
            $html = '<button type="submit" class="btn btn-primary" ' . $params . '>' . $data['label'] . '</button>';
        }else{
            $html = '<div class="form-group row">
                        <label class="col-sm-3"></label>
                        <div class="col-sm-9"><button type="submit" class="btn btn-primary" ' . $params . '>' . $data['label'] . '</button></div>
                      </div>';
        }
        return $html;
    }
    
    public static function _get_button($data, $params = ''){
        
        if(isset($data['only'])){
            $html = '<button type="button" class="btn btn-primary" ' . $params . '>' . $data['label'] . '</button>';
        }else{
            $html = '<div class="form-group row">
                        <label class="col-sm-3"></label>
                        <div class="col-sm-9"><button type="button" class="btn btn-primary" ' . $params . '>' . $data['label'] . '</button></div>
                      </div>';
        }
        return $html;
    }
    
    public static function get_input_for_user($obj, $data){
        $field = '';
        $value = '';
        if(is_object($data) && property_exists($data, $obj->unique)){
            $value = $data->{$obj->unique};
        }
        $array = array(
                    'label' => $obj->name,
                    'name' => $obj->unique,
                    'required' => $obj->required,
                    'value' => $value,
                    'params' => $obj->params
                    );
        if($obj->type == 'list'){
            $array['list'] = unserialize($obj->params);
            $field = ViewInput::_list_type($array);
        }else{
            $func = '_' . $obj->type;
            $field = ViewInput::$func($array);
        }

        return $field;
    }
    
    public static function get_input_for_user_only($obj, $data){
        $field = '';
        $value = '';
        if(property_exists($data, $obj->unique)){
            $value = $data->{$obj->unique};
        }
        $array = array(
                    'only' => true,
                    'label' => $obj->name,
                    'name' => $obj->unique,
                    'required' => $obj->required,
                    'value' => $value
                    );
        if($obj->type == 'list'){
            $array['list'] = unserialize($obj->params);
            $field = ViewInput::_list_type($array);
        }else{
            $func = '_' . $obj->type;
            $field = ViewInput::$func($array);
        }

        return $field;
    }
    
    public static function get_field_data($fields_data, $field){
        $data = '';
        switch($field->type){
            case 'checkbox' :
                if(isset($fields_data->{$field->unique})){
                    $checked = '';
                    if($fields_data->{$field->unique}){
                        $checked = 'checked="checked"';
                    }
                    $data = '<input type="checkbox" class="checkbox" '.$checked.'><label></label>';
                }
                break;
                
            case 'list' :
                $list = unserialize($field->params);
                $data = isset($list[$fields_data->{$field->unique}][0]) ?  '<div style="padding: 0 3px;' . $list[$fields_data->{$field->unique}][1]  . '">' . $list[$fields_data->{$field->unique}][0] . '</div>' : '';
                break;
            case 'date' :
                $data = ($fields_data->{$field->unique} !== NULL ) ? date(lang('date_format'), strtotime($fields_data->{$field->unique})) : '';
                break;
            
            case 'datetime' :
                $data = ($fields_data->{$field->unique} !== NULL ) ? date(lang('datetime_format'), strtotime($fields_data->{$field->unique})) : '';
                break;
            
            default :
                
                $data = (isset($fields_data->{$field->unique})) ? $fields_data->{$field->unique} : '';
                break;
        }
        return $data;
    }
    
    public static function get_field_data_export($fields_data, $field){
        global $_CITIES;
        $data = '';
        switch($field->type){
            case 'checkbox' :
                if(isset($fields_data->{$field->unique})){
                    $checked = '';
                    if($fields_data->{$field->unique}){
                        $checked = 'checked="checked"';
                    }
                    $data = '<input type="checkbox" class="checkbox" '.$checked.'><label></label>';
                }
                break;
                
            case 'list' :
                $list = unserialize($field->params); 
                $data = isset($list[$fields_data->{$field->unique}][0]) ?  $list[$fields_data->{$field->unique}][0] : '';
                break;
            case 'date' :
                $data = ($fields_data->{$field->unique} !== NULL ) ? date(lang('date_format'), strtotime($fields_data->{$field->unique})) : '';
                break;
            case 'city' :
                $data = ($fields_data->{$field->unique} ) ? $_CITIES[$fields_data->{$field->unique}] : '';
                break;
            case 'datetime' :
                $data = ($fields_data->{$field->unique} !== NULL ) ? date(lang('datetime_format'), strtotime($fields_data->{$field->unique})) : '';
                break;
            
            default :
                
                $data = (isset($fields_data->{$field->unique})) ? $fields_data->{$field->unique} : '';
                break;
        }
        return $data;
    }
}
