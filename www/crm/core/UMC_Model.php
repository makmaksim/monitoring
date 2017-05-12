<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Copyright © 2015 г. Коротков Е.А.

Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми «Программное Обеспечение»), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение копий Программного Обеспечения, а также лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:

Запрещено изменять (модернизировать, удалять, скрывать любыми средствами) ссылку на Веб-сайт разработчика данного Програмного Обеспечения.
Данное Программное Обеспечение является бесплатным только на стадии разработки, пользователь имеет право бесплатного пользования только версиями данного Программное Обеспечение без пометки "final" и "release".Запрещено продавать копии, а также модернизированные версии данного Програмного Обеспечения
Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.

ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ ГАРАНТИИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ, НО НЕ ОГРАНИЧИВАЯСЬ ИМИ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО КАКИМ-ЛИБО ИСКАМ, ЗА УЩЕРБ ИЛИ ПО ИНЫМ ТРЕБОВАНИЯМ, В ТОМ ЧИСЛЕ, ПРИ ДЕЙСТВИИ КОНТРАКТА, ДЕЛИКТЕ ИЛИ ИНОЙ СИТУАЦИИ, ВОЗНИКШИМ ИЗ-ЗА ИСПОЛЬЗОВАНИЯ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫХ ДЕЙСТВИЙ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ. */

class UMC_Model extends CI_Model {

    public $perms;
    public function __construct(){
        $this->get_perms();
        $this->update_date_active_user();
    }
    private function get_perms(){
        $sql = 'SELECT * FROM ' . $this->db->dbprefix . 'group_perms WHERE `group_id` = "' . (int)$this->session->userdata('group_id') . '"';
        $query = $this->db->query($sql);
        $this->perms = $query->row();        
    }
    
    private function update_date_active_user(){
        $this->db
            ->set('last_active', 'NOW()', FALSE)
            ->set('status', '2')
            ->where('user_id', (int)$this->session->userdata('id'))
            ->update('{PRE}users_online');
    }
    
    public function format_data($field, $data){
        $result = '';
        switch($field->type){
            case 'list' :
            case 'checkbox' :
                $result = $data[$field->unique];
                break;
                
            case 'date' :
                $result = date( 'Y-m-d', strtotime($data[$field->unique]));
                break;
                
            case 'datetime' :
                $result = date( 'Y-m-d H:i:s', strtotime($data[$field->unique]));
                break;  
            case 'textarea' :
                $tags = '<p><a><br><br/><hr/><hr><div>';

                $result = $this->security->xss_clean(strip_tags($data[$field->unique], $tags));
                break;            
            default :
                $result = $this->security->xss_clean($data[$field->unique]);
                break;
                
        }
        return $result;
            
    }
    
    public function fomat_unique($unique){
        return preg_match('/^[a-z0-9]+$/', $unique);
    }

}
