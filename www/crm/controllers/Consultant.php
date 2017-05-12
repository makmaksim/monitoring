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
        $this->load->model('consmodel');
        
        $data['users_opened'] = array();
        
        if(isset($_COOKIE['cons_users_opened'])){
            foreach(unserialize($_COOKIE['cons_users_opened']) as $user){
                if(FALSE !== ($res = $this->consmodel->get_user($user)))
                    $data['users_opened'][] = $res;
            }
        }
        get_header($this);
        $this->load->view('consultant/consultant', $data);
        get_footer($this);
    }
    
    public function get_cons_users(){
        $this->lang->load('consultant');
        $this->load->model('consmodel');
        
        $consultants = $this->consmodel->get_consultants();
        
        $data['users_list'] = $this->consmodel->get_cons_users_online($this->session->userdata('id'));
        
        $data['users_list_all'] = array();        
        foreach($consultants as $cons){
            
            $data['users_list_all'][$cons->cons_id] = $cons;
            $data['users_list_all'][$cons->cons_id]->users = $this->consmodel->get_cons_users_online(0,$cons->cons_id);
            
        }

        $this->load->view('consultant/cons_users', $data);
    }
    
    public function get_user_inner(){
        $this->lang->load('consultant');
        $this->load->model('consmodel');
        
        $id = $this->input->post('user');
        
        if(isset($_COOKIE['cons_users_opened'])){
            $users_opened = unserialize($_COOKIE['cons_users_opened']);
            $users_opened[$id] = $id;
            setcookie('cons_users_opened', serialize(array_unique($users_opened)));
        }else{
            setcookie('cons_users_opened', serialize(array($id => $id)));
        }
        
        $data['user'] = $this->consmodel->get_user($id);

        $this->load->view('consultant/user_inner', $data);
    }
    
    public function send_message(){
        $user_id = $this->input->post('user');
        $message = $this->input->post('message');
        
        $this->load->model('consmodel');
        $user = $this->consmodel->get_user($user_id);
        if($user->operator_id == $this->session->userdata('id')){
            $this->consmodel->insert_message($user_id, $message);
        }
        $this->get_user_inner();
    }
    
    public function user_close(){
        $id = $this->input->post('id');
        $users_opened = unserialize($_COOKIE['cons_users_opened']);
        unset($users_opened[substr($id, 1)]);
        setcookie('cons_users_opened', serialize(array_unique($users_opened)));
        echo 'ok';
    }
    
    public function set_read_status_messages(){
        $this->load->model('consmodel');
        $user_id = $this->input->post('user');
        $user = $this->consmodel->get_user($user_id);
        if($user->operator_id == $this->session->userdata('id')){
            $res = $this->consmodel->update_status_messages($user_id);
            echo $res;
        }
        echo 'ok';
    }
    
    public function rename_user(){
        $this->load->model('consmodel');
        
        $user_id = $this->input->post('user');
        $new_name = $this->input->post('new_name');

        $user = $this->consmodel->get_user($user_id);
        if($user->operator_id == $this->session->userdata('id')){
            $this->consmodel->udate_user_name($user_id, $new_name);
            echo json_encode(array('error' => FALSE));
        }else{
            echo json_encode(array('error' => TRUE));
        }
    }
    
    public function get_cont_new_mess(){
        $this->load->model('consmodel');
        $count = $this->consmodel->get_cons_new_mess($this->session->userdata('id'));
        if($count->count){
            echo json_encode(array('newMess' => TRUE, 'count_new' => $count->count));
        }else{
            echo json_encode(array('newMess' => FALSE));
        }
    }
}
