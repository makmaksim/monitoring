<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Copyright © 2015 г. Коротков Е.А.

Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми «Программное Обеспечение»), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение копий Программного Обеспечения, а также лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:

Запрещено изменять (модернизировать, удалять, скрывать любыми средствами) ссылку на Веб-сайт разработчика данного Програмного Обеспечения.
Данное Программное Обеспечение является бесплатным только на стадии разработки, пользователь имеет право бесплатного пользования только версиями данного Программное Обеспечение без пометки "final" и "release".Запрещено продавать копии, а также модернизированные версии данного Програмного Обеспечения
Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.

ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ ГАРАНТИИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ, НО НЕ ОГРАНИЧИВАЯСЬ ИМИ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО КАКИМ-ЛИБО ИСКАМ, ЗА УЩЕРБ ИЛИ ПО ИНЫМ ТРЕБОВАНИЯМ, В ТОМ ЧИСЛЕ, ПРИ ДЕЙСТВИИ КОНТРАКТА, ДЕЛИКТЕ ИЛИ ИНОЙ СИТУАЦИИ, ВОЗНИКШИМ ИЗ-ЗА ИСПОЛЬЗОВАНИЯ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫХ ДЕЙСТВИЙ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ. */


class Groups extends UMC_Controller{
    
    public function index(){
        
        show_404('page');
    }
    
    public function get_group(){
        
        $this->lang->load('groups');
        $this->load->model('groupmodel');
        $this->load->library('pagination');
        $this->load->helper('header');
        
        $get = $this->input->get();
        
        $menu_id = $this->uri->segment(2);
        $menu_item = $this->groupmodel->db->get_where('menu', array('menu_id' => $menu_id))->row();
        $group_id = $menu_item->group_id;
        $data['group_href'] = '/groups/' . $group_id;
        $perms = $this->groupmodel->perms;
        $group = $this->groupmodel->get_group($group_id);
        $data['menu_item'] = $menu_item;
        if(!$perms->{$group->unique}) redirect('error403');
        
        $data['filter_val'] = '';
        if(isset($get['filter_val'])){
            $filter_val_field = $this->groupmodel->get_field($get['filter_field']);
            $filter_val_field->unique = 'filter_val';
            $field_data = new stdClass();
            $field_data->filter_val = $get['filter_val'];
            $data['filter_val'] = ViewInput::get_input_for_user_only($filter_val_field, $field_data);
        }
        
        
        $data['editop'] = $this->session->userdata('id');
        $data['fields'] = $this->groupmodel->get_fields_list($menu_item);
        
        $config['base_url'] = base_url() . 'groups/' . $this->uri->segment(2) . '/';
        $config['total_rows'] = $this->groupmodel->count_all_users($data['fields'], $group_id, $get, $menu_item);
        $config['per_page'] = ($this->session->userdata('count_in_page')) ? $this->session->userdata('count_in_page') : $this->pagination->default_per_page;

        if (count($_GET) > 0){
            $config['suffix'] = '?' . http_build_query($_GET, '', "&");
            $config['first_url'] = $config['base_url'].'?'.http_build_query($_GET, '', "&");
        } 
        
        $this->pagination->initialize($config);
        
        
        
        $data['all_count'] = lang('all_count_text') . $config['total_rows'];
        $data['count_in_page'] = $this->pagination->get_count_in_page();
        $data['pagination'] = $this->pagination->create_links();
                
        $limit = $config['per_page'];
        $limitstart = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['users'] = $this->groupmodel->get_users_list($data['fields'], $group_id, $get, $limitstart, $limit, $menu_item);
        $data['perms'] = $perms; 
         
        $data['export_link'] = '/groups/export?menu_id=' . $menu_id;

        if(!empty($get)){
            $export_get = array();
            foreach($get as $key => $v){
                $export_get[] = $key . '=' . $v;
            }
            
            $data['export_link'] .= '&' . implode('&', $export_get);
        }
        get_header($this);
        $this->load->view('groups/groups', $data);
        get_footer($this);
    }
    
    public function get_new_user_form(){
        $this->lang->load('groups');
        $this->load->model('groupmodel');
        $group_id = $this->input->post('group_id');
        $data['group'] = $this->groupmodel->get_group($group_id);
        $data['perms'] = $this->groupmodel->perms;
        
        if(!$data['perms']->{$data['group']->unique} || !$data['perms']->control_user) $this->load->view('errors/error403');
        $data['fields'] = $this->groupmodel->get_fields($group_id);
        $data['count_field_cell'] = $this->groupmodel->get_count_fields_cell($group_id);
        $this->load->view('groups/new_user', $data);
    }
    
    public function add_user(){
        $this->lang->load('groups');
        $this->load->model('groupmodel');
        
        $post = $this->input->post();
                
        $insert_cell_hidden = (isset($post['insert_cell_hidden'])) ? $post['insert_cell_hidden'] : 0;
        $perms = $this->groupmodel->perms;
        if(!$perms->control_user){
            echo json_encode(array('error' => TRUE, 'mess' => lang('permission_denied')));
        }else{
            $group = $this->groupmodel->get_group($post['group_id']);
            if(!$perms->{$group->unique}){
                echo json_encode(array('error' => TRUE, 'mess' => lang('permission_denied')));
            }else{
                $fields = $this->groupmodel->get_fields($post['group_id']);
                
                $required = 0;
                if($insert_cell_hidden){
                    $this->form_validation->set_rules('cell_name', lang('cell_name'), 'required');
                    $required++;
                }                
                foreach($fields as $val){ 
                    if($val->required){
                        if($val->in_cell && $insert_cell_hidden){
                            $this->form_validation->set_rules($val->unique, $val->name, 'required');
                            $required++;
                        }elseif($val->in_cell == 0){
                            $this->form_validation->set_rules($val->unique, $val->name, 'required');
                            $required++;
                        }
                    }
                }
                if ($required && $this->form_validation->run() == FALSE){
                    echo json_encode(array('error' => TRUE, 'mess' => validation_errors()));
                }else{
                    //        проверяем наличие логина в базе, если нету - сохраняем
                    $res = $this->groupmodel->get_isset_username($post['login']);
                    if($post['login'] && isset($res->id)){
                        echo json_encode(array('error' => true, 'mess' => lang('login_isset_text')));
                        return;
                    }
                        
                    if($res = $this->groupmodel->save_new_user($post)){
                        $link = sprintf($this->lang->line('new_user_success'), $res);
                        $mess[] = array('type' => 'success', 'message' => $link);
                        $this->send_notice_mail_sotr('user', $res, $post['group_id']);
                        echo json_encode(array('error' => false));
                    }else{
                        $mess[] = array('type' => 'error', 'message' => lang('new_user_error'));
                        echo json_encode(array('error' => false));
                    }
                    $this->session->set_userdata('mess', $mess);
                }
            }
        }
        return;
    }
    
    public function get_field_search(){
        $this->load->model('groupmodel');
        $this->load->helper('fields_script');
        $field_id = $this->input->post('id');
        $field = $this->groupmodel->get_field($field_id);
        $field->unique = 'filter_val';
        $html =  ViewInput::get_input_for_user_only($field, new stdClass());
        $html .= get_scripts();
        $html .= get_user_auto($field);
        echo $html;
    }
    
    public function set_count_in_page(){
        $count = $this->input->post('count');
        $this->session->set_userdata('count_in_page', $count);
        echo 'ok';
    }
    
    private function send_notice_mail_sotr($type, $user_id, $group){
        $this->config->load('mail_config', TRUE);
        $config = $this->config->item('mail_config');
        if($config['send_system_messages'] && isset($config['mail_event_' . $type][$group]) ){
            $this->load->model('usermodel');
            $this->lang->load('email');
            $this->load->library('email');
            $this->email->init($config);
            foreach($config['mail_event_' . $type] as $key => $val){
                if($key == $group){
                    $mails = $this->usermodel->get_email_field_group($val);
                    $body = @sprintf($this->lang->line($type . '_message_body'), $user_id, $user_id);
                    if($config['mailtype'] == 'html'){
                        $body = $this->email->get_system_template(array('message_body' => $body));
                    }
                    $this->email->umc_system_send($mails, lang($type . '_message_subject'), $body);
                }
            }
        }
    }
    
    public function dynamic_field_edit(){
        $user_id = $this->input->post('user_id');
        $field = $this->input->post('field');
        $cell_id = $this->input->post('cell_id');
        $this->load->model('groupmodel', 'model', TRUE);
        if($this->model->perms->{$field . '_rec'}){
            $data['field'] = $this->model->get_field_dynamic($user_id, $field, $cell_id);
            $this->load->view('groups/dynamic_field_edit', $data);
        }
    }
    
    public function save_dynamic_field(){
        $post = $this->input->post();
        $this->load->model('groupmodel', 'model', TRUE);
        
        if($this->model->perms->{$post['field'] . '_rec'}){
            $field = $this->model->get_field_dynamic($post['user_id'], $post['field'], $post['cell_id']);
            $this->model->db->set($post['field'], $this->model->format_data($field, $post))
                            ->where('user_id', $post['user_id']);
            if($field->in_cell){
               $this->model->db->where('cell_id', $post['cell_id']);
            }
            $this->model->db->update('users_data');
            redirect($post['href']);
        }
    }
    
    public function export(){
        $this->load->model('groupmodel');

        if(!$this->groupmodel->control_export) redirect('error403');
        $get = $this->input->get();
        
        $menu_id = $get['menu_id'];
        $menu_item = $this->groupmodel->db->get_where('menu', array('menu_id' => $menu_id))->row();
        $group_id = $menu_item->group_id;
        $group = $this->groupmodel->get_group($group_id);
        $fields = $this->groupmodel->get_fields_list($menu_item);              
        $users = $this->groupmodel->get_users_list_export($fields, $group_id, $get, $menu_item);

        $names = array();
        foreach($fields as $field){
            $names[] = iconv("UTF-8", "Windows-1251", $field->name);
        }
        
        $file = FCPATH . 'temp/users_' . date('Y-m-d') . '.csv';
        if(file_exists($file)){
            unlink($file);
        }
        $fp = fopen($file, 'w');
        fputcsv($fp, $names, ';');        
        
        foreach($users as $us){
            
            $line = array();
            foreach($fields as $field){
                $line[] = iconv("UTF-8", "Windows-1251", ViewInput::get_field_data_export($us, $field));
            }
            
            fputcsv($fp, $line, ';');
        }
        
        fclose($fp);
        
        if (file_exists($file)) {
            if (ob_get_level()) {
              ob_end_clean();
            }
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . $group->name . '_' . date('d.m.Y') . '.csv');
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            if ($fd = fopen($file, 'rb')) {
              while (!feof($fd)) {
                print fread($fd, 1024);
              }
              fclose($fd);
            }
            
            exit;
        }else{
            echo 'Ошибка создания файла!';
        }
    }
}