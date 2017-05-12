<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Copyright © 2015 г. Коротков Е.А.

Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми «Программное Обеспечение»), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение копий Программного Обеспечения, а также лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:

Запрещено изменять (модернизировать, удалять, скрывать любыми средствами) ссылку на Веб-сайт разработчика данного Програмного Обеспечения.
Данное Программное Обеспечение является бесплатным только на стадии разработки, пользователь имеет право бесплатного пользования только версиями данного Программное Обеспечение без пометки "final" и "release".Запрещено продавать копии, а также модернизированные версии данного Програмного Обеспечения
Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.

ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ ГАРАНТИИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ, НО НЕ ОГРАНИЧИВАЯСЬ ИМИ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО КАКИМ-ЛИБО ИСКАМ, ЗА УЩЕРБ ИЛИ ПО ИНЫМ ТРЕБОВАНИЯМ, В ТОМ ЧИСЛЕ, ПРИ ДЕЙСТВИИ КОНТРАКТА, ДЕЛИКТЕ ИЛИ ИНОЙ СИТУАЦИИ, ВОЗНИКШИМ ИЗ-ЗА ИСПОЛЬЗОВАНИЯ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫХ ДЕЙСТВИЙ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ. */

class FieldsModel extends UMC_Model{
    
    private function get_alter_types($type){
        $data = '';
        switch($type){
            case 'list' :
                $data = ' INT (11) NOT NULL ';
                break;
            
            case 'checkbox' :
                $data = ' ENUM ("0","1") NOT NULL ';
                break;
            
            case 'text' :
            case 'tel' :
            case 'email' :
                $data = ' VARCHAR (255) NOT NULL DEFAULT "" ';
                break;
            case 'date' :
                $data = ' DATE NULL ';
                break;
            case 'datetime' :
                $data = ' DATETIME NULL ';
                break;
            default :
                $data = ' TEXT NULL ';
                break;
        }
        
        return $data;
    }
    
    function add_field($post){
        $sql = 'INSERT INTO {PRE}fields (`name`, `unique`, `type`, `required`, `in_cell`, `data`) 
                    VALUES (
                        ' . $this->db->escape($post['name']) . ', 
                        ' . $this->db->escape($post['unique']) . ', 
                        ' . $this->db->escape($post['type']) . ', 
                        ' . $this->db->escape($post['required']) . ', 
                        ' . $this->db->escape($post['in_cell']) . ', 
                        ' . $this->db->escape($post['data']) . '
                    )';
        $query = $this->db->query($sql);
        
        if($this->db->affected_rows()){
            $field_id = $this->db->insert_id();
            foreach($post['groups'] as $val){
                $sql = 'INSERT INTO {PRE}fields_groups SET `field_id` = "' . (int)$field_id . '", `group_id` = "' . (int)$val . '"';
                $this->db->query($sql); 
            }
            
            $alter_type = $this->get_alter_types($post['type']);
            
            $sql = 'ALTER TABLE {PRE}group_perms ADD `' . $post['unique'] . '_read` ENUM ("0","1") NOT NULL, ADD `' . $post['unique'] . '_rec` ENUM ("0","1") NOT NULL;';
 
            $sql .= 'ALTER TABLE {PRE}users_data ADD `' . $post['unique'] . '` ' . $alter_type . ';';
            $sql .= 'UPDATE {PRE}group_perms SET `' . $post['unique'] . '_read` = "1", `' . $post['unique'] . '_rec` = "1" WHERE `admin` = "1"';
            $this->db->query($sql);            
        }
        
        
        
        return true;
    }
    
    function get_fields($group_id){
        $sql = 'SELECT f.* FROM {PRE}fields AS f
                    LEFT JOIN {PRE}fields_groups AS fg USING(`field_id`)
                    WHERE fg.`group_id` = "' . (int)$group_id . '" 
                    ORDER BY f.`in_cell` ASC, f.`sort` ASC';
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    function get_count_fields($group_id){
        $sql = 'SELECT COUNT(f.`field_id`) AS count FROM {PRE}fields AS f
                    LEFT JOIN {PRE}fields_groups AS fg USING(`field_id`)
                    WHERE fg.`group_id` = "' . (int)$group_id . '"';
        $query = $this->db->query($sql);
        return $query->row();
    }
    
    function get_all_fields(){
        $sql = 'SELECT * FROM {PRE}fields ORDER BY `sort` ASC';
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    function get_fileds_cell($group_id){
        $sql = 'SELECT * FROM {PRE}fields AS f
                    LEFT JOIN {PRE}fields_groups AS fg USING(`field_id`)
                    WHERE 
                        fg.`group_id` = "' . (int)$group_id . '" AND
                        f.`in_cell` = "1"
                    ORDER BY f.`sort` ASC';
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    function get_field($id){
        $sql = 'SELECT * FROM {PRE}fields WHERE `field_id` = ' . $this->db->escape($id);
        $query = $this->db->query($sql);
        return $query->row();
    }
    
    function save_field($post){
        
        $field = $this->get_field($post['field_id']);
        
        $sql = 'UPDATE {PRE}fields 
                    SET 
                        `name` = ' . $this->db->escape($post['name']) . ', 
                        `type` = ' . $this->db->escape($post['type']) . ',
                        `data` = ' . $this->db->escape($post['data']) . ',
                        `in_cell` = ' . $this->db->escape($post['in_cell']) . ', 
                        `required` = ' . $this->db->escape($post['required']) . ' 
                    WHERE 
                        `field_id` = ' . $this->db->escape($post['field_id']) . '; ';
        if($field->type != $post['type']){
            $alter_type = $this->get_alter_types($post['type']);
            $sql .= 'ALTER TABLE {PRE}users_data MODIFY `' . $field->unique . '` ' . $alter_type . '; ';
        }

            $sql .= 'DELETE FROM {PRE}fields_groups WHERE `field_id` = "' . (int)$post['field_id'] . '"';
            $this->db->query($sql);
            
            if(count($post['groups'])){
                $insert = array();
                foreach($post['groups'] as $group_id){
                    $insert[] = '("' . (int)$post['field_id'] . '", "' . (int)$group_id . '")';
                }
                
                $sql = 'INSERT INTO {PRE}fields_groups (`field_id`, `group_id`) VALUES ' . implode(', ', $insert);
                $this->db->query($sql);
            }
            return true;

    }
    
    function save_field_params($post){
        $sql = 'UPDATE {PRE}fields 
                    SET 
                        `params` = ' . $this->db->escape($post['params']) . '
                    WHERE 
                        `field_id` = ' . $this->db->escape($post['field_id']);
        $query = $this->db->query($sql);
        return $this->db->affected_rows();
    }
    
    function delete_field($id){
        
        $sql = 'SELECT `unique` FROM {PRE}fields WHERE `field_id` = "' . (int)$id . '"';
        $query = $this->db->query($sql);
        $unique = $query->row();

        $sql = 'DELETE FROM {PRE}fields WHERE `field_id` = "' . (int)$id . '";';       
        $sql .= 'DELETE FROM {PRE}fields_groups WHERE `field_id` = "' . (int)$id . '";';       
        $sql .= 'ALTER TABLE {PRE}group_perms DROP `' . $unique->unique . '_read`, DROP `' . $unique->unique  . '_rec`;';
        $sql .= 'ALTER TABLE {PRE}users_data DROP `' . $unique->unique  . '`;';
        $this->db->query($sql);
        
        return true;
    }
    
    function update_sort_fields($data){
        foreach($data as $key => $value){
            $sql = 'UPDATE {PRE}fields 
                    SET 
                        `sort` = ' . $this->db->escape($key) . '
                    WHERE 
                        `field_id` = ' . $this->db->escape($value);
            $this->db->query($sql);
        }
        
        return true;
    }
    
    function get_groups(){
        $sql = 'SELECT * FROM {PRE}user_groups ORDER BY `sort` ASC';
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    function get_groups_vals($field_id){
        $sql = 'SELECT ug.`group_id` FROM {PRE}user_groups AS ug 
                    LEFT JOIN {PRE}fields_groups AS fg USING(`group_id`)
                    WHERE fg.`field_id` = "' . (int)$field_id . '"
                    ORDER BY `sort` ASC';
        $query = $this->db->query($sql);
        $data = array();
        foreach($query->result() as $row){
            $data[] = $row->group_id;
        }
        return $data;
    }
    
    function get_group_perms($id){
        $sql = 'SELECT * FROM {PRE}user_groups AS ug
                    LEFT JOIN {PRE}group_perms AS gp USING(`group_id`) 
                    WHERE `group_id` = ' . $this->db->escape($id);
        $query = $this->db->query($sql);
        return $query->row();
    }
    
    function get_group_unique($unique){
        $sql = 'SELECT COUNT(*) AS count FROM {PRE}user_groups 
                    WHERE `unique` = ' . $this->db->escape($unique);
        $query = $this->db->query($sql);
        return $query->row();
    }
    
    function add_group($post){
        
        $sql = 'INSERT INTO {PRE}user_groups (`name`, `unique`, `workmans`, `postfix`) 
                    VALUES (
                        ' . $this->db->escape($post['name']) . ', 
                        ' . $this->db->escape($post['unique']) . ', 
                        ' . $this->db->escape($post['workmans']) . ',
                        ' . $this->db->escape($post['postfix']) . ' 
                        );';
        $sql .= 'ALTER TABLE {PRE}group_perms ADD `' . $post['unique'] . '` ENUM ("0","1") NOT NULL;';
        $sql .= 'INSERT INTO {PRE}group_perms SET `group_id` = LAST_INSERT_ID();';
        $sql .= 'UPDATE {PRE}group_perms SET `' . $post['unique'] . '` = "1" WHERE `admin` = "1"';
        $this->db->query($sql);
        
        return $this->db->affected_rows();
    }
    
    function get_group($id){
        $sql = 'SELECT * FROM {PRE}user_groups WHERE `group_id` = ' . $this->db->escape($id);
        $query = $this->db->query($sql);
        return $query->row();
    }
    
    function delete_group($id){
        
        $sql = 'SELECT `unique` FROM {PRE}user_groups WHERE `group_id` = ' . $this->db->escape($id); //получаеи уникальное значение группы
        $query = $this->db->query($sql);
        $unique = $query->row();
        
        $sql = 'DELETE FROM {PRE}user_groups WHERE `group_id` = "' . (int)$id . '";'; //удаляем группу
        $sql .= 'DELETE FROM {PRE}group_perms WHERE `group_id` = "' . (int)$id . '";'; //удаляем запись в правах
        $sql .= 'ALTER TABLE {PRE}group_perms DROP `' . $unique->unique. '`;'; //удаляем столбец из прав доступа
        $this->db->query($sql);    
        return true;
    }
    
    function update_sort_groups($data){
        foreach($data as $key => $value){
            $sql = 'UPDATE {PRE}user_groups 
                    SET 
                        `sort` = ' . $this->db->escape($key) . '
                    WHERE 
                        `group_id` = ' . $this->db->escape($value);
            $this->db->query($sql);
        }
        
        return true;
    }
    
    function save_group($post){
        $sql = 'UPDATE {PRE}user_groups 
                    SET 
                        `name` = ' . $this->db->escape($post['name']) . ', 
                        `workmans` = ' . $this->db->escape($post['workmans']) . ',
                        `single` = ' . $this->db->escape($post['single']) . ',
                        `postfix` = ' . $this->db->escape($post['postfix']) . '
                    WHERE 
                        `group_id` = ' . $this->db->escape($post['group_id']);

        $query = $this->db->query($sql);
        return $this->db->affected_rows();
    }
    
    function save_perm_group_control($post){
        
        $sql = 'UPDATE {PRE}group_perms 
                    SET 
                        `' . $post['p_name'] . '` = ' . $this->db->escape($post['p']) . '
                    WHERE 
                        `group_id` = ' . $this->db->escape($post['from_group']);

        $query = $this->db->query($sql);
        return $this->db->affected_rows();
    }
    
    function save_perm_group($post){
        
        $sql = 'SELECT `unique` FROM {PRE}user_groups WHERE `group_id` = ' . $this->db->escape($post['id']);
        $query = $this->db->query($sql);
        $u = $query->row();
        
        $sql = 'UPDATE {PRE}group_perms 
                    SET 
                        `' . $u->unique . '` = ' . $this->db->escape($post['p']) . '
                    WHERE 
                        `group_id` = ' . $this->db->escape($post['from_group']);

        $query = $this->db->query($sql);
        return $this->db->affected_rows();
    }
    
    function save_perm_filed($post){
        
        $sql = 'SELECT `unique` FROM {PRE}fields WHERE `field_id` = ' . $this->db->escape($post['id']);
        $query = $this->db->query($sql);
        $f = $query->row();
        
        $sql = 'UPDATE {PRE}group_perms 
                    SET 
                        `' . $f->unique . $post['type'] . '` = ' . $this->db->escape($post['p']) . '
                    WHERE 
                        `group_id` = ' . $this->db->escape($post['from_group']);
                    
        $query = $this->db->query($sql);
        return $this->db->affected_rows();
    }
    
    function update_cell_params($post){

        foreach($post['params'] as $key => $val){
            $set[] = '`' . $key . '` = ' . $this->db->escape($val);
        }
        
        $sql = 'UPDATE {PRE}user_groups 
                    SET 
                        ' . implode(', ', $set) . '
                    WHERE 
                        `group_id` = ' . $this->db->escape($post['group_id']);

        $query = $this->db->query($sql);
        return $this->db->affected_rows();
    }
    
    function get_field_unique($unique){
        $sql = 'SELECT `name`, `unique` FROM {PRE}fields WHERE `unique` = ' . $this->db->escape($unique);
        $query = $this->db->query($sql);
        return $query->row();
    }
    
}
