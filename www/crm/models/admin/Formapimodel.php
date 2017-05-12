<?php defined('BASEPATH') OR exit('No direct script access allowed');
  
/* Copyright © 2015 г. Коротков Е.А.

Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми «Программное Обеспечение»), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение копий Программного Обеспечения, а также лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:

Запрещено изменять (модернизировать, удалять, скрывать любыми средствами) ссылку на Веб-сайт разработчика данного Програмного Обеспечения.
Данное Программное Обеспечение является бесплатным только на стадии разработки, пользователь имеет право бесплатного пользования только версиями данного Программное Обеспечение без пометки "final" и "release".Запрещено продавать копии, а также модернизированные версии данного Програмного Обеспечения
Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.

ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ ГАРАНТИИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ, НО НЕ ОГРАНИЧИВАЯСЬ ИМИ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО КАКИМ-ЛИБО ИСКАМ, ЗА УЩЕРБ ИЛИ ПО ИНЫМ ТРЕБОВАНИЯМ, В ТОМ ЧИСЛЕ, ПРИ ДЕЙСТВИИ КОНТРАКТА, ДЕЛИКТЕ ИЛИ ИНОЙ СИТУАЦИИ, ВОЗНИКШИМ ИЗ-ЗА ИСПОЛЬЗОВАНИЯ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫХ ДЕЙСТВИЙ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ. */  

class FormapiModel extends UMC_Model{
    
    function get_groups(){
        $sql = 'SELECT * FROM ' . $this->db->dbprefix . 'user_groups ORDER BY `sort` ASC';
        $query = $this->db->query($sql);
        $res = $query->result();
        $groups = array();
        foreach($res as $row){
            $row->fields = $this->get_fields($row->group_id);
            $groups[$row->group_id] = $row;
        }
        return $groups;
    }
    
    function get_forms(){
        $sql = 'SELECT * FROM ' . $this->db->dbprefix . 'formapi
                    GROUP BY `form_id`
                    ORDER BY `form_id` ASC ';
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    function get_fields($group_id){
        if(!$group_id) return array();
        $sql = 'SELECT * FROM ' . $this->db->dbprefix . 'fields AS f 
                    LEFT JOIN ' . $this->db->dbprefix . 'fields_groups AS fg USING(`field_id`)
                    WHERE fg.`group_id` = "' . $group_id . '"
                    ORDER BY `sort` ASC';
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    function insert_formapi($post){
        $sql = 'INSERT INTO ' . $this->db->dbprefix . 'formapi SET 
                    `form_title` = ' .$this->db->escape($post['name']) . ',
                    `api_key` = ' . $this->db->escape(uniqid(rand(), TRUE)) . ',
                    `group_id` = "' . (int)$post['groups'] . '", 
                    `status` = "' . (int)$post['status'] . '"';
        $this->db->query($sql);
        return $this->db->affected_rows();
    }
    
    function update_formapi($post, $fields){
        $sql = 'UPDATE ' . $this->db->dbprefix . 'formapi 
                    SET 
                        `form_title` = ' .$this->db->escape($post['form_title']) . ',
                        `api_key` = ' . $this->db->escape($post['api_key']) . ',
                        `group_id` = "' . (int)$post['group_id'] . '", 
                        `status` = "' . (int)$post['status'] . '",
                        `fields` = ' . $this->db->escape($fields) . '
                    WHERE `form_id` = "' . (int)$post['form_id'] . '"';
        $this->db->query($sql);
        return $this->db->affected_rows();
    }
    
    function delete_formapi($id){
        $sql = 'DELETE FROM ' . $this->db->dbprefix . 'formapi WHERE `form_id` = "' . (int)$id . '"';
        $this->db->query($sql);
        return $this->db->affected_rows();
    }
}
