<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Copyright © 2015 г. Коротков Е.А.

Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми «Программное Обеспечение»), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение копий Программного Обеспечения, а также лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:

Запрещено изменять (модернизировать, удалять, скрывать любыми средствами) ссылку на Веб-сайт разработчика данного Програмного Обеспечения.
Данное Программное Обеспечение является бесплатным только на стадии разработки, пользователь имеет право бесплатного пользования только версиями данного Программное Обеспечение без пометки "final" и "release".Запрещено продавать копии, а также модернизированные версии данного Програмного Обеспечения
Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.

ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ ГАРАНТИИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ, НО НЕ ОГРАНИЧИВАЯСЬ ИМИ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО КАКИМ-ЛИБО ИСКАМ, ЗА УЩЕРБ ИЛИ ПО ИНЫМ ТРЕБОВАНИЯМ, В ТОМ ЧИСЛЕ, ПРИ ДЕЙСТВИИ КОНТРАКТА, ДЕЛИКТЕ ИЛИ ИНОЙ СИТУАЦИИ, ВОЗНИКШИМ ИЗ-ЗА ИСПОЛЬЗОВАНИЯ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫХ ДЕЙСТВИЙ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ. */

class SystemParams extends UMC_Controller{
    
    public function index($tempalte = FALSE){
        
        $this->load->helper('header');
        $this->load->model('admin/fieldsmodel', 'model', TRUE);
        $this->lang->load('systemparams');
        $this->config->load('mail_config', TRUE);
        $data['umc_mail'] = $this->config->item('mail_config');
        $this->config->load('vk_config', TRUE);
        $data['vk_params'] = $this->config->item('vk_config');
        if(!$tempalte){
            $system_template = APPPATH . 'config/system_template.html';
            $data['system_template'] = file_get_contents($system_template);
        }else{
            $data['system_template'] = $tempalte;
        }
        $data['groups'] = $this->model->get_groups();
        get_header($this);
        $this->load->view('admin/params/params', $data);
        get_footer($this);
    }
    
    public function edit_email_system(){
        $this->lang->load('systemparams');
        $html = $this->input->post('email_template_system');
        $file_path = APPPATH . 'config/system_template.html';
        $fp = fopen($file_path, 'w+'); 
        $test = fwrite($fp, $html);
        fclose($fp);
        if(!$test){
            $mess[] = array('type' => 'error', 'message' => $this->lang->line('system_template_rec_error'));
            $this->session->set_userdata('mess', $mess);
        } 
        $this->index($html);
    }
    
    public function edit_email_params(){
        $post = $this->input->post();
        $this->lang->load('systemparams');
        $html = $this->mail_config_file($post);
        $file_path = APPPATH . 'config/mail_config.php';
        $fp = fopen($file_path, 'w+'); 
        $test = fwrite($fp, $html);
        fclose($fp);
        if(!$test){
            $mess[] = array('type' => 'error', 'message' => $this->lang->line('system_template_rec_error'));
            $this->session->set_userdata('mess', $mess);
        } 
        $this->index();
    }
    
    public function edit_vk_params(){
        $post = $this->input->post();
        $this->lang->load('systemparams');
        $html = $this->vk_config_file($post);
        $file_path = APPPATH . 'config/vk_config.php';
        $fp = fopen($file_path, 'w+'); 
        $test = fwrite($fp, $html);
        fclose($fp);
        if(!$test){
            $mess[] = array('type' => 'error', 'message' => $this->lang->line('system_template_rec_error'));
            $this->session->set_userdata('mess', $mess);
        } 
        $this->index();
    }
    
    public function mail_config_file($post){
        $html = '<?php
defined(\'BASEPATH\') OR exit(\'No direct script access allowed\');
    /* Copyright © 2015 г. Коротков Е.А.

Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми «Программное Обеспечение»), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение, сублицензирование и/или продажу копий Программного Обеспечения, а также лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:

Запрещено изменять (модернизировать, удалять, скрывать любыми средствами) нижнюю часть Программного Обеспечения (footer) в административной панели и на front-end
Данное Программное Обеспечение является бесплатным только на стадии разработки, пользователь имеет право бесплатного пользования только версиями данного Программное Обеспечение без пометки "final" и "release"
Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.

ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ ГАРАНТИИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ, НО НЕ ОГРАНИЧИВАЯСЬ ИМИ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО КАКИМ-ЛИБО ИСКАМ, ЗА УЩЕРБ ИЛИ ПО ИНЫМ ТРЕБОВАНИЯМ, В ТОМ ЧИСЛЕ, ПРИ ДЕЙСТВИИ КОНТРАКТА, ДЕЛИКТЕ ИЛИ ИНОЙ СИТУАЦИИ, ВОЗНИКШИМ ИЗ-ЗА ИСПОЛЬЗОВАНИЯ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫХ ДЕЙСТВИЙ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ. */

$config["send_system_messages"] = "' . $post['send_system_messages'] . '";
$config["mail_sender"] = "' . $post['mail_sender'] . '";
$config["smtp_user"] = "' . $post['smtp_user'] . '";
$config["protocol"] = "' . $post['protocol'] . '";
$config["mailtype"] = "' . $post['mailtype'] . '";
$config["smtp_pass"] = "' . $post['smtp_pass'] . '";
$config["smtp_crypto"] = "' . $post['smtp_crypto'] . '";
$config["smtp_host"] = "' . $post['smtp_host'] . '";
$config["smtp_port"] = "' . $post['smtp_port'] . '";
$config["mail_edit_fields"] = "' . $post['mail_edit_fields'] . '";
$config["mail_new_cell"] = "' . $post['mail_new_cell'] . '";
$config["mail_delete_cell"] = "' . $post['mail_delete_cell'] . '";
$config["mail_add_comment"] = "' . $post['mail_add_comment'] . '";
$config["mail_edit_comment"] = "' . $post['mail_edit_comment'] . '";
$config["mail_comments_delete"] = "' . $post['mail_comments_delete'] . '";
$config["mail_upload_files"] = "' . $post['mail_upload_files'] . '";
';
//        var_dump($post['user_group_from']);die();
        if(!empty($post['user_group_from'])){
            $html .= '$config["mail_event_user"] = array(';
            $arr = array();
            foreach($post['user_group_from'] as $key => $val){
                $arr[] = $val . ' => ' . $post['user_group_to'][$key];
            }
            $html .= implode(',', $arr);
            $html .= ');
';
        }else{
            $html .= '$config["mail_event_user"] = array();
';
        }
        
        if(!empty($post['cell_group_from'])){
            $html .= '$config["mail_event_cell"] = array(';
            $arr = array();
            foreach($post['cell_group_from'] as $key => $val){
                $arr[] = $val . ' => ' . $post['cell_group_to'][$key];
            }
            $html .= implode(',', $arr);
            $html .= ');
';
        }else{
            $html .= '$config["mail_event_cell"] = array();
';
        }
        
        return $html;
    }
    
    public function vk_config_file($post){
        $html = '<?php
defined(\'BASEPATH\') OR exit(\'No direct script access allowed\');
    /* Copyright © 2015 г. Коротков Е.А.

Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми «Программное Обеспечение»), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение, сублицензирование и/или продажу копий Программного Обеспечения, а также лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:

Запрещено изменять (модернизировать, удалять, скрывать любыми средствами) нижнюю часть Программного Обеспечения (footer) в административной панели и на front-end
Данное Программное Обеспечение является бесплатным только на стадии разработки, пользователь имеет право бесплатного пользования только версиями данного Программное Обеспечение без пометки "final" и "release"
Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.

ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ ГАРАНТИИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ, НО НЕ ОГРАНИЧИВАЯСЬ ИМИ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО КАКИМ-ЛИБО ИСКАМ, ЗА УЩЕРБ ИЛИ ПО ИНЫМ ТРЕБОВАНИЯМ, В ТОМ ЧИСЛЕ, ПРИ ДЕЙСТВИИ КОНТРАКТА, ДЕЛИКТЕ ИЛИ ИНОЙ СИТУАЦИИ, ВОЗНИКШИМ ИЗ-ЗА ИСПОЛЬЗОВАНИЯ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫХ ДЕЙСТВИЙ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ. */
$config["api_id"] = "' . $post['api_id'] . '";
$config["secret_key"] = "' . $post['secret_key'] . '";
$config["enable_vk_messages"] = "' . $post['enable_vk_messages'] . '";
$config["vk_edit_fields"] = "' . $post['vk_edit_fields'] . '";
$config["vk_new_cell"] = "' . $post['vk_new_cell'] . '";
$config["vk_delete_cell"] = "' . $post['vk_delete_cell'] . '";
$config["vk_add_comment"] = "' . $post['vk_add_comment'] . '";
$config["vk_edit_comment"] = "' . $post['vk_edit_comment'] . '";
$config["vk_comments_delete"] = "' . $post['vk_comments_delete'] . '";
$config["vk_upload_files"] = "' . $post['vk_upload_files'] . '";
';
        return $html;
    }
}