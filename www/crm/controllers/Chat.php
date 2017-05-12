<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
  /*
    Copyright © 2015 г. Коротков Е.А.

Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми «Программное Обеспечение»), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение копий Программного Обеспечения, а также лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:

Запрещено изменять (модернизировать, удалять, скрывать любыми средствами) ссылку на Веб-сайт разработчика данного Програмного Обеспечения.
Данное Программное Обеспечение является бесплатным только на стадии разработки, пользователь имеет право бесплатного пользования только версиями данного Программное Обеспечение без пометки "final" и "release".Запрещено продавать копии, а также модернизированные версии данного Програмного Обеспечения
Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.

ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ ГАРАНТИИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ, НО НЕ ОГРАНИЧИВАЯСЬ ИМИ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО КАКИМ-ЛИБО ИСКАМ, ЗА УЩЕРБ ИЛИ ПО ИНЫМ ТРЕБОВАНИЯМ, В ТОМ ЧИСЛЕ, ПРИ ДЕЙСТВИИ КОНТРАКТА, ДЕЛИКТЕ ИЛИ ИНОЙ СИТУАЦИИ, ВОЗНИКШИМ ИЗ-ЗА ИСПОЛЬЗОВАНИЯ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫХ ДЕЙСТВИЙ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ.
  */
class Chat extends UMC_Controller{
    
    function __construct(){
        parent::__construct();
        $this->load->model('chatmodel', 'model', TRUE);
    }
    
    public function get_chat_users(){
        if($this->model->perms->control_chat){
            $opened = $this->input->post('opened');
            $autor = $this->session->id;
            $data['names'] = $this->model->get_names();
            $data['users'] = $this->model->get_users($data['names'], $opened, $autor);
            $data['messages'] = $this->model->get_messages($opened, $autor);
            $data['autor'] = $autor;
            $data['opened_all'] = ($opened == 0) ? 'chat_user_opened' : '';
            
            ob_start();
            $this->load->view('chat/users', $data);
            $users = ob_get_contents();
            ob_end_clean();
            
            ob_start();
            $this->load->view('chat/messages', $data);
            $messages = ob_get_contents();
            ob_end_clean();
            $new_m = $this->model->new_m;
            echo json_encode(array('users' => $users, 'messages' => $messages, 'new_m' => $new_m));
        }
    }
    
    public function send_mess(){
        if($this->model->perms->control_chat){
            $post = $this->input->post();
            $post['autor'] = $this->session->id;
            $data['messages'] = $this->model->insert_message($post);
            $data['autor'] = $this->session->id;
            $this->model->read_messages($post['user_id'], $data['autor']);
            ob_start();
            $this->load->view('chat/messages', $data);
            $messages = ob_get_contents();
            ob_end_clean();
            echo json_encode(array('messages' => $messages));
        }
    }
    
    public function read_mess(){
        if($this->model->perms->control_chat){
            $post = $this->input->post();
            $data['autor'] = $this->session->id;
            $data['messages'] = $this->model->get_messages($post['user_id'], $data['autor']);
            $this->model->read_messages($post['user_id'], $data['autor']);
            ob_start();
            $this->load->view('chat/messages', $data);
            $messages = ob_get_contents();
            ob_end_clean();
            echo json_encode(array('messages' => $messages));
        }
    }
    
    public function get_all_mess_form(){
        if($this->model->perms->control_chat){
            $data['groups'] = $this->model->get_groups();

            $this->load->view('chat/all_mess', $data);
        }
    }
    
    public function send_all_mess(){
        $post = $this->input->post();
        if($this->model->perms->control_chat){
            $post['autor'] = $this->session->id;
            $this->model->send_all_users($post);
        }
        redirect($post['href']);
    }
}
