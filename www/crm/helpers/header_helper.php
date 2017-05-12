<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* Copyright © 2015 г. Коротков Е.А.

Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми «Программное Обеспечение»), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение копий Программного Обеспечения, а также лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:

Запрещено изменять (модернизировать, удалять, скрывать любыми средствами) ссылку на Веб-сайт разработчика данного Програмного Обеспечения.
Данное Программное Обеспечение является бесплатным только на стадии разработки, пользователь имеет право бесплатного пользования только версиями данного Программное Обеспечение без пометки "final" и "release".Запрещено продавать копии, а также модернизированные версии данного Програмного Обеспечения
Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.

ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ ГАРАНТИИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ, НО НЕ ОГРАНИЧИВАЯСЬ ИМИ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО КАКИМ-ЛИБО ИСКАМ, ЗА УЩЕРБ ИЛИ ПО ИНЫМ ТРЕБОВАНИЯМ, В ТОМ ЧИСЛЕ, ПРИ ДЕЙСТВИИ КОНТРАКТА, ДЕЛИКТЕ ИЛИ ИНОЙ СИТУАЦИИ, ВОЗНИКШИМ ИЗ-ЗА ИСПОЛЬЗОВАНИЯ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫХ ДЕЙСТВИЙ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ. */

  function get_header($CI){
        
        $CI->lang->load('header');
        $segment = $CI->uri->segment(1);
        
        $data['mess'] = $CI->session->userdata('mess');
        $CI->session->unset_userdata('mess');
        
        if($segment == 'admin'){ // если мы в админке
            $s = $CI->uri->segment(2);
            $data['title'] = $CI->lang->line('admin_nav') . ' | ' . $CI->lang->line($segment . '_' . $s . '_title'); // $segment всегда админ, $s второй сегмент адреса
            $data['page_title'] = $CI->lang->line($segment . '_' . $s . '_page_title'); // $segment всегда админ, $s второй сегмент адреса
            $data['frontend_nav'] = $CI->lang->line($segment . '_frontend_nav'); 
            $data['groups_nav'] = $CI->lang->line($segment . '_groups_nav'); 
            $data['home_nav'] = $CI->lang->line($segment . '_home_nav');
            $data['logout_nav'] = $CI->lang->line($segment . '_logout_nav');
            $data['fields_nav'] = $CI->lang->line($segment . '_fields_nav');
            $data['css'][] = 'admin';
            $CI->load->view($segment . '/header', $data);
            
        }elseif($segment !== 'login'){ //или во фронте
        
            $CI->load->model('headersmodel');
            $data['groups'] = $CI->headersmodel->get_roups();
            $data['menu'] = $CI->headersmodel->get_menu();
            $data['isset_cons'] = $CI->headersmodel->get_in_cons($CI->session->userdata('id'));
            if($data['isset_cons']){
                $data['cons_count_new_messages'] = $CI->headersmodel->get_cons_new_mess($CI->session->userdata('id'));
            }
            $data['css'][] = 'front_' . $segment;
            $data['title'] = $CI->lang->line($segment . '_title');
            $data['control_chat'] = $CI->headersmodel->perms->control_chat;
            $data['control_chart'] = $CI->headersmodel->perms->control_chart;
            $CI->load->view('header', $data);
            if($CI->headersmodel->perms->control_chat && $CI->session->workmans == 1){
                $CI->load->view('chat/chat_body');
            }
        }
    } 
    
    function get_footer($CI){
        $CI->lang->load('footer');
        $CI->load->helper('fields_script');
        
        $data['datepicker'] = $CI->lang->line('datepicker');
        
        $segment = $CI->uri->segment(1);
        
        $admin = '';
        if($segment == 'admin'){
            $admin = $segment . '_';
            $segment = $CI->uri->segment(2);
        }
        $CI->config->load('vk_config', TRUE);
        $data['vk_params'] = $CI->config->item('vk_config');
        $data['js'][] = $admin . $segment;
        $data['segment'] = $CI->uri->segment(1);
        $CI->load->view('footer',$data);
    }

