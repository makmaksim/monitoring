<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Copyright © 2015 г. Коротков Е.А.

Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми «Программное Обеспечение»), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение копий Программного Обеспечения, а также лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:

Запрещено изменять (модернизировать, удалять, скрывать любыми средствами) ссылку на Веб-сайт разработчика данного Програмного Обеспечения.
Данное Программное Обеспечение является бесплатным только на стадии разработки, пользователь имеет право бесплатного пользования только версиями данного Программное Обеспечение без пометки "final" и "release".Запрещено продавать копии, а также модернизированные версии данного Програмного Обеспечения
Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.

ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ ГАРАНТИИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ, НО НЕ ОГРАНИЧИВАЯСЬ ИМИ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО КАКИМ-ЛИБО ИСКАМ, ЗА УЩЕРБ ИЛИ ПО ИНЫМ ТРЕБОВАНИЯМ, В ТОМ ЧИСЛЕ, ПРИ ДЕЙСТВИИ КОНТРАКТА, ДЕЛИКТЕ ИЛИ ИНОЙ СИТУАЦИИ, ВОЗНИКШИМ ИЗ-ЗА ИСПОЛЬЗОВАНИЯ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫХ ДЕЙСТВИЙ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ. */

class GroupModel extends UMC_Model{
    
    function get_group($group_id){
        $sql = 'SELECT * FROM {PRE}user_groups WHERE `group_id` = "' . (int)$group_id . '"';
        $query = $this->db->query($sql);
        return $query->row();
    }
    
    function get_fields($group_id){
        $sql = 'SELECT * FROM {PRE}fields AS f
                    LEFT JOIN {PRE}fields_groups AS fg USING(`field_id`)
                    WHERE 
                        fg.`group_id` = "' . (int)$group_id . '"
                    ORDER BY `sort` ASC';
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    function get_field($field_id){
        $sql = 'SELECT * FROM {PRE}fields 
                    WHERE 
                        `field_id` = "' . (int)$field_id . '"
                    ORDER BY `sort` ASC';
        $query = $this->db->query($sql);
        return $query->row();
    }
    
    function get_fields_list($menu_item){

        $sql = 'SELECT `field_id`, `name`, `unique`, `type`, `params`, `data`, `in_cell` FROM {PRE}fields AS f
                    LEFT JOIN {PRE}fields_groups AS fg USING(`field_id`) 
                    WHERE 
                        fg.`group_id` = "' . (int)$menu_item->group_id . '" AND 
                        f.`field_id` IN (' . implode(', ', unserialize($menu_item->fields)) . ')
                    ORDER BY f.`in_cell` ASC, f.`sort` ASC';
        $query = $this->db->query($sql);
        $fields = array();

        foreach($query->result() as $row){
            if($this->perms->{$row->unique . '_read'}){
                if(isset($_COOKIE['sort_order']) && $_COOKIE['sort_order'] && $row->unique == $_COOKIE['sort_field']){
                    $row->order = $_COOKIE['sort_order'];
                }else{
                    $row->order = 0;
                }
                $fields[] = $row;
            }
        }
        
        return $fields;
    }
    
    function get_users_list($fields, $group_id, $get, $limitstart, $limit, $menu_item){
        if($fields){
            $select = array();
            $count_in_cell = 0;
            foreach($fields as $field){
                if($this->perms->{$field->unique . '_read'}){
                    if($field->in_cell){
                        $count_in_cell++;
                    }
                    $select[] = 'ud.`' . $field->unique . '`';
                }
            }
            
            $where = ' AND ud.`cell_id` = "0"';
//            $where = ' AND ud.`cell_id` = (SELECT MAX(udd.`cell_id`) FROM {PRE}users_data AS udd WHERE ud.`user_id` = udd.`user_id`)';
            if($count_in_cell > 0){
                $where = 'AND IF( (SELECT MAX(udd.`cell_id`) FROM {PRE}users_data AS udd WHERE ud.`user_id` = udd.`user_id`) != 0, ud.`cell_id` > 0, ud.`cell_id` = 0 ) ';
            }
            $group_by = '';
            if(isset($get['filter_val'])){
                if($get['filter_field']){
                    $f = $this->get_field($get['filter_field']);
                    if($this->perms->{$f->unique . '_read'}){
                        if($f->in_cell){
                            $where = ' AND ud.`' . $f->unique . '` LIKE ' . $this->db->escape('%' . $get['filter_val'] . '%');
                        }else{
                            $where .= ' AND ud.`' . $f->unique . '` LIKE ' . $this->db->escape('%' . $get['filter_val'] . '%');
                        }
                    }
                }
                
                
            }
            if($menu_item->type == 1){
                $group_by = ' GROUP BY  ud.`user_id` ';
            }elseif($menu_item->type == 2){
                $where .= ' AND ud.`cell_id` != "0"';
            }
            $order_arr = array(
                            1 => 'ASC',
                            2 => 'DESC',
                            0 => 'ASC'
                        );
            
            $order_field = 'ud.`data_id`';
            $order = 'DESC';
            
            if(isset($_COOKIE['sort_field']) && $_COOKIE['sort_field']){
                $order_field = 'ud.`' . $_COOKIE['sort_field'] . '`';
                $order = $order_arr[$_COOKIE['sort_order']];
            }
            
            $sql = 'SELECT ud.`user_id`, ud.`cell_id`, ' . implode(', ', $select) . ', (SELECT MAX(udd.`cell_id`) FROM {PRE}users_data AS udd WHERE ud.`user_id` = udd.`user_id`) AS count_cell, uso.`status`, uso.`last_active` FROM {PRE}users_data AS ud
                        LEFT JOIN {PRE}users AS us ON us.`id` = ud.`user_id`
                        LEFT JOIN {PRE}users_online AS uso ON us.`id` = uso.`user_id`
                        WHERE 
                            us.`group_id` = "' . (int)$group_id . '" ' . $where . '
                        ' . $group_by . '
                        ORDER BY ' . $order_field . ' ' . $order . ' 
                        LIMIT ' . $limitstart . ', ' . $limit;

            $query = $this->db->query($sql);            
            return $query->result();
        }
        return array();
    }
    
    function count_all_users($fields, $group_id, $get, $menu_item){
        $select = array();
            $count_in_cell = 0;
            foreach($fields as $field){
                if($this->perms->{$field->unique . '_read'} && $field->in_cell){
                    $count_in_cell++;
                }
            }
            
            $where = ' AND ud.`cell_id` = "0"';
            if($count_in_cell > 0){
                $where = 'AND IF( (SELECT MAX(udd.`cell_id`) FROM {PRE}users_data AS udd WHERE ud.`user_id` = udd.`user_id`) != 0, ud.`cell_id` > 0, ud.`cell_id` = 0 ) ';
            }
            $group_by = '';
            if(isset($get['filter_val'])){
                if($get['filter_field']){
                    $f = $this->get_field($get['filter_field']);
                    if($this->perms->{$f->unique . '_read'}){
                        if($f->in_cell){
                            $where = ' AND ud.`' . $f->unique . '` LIKE ' . $this->db->escape('%' . $get['filter_val'] . '%');
                        }else{
                            $where .= ' AND ud.`' . $f->unique . '` LIKE ' . $this->db->escape('%' . $get['filter_val'] . '%');
                        }
                    }
                }
                
                
            }
            if($menu_item->type == 1){
                $group_by = ' GROUP BY  ud.`user_id` ';
            }
            $order_arr = array(
                            1 => 'ASC',
                            2 => 'DESC',
                            0 => 'ASC'
                        );
            
            $sql = 'SELECT ud.`data_id` AS count FROM {PRE}users_data AS ud
                        LEFT JOIN {PRE}users AS us ON us.`id` = ud.`user_id`
                        WHERE 
                            us.`group_id` = "' . (int)$group_id . '" ' . $where . '
                        ' . $group_by;
        $query = $this->db->query($sql);            
        return count($query->result());
    }
    
    function save_new_user($post){
        
        $group = $this->get_group($post['group_id']);
        $insert_cell = (isset($post['insert_cell_hidden'])) ? $post['insert_cell_hidden'] : 0;
        unset($post['insert_cell_hidden']);
        if(!$this->perms->control_user || !$this->perms->{$group->unique}) return false;
        
        $sql = 'INSERT INTO {PRE}users SET `username` = ' . $this->db->escape($post['login']) . ', `password` = "' . $this->hashPassword($post['password'], $this->db->md5_key) . '", group_id = "' . (int)$group->group_id . '", `date_creat` = NOW()';
            
        $query = $this->db->query($sql);
        
        if($ar = $this->db->affected_rows()){

            $user_id = $this->db->insert_id();
            
            $fields = $this->get_fields($group->group_id);
            $set = array();
            $set_cell = array();

            foreach($fields as $field){
                if($this->perms->{$field->unique . '_rec'} && isset($post[$field->unique])){
                    if(!$field->in_cell){
                        $set[] = '`' . $field->unique  . '` = ' . $this->db->escape($this->format_data($field,$post));
                    }else{
                        $set_cell[] = '`' . $field->unique  . '` = ' . $this->db->escape($this->format_data($field,$post));
                    }
                }
             } 
             
             $sql = 'INSERT INTO {PRE}users_data SET `user_id` = "' . (int)$user_id . '", ' . implode(', ', $set);
             $query = $this->db->query($sql);
             
             $cell_id = 0;
             if($this->perms->control_cell && $insert_cell){
                    
                 $cell_name = $post['cell_name'];
                 unset($post['cell_name']);
                 preg_match_all('|\{([^}]+)\}|i', $cell_name, $cell_nf);
                 foreach($cell_nf[0] as $key => $val){
                     $cell_name = str_replace($val, $post[$cell_nf[1][$key]], $cell_name);
                 }
                 $sets = implode(', ', $set) . ', ' . implode(', ', $set_cell);
                 
                 $sql = 'INSERT INTO {PRE}cells SET `name` = ' . $this->db->escape($cell_name) . ';';
                 $sql .= 'INSERT INTO {PRE}users_data SET `user_id` = "' . (int)$user_id . '", `cell_id` = LAST_INSERT_ID(), ' . $sets;
                 $query = $this->db->query($sql);
             }  
                
             return $user_id;
        }

        return false;
    }
    
    function hashPassword($password, $key){
        $salt = md5(uniqid($this->db->md5_key, true));
        $salt = substr(strtr(base64_encode($salt), '+', '.'), 0, 22);
        return crypt($password, '$2a$08$' . $salt);
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
    
    function get_isset_username($username){
        $this->db->select('`id`');
        $query = $this->db->get_where('{PRE}users', array('username' => $username));
        return $query->row();
    }
    
    function get_field_dynamic($user_id, $field, $cell_id){
        $field = $this->db->get_where('fields', array('unique' => $field))->row();
        $field->data = $this->db->get_where('users_data', array('user_id' => $user_id, 'cell_id' => $cell_id))->row();
        return $field;
    }
    
    function get_users_list_export($fields, $group_id, $get, $menu_item){
        if($fields){
            $select = array();
            $count_in_cell = 0;
            foreach($fields as $field){
                if($this->perms->{$field->unique . '_read'}){
                    if($field->in_cell){
                        $count_in_cell++;
                    }
                    $select[] = 'ud.`' . $field->unique . '`';
                }
            }
            
            $where = ' AND ud.`cell_id` = "0"';
//            $where = ' AND ud.`cell_id` = (SELECT MAX(udd.`cell_id`) FROM {PRE}users_data AS udd WHERE ud.`user_id` = udd.`user_id`)';
            if($count_in_cell > 0){
                $where = 'AND IF( (SELECT MAX(udd.`cell_id`) FROM {PRE}users_data AS udd WHERE ud.`user_id` = udd.`user_id`) != 0, ud.`cell_id` > 0, ud.`cell_id` = 0 ) ';
            }
            $group_by = '';
            if(isset($get['filter_val'])){
                if($get['filter_field']){
                    $f = $this->get_field($get['filter_field']);
                    if($this->perms->{$f->unique . '_read'}){
                        if($f->in_cell){
                            $where = ' AND ud.`' . $f->unique . '` LIKE ' . $this->db->escape('%' . $get['filter_val'] . '%');
                        }else{
                            $where .= ' AND ud.`' . $f->unique . '` LIKE ' . $this->db->escape('%' . $get['filter_val'] . '%');
                        }
                    }
                }
                
                
            }
            if($menu_item->type == 1){
                $group_by = ' GROUP BY  ud.`user_id` ';
            }
            $order_arr = array(
                            1 => 'ASC',
                            2 => 'DESC',
                            0 => 'ASC'
                        );
            
            $order_field = 'ud.`data_id`';
            $order = 'DESC';
            
            if(isset($_COOKIE['sort_field']) && $_COOKIE['sort_field']){
                $order_field = 'ud.`' . $_COOKIE['sort_field'] . '`';
                $order = $order_arr[$_COOKIE['sort_order']];
            }
            
            $sql = 'SELECT ud.`user_id`, ' . implode(', ', $select) . ', (SELECT MAX(udd.`cell_id`) FROM {PRE}users_data AS udd WHERE ud.`user_id` = udd.`user_id`) AS count_cell, uso.`status`, uso.`last_active` FROM {PRE}users_data AS ud
                        LEFT JOIN {PRE}users AS us ON us.`id` = ud.`user_id`
                        LEFT JOIN {PRE}users_online AS uso ON us.`id` = uso.`user_id`
                        WHERE 
                            us.`group_id` = "' . (int)$group_id . '" ' . $where . '
                        ' . $group_by . '
                        ORDER BY ' . $order_field . ' ' . $order;

            $query = $this->db->query($sql);            
            return $query->result();
        }
        return array();
    }
}