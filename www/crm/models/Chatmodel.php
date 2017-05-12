<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* Copyright © 2015 г. Коротков Е.А.

Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми «Программное Обеспечение»), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение копий Программного Обеспечения, а также лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:

Запрещено изменять (модернизировать, удалять, скрывать любыми средствами) ссылку на Веб-сайт разработчика данного Програмного Обеспечения.
Данное Программное Обеспечение является бесплатным только на стадии разработки, пользователь имеет право бесплатного пользования только версиями данного Программное Обеспечение без пометки "final" и "release".Запрещено продавать копии, а также модернизированные версии данного Програмного Обеспечения
Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.

ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ ГАРАНТИИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ, НО НЕ ОГРАНИЧИВАЯСЬ ИМИ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО КАКИМ-ЛИБО ИСКАМ, ЗА УЩЕРБ ИЛИ ПО ИНЫМ ТРЕБОВАНИЯМ, В ТОМ ЧИСЛЕ, ПРИ ДЕЙСТВИИ КОНТРАКТА, ДЕЛИКТЕ ИЛИ ИНОЙ СИТУАЦИИ, ВОЗНИКШИМ ИЗ-ЗА ИСПОЛЬЗОВАНИЯ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫХ ДЕЙСТВИЙ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ. */

class ChatModel extends UMC_Model{
    
    public $new_m = FALSE;
    
    function get_names(){
        $sql = 'SELECT `unique` FROM {PRE}fields WHERE `type` = "text" AND `params` = "1"';
        return $this->db->query($sql)->result();
    }
    
    function get_users($names, $opened, $autor){
        
        $select = array();
        foreach($names as $field){
            $select[] = 'ud.`' . $field->unique . '`';
        }
        $groups = array();
        $sql = 'SELECT `group_id` FROM {PRE}group_perms WHERE `control_chat` = "1"';
        $res = $this->db->query($sql);
        foreach($res->result() as $row){
            $groups[] = '"' . (int)$row->group_id . '"';
        }
        
        
        $sql = 'SELECT 
                        ud.`user_id`, 
                        ' . implode(', ', $select) . ',
                        ug.`name`, 
                        ug.`group_id`, 
                        ug.`unique`, 
                        uo.`status`,
                        (SELECT COUNT(`read`) FROM {PRE}chat_messages WHERE `user_id` = "' . (int)$autor . '" AND `autor` = ud.`user_id` AND `read` = "0") AS `not_read`
                    FROM {PRE}users_data AS ud
                    LEFT JOIN {PRE}users AS us ON us.`id` = ud.`user_id`
                    LEFT JOIN {PRE}user_groups AS ug ON us.`group_id` = ug.`group_id`
                    LEFT JOIN {PRE}users_online AS uo ON us.`id` = uo.`user_id`
                    WHERE ug.`group_id` IN (' . implode(', ', $groups) . ') AND ud.`cell_id` = "0"
                    GROUP BY us.`id`
                    ORDER BY ug.`sort` ASC';

        $query = $this->db->query($sql);
        
        $users = array();
        foreach($query->result() as $row){
            if($this->perms->{$row->unique}){
                foreach($names as $field){
                    if($row->{$field->unique}){
                        $row->username = $row->{$field->unique};
                    }
                }
                if($opened && $opened == $row->user_id){
                    $row->opened = 'chat_user_opened';
                }else{
                    $row->opened = '';
                }
                
                if($row->not_read > 0){
                    if(isset($_COOKIE['chat_open']) && $_COOKIE['chat_open'] && $opened == $row->user_id){
                        $this->new_m = FALSE;
                        $this->db->set('read', '1')
                                 ->where('user_id', $autor)
                                 ->where('autor', $row->user_id)
                                 ->update('chat_messages');
                    }else{
                        $this->new_m = TRUE;
                    }
                    
                }
                $users[$row->group_id]['group_name'] = $row->name;
                $users[$row->group_id]['list'][] = $row;
                
            }
        }
        return $users;
    }
    
    function insert_message($post){
        $this->db->insert('chat_messages', $post);
        return $this->get_messages($post['user_id'], $post['autor']);
    }
    
    function get_messages($user_id, $autor){
        if($user_id == 0){
            return $this->db->where('chat_messages.user_id', '0')
                        ->where('users_data.cell_id', '0')
                        ->join('users_data', 'users_data.user_id = chat_messages.autor', 'left')
                        ->order_by('chat_messages.date', 'ASC')
                        ->get('chat_messages')
                        ->result();
        }else{
            return $this->db->where_in('chat_messages.user_id', array($user_id, $autor))
                        ->where_in('chat_messages.autor', array($user_id, $autor))
                        ->where('users_data.cell_id', '0')
                        ->join('users_data', 'users_data.user_id = chat_messages.autor', 'left')
                        ->order_by('date', 'ASC')
                        ->get('chat_messages')
                        ->result();
        }
        
    }
    
    function read_messages($user_id, $autor){
        $this->db->set('read', '1')
                 ->where('user_id', $autor)
                 ->where('autor', $user_id)
                 ->update('chat_messages');
        return TRUE;
    }
    
    function get_groups(){
        $sql = 'SELECT ug.* FROM {PRE}group_perms AS gp 
                    JOIN {PRE}user_groups AS ug USING(`group_id`)
                    WHERE gp.`control_chat` = "1"';
        $query = $this->db->query($sql);
        $groups = array();
        foreach($query->result() as $row){
            if($this->perms->{$row->unique}){
                $groups[] = $row;
            }
        }
        return $groups;
    }
    
    function send_all_users($post){
        $groups = array();
        
        foreach($this->get_groups() as $group){
            if(in_array($group->group_id, $post['groups'])){
                $groups[] = (int)$group->group_id;
            }
        }
        
        $users = $this->db->select('id')
                        ->where_in('group_id', $groups)
                        ->get('users')
                        ->result();

        foreach($users as $user){
            if($user->id != $post['autor']){
                $this->db->insert('chat_messages', array('autor' => $post['autor'], 'user_id' => $user->id, 'message' => $post['message']));
            }
        }
        return TRUE;
    }
}