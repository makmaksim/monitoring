<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* Copyright © 2015 г. Коротков Е.А.

Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми «Программное Обеспечение»), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение копий Программного Обеспечения, а также лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:

Запрещено изменять (модернизировать, удалять, скрывать любыми средствами) ссылку на Веб-сайт разработчика данного Програмного Обеспечения.
Данное Программное Обеспечение является бесплатным только на стадии разработки, пользователь имеет право бесплатного пользования только версиями данного Программное Обеспечение без пометки "final" и "release".Запрещено продавать копии, а также модернизированные версии данного Програмного Обеспечения
Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.

ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ ГАРАНТИИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ, НО НЕ ОГРАНИЧИВАЯСЬ ИМИ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО КАКИМ-ЛИБО ИСКАМ, ЗА УЩЕРБ ИЛИ ПО ИНЫМ ТРЕБОВАНИЯМ, В ТОМ ЧИСЛЕ, ПРИ ДЕЙСТВИИ КОНТРАКТА, ДЕЛИКТЕ ИЛИ ИНОЙ СИТУАЦИИ, ВОЗНИКШИМ ИЗ-ЗА ИСПОЛЬЗОВАНИЯ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫХ ДЕЙСТВИЙ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ. */

class ConsapiModel extends UMC_Model{
    
    function get_consultant($id){
        $query = $this->db->get_where('{PRE}consultant', array('cons_id' => (int)$id), 1);
        return $query->row();
    }
    
    function insert_new($post, $operator){
        $operator_id = $operator['user_id'];
        unset($operator['user_id']);
        $sql = 'INSERT INTO {PRE}cons_users 
                        SET 
                            `cons_id` = ' . (int)$post['umc_id'] . ',
                            `operator_id` = ' . (int)$operator_id . ',
                            `operator_data` = ' . $this->db->escape(serialize($operator)) . ',
                            `cons_user_id` = ' . $this->db->escape($post['umc_user']) . ',
                            `cons_user_geo` = ' . $this->db->escape($post['user_geo']) . ',
                            `first_time` = NOW(),
                            `last_time` = NOW(),
                            `umc_cons_open` = "' . (int)$post['umc_cons_open'] . '",
                            `url` = ' . $this->db->escape($post['umc_last_url']) . ',
                            `url_history` = ' . $this->db->escape($post['umc_last_url']) . '
                         
                    ON DUPLICATE KEY 
                        UPDATE
                            `cons_id` = ' . (int)$post['umc_id'] . ',
                            `cons_user_geo` = ' . $this->db->escape($post['user_geo']) . ',
                            `first_time` = NOW(),
                            `last_time` = NOW(),
                            `umc_cons_open` = "' . (int)$post['umc_cons_open'] . '",
                            `url` = ' . $this->db->escape($post['umc_last_url']) . ',
                            `url_history` = ' . $this->db->escape($post['umc_last_url']);
        $this->db->query($sql);
        return $this->db->affected_rows();
    }
    
    function update_old($post){
        $query = $this->db->get_where('{PRE}cons_users', array('cons_user_id' => $_POST['umc_user']));
        $user = $query->row();
        if($user){ // на случай если базу почистили, а кука существует
            $sql = 'UPDATE {PRE}cons_users 
                            SET 
                                `last_time` = NOW(),
                                `umc_cons_open` = "' . (int)$post['umc_cons_open'] . '",
                                `url` = ' . $this->db->escape($post['umc_last_url']) . ',
                                `url_history` = CONCAT(`url_history`, ' . $this->db->escape( '|' . $post['umc_last_url']) . ')
                            WHERE `cons_user_id` = ' . $this->db->escape($post['umc_user']);
            $this->db->query($sql);
        }else{
            $operator = $this->get_operator($post['umc_id']);
            $operator_id = $operator['user_id'];
            unset($operator['user_id']);
            $sql = 'INSERT INTO {PRE}cons_users 
                        SET 
                            `cons_id` = ' . (int)$post['umc_id'] . ',
                            `operator_id` = ' . (int)$operator_id . ',
                            `operator_data` = ' . $this->db->escape(serialize($operator)) . ',
                            `cons_user_id` = ' . $this->db->escape($post['umc_user']) . ',
                            `cons_user_geo` = ' . $this->db->escape($post['user_geo']) . ',
                            `first_time` = NOW(),
                            `last_time` = NOW(),
                            `umc_cons_open` = "' . (int)$post['umc_cons_open'] . '",
                            `url` = ' . $this->db->escape($post['umc_last_url']) . ',
                            `HTTP_USER_AGENT` = ' . $this->db->escape($post['HTTP_USER_AGENT']) . ',
                            `url_history` = ' . $this->db->escape($post['umc_last_url']);
        $this->db->query($sql);
        }
        return $this->db->affected_rows();
    }
    
    // ищем случаного оператора
    function get_operator($cons_id){
        $sql = 'SELECT `unique` FROM {PRE}fields 
                         WHERE 
                            `type` = "text" AND 
                            `params` = "1"
                        GROUP BY `field_id`';
        $query = $this->db->query($sql);
        $fields = $query->result(); 
        $select = array();

        foreach($fields as $row){
            $select[] = 'ud.`' . $row->unique . '`';
        }
        
        $sql = 'SELECT ' . implode(', ', $select) . ', ud.`user_id` FROM {PRE}users_data AS ud
                    LEFT JOIN {PRE}consultant_operator AS co USING(`user_id`)  
                    LEFT JOIN {PRE}users_online AS uo USING(`user_id`)  
                    WHERE co.`cons_id` = "' . (int)$cons_id . '" AND uo.`status` = "2"
                    GROUP BY ud.`user_id` ';
        $query = $this->db->query($sql);
        $users = $query->result();
        $data = array();
        foreach($users as $row){
            foreach($fields as $val){
                if($row->{$val->unique}){
                    $arr['user_id'] = $row->user_id;
                    $arr['name'] = $row->{$val->unique};
                    $data[] = $arr;
                }
            }
        }
        
        $count = count($data);
        if($count <= 0){
            return false;
        }
        $user = $data[rand(0, $count-1)];

        return $user;
    }
    
//    данные для отправки модулю
    function get_cons_data($post){
        $query = $this->db
                    ->select('operator_id, operator_data')
                    ->get_where('{PRE}cons_users', array('cons_user_id' => $_POST['umc_user']));
        $operator = $query->row();
        // если оператор назначен
        if($operator){
            
    //        проверяем в сети ли оператор, если нет - назначаем нового
            $query = $this->db
                            ->where('user_id', $operator->operator_id)
                            ->get('{PRE}users_online');
            $res = $query->row();
            if($res && $res->status != 2){
                $data['operator'] = $this->get_new_operator($post);
            }else{
                $data['operator'] = unserialize($operator->operator_data);
            }
        }else{
//            если оператор не назначен - назначаем нового
            $data['operator'] = $this->get_new_operator($post);
        }
        
         
        $query = $this->db
                    ->order_by('datetime', 'ASC')
                    ->get_where('{PRE}cons_messages', array('cons_user_id' => $_POST['umc_user']));
        $data['messages'] = $query->result_array();
        return $data;
    }
    
    // назначение нового оператора
    
    function get_new_operator($post){
        $operator = $this->get_operator($post['umc_id']);
        if($operator){
            $operator_id = $operator['user_id'];
            unset($operator['user_id']);
            $sql = 'UPDATE {PRE}cons_users 
                            SET 
                               `operator_id` = "' . (int)$operator . '",
                               `operator_data` = ' . $this->db->escape(serialize($operator)) . '
                            WHERE `cons_user_id` = ' . $this->db->escape($post['umc_user']);
            $this->db->query($sql);
            return $operator;
        }else{
            return false;
        }
        return false;
    }
    
    function new_message($post){
        $this->db
            ->set('cons_user_id', $post['umc_user'])
            ->set('from_to', '0')
            ->set('message', $post['message'])
            ->insert('{PRE}cons_messages');
    }
    
}