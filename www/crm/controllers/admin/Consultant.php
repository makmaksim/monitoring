<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* Copyright © 2015 г. Коротков Е.А.

Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми «Программное Обеспечение»), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение копий Программного Обеспечения, а также лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:

Запрещено изменять (модернизировать, удалять, скрывать любыми средствами) ссылку на Веб-сайт разработчика данного Програмного Обеспечения.
Данное Программное Обеспечение является бесплатным только на стадии разработки, пользователь имеет право бесплатного пользования только версиями данного Программное Обеспечение без пометки "final" и "release".Запрещено продавать копии, а также модернизированные версии данного Програмного Обеспечения
Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.

ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ ГАРАНТИИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ, НО НЕ ОГРАНИЧИВАЯСЬ ИМИ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО КАКИМ-ЛИБО ИСКАМ, ЗА УЩЕРБ ИЛИ ПО ИНЫМ ТРЕБОВАНИЯМ, В ТОМ ЧИСЛЕ, ПРИ ДЕЙСТВИИ КОНТРАКТА, ДЕЛИКТЕ ИЛИ ИНОЙ СИТУАЦИИ, ВОЗНИКШИМ ИЗ-ЗА ИСПОЛЬЗОВАНИЯ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫХ ДЕЙСТВИЙ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ. */

class Consultant extends UMC_Controller{
    
    public function index(){
        $this->load->helper('header');
        $this->lang->load('consultant');
        
        $this->load->model('admin/consmodel', 'consmodel', TRUE);
        $cons_list = $this->consmodel->get_cons_list();
        $data['cons_list'] = array();
        foreach($cons_list as $cons){
            $cons->users_list = $this->consmodel->get_users_cons($cons->cons_id);
            $data['cons_list'][] = $cons;
        }
        
        get_header($this);
        $this->load->view('admin/consultant/consultant', $data);
        get_footer($this);
    }
    
    public function add_cons(){
        $this->lang->load('consultant');
        $this->load->model('admin/consmodel', 'consmodel', TRUE);
        
        $post = $this->input->post();

        $users = $post['user_id'];
        unset($post['user_id']);
        $post['api_key'] = $this->api_key();
        
        if($cons_id = $this->consmodel->insert_cons($post)){
            $this->consmodel->insert_users($users, $cons_id);
            echo json_encode(array('error' => FALSE));
        }else{
            echo json_encode(array('error' => TRUE, 'mess' => lang('error_save_mess')));
        }
        return;
    }
    
    public function edit_cons(){
        $this->lang->load('consultant');
        $mess = array();
        $post = $this->input->post();
        if(!isset($post['user_id'])){
            $mess[] = array('type' => 'error', 'message' => lang('error_not_users'));
        }
        
        foreach($post['user_id'] as $id){
            if(!$id){
                $mess[] = array('type' => 'error', 'message' => lang('error_select_user_in_list'));
            }
        }
        
        if(!empty($mess)){
            $this->session->set_userdata('mess', $mess);
            redirect('admin/consultant');
        }
        $users = $post['user_id'];
        $cons_id = $post['cons_id'];
        unset($post['user_id']);
        unset($post['cons_id']);

        $this->load->model('admin/consmodel', 'consmodel', TRUE);
        $this->consmodel->update_cons($post, $cons_id);
        $this->consmodel->insert_users($users, $cons_id);
        redirect('admin/consultant');
    }
    
    public function remove_cons(){
        $this->load->model('admin/consmodel', 'consmodel', TRUE);
        $id = $this->input->post('id');
        $this->consmodel->delete_cons($id);
        redirect('admin/consultant');
    }
    
    function get_api_key(){
        echo uniqid(rand(), TRUE);
    }
    
    function api_key(){
        return uniqid(rand(), TRUE);
    }
    
}
