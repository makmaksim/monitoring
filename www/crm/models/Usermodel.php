<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/* Copyright © 2015 г. Коротков Е.А.

Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми «Программное Обеспечение»), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение копий Программного Обеспечения, а также лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:

Запрещено изменять (модернизировать, удалять, скрывать любыми средствами) ссылку на Веб-сайт разработчика данного Програмного Обеспечения.
Данное Программное Обеспечение является бесплатным только на стадии разработки, пользователь имеет право бесплатного пользования только версиями данного Программное Обеспечение без пометки "final" и "release".Запрещено продавать копии, а также модернизированные версии данного Програмного Обеспечения
Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.

ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ ГАРАНТИИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ, НО НЕ ОГРАНИЧИВАЯСЬ ИМИ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО КАКИМ-ЛИБО ИСКАМ, ЗА УЩЕРБ ИЛИ ПО ИНЫМ ТРЕБОВАНИЯМ, В ТОМ ЧИСЛЕ, ПРИ ДЕЙСТВИИ КОНТРАКТА, ДЕЛИКТЕ ИЛИ ИНОЙ СИТУАЦИИ, ВОЗНИКШИМ ИЗ-ЗА ИСПОЛЬЗОВАНИЯ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫХ ДЕЙСТВИЙ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ. */

class UserModel extends UMC_Model{
    
    function get_groups(){
        $sql = 'SELECT * FROM {PRE}user_groups ORDER BY `sort`';
        $query = $this->db->query($sql);
        $groups = array();
        foreach($query->result() as $row){
            if($this->perms->{$row->unique})
                $groups[] = $row;
        }
        return $groups;
    }
    
    function get_fields($group_id, $user_id){
        $sql = 'SELECT * FROM {PRE}fields AS f
                    LEFT JOIN {PRE}fields_groups AS fg USING(`field_id`)
                    WHERE fg.`group_id` = "' . (int)$group_id . '" ORDER BY f.`sort` ASC';
        $query = $this->db->query($sql);
        
        $select = array();
        $select_cells = array();
        $fields = array();
        
        foreach($query->result() as $row){
            if($this->perms->{$row->unique . '_read'}){
                if(!$row->in_cell){
                    $fields['not_cell']['fields_list'][] = $row;
                    if($this->perms->{$row->unique . '_rec'})
                        $fields['not_cell']['fields_rec'][] = $row->unique;
                    $select[] = '`' . $row->unique . '`';
                }else{
                    $fields['in_cell']['fields_list'][$row->unique] = $row;
                    $select_cells[] = '`' . $row->unique . '`';
                }
            }
        }
        
        if(!count($select)) return $fields;
        
        $sql = 'SELECT ' . implode(', ', $select) . ' FROM {PRE}users_data WHERE `user_id` = "' . (int)$user_id .'" AND `cell_id` = "0" LIMIT 1';
        $query = $this->db->query($sql);
        $fields['not_cell']['fields_data'] = $query->row();
        
        if(count($select_cells)){
            $sql = 'SELECT ' . implode(', ', $select_cells) . ', c.`name`, c.`cell_id` FROM {PRE}users_data AS ud
                            LEFT JOIN {PRE}cells AS c USING (`cell_id`) 
                            WHERE 
                                ud.`user_id` = "' . (int)$user_id .'" AND 
                                ud.`cell_id` != "0"
                            ORDER BY c.`cell_id` ASC';
            $query = $this->db->query($sql);
            foreach($query->result() as $row){
                $fields['in_cell']['fields_data'][$row->cell_id] = $row;
            }
            $fields['in_cell']['comments'] = $this->get_comments($user_id);
            $fields['in_cell']['files'] = $this->get_files($user_id);
        }
        return $fields;        
    }
    
    function get_files($user_id){
        $query = $this->db->get_where('{PRE}files', array('user_id' => $user_id));
        $res = array();
        foreach($query->result() as $row){
            $res[$row->cell_id][] = $row;
        }
        return $res;
    }
    
    function get_file($file_id){
        $query = $this->db->get_where('{PRE}files', array('file_id' => $file_id), 1);
        return $query->row();
    }
    
    function get_comments($user_id){
        $sql = 'SELECT (SELECT f.`unique` FROM {PRE}fields AS f 
                                                LEFT JOIN {PRE}fields_groups AS fg USING(`field_id`)
                                                LEFT JOIN {PRE}users AS us USING(`group_id`)
                                                WHERE f.`type` = "text" AND f.`params` = "1" AND us.`id` = c.`autor`) AS `unique`
                        FROM {PRE}comments AS c 
                        LEFT JOIN {PRE}users_data AS ud ON ud.`user_id` = c.`autor`
                        WHERE 
                            c.`user_id` = "' . (int)$user_id . '" AND 
                            ud.`cell_id` = "0"
                        LIMIT 1';
        $query = $this->db->query($sql);
        $field = $query->row();
        if(!$field) return false;
        $sql = 'SELECT c.*, ud.`' . $field->unique . '` AS autor_name
                        FROM {PRE}comments AS c 
                        LEFT JOIN {PRE}users_data AS ud ON ud.`user_id` = c.`autor`
                        WHERE 
                            c.`user_id` = "' . (int)$user_id . '" AND 
                            ud.`cell_id` = "0"
                        ORDER BY c.`date` ASC';
        $query = $this->db->query($sql);
        $res = array();
        foreach($query->result() as $row){
            $res[$row->cell_id][] = $row;
        }
        return $res;
    }
    
    function delete_file($id){
        $query = $this->db->delete('{PRE}files', array('file_id' => (int)$id)); 
        return $this->db->affected_rows();
    }
    
    function get_comment_autor($id){
        $sql = 'SELECT `autor` FROM {PRE}comments WHERE `comment_id` = "' . (int)$id . '"';
        $query = $this->db->query($sql);
        return $query->row();
    }
    
    function update_comment($comment, $id){
        $sql = 'UPDATE {PRE}comments SET `comment` = ' . $this->db->escape($comment) . ' WHERE `comment_id` = "' . $id . '"';
        $this->db->query($sql);
        return $this->db->affected_rows();
    }
    
    function delete_comment($id){
        $sql = 'DELETE FROM {PRE}comments WHERE `comment_id` = "' . (int)$id . '"';
        $this->db->query($sql);
        return $this->db->affected_rows();
    }
    
    function get_fields_cell($group_id, $user_id, $cell_id){
        $sql = 'SELECT * FROM {PRE}fields AS f
                    LEFT JOIN {PRE}fields_groups AS fg USING(`field_id`)
                    WHERE fg.`group_id` = "' . (int)$group_id . '"  ORDER BY f.`sort` ASC';
        $query = $this->db->query($sql);
        
        $select_cells = array();
        $fields = array();
        
        foreach($query->result() as $row){
            if($this->perms->{$row->unique . '_rec'}){
                if($row->in_cell){
                    $fields['fields_list'][$row->unique] = $row;
                    $select_cells[] = '`' . $row->unique . '`';
                }
            }
        }
        
        if(count($select_cells)){
            $sql = 'SELECT ' . implode(', ', $select_cells) . ', c.`name`, c.`cell_id` FROM {PRE}users_data AS ud
                            LEFT JOIN {PRE}cells AS c USING (`cell_id`) 
                            WHERE 
                                ud.`user_id` = "' . (int)$user_id .'" AND 
                                ud.`cell_id` = "' . (int)$cell_id . '"
                            ORDER BY c.`cell_id` ASC';
            $query = $this->db->query($sql);
            $fields['fields_data'] = $query->row();
        }
        return $fields;        
    }
    
    function get_fields_not_cell($user_id, $group_id){
        $sql = 'SELECT * FROM {PRE}fields AS f
                    LEFT JOIN {PRE}fields_groups AS fg USING(`field_id`)
                    WHERE fg.`group_id` = "' . (int)$group_id . '"  ORDER BY f.`sort` ASC';
        $query = $this->db->query($sql);
        
        $select_cells = array();
        $fields = array();
        
        foreach($query->result() as $row){
            if($this->perms->{$row->unique . '_rec'}){
                if(!$row->in_cell){
                    $fields['fields_list'][$row->unique] = $row;
                    $select_cells[] = '`' . $row->unique . '`';
                }
            }
        }
        
        if(count($select_cells)){
            $sql = 'SELECT ' . implode(', ', $select_cells) . ' FROM {PRE}users_data AS ud
                            LEFT JOIN {PRE}cells AS c USING (`cell_id`) 
                            WHERE 
                                ud.`user_id` = "' . (int)$user_id .'" AND 
                                ud.`cell_id` = "0"
                            ORDER BY c.`cell_id` ASC';
            $query = $this->db->query($sql);
            $fields['fields_data'] = $query->row();
        }
        return $fields;        
    }
    
    function get_count_fields_cell($group_id){
        $sql = 'SELECT f.`unique` FROM {PRE}fields AS f
                    LEFT JOIN {PRE}fields_groups AS fg USING(`field_id`)
                    WHERE 
                        fg.`group_id` = "' . (int)$group_id . '" AND 
                        f.`in_cell` = "1"';
        $query = $this->db->query($sql);
        $fields = array();
        foreach($query->result() as $row){
            if($this->perms->{$row->unique . '_rec'}){
                $fields[] = $row;
            }
        }
        return count($fields);        
    }
    
    function get_group_unique($user_id){
        $sql = 'SELECT ug.`unique` FROM {PRE}users AS us
                        LEFT JOIN {PRE}user_groups AS ug USING(`group_id`) 
                        WHERE us.`id` = "' . (int)$user_id . '"';
        $query = $this->db->query($sql);
        $res = $query->row();
        return $res->unique;
        
    }
    
    function get_group_from_user($user_id){
        $sql = 'SELECT ug.*, us.`vk_id` FROM {PRE}users AS us
                        LEFT JOIN {PRE}user_groups AS ug USING(`group_id`) 
                        WHERE us.`id` = "' . (int)$user_id . '"';
        $query = $this->db->query($sql);
        return $query->row();
        
    }
    
    function get_field($unique){
        $sql = 'SELECT * FROM {PRE}fields WHERE `unique` = ' . $this->db->escape($unique);
        $query = $this->db->query($sql);
        return $query->row();
    }
    
    function save_data_fields($post, $user_id){
        
            foreach($post as $key => $val){
                
                if($this->perms->{$key . '_rec'} == 1 && $this->fomat_unique($key)){
                    $fields[] = '`' . $key . '` = ' . $this->db->escape($this->format_data($this->get_field($key),$post));
                }
            }
            
            if(!empty($fields)){
                $sql = 'UPDATE {PRE}users_data 
                                SET  ' . implode(', ', $fields) . ' 
                                WHERE `user_id` = "' . (int)$user_id . '"';
                                
                $this->db->query($sql);
                return $this->db->affected_rows();
            }
        
        return false;    
    }
    
    function insert_new_cell($post, $cell_name, $user_id, $group){

        $sql = 'INSERT INTO {PRE}cells (`name`) 
                    VALUES (' . $this->db->escape($cell_name) . ')';
        $query = $this->db->query($sql);
        if($this->db->affected_rows()){
            $cell_id = $this->db->insert_id();
            $sets = array();
            foreach($post as $key => $val){
                if($this->perms->{$key . '_rec'} && $this->fomat_unique($key))
                    $sets[] = '`' . $key . '` = ' . $this->db->escape($this->format_data($this->get_field($key),$post));
            }
            
            $fields = $this->get_fields_not_cell($user_id, $group->group_id);
            
            foreach($fields['fields_list'] as $key => $val){
                $sets[] = '`' . $key . '` = ' . $this->db->escape($fields['fields_data']->$key);
            }
            
            $sets[] .= '`user_id` = ' . $this->db->escape($user_id);
            $sets[] .= '`cell_id` = "' . (int)$cell_id . '"';
            
            $sql = 'INSERT INTO {PRE}users_data SET ' . implode(', ', $sets);
            $query = $this->db->query($sql);
            
            return $this->db->affected_rows();
        }
        return false;
    }
    
    function update_cell($post, $cell_name, $cell_id, $user_id){
            if($this->perms->control_cell){
                $sql = 'UPDATE {PRE}cells SET `name` = ' . $this->db->escape($cell_name) . ' WHERE `cell_id` = "' . (int)$cell_id . '"';
                $this->db->query($sql);
            }
            
            foreach($post as $key => $val){
                if($this->perms->{$key . '_rec'} == 1 && $this->fomat_unique($key)){
                    $fields[] = '`' . $key . '` = ' . $this->db->escape($this->format_data($this->get_field($key),$post));
                }
            }

            if(!empty($fields)){
                $sql = 'UPDATE {PRE}users_data SET  ' . implode(', ', $fields) . ' WHERE `user_id` = "' . (int)$user_id . '" AND `cell_id` = "' . $cell_id . '"';
                $this->db->query($sql);
                return $this->db->affected_rows();
            }
        return false; 
    }
    
    function delete_cell($cell_id, $user_id){
        $sql = 'DELETE FROM {PRE}cells WHERE `cell_id` = "' . (int)$cell_id . '"';
        $query = $this->db->query($sql);
        if($this->db->affected_rows()){
            $sql = 'DELETE FROM {PRE}users_data WHERE `user_id` = "' . (int)$user_id . '" AND `cell_id` = "' . (int)$cell_id . '"';
            $this->db->query($sql);
            return true;
        }
        return false;
    }
    
    function get_user_status($id){
        $query = $this->db
                    ->select('status, last_active')
                    ->get_where('{PRE}users_online', array('user_id' => $id), 1);
        return $query->row();
        
    }
    
    function delete_user($user_id){
        $sql = 'DELETE FROM {PRE}users WHERE `id` = "' . (int)$user_id . '"';
        $this->db->query($sql);
        
        if($res = $this->db->affected_rows()){
            
            $sql = 'SELECT `cell_id` FROM {PRE}users_data WHERE `user_id` = "' . (int)$user_id . '"';
            $query = $this->db->query($sql);
            $in = array();
            foreach($query->result() as $row){
                $in[] = $row->cell_id;
            }
            
            $sql = 'DELETE FROM {PRE}cells WHERE `cell_id` IN (' . implode(', ', $in) . ') ';
            $this->db->query($sql);
            
            $sql = 'DELETE FROM {PRE}users_data WHERE `user_id` = "' . (int)$user_id . '"';
            $this->db->query($sql);
            return $res;
        }
        return false;
    }
    
    function get_group($group_id){
        $sql = 'SELECT * FROM {PRE}user_groups WHERE `group_id` = "' . (int)$group_id . '"';
        $query = $this->db->query($sql);
        return $query->row();
    }
    
    function get_user_auto($text, $limit, $group_id = 0){
        $sql = 'SELECT `unique` FROM {PRE}fields 
                         WHERE 
                            `type` = "text" AND 
                            `params` = "1"
                        GROUP BY `field_id`';
        $query = $this->db->query($sql);
        $fields = $query->result(); 
        $select = array();
        $where = array();
        foreach($fields as $row){
            if($this->perms->{$row->unique . '_read'}){
                $select[] = 'ud.`' . $row->unique . '`';
                $where[] = 'ud.`' . $row->unique  . '` LIKE ' . $this->db->escape('%' . $text . '%');
            }
        }
        $and = '';
        if($group_id){
            $group = $this->get_group($group_id);
            if($this->perms->{$group->unique}){
                $and = ' AND us.`group_id` = "' . (int)$group_id . '"';
            }
        }else{
            $groups_arr = array();
            foreach($this->get_groups() as $group){
                if($this->perms->{$group->unique}){
                    $groups_arr[] = $group->group_id;
                }
            }
            $and = ' AND us.`group_id` IN (' . implode(',',$groups_arr) . ')';
        }
        
        $sql = 'SELECT ' . implode(', ', $select) . ', ud.`user_id` FROM {PRE}users_data AS ud
                    LEFT JOIN {PRE}users AS us ON ud.user_id = us.id  
                    WHERE (' . implode(' OR ', $where) . ') ' . $and . '
                    GROUP BY ud.`user_id`
                    LIMIT ' . (int)$limit;
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
    
    function save_user($array, $id){
        $set = array();
        foreach($array as $key => $val){
            if($val){
                $set[] = '`' . $key . '` = ' . $this->db->escape($val);
            }
        }
        
        if(count($set)){
            $sql = 'UPDATE {PRE}users SET ' . implode(', ', $set) . ' WHERE `id` = "' . (int)$id . '"';
            $this->db->query($sql);
            return $this->db->affected_rows();
        }
        return false;
    }
    
    function get_username($id){
        $sql = 'SELECT `username`, `vk_id` FROM {PRE}users WHERE `id` = "' . (int)$id . '"';
        $query = $this->db->query($sql);
        return $query->row();
    }
    
    function insert_comment($cell_id, $user_id, $comment, $autor){
        $sql = 'INSERT INTO {PRE}comments SET
                    `cell_id` = "' . (int)$cell_id . '", 
                    `user_id` = "' . (int)$user_id . '", 
                    `autor` = "' . (int)$autor . '", 
                    `comment` = ' . $this->db->escape($comment) . ',
                    `date` = NOW()';
        $this->db->query($sql);
        return $this->db->affected_rows();
    }
    
    function insert_file($data){
        foreach($data as $key => $val){
            $this->db->set($key, $val);
        }
        $this->db->insert('{PRE}files'); 
        return $this->db->affected_rows();
    }
    
    function get_isset_username($username){
        $this->db->select('`id`');
        $query = $this->db->get_where('{PRE}users', array('username' => $username));
        return $query->row();
    }
    
    function get_email_notice($id){
        $sql = 'SELECT f.`unique` FROM {PRE}fields AS f 
                    LEFT JOIN {PRE}fields_groups AS fg USING(`field_id`)
                    LEFT JOIN {PRE}users AS us USING(`group_id`)
                    WHERE us.`id` = "' . (int)$id . '" AND f.type = "email" AND f.`params` = "1" 
                    LIMIT 1';
        $query = $this->db->query($sql);
        $field = $query->row();
        $unique = $field->unique;
        $sql = 'SELECT `' . $unique . '` FROM {PRE}users_data WHERE `user_id` = "' . (int)$id . '"';
        $query = $this->db->query($sql);
        $res = $query->row();
        return $res->$unique;
    }
    
    function get_email_field_group($group_id){
        $sql = 'SELECT f.`unique` FROM {PRE}fields AS f 
                    LEFT JOIN {PRE}fields_groups AS fg USING(`field_id`)
                    WHERE fg.`group_id` = "' . (int)$group_id . '" AND f.type = "email" AND f.`params` = "1" 
                    LIMIT 1';
        $query = $this->db->query($sql);
        $field = $query->row();
        $unique = $field->unique;
        $sql = 'SELECT ud.`' . $unique . '` FROM {PRE}users_data AS ud 
                    LEFT JOIN {PRE}users AS us ON us.`id` = ud.`user_id`
                    WHERE us.`group_id` = "' . (int)$group_id . '" AND ud.`' . $unique . '` != ""
                    GROUP BY ud.`' . $unique . '`';
        $query = $this->db->query($sql);
        $res = $query->result();
        $mail_array = array();
        foreach($res as $mail){
            $mail_array[] = $mail->$unique;
        }
        return implode(', ', $mail_array);
    }
    
    
    
}