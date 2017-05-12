<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Copyright © 2015 г. Коротков Е.А.

Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми «Программное Обеспечение»), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение копий Программного Обеспечения, а также лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:

Запрещено изменять (модернизировать, удалять, скрывать любыми средствами) ссылку на Веб-сайт разработчика данного Програмного Обеспечения.
Данное Программное Обеспечение является бесплатным только на стадии разработки, пользователь имеет право бесплатного пользования только версиями данного Программное Обеспечение без пометки "final" и "release".Запрещено продавать копии, а также модернизированные версии данного Програмного Обеспечения
Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.

ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ ГАРАНТИИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ, НО НЕ ОГРАНИЧИВАЯСЬ ИМИ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО КАКИМ-ЛИБО ИСКАМ, ЗА УЩЕРБ ИЛИ ПО ИНЫМ ТРЕБОВАНИЯМ, В ТОМ ЧИСЛЕ, ПРИ ДЕЙСТВИИ КОНТРАКТА, ДЕЛИКТЕ ИЛИ ИНОЙ СИТУАЦИИ, ВОЗНИКШИМ ИЗ-ЗА ИСПОЛЬЗОВАНИЯ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫХ ДЕЙСТВИЙ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ. */

class Fields extends UMC_Controller{
    
    public function index(){
        
        $this->lang->load('fields');
        $this->load->model('admin/fieldsmodel', 'model', true);
        $this->load->helper('header');
        
        $data['groups'] = $this->model->get_groups();
        foreach($data['groups'] as $key => $group){
            $data['groups'][$key]->fields_list = $this->model->get_fields($group->group_id);
            
        }
        
        get_header($this);
        $this->load->view('admin/fields/fields', $data);
        get_footer($this);
    }
    
    public function new_field(){
        $this->lang->load('fields');
        $this->form_validation->set_rules('name', 'lang:form_input_name', 'required');
        $this->form_validation->set_rules('group_id[]', 'lang:group_name', 'required');
        $this->form_validation->set_rules('type', 'lang:form_input_type', 'required');
        
        if ($this->form_validation->run() == FALSE){
            echo json_encode(array('error' => TRUE, 'mess' => validation_errors()));
        }else{
            $this->load->model('admin/fieldsmodel', 'model', true);
            
            $groups = $this->input->post('group_id');
            $name = $this->input->post('name');
            
            $post['name'] = $name;
            $post['unique'] = $this->get_unique($name);
            
            $isset_field = $this->model->get_field_unique($post['unique']);
            if($isset_field){
                for($i = 1; $i < 100; $i++){
                    $isset_field = $this->model->get_field_unique($post['unique'].$i);
                    if(!$isset_field){
                        $post['unique'] = $post['unique'].$i;
                        break;
                    }
                }
            }
            
            $post['groups'] = $groups;
            $post['type'] = $this->input->post('type');
            $post['data'] = $this->input->post('data');
            $post['required'] = $this->input->post('required');
            $post['in_cell'] = $this->input->post('in_cell');
            
            $this->model->add_field($post);
            
            echo json_encode(array('error' => FALSE));
        }
    }
    
    public function get_edit_form_field(){
        
        $this->lang->load('fields');
        $this->load->model('admin/fieldsmodel', 'model', true);
        
        $id = $this->input->post('id');
        $data['field'] = $this->model->get_field($id);
        $data['groups'] = $this->model->get_groups();
        $data['groups_vals'] = $this->model->get_groups_vals($id);
        
        $this->load->view('admin/fields/field_edit', $data);
    }
    
    public function get_field_params_form(){
        
        $this->lang->load('fields');
        $this->load->model('admin/fieldsmodel', 'model', true);
        
        $data['form_btn_send'] = $this->lang->line('form_btn_send');
        $data['field_params_text'] = $this->lang->line('field_params_text');
        
        $id = $this->input->post('id');
        $data['field'] = $this->model->get_field($id);
        $data['groups'] = $this->model->get_groups();
        $this->load->view('admin/fields/field_params', $data);
    }
    
    public function edit_field(){
        $this->lang->load('fields');
        $this->form_validation->set_rules('name', 'lang:form_input_name', 'required');
        $this->form_validation->set_rules('groups[]', 'lang:group_name', 'required');
        $this->form_validation->set_rules('type', 'lang:form_input_type', 'required');
        
        if ($this->form_validation->run() == FALSE){
            echo json_encode(array('error' => TRUE, 'mess' => validation_errors()));
        }else{
            $this->load->model('admin/fieldsmodel', 'model', true);
            $post['field_id'] = $this->input->post('field_id');
            $post['name'] = $this->input->post('name');
            $post['groups'] = $this->input->post('groups');
            $post['type'] = $this->input->post('type');
            $post['data'] = $this->input->post('data');
            $post['required'] = $this->input->post('required');
            $post['in_cell'] = $this->input->post('in_cell');

            $this->model->save_field($post);
            echo json_encode(array('error' => FALSE));
        }
    }
    
     public function edit_field_params(){
        
        $this->load->model('admin/fieldsmodel', 'model', true);
        $params = $this->input->post('params');
        $post['field_id'] = $this->input->post('field_id');
        
        $field = $this->model->get_field($post['field_id']);
        
        switch($field->type){
            case 'list' :
                $post['params'] = serialize($params);
                break;
            default :
                $post['params'] = $params;
                break;
        }
        
        

        $this->model->save_field_params($post);
        redirect('/admin/fields');
    }
    
    function remove_field(){
        $this->load->model('admin/fieldsmodel', 'model', true);
        $id = $this->input->post('id');
        $this->model->delete_field($id);
        print_r('ok');
    }
    
    function update_sort_fields(){
        $this->load->model('admin/fieldsmodel', 'model', true);
        $this->model->update_sort_fields($this->input->post('ids'));
        print_r('ok');
    }
    
    public function new_group(){
        
        $this->lang->load('fields');
        $this->form_validation->set_rules('name', 'lang:name', 'required');

        if ($this->form_validation->run() == FALSE){   
            echo json_encode(array('error' => TRUE, 'mess' => validation_errors()));
        }else{
            $this->load->model('admin/fieldsmodel', 'model', TRUE);

            $name = $this->input->post('name');
            $unique = $this->get_unique($name);
            
            $group_unique = $this->model->get_group_unique($unique);
            
            if($group_unique->count){
                $this->lang->load('fields');
                $mess[] = array('type' => 'error', 'message' => $this->lang->line('group_name_isset'));
                $this->session->set_userdata('mess', $mess);
                redirect('admin/fields');
            }else{
                $post['name'] = $name;
                $post['unique'] = $unique;
                $post['workmans'] = $this->input->post('workmans');
                $post['postfix'] = $this->input->post('postfix');
                $post['single'] = $this->input->post('single');
               
                $res = $this->model->add_group($post);
                echo json_encode(array('error' => FALSE));
            }
        }
        
    }
    
    public function get_edit_form_group(){
        $this->lang->load('fields');
        $this->load->model('admin/fieldsmodel', 'model', true);
        
        $data['group'] = $this->model->get_group($this->input->post('id'));

        $this->load->view('admin/fields/group_edit',$data);

    }
    
    function get_fields_list(){
        $this->load->model('admin/groupsmodel', 'model', true);
        $this->model->get_fields();
        redirect('admin/fields_list');
    }
    
    function edit_group(){
        $this->lang->load('fields');
        $this->form_validation->set_rules('name', 'lang:name', 'required');

        if ($this->form_validation->run() == FALSE){
            echo json_encode(array('error' => TRUE, 'mess' => validation_errors()));
        }else{
            $post['name'] = $this->input->post('name');
            $post['workmans'] = $this->input->post('workmans');
            $post['postfix'] = $this->input->post('postfix');
            $post['single'] = $this->input->post('single');
            $post['group_id'] = $this->input->post('group_id');

            $this->load->model('admin/fieldsmodel', 'model', true);
            $this->model->save_group($post);
            echo json_encode(array('error' => FALSE));
        }
    }
    
    function remove_group(){
        $this->load->model('admin/fieldsmodel', 'model', true);
        $this->lang->load('fields');
        $id = $this->input->post('id');
        
        $fields = $this->model->get_count_fields($id);
        if($fields->count > 0){
            echo json_encode(array('error' => TRUE, 'mess' => lang('remove_fileds_from_del_group')));
        }else{
            $this->model->delete_group($id);
            echo json_encode(array('error' => FALSE));
        }
    }
    
    function update_sort_group(){
        $this->load->model('admin/fieldsmodel', 'model', true);
        $this->model->update_sort_groups($this->input->post('ids'));
        print_r('ok');
    }
    
    function get_perm_form(){
        $id = $this->input->post('id');
        $this->lang->load('fields');
        $this->load->model('admin/fieldsmodel', 'model', true);
        $data['g'] = $this->model->get_group_perms($id);       
        
        $groups = $this->model->get_groups();
        $data['groups'] = array();
        foreach($groups as $group){
            $group->fields = $this->model->get_fields($group->group_id);
            $data['groups'][] = $group;
        }

        $this->load->view('admin/fields/group_perms',$data);
    }
    
    function save_group_control_perms(){
        $this->load->model('admin/fieldsmodel', 'model', true);
        $this->model->save_perm_group_control($this->input->post());
        print_r('ok');
    }
    
    function save_perms_group(){
        
        $post['id'] = $this->input->post('id');
        $post['from_group'] = $this->input->post('from_group');
        $post['p'] = $this->input->post('p');
        
        $this->load->model('admin/fieldsmodel', 'model', true);
        
        $this->model->save_perm_group($post);
        
        print_r('ok');
    }
    
    function save_perms_fields(){
        
        $post['id'] = $this->input->post('id');
        $post['from_group'] = $this->input->post('from_group');
        $post['p'] = $this->input->post('p');
        $post['type'] = $this->input->post('type');
        
        $this->load->model('admin/fieldsmodel', 'model', true);
        
        $this->model->save_perm_filed($post);
        
        print_r('ok');
    }
    
    public function get_cell_params(){
        $this->lang->load('fields');
        $this->load->model('admin/fieldsmodel', 'model', true);
        $data['group'] = $this->model->get_group($this->input->post('id'));
        $data['fields'] = $this->model->get_fileds_cell($data['group']->group_id);
        $this->load->view('admin/fields/cell_params',$data);
    }
    
    public function save_cell_params(){
        $this->load->model('admin/fieldsmodel', 'model', true);
        $this->model->update_cell_params($this->input->post());
        redirect('admin/fields');
    }
    
    public function get_group_control_perms(){
        $this->lang->load('fields');
        $this->load->model('admin/fieldsmodel', 'model', true);
        
        $data['group'] = $this->model->get_group_perms($this->input->post('id'));
        $this->load->view('admin/fields/group_control_perms',$data);
    }
    
    private function get_unique($str){
        $str = mb_strtolower($str, 'UTF-8');
        $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => '',  'ы' => 'y',   'ъ' => '',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya'
        );
        
        $str = strtr($str, $converter);
        return preg_replace("/[^a-z0-9]/", "", $str);
    }
    
}