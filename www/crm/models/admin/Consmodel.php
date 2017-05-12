<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* Copyright © 2015 г. Коротков Е.А.

Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми «Программное Обеспечение»), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение копий Программного Обеспечения, а также лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:

Запрещено изменять (модернизировать, удалять, скрывать любыми средствами) ссылку на Веб-сайт разработчика данного Програмного Обеспечения.
Данное Программное Обеспечение является бесплатным только на стадии разработки, пользователь имеет право бесплатного пользования только версиями данного Программное Обеспечение без пометки "final" и "release".Запрещено продавать копии, а также модернизированные версии данного Програмного Обеспечения
Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.

ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ ГАРАНТИИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ, НО НЕ ОГРАНИЧИВАЯСЬ ИМИ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО КАКИМ-ЛИБО ИСКАМ, ЗА УЩЕРБ ИЛИ ПО ИНЫМ ТРЕБОВАНИЯМ, В ТОМ ЧИСЛЕ, ПРИ ДЕЙСТВИИ КОНТРАКТА, ДЕЛИКТЕ ИЛИ ИНОЙ СИТУАЦИИ, ВОЗНИКШИМ ИЗ-ЗА ИСПОЛЬЗОВАНИЯ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫХ ДЕЙСТВИЙ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ. */

class ConsModel extends UMC_Model{
    
    function insert_cons($post){
        $this->db->insert('{PRE}consultant', $post);
        return $this->db->insert_id();
    }
    
    function insert_users($users, $cons_id){
        $this->db
            ->where('cons_id', (int)$cons_id)
            ->delete('{PRE}consultant_operator');
        $set = array();
        foreach($users as $user){
            $set[] = ' ("' . (int)$cons_id . '", "' . $user . '") ';
        }
        
        $sql = 'INSERT INTO {PRE}consultant_operator (`cons_id`, `user_id`) VALUES ' . implode(',', $set);
        $this->db->query($sql);
        return $this->db->affected_rows();
    }
    
    function update_cons($post, $cons_id){
        $this->db
            ->set($post)
            ->where('cons_id', $cons_id)
            ->update('{PRE}consultant');
        return $this->db->affected_rows();
    }
    
    function get_cons_list(){
        $query = $this->db->get('{PRE}consultant');
        return $query->result();
    }
    
    function get_users_cons($cons_id){
        $sql = 'SELECT `unique` FROM {PRE}fields 
                         WHERE 
                            `type` = "text" AND 
                            `params` = "1"
                        GROUP BY `field_id`';
        $query = $this->db->query($sql);
        $fields = $query->result(); 
        $select = array();
        foreach($fields as $row){
            if($this->perms->{$row->unique . '_read'}){
                $select[] = 'ud.`' . $row->unique . '`';
            }
        }
        $sql = 'SELECT ' . implode(', ', $select) . ', ud.`user_id` FROM {PRE}users_data AS ud
                    LEFT JOIN {PRE}consultant_operator USING(`user_id`)
                    WHERE `cons_id` = "' . (int)$cons_id . '"
                    GROUP BY ud.`user_id`';

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
        return $data;
    }
    
    function delete_cons($id){
        $this->db->delete('{PRE}consultant', array('cons_id' => (int)$id)); 
        return $this->db->affected_rows();
    }
    
}
