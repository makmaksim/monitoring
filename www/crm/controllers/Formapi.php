<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* Copyright © 2015 г. Коротков Е.А.

Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми «Программное Обеспечение»), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение копий Программного Обеспечения, а также лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:

Запрещено изменять (модернизировать, удалять, скрывать любыми средствами) ссылку на Веб-сайт разработчика данного Програмного Обеспечения.
Данное Программное Обеспечение является бесплатным только на стадии разработки, пользователь имеет право бесплатного пользования только версиями данного Программное Обеспечение без пометки "final" и "release".Запрещено продавать копии, а также модернизированные версии данного Програмного Обеспечения
Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.

ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ ГАРАНТИИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ, НО НЕ ОГРАНИЧИВАЯСЬ ИМИ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО КАКИМ-ЛИБО ИСКАМ, ЗА УЩЕРБ ИЛИ ПО ИНЫМ ТРЕБОВАНИЯМ, В ТОМ ЧИСЛЕ, ПРИ ДЕЙСТВИИ КОНТРАКТА, ДЕЛИКТЕ ИЛИ ИНОЙ СИТУАЦИИ, ВОЗНИКШИМ ИЗ-ЗА ИСПОЛЬЗОВАНИЯ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫХ ДЕЙСТВИЙ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ. */

class Formapi extends UMC_Controller{
    public function index(){
        echo '';
    }
    
    public function get_data(){
        $post = $this->input->post();
        $this->load->model('formapimodel', 'fa_model', true);
        $form = $this->fa_model->get_form((int)$post['id']);
        if(!empty($form) && $form->api_key == $post['api_key']){
            $data_type = ($post['data_type']) ? 'html' : 'array';
            $data = $this->get_fields(unserialize($form->fields), $data_type);
            print_r(serialize($data));
        }else{
            echo '';
        }
        return;
    }
    
    public function getform(){
        echo 'pl';
    }
    
    public function set_data(){
        $post = $this->input->post();
        $this->load->model('formapimodel', 'fa_model', true);
        $form = $this->fa_model->get_form((int)$post['id']);
        if(!empty($form) && $form->api_key == $post['api_key'] && isset($post['umcfields'])){
            $fields_array = array();
            $fields_names = array();
            foreach($post['umcfields'] as $key => $val){
                $field = $this->fa_model->get_field((int)$key, $form->group_id);
                $fn['name'] = $field->name;
                $fn['id'] = $field->field_id;
                if($field->type == 'list'){
                    $fn['params'] = unserialize($field->params);
                }else{
                    $fn['params'] = $field->params;
                }
                $fn['type'] = $field->type;

                $fields_names[] = $fn;
                if(!empty($field)){
                    $field->val = $post['umcfields'][$field->field_id];
                    $fields_array[] = $field;
                    if($field->required){
                        $this->form_validation->set_rules('umcfields[' . $field->field_id . ']' , $field->name, 'required');
                    }
                }
            }

            if ($this->form_validation->run() == FALSE){
                print_r(serialize(array('error' => TRUE, 'mess' => validation_errors())));
            }else{
                $res = $this->fa_model->save_form($fields_array, $form->group_id);
                print_r(serialize(array('error' => FALSE, 'mess' => $res, 'fields' => $fields_names)));
            }
            
        }else{
            echo '';
        }
        return;
    }
    
    private function get_fields($fields, $data_type){
        $array = array();
        foreach($fields as $key => $val){
            $field = $this->fa_model->get_field($val);
            $array[] = $this->{'get_field_' . $data_type}($field);
        }
        return $array;
    }
    
    private function get_field_array($field){
        $array = array();
        switch($field->type){
            case 'list' :
                $array['label'] = $field->name;
                $array['required'] = $field->required;
                $array['options'] = unserialize($field->params);
                $array['type'] = 'list';
                $array['name'] = 'umcfields[' . $field->field_id . ']';
                break;
                
            default :
                $array['label'] = $field->name;
                $array['type'] = $field->type;
                $array['required'] = $field->required;
                $array['name'] = 'umcfields[' . $field->field_id . ']';
                break;
        }
        return $array;
    }
    
    private function get_field_html($field){
        $array = array();
        $required = ' data-required="' . $field->required . '" ';
        switch($field->type){
            case 'list' :
                $array['html'] = '<select name="umcfields[' . $field->field_id . ']" class="form-control" id="' . $field->unique . '" ' . $required . '>';
                foreach(unserialize($field->params) as $key => $val){
                    $array['html'] .= '<option value="' . $key . '">' . $val[0] . '</option>';
                }
                $array['html'] .= '</select>';
                break;
                
            case 'textarea' :
                $array['html'] = '<textarea name="umcfields[' . $field->field_id . ']"  class="form-control" id="' . $field->unique . '"  ' . $required . '></textarea>';
                break;
                
            case 'email' :
                $array['html'] = '<input type="email" name="umcfields[' . $field->field_id . ']" class="form-control" id="' . $field->unique . '"  ' . $required . '>';
                break;
            case 'phone' :
                $array['html'] = '<input type="phone" name="umcfields[' . $field->field_id . ']" class="form-control" id="' . $field->unique . '"  ' . $required . '>';
                break;
            case 'checkbox' :
                $array['html'] = '<input type="checkbox" name="umcfields[' . $field->field_id . ']" class="form-control" id="' . $field->unique . '" ' . $required . '>';
                break;
            default :
                $array['html'] = '<input type="text" name="umcfields[' . $field->field_id . ']" class="form-control" id="' . $field->unique . '" ' . $required . '>';
                break;
        }
        $star = '';
        if($field->required){
            $star = '<span class="umc_required">*</span>';
        }
        
        $array['label'] = '<label for="' . $field->unique . '">' . $field->name . $star . '</label>';
        
        return $array;
    }
}
