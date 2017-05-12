<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Copyright © 2015 г. Коротков Е.А.

Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми «Программное Обеспечение»), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение копий Программного Обеспечения, а также лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:

Запрещено изменять (модернизировать, удалять, скрывать любыми средствами) ссылку на Веб-сайт разработчика данного Програмного Обеспечения.
Данное Программное Обеспечение является бесплатным только на стадии разработки, пользователь имеет право бесплатного пользования только версиями данного Программное Обеспечение без пометки "final" и "release".Запрещено продавать копии, а также модернизированные версии данного Програмного Обеспечения
Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.

ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ ГАРАНТИИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ, НО НЕ ОГРАНИЧИВАЯСЬ ИМИ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО КАКИМ-ЛИБО ИСКАМ, ЗА УЩЕРБ ИЛИ ПО ИНЫМ ТРЕБОВАНИЯМ, В ТОМ ЧИСЛЕ, ПРИ ДЕЙСТВИИ КОНТРАКТА, ДЕЛИКТЕ ИЛИ ИНОЙ СИТУАЦИИ, ВОЗНИКШИМ ИЗ-ЗА ИСПОЛЬЗОВАНИЯ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫХ ДЕЙСТВИЙ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ. */

class Home extends UMC_Controller {

    function __construct(){
        parent::__construct();
        $this->load->helper('file');
    }
    
	public function index(){   
        $this->lang->load('user');
        $this->load->model('usermodel');
        $this->load->helper('header');
        
        $data['user_id'] = ($this->uri->segment(2)) ? $this->uri->segment(2) : $this->session->userdata('id');
        $editop = $this->session->userdata('id');
        $group = $this->usermodel->get_group_from_user($data['user_id']);
        
        if(empty($group)) show_404('page');
        $data['perms'] = $this->usermodel->perms;
        
        if($editop != $data['user_id']){
            if(!$data['perms']->{$group->unique}) redirect('error403');
        }
        $data['editop'] = $editop;
        $data['fields'] = $this->usermodel->get_fields($group->group_id, $data['user_id']);
        $data['group'] = $group;
        if($data['perms'] ->control_user){
            $data['groups'] = $this->usermodel->get_groups();
        }
        $data['user_status'] = $this->usermodel->get_user_status($data['user_id']);
        $data['count_field_cell'] = $this->usermodel->get_count_fields_cell($group->group_id);
        get_header($this);
        $this->load->view('user/user', $data);
        get_footer($this);
	}
    
    public function get_fields_edit_form(){
        $this->lang->load('user');
        $this->load->model('usermodel');
        $user_id = $this->input->post('user_id');
        $editop = $this->session->userdata('id');
        $group = $this->usermodel->get_group_from_user($user_id);
        $perms = $this->usermodel->perms;
        if($editop != $user_id){
            if(!$perms->{$group->unique}) $this->load->view('errors/error403');
        }
        
        $data['perms'] = $perms;
        $data['user_id'] = $user_id;
        $data['fields'] = $this->usermodel->get_fields($group->group_id, $data['user_id']);
        
        $this->load->view('user/edit_fields', $data);
    }
    
    public function edit_user_fields(){
        $this->lang->load('user');
        $this->load->model('usermodel');
        $post = $this->input->post();
        $user_id = $post['user_id'];
        
        $group = $this->usermodel->get_group_from_user($user_id);
        $fields = $this->usermodel->get_fields($group->group_id, $user_id);

        $required = 0;
        foreach($fields['not_cell']['fields_list'] as $val){ 
            if($val->required){
                $this->form_validation->set_rules($val->unique, $val->name, 'required');
                $required++;
            }
        }

        if ($required && $this->form_validation->run() == FALSE){
            echo json_encode(array('error' => TRUE, 'mess' => validation_errors()));
        }else{
            unset($post['user_id']);
            if(!$this->get_group_perms($user_id)){
                echo json_encode(array('error' => TRUE, 'mess' => lang('permission_denied')));
            }else{
                $href = $post['href'];
                unset($post['href']);
                if($this->usermodel->save_data_fields($post, $user_id)){
                    echo json_encode(array('error' => false));
                    //отправим уведомление
                    $this->send_notice_mail('edit_fields', $user_id);
                    $this->send_notice_vk('edit_fields', $user_id, $group);
                }
            }
        }
        return false;
    }
    
    private function get_group_perms($user_id){
        $unique = $this->usermodel->get_group_unique($user_id);
        if($this->usermodel->perms->$unique || $user_id == $this->session->userdata('id')){
            return true;
        }
        return false;
    }
    
    public function get_new_cell_form(){
        $this->lang->load('user');
        $this->load->model('usermodel');
        $user_id = $this->input->post('user_id');
        $editop = $this->session->userdata('id');
        $group = $this->usermodel->get_group_from_user($user_id);
        $perms = $this->usermodel->perms;
        if($editop != $user_id){
            if(!$perms->{$group->unique}) $this->load->view('errors/error403');
        }
        
        $data['perms'] = $perms;
        $data['user_id'] = $user_id;
        $data['fields'] = $this->usermodel->get_fields($group->group_id, $data['user_id']);
        $data['group'] = $group;
        $this->load->view('user/new_cell', $data);
    }
    
    public function new_cell(){
        $this->load->model('usermodel');
        $this->lang->load('user');
        $perms = $this->usermodel->perms;
        if(!$perms->control_cell){
            echo json_encode(array('error' => TRUE, 'mess' => lang('permission_denied')));
        }else{
        
            $post = $this->input->post();
            $user_id = $post['user_id'];
            unset($post['user_id']);
            $editop = $this->session->userdata('id');
            $group = $this->usermodel->get_group_from_user($user_id);
            
            $fields = $this->usermodel->get_fields($group->group_id, $user_id);
            
            $this->form_validation->set_rules('cell_name', lang('cell_name'), 'required');
            
            foreach($fields['in_cell']['fields_list'] as $val){ 
                if($val->required){
                    $this->form_validation->set_rules($val->unique, $val->name, 'required');
                }
            }

            if ($this->form_validation->run() == FALSE){
                echo json_encode(array('error' => TRUE, 'mess' => validation_errors()));
            }else{
                if(empty($group)) show_404('page');
                if($editop != $user_id){
                    if(!$perms->{$group->unique}){ 
                        echo json_encode(array('error' => TRUE, 'mess' => lang('permission_denied'))); 
                        return;
                    }
                }
                $cell_name = $post['cell_name'];
                unset($post['cell_name']);
                preg_match_all('|\{([^}]+)\}|i', $cell_name, $cell_nf);
                foreach($cell_nf[0] as $key => $val){
                    $cell_name = str_replace($val, $post[$cell_nf[1][$key]], $cell_name);
                }
                $href = $post['href'];
                unset($post['href']);
                if($this->usermodel->insert_new_cell($post, $cell_name, $user_id, $group)){
                    echo json_encode(array('error' => false));
//                    отправим уведомление
                    $this->send_notice_mail('new_cell', $user_id);
                    $this->send_notice_vk('new_cell', $user_id, $group);
                    $this->send_notice_mail_sotr('cell', $user_id, $group);
                }
            }
        }
        return;
    }
    
    public function get_cell_edit_form(){
        
        $this->lang->load('user');
        $this->load->model('usermodel');
        
        $cell_id = $this->input->post('id');
        $user_id = $this->input->post('user_id');
        $editop = $this->session->userdata('id');
        $group = $this->usermodel->get_group_from_user($user_id);
        $perms = $this->usermodel->perms;
        if($editop != $user_id){
            if(!$perms->{$group->unique}) $this->load->view('errors/error403');
        }
        
        $data['perms'] = $perms;
        $data['user_id'] = $user_id;
        $data['cell_id'] = $cell_id;
        $data['fields'] = $this->usermodel->get_fields_cell($group->group_id, $user_id, $cell_id);
        
        $this->load->view('user/edit_cell', $data);
    }
    
    public function edit_cell(){
        $this->load->model('usermodel');

        $editop = $this->session->userdata('id');
        $post = $this->input->post();
        $perms = $this->usermodel->perms;
            
        $cell_name = $post['cell_name'];
        $user_id = $post['user_id'];
        $cell_id = $post['cell_id'];
        $group = $this->usermodel->get_group_from_user($user_id);
        $fields = $this->usermodel->get_fields_cell($group->group_id, $user_id, $cell_id);    
        
        $this->form_validation->set_rules('cell_name', lang('cell_name'), 'required');
        
        foreach($fields['fields_data'] as $key => $val){ 
            if(isset($fields['fields_list'][$key]) && $fields['fields_list'][$key]->required){
                $this->form_validation->set_rules($fields['fields_list'][$key]->unique, $fields['fields_list'][$key]->name, 'required');
            }
        }
        
        if ($this->form_validation->run() == FALSE){
            echo json_encode(array('error' => TRUE, 'mess' => validation_errors()));
        }else{

            unset($post['cell_name']);
            unset($post['user_id']);
            unset($post['cell_id']);
            
            $group = $this->usermodel->get_group_from_user($user_id);
            
            if($editop != $user_id){
                if(!$perms->{$group->unique}) redirect('error403');
            }
            preg_match_all('|\{([^}]+)\}|i', $cell_name, $cell_nf);
                foreach($cell_nf[0] as $key => $val){
                    $cell_name = str_replace($val, $post[$cell_nf[1][$key]], $cell_name);
                }
            if($this->usermodel->update_cell($post, $cell_name, $cell_id, $user_id)){
                echo json_encode(array('error' => FALSE));
                //отправим уведомление
                $this->send_notice_mail('edit_fields', $user_id);
                $this->send_notice_vk('edit_fields', $user_id, $group);
            }
        }       
    }
    
    public function remove_cell(){
        
        $this->load->model('usermodel');
        
        $perms = $this->usermodel->perms;
        if(!$perms->control_cell) redirect('error403');
        
        $cell_id = $this->input->post('id');
        $user_id = $this->input->post('user_id');
        $editop = $this->session->userdata('id');
        $group = $this->usermodel->get_group_from_user($user_id);
        
        if($editop != $user_id){
            if(!$perms->{$group->unique}) redirect('error403');
        }
        if($res = $this->usermodel->delete_cell($cell_id, $user_id)){
            print_r($res);
            //отправим уведомление
            $this->send_notice_mail('delete_cell', $user_id);
            $this->send_notice_vk('delete_cell', $user_id, $group);
        }
    }
    
    public function remove_user(){
        
        $this->lang->load('user');
        $this->load->model('usermodel');
        $user_id = $this->input->post('user_id');
        $group = $this->usermodel->get_group_from_user($user_id);

        if($user_id == $this->session->userdata('id') 
            || !$this->usermodel->perms->control_user
            || !$this->usermodel->perms->{$group->unique}){
                echo json_encode(array('mess' => lang('remove_user_false'), 'error' => true));exit();
            } 
            
            
        if($this->usermodel->delete_user($user_id)){
            echo json_encode(array('mess' => lang('remove_user_true'), 'error' => false));
        }else{
            echo json_encode(array('mess' => lang('remove_user_false'), 'error' => true));
        }
    }
    
    public function get_user_autocomplete(){
        $text = $this->input->post('text');
        $limit = $this->input->post('limit');
        $group_id = $this->input->post('group_id');
        $this->load->model('usermodel');
        $group = $this->usermodel->get_group($group_id);
        $data = array();
        if($group_id == 0 || $this->usermodel->perms->{$group->unique}){
            $data = $this->usermodel->get_user_auto($text, $limit, $group_id);
        }
        echo json_encode($data);
    }
    
    public function get_edit_params_form(){
        $this->lang->load('user');
        $this->load->model('usermodel');
        
        $data['user_id'] = $this->input->post('user_id');
        $editop = $this->session->userdata('id');
        $group = $this->usermodel->get_group_from_user($data['user_id']);
        
        if(empty($group)) show_404('page');
        $data['perms'] = $this->usermodel->perms;
        if($data['perms'] ->control_user){
            $data['groups'] = $this->usermodel->get_groups();
        }
        if($editop != $data['user_id']){
            if(!$data['perms']->{$group->unique}) redirect('error403');
        }
        $data['group'] = $group;
        $data['user'] = $this->usermodel->get_username($data['user_id']);
        $this->load->view('user/edit_params', $data);
    }
    
    public function edit_params_user(){
        $this->load->model('usermodel');
        
        $group_id = $this->input->post('group_id');
        $user_id = $this->input->post('user_id');
        $pass = $this->input->post('new_pass');
        $vk_id = $this->input->post('vk_id');
        $username = $this->input->post('new_login');
        $group = $this->usermodel->get_group($group_id);
        $perms = $this->usermodel->perms;
        $editop = $this->session->userdata('id');
        $group_user = $this->usermodel->get_group_from_user($user_id);
        $href = $this->input->post('href');
        $array = array();
        
        if($perms->{$group->unique} && $perms->{$group_user->unique} && $perms->control_user){
            $array['group_id'] = $group_id;
        }
        
        if($editop == $user_id || $perms->control_user){
            if($pass){
                $array['password'] =  $this->hashPassword($pass, $this->usermodel->db->md5_key);
            }
            $array['vk_id'] = $vk_id;
        }

        $res = $this->usermodel->get_isset_username($username);
        if(!isset($res->id)){
            $array['username'] = $username;
        }

        if(count($array) && ($editop == $user_id || $perms->control_user)){
            $this->usermodel->save_user($array, $user_id);
        }
        
        redirect($href);   
    }
    
    private function hashPassword($password, $key){
        $salt = md5(uniqid($this->db->md5_key, true));
        $salt = substr(strtr(base64_encode($salt), '+', '.'), 0, 22);
        return crypt($password, '$2a$08$' . $salt);
    }
    
    public function add_comment(){
        $this->load->model('usermodel');
        
        $cell_id = $this->input->post('cell_id');
        $user_id = $this->input->post('user_id');
        $comment = $this->input->post('comment_text');
        
        $group = $this->usermodel->get_group_from_user($user_id);
        $autor = $this->session->userdata('id');
        if($autor != $user_id){
            if(!$this->usermodel->perms->{$group->unique}) redirect('error403');
        }
        
        if($this->usermodel->insert_comment($cell_id, $user_id, $comment, $autor)){
            //отправим уведомление
            $this->send_notice_mail('add_comment', $user_id, $comment);
            $this->send_notice_vk('add_comment', $user_id, $group);
        }
        redirect('/home/' . $user_id);
    }
    
    public function edit_comment(){
        $this->load->model('usermodel');
        
        $user_id = $this->input->post('user_id');
        $comment = $this->input->post('comment_text');
        $comment_id = $this->input->post('comment_id');
        
        $autor = $this->session->userdata('id');
        
        $comment_autor = $this->usermodel->get_comment_autor($comment_id);
        $group = $this->usermodel->get_group_from_user($user_id);
        if($comment_autor->autor == $autor){
            if($this->usermodel->update_comment($comment, $comment_id)){
                //отправим уведомление
                $this->send_notice_mail('edit_comment', $user_id, $comment);
                $this->send_notice_vk('edit_comment', $user_id, $group);
            }
        }
        redirect('/home/' . $user_id);
    }
    
    public function remove_comment(){
        $this->load->model('usermodel');
        
        $user_id = $this->input->post('user_id');
        $comment_id = $this->input->post('id');
        
        $autor = $this->session->userdata('id');
        
        $comment_autor = $this->usermodel->get_comment_autor($comment_id);
        $group = $this->usermodel->get_group_from_user($user_id);
        if($comment_autor->autor == $autor){
            if($this->usermodel->delete_comment($comment_id)){
                //отправим уведомление
                $this->send_notice_mail('comments_delete', $user_id);
                $this->send_notice_vk('comments_delete', $user_id, $group);
            }
        }
        
        print_r('ok');
    }
    
    public function upload_file(){
        $this->load->model('usermodel');
        
        $cell_id = $this->input->post('cell_id');
        $user_id = $this->input->post('user_id');
        $autor = $this->session->userdata('id');        
        
        $group = $this->usermodel->get_group_from_user($user_id);

        if($autor != $user_id){
            if(!$this->usermodel->perms->{$group->unique}) redirect('error403');
        }
        
        $files_folder = FCPATH . 'files/';
        if(!is_dir($files_folder)){
            mkdir($files_folder, 0777);
        }

        $config['upload_path'] = $files_folder;
        $config['allowed_types'] = 'gif|jpg|jpeg|png|psd|txt|csv|pdf|gzip|zip|rar|doc|docx|xlsx|word|xl|cdr';
        $config['max_size']    = '0';
        $config['encrypt_name']    = TRUE;

        $this->load->library('upload', $config);
        
        
        if ( ! $this->upload->do_upload('file'))
        {
            $mess[] = array('type' => 'error', 'message' => $this->upload->display_errors('',''));
            $this->session->set_userdata('mess', $mess);
            redirect('/home/' . $user_id);
        }
        else
        {
            $file = $this->upload->data();
            
            $data['user_id'] = $user_id;
            $data['cell_id'] = $cell_id;
            $data['autor'] = $autor;
            $data['file_name'] = $file['file_name'];
            $data['file_type'] = $file['file_type'];
            $data['orig_name'] = $file['orig_name'];
            $data['file_ext'] = $file['file_ext'];
            $data['file_size'] = $file['file_size'];
            $data['is_image'] = ($file['is_image']) ? '1' : '0';

            if($this->usermodel->insert_file($data)){                
                //отправим уведомление
                $this->send_notice_mail('upload_files', $user_id);
                $this->send_notice_vk('upload_files', $user_id, $group);                    
            }
            redirect('/home/' . $user_id);
        }
    }
    
    public function download_file(){
        $this->load->model('usermodel');
        
        $file_id = $this->input->get('id');
        $user_id = $this->input->get('user_id');
        $autor = $this->session->userdata('id');        
        $group = $this->usermodel->get_group_from_user($user_id);

        if($autor != $user_id){
            if(!$this->usermodel->perms->{$group->unique}) redirect('error403');
        }
        
        $file = $this->usermodel->get_file($file_id);
        $file_path = FCPATH . 'files/' . $file->file_name;
        if (file_exists($file_path)) {
            if (ob_get_level()) {
              ob_end_clean();
            }
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file->orig_name));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_path));
            if ($fd = fopen($file_path, 'rb')) {
              while (!feof($fd)) {
                print fread($fd, 1024);
              }
              fclose($fd);
            }
            exit;
        }
        
    }
    
    public function remove_file(){
        $this->load->model('usermodel');
        
        $file_id = $this->input->post('id');
        $autor = $this->session->userdata('id');
        
        $file = $this->usermodel->get_file($file_id);

        if($file->autor == $autor){
            if(($res = $this->usermodel->delete_file($file_id))){
                unlink(FCPATH . 'files/' . $file->file_name);
            }
        } 
        print_r('ok');
    }
    
    public function isset_username(){
        $this->load->model('usermodel');
        $user_id = $this->input->post('user_id');
        $username = $this->input->post('username');
        $res = $this->usermodel->get_isset_username($username);
        if(isset($res->id)){
            if($user_id == $res->id){
                echo json_encode(array('issetUsername' => FALSE));
            }else{
                echo json_encode(array('issetUsername' => TRUE));
            }
                    
        }else{
            echo json_encode(array('issetUsername' => FALSE));
        }
        return;
    }
    
    private function send_notice_mail_sotr($type, $user_id, $group){
        $this->config->load('mail_config', TRUE);
        $config = $this->config->item('mail_config');
        if($config['send_system_messages'] && isset($config['mail_event_' . $type][$group->group_id]) ){
            $this->load->model('usermodel');
            $this->lang->load('email');
            $this->load->library('email');
            $this->email->init($config);
            foreach($config['mail_event_' . $type] as $key => $val){
                if($key == $group->group_id){
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
    
    private function send_notice_mail($type, $user_id, $message = ''){
        
        $this->config->load('mail_config', TRUE);
        $config = $this->config->item('mail_config');
        if($config['send_system_messages'] && $config['mail_' . $type] ){ 
            $this->load->model('usermodel');
            $this->lang->load('email');
            $this->load->library('email');
            $this->email->init($config);
            if($email = $this->usermodel->get_email_notice($user_id)){
                $body = @sprintf($this->lang->line($type . '_message_body'), $user_id, $user_id, $message);
                if($config['mailtype'] == 'html'){
                    $body = $this->email->get_system_template(array('message_body' => $body));
                }
                return $this->email->umc_system_send($email, lang($type . '_message_subject'), $body);
            }
        }
    }
    
    private function send_notice_vk($type, $user_id, $group){
        $this->config->load('vk_config', TRUE);
        $config = $this->config->item('vk_config');
        if($config['enable_vk_messages'] && $config['vk_' . $type] && $group->vk_id){
            $this->lang->load('vk');
            $this->load->library('vkapi', $config);
            $body = @sprintf($this->lang->line($type . '_message_body'), $user_id);
            return $this->vkapi->api('secure.sendNotification', array('uids' => $group->vk_id, 'message' => $body));
        }
    }
    
}
