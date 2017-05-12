<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* Copyright © 2015 г. Коротков Е.А.

Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми «Программное Обеспечение»), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение копий Программного Обеспечения, а также лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:

Запрещено изменять (модернизировать, удалять, скрывать любыми средствами) ссылку на Веб-сайт разработчика данного Програмного Обеспечения.
Данное Программное Обеспечение является бесплатным только на стадии разработки, пользователь имеет право бесплатного пользования только версиями данного Программное Обеспечение без пометки "final" и "release".Запрещено продавать копии, а также модернизированные версии данного Програмного Обеспечения
Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.

ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ ГАРАНТИИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ, НО НЕ ОГРАНИЧИВАЯСЬ ИМИ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО КАКИМ-ЛИБО ИСКАМ, ЗА УЩЕРБ ИЛИ ПО ИНЫМ ТРЕБОВАНИЯМ, В ТОМ ЧИСЛЕ, ПРИ ДЕЙСТВИИ КОНТРАКТА, ДЕЛИКТЕ ИЛИ ИНОЙ СИТУАЦИИ, ВОЗНИКШИМ ИЗ-ЗА ИСПОЛЬЗОВАНИЯ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫХ ДЕЙСТВИЙ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ. */

class ConsModel extends UMC_Model{
    
    function get_consultants(){
        $query = $this->db
                    ->select('cons_id, site_adress')
                    ->get('{PRE}consultant');
        return $query->result();
    }
    
    function get_cons_users_online($operator_id = 0, $cons_id = 0){
        $where = ($operator_id) ? ' AND `operator_id` = "' . (int)$operator_id . '"' : '' ;
        $where .= ($cons_id) ? ' AND `cons_id` = "' . (int)$cons_id . '"' : '' ;
        $sql = 'SELECT {PRE}cons_users.*, (SELECT COUNT(*) FROM {PRE}cons_messages WHERE {PRE}cons_messages.cons_user_id = {PRE}cons_users.cons_user_id AND new_message = "1") AS count_new_messages FROM {PRE}cons_users 
                        WHERE 
                            `last_time` > DATE_SUB(NOW(), INTERVAL ' . (int)$this->config->item('cons_online_time') . ' SECOND)' . $where . '
                        ORDER BY `umc_cons_open` DESC';
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    function get_user($id){
        $query = $this->db
                    ->join('{PRE}consultant', 'consultant.cons_id = cons_users.cons_id')
                    ->get_where('{PRE}cons_users', array('cons_user_id' => $id));
        $user = $query->row();
        if(!empty($user)){
            $query = $this->db
                        ->order_by('datetime', 'ASC')
                        ->get_where('{PRE}cons_messages', array('cons_user_id' => $id));
            $user->messages = $query->result();
            $query = $this->db
                        ->select('COUNT(*) AS count')
                        ->order_by('datetime', 'ASC')
                        ->get_where('{PRE}cons_messages', array('cons_user_id' => $id, 'new_message' => '1'));
            $user->new_messages = $query->row();
            return $user;
        }
        return FALSE;
    }
    
    function insert_message($user_id, $message){
        $this->db
                ->set('cons_user_id', $user_id)
                ->set('message', $message)
                ->set('from_to', '1')
                ->set('new_message', '0')
                ->insert('{PRE}cons_messages');
        $rows = $this->db->affected_rows();
        $this->db
            ->set('new_message', 0)
            ->where('cons_user_id', $user_id)
            ->update('{PRE}cons_messages');
        return $rows;
    }
    
    function update_status_messages($user_id){
        $this->db
            ->set('new_message', 0)
            ->where('cons_user_id', $user_id)
            ->update('{PRE}cons_messages');
        return $this->db->affected_rows();
    }
    
    function udate_user_name($user_id, $new_name){
        $this->db
            ->set('cons_user_name', $new_name)
            ->where('cons_user_id', $user_id)
            ->update('{PRE}cons_users');
        return $this->db->affected_rows();
    }
    
    function get_cons_new_mess($user_id){
        $sql = 'SELECT COUNT(cm.`new_message`) AS count FROM {PRE}cons_messages AS cm
                    LEFT JOIN {PRE}cons_users AS cu USING(`cons_user_id`)
                    WHERE 
                        cu.`operator_id` = "' . (int)$user_id . '" AND 
                        cm.`new_message` = "1"';
        $query = $this->db->query($sql);
        return $query->row(); 
    }
}
