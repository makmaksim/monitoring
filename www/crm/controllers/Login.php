<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Copyright © 2015 г. Коротков Е.А.

Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми «Программное Обеспечение»), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение копий Программного Обеспечения, а также лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:

Запрещено изменять (модернизировать, удалять, скрывать любыми средствами) ссылку на Веб-сайт разработчика данного Програмного Обеспечения.
Данное Программное Обеспечение является бесплатным только на стадии разработки, пользователь имеет право бесплатного пользования только версиями данного Программное Обеспечение без пометки "final" и "release".Запрещено продавать копии, а также модернизированные версии данного Програмного Обеспечения
Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.

ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ ГАРАНТИИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ, НО НЕ ОГРАНИЧИВАЯСЬ ИМИ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО КАКИМ-ЛИБО ИСКАМ, ЗА УЩЕРБ ИЛИ ПО ИНЫМ ТРЕБОВАНИЯМ, В ТОМ ЧИСЛЕ, ПРИ ДЕЙСТВИИ КОНТРАКТА, ДЕЛИКТЕ ИЛИ ИНОЙ СИТУАЦИИ, ВОЗНИКШИМ ИЗ-ЗА ИСПОЛЬЗОВАНИЯ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫХ ДЕЙСТВИЙ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ. */

class Login extends UMC_Controller {
    
    private $data = array();
    
    public function index(){

        $this->lang->load('login');
        
        $this->data['title'] = $this->lang->line('title');
        $this->data['page_title'] = $this->lang->line('page_title');
        $this->data['button_login'] = $this->lang->line('button_login');
        
        $this->load->view('login', $this->data);
    }
    
    public function auth(){
        
        $post = $this->input->post();
        $this->lang->load('login');
        if(!isset($post['loginname'])){
            $this->data['mess']  = array('type' => 'error', 'message' => lang('auth_no_data'));
            redirect('login');
        }
        if($post['loginname'] && $post['password']){
            $this->load->model('loginmodel', 'model', true);
            $auth = $this->model->auth($post);
            
            if(!empty($auth)){
                $this->model->insert_online($auth->id);
                $this->session->set_userdata((array)$auth);
                $this->session->set_userdata('guest', 1); 
                $this->session->set_userdata('workmans', $auth->workmans); 
                redirect('home');
            }else{
                $this->data['mess'] = array('type'=>'error', 'message'=>$this->lang->line('auth_no_user'));
            }
//           $ass =  'CREATE TRIGGER `delete_not_active` before insert ON `leb_users_online` FOR EACH ROW BEGIN  DELETE FROM `leb_users_online` WHERE `last_active` < DATE_SUB(NOW(), INTERVAL 2 HOUR);END';
        }else{
            $this->data['mess'] = array('type'=>'error', 'message'=>$this->lang->line('auth_no_data'));
        }
        $this->index();
    }
    
    public function logout(){
        $this->load->model('loginmodel', 'model', true);
        $this->model->set_user_offline($this->session->userdata('id'));
        $this->session->sess_destroy();
        redirect('home');
    }
}