<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* Copyright © 2015 г. Коротков Е.А.

Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми «Программное Обеспечение»), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение копий Программного Обеспечения, а также лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:

Запрещено изменять (модернизировать, удалять, скрывать любыми средствами) ссылку на Веб-сайт разработчика данного Програмного Обеспечения.
Данное Программное Обеспечение является бесплатным только на стадии разработки, пользователь имеет право бесплатного пользования только версиями данного Программное Обеспечение без пометки "final" и "release".Запрещено продавать копии, а также модернизированные версии данного Програмного Обеспечения
Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.

ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ ГАРАНТИИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ, НО НЕ ОГРАНИЧИВАЯСЬ ИМИ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО КАКИМ-ЛИБО ИСКАМ, ЗА УЩЕРБ ИЛИ ПО ИНЫМ ТРЕБОВАНИЯМ, В ТОМ ЧИСЛЕ, ПРИ ДЕЙСТВИИ КОНТРАКТА, ДЕЛИКТЕ ИЛИ ИНОЙ СИТУАЦИИ, ВОЗНИКШИМ ИЗ-ЗА ИСПОЛЬЗОВАНИЯ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫХ ДЕЙСТВИЙ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ. */
 
class FormapiModel extends UMC_Model{
    
    function get_form($id){
        $sql = 'SELECT * FROM {PRE}formapi WHERE `form_id` = "' . (int)$id . '" AND `status` = "1"';
        $query = $this->db->query($sql);
        return $query->row();
    }
    
    function get_field($id, $group_id = 0){
        $sql = 'SELECT * FROM {PRE}fields AS f
                    LEFT JOIN {PRE}fields_groups AS fg USING(`field_id`) 
                    WHERE f.`field_id` = "' . (int)$id . '"';
        if($group_id > 0){
            $sql .= ' AND fg.`group_id` = "' . (int)$group_id . '"';
        }
        $query = $this->db->query($sql);
        return $query->row();
    }
    
    private function get_group($group_id){
        $this->db->select('cell_name');
        $query = $this->db->get_where('{PRE}user_groups', array('group_id' => (int)$group_id));
        return $query->row();
    }
    
    function save_form($array, $group_id){
        
        $this->db->insert('{PRE}users', array('group_id' => (int)$group_id)); 
        $id = $this->db->insert_id();
        $set = array();
        $set_in_cell = array();
        $set_cell = 0;
        foreach($array as $field){
            if($field->in_cell){
                $set_cell++; 
                $set_in_cell[$field->unique] = $this->format_data($field, array($field->unique => $field->val));
            }else{
                $set[$field->unique] = $this->format_data($field, array($field->unique => $field->val));
            }
            
        }
        
        $this->db->set($set);
        
        $this->db->set('user_id', $id);
        $this->db->insert('{PRE}users_data'); 
        
        if($set_cell > 0){
            $group = $this->get_group($group_id);
            $this->db->insert('{PRE}cells', array('name' => $group->cell_name)); 
            $cell_id = $this->db->insert_id();
            $this->db->set('cell_id', $cell_id);
            $this->db->set($set_in_cell);
            $this->db->set($set);
            $this->db->set('user_id', $id);
            $this->db->insert('{PRE}users_data');
        }
 
        return $this->db->affected_rows();
    }
       
}