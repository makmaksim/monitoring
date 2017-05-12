<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* Copyright © 2015 г. Коротков Е.А.

Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми «Программное Обеспечение»), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение копий Программного Обеспечения, а также лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:

Запрещено изменять (модернизировать, удалять, скрывать любыми средствами) ссылку на Веб-сайт разработчика данного Програмного Обеспечения.
Данное Программное Обеспечение является бесплатным только на стадии разработки, пользователь имеет право бесплатного пользования только версиями данного Программное Обеспечение без пометки "final" и "release".Запрещено продавать копии, а также модернизированные версии данного Програмного Обеспечения
Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.

ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ ГАРАНТИИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ, НО НЕ ОГРАНИЧИВАЯСЬ ИМИ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО КАКИМ-ЛИБО ИСКАМ, ЗА УЩЕРБ ИЛИ ПО ИНЫМ ТРЕБОВАНИЯМ, В ТОМ ЧИСЛЕ, ПРИ ДЕЙСТВИИ КОНТРАКТА, ДЕЛИКТЕ ИЛИ ИНОЙ СИТУАЦИИ, ВОЗНИКШИМ ИЗ-ЗА ИСПОЛЬЗОВАНИЯ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫХ ДЕЙСТВИЙ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ. */
 
class Formapi extends UMC_Controller{
    
    public function index(){
        $this->load->helper('header');
        $this->lang->load('formapi');
        $this->load->model('admin/formapimodel', 'fa_model', true);
        
        $data['forms'] = $this->fa_model->get_forms();
        $data['groups'] = $this->fa_model->get_groups();
        get_header($this);
        $this->load->view('admin/formapi/formapi', $data);
        get_footer($this);
    }
    
    public function get_new_formapi_form(){
        $this->load->model('admin/formapimodel', 'fa_model', true);
        $this->lang->load('formapi');

        $data['groups'] = $this->fa_model->get_groups();
        $this->load->view('admin/formapi/new_formapi', $data);
    }
    
    public function save_new_formapi(){
        
        $this->load->model('admin/formapimodel', 'fa_model', true);
        $post = $this->input->post();
        $this->fa_model->insert_formapi($post);
        
        redirect('admin/formapi');
    }
    
    public function edit_formapi(){
        
        $post = $this->input->post();
        $this->load->model('admin/formapimodel', 'fa_model', true);
        $fields = array();
        foreach($post['field'] as $field){
            if($field){
                $fields[] = $field;
            } 
        }
        $this->fa_model->update_formapi($post, serialize($fields));
        
        redirect('admin/formapi');
    }
    
    public function remove_form(){
        $id = $this->input->post('id');
        $this->load->model('admin/formapimodel', 'fa_model', true);
        $this->fa_model->delete_formapi($id);
        echo 'ok';
    }
    
    public function get_fields(){
        $this->lang->load('formapi');
        $group_id = $this->input->post('id');
        $this->load->model('admin/formapimodel', 'fa_model', true);
        $fields = $this->fa_model->get_fields($group_id);
        echo '<div class="field_formapi_block row">
                
                <div class="col-md-11">
                    ' . ViewInput::_list(array(
                        'label' => lang('field_label'),
                        'name' => 'field[]',
                        'list' => $fields,
                        'key' => 'unique',
                        'val' => 'name'
                    )) . '
                </div>
                <div class="col-md-1"><span class="btn glyphicon glyphicon-remove remove_field"></span></div>
            </div>';
        
    }
    
    function get_api_key(){
        echo uniqid(rand(), TRUE);
    }
}