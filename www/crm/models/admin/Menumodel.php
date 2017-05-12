<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* Copyright © 2015 г. Коротков Е.А.

Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми «Программное Обеспечение»), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение копий Программного Обеспечения, а также лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:

Запрещено изменять (модернизировать, удалять, скрывать любыми средствами) ссылку на Веб-сайт разработчика данного Програмного Обеспечения.
Данное Программное Обеспечение является бесплатным только на стадии разработки, пользователь имеет право бесплатного пользования только версиями данного Программное Обеспечение без пометки "final" и "release".Запрещено продавать копии, а также модернизированные версии данного Програмного Обеспечения
Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.

ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ ГАРАНТИИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ, НО НЕ ОГРАНИЧИВАЯСЬ ИМИ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО КАКИМ-ЛИБО ИСКАМ, ЗА УЩЕРБ ИЛИ ПО ИНЫМ ТРЕБОВАНИЯМ, В ТОМ ЧИСЛЕ, ПРИ ДЕЙСТВИИ КОНТРАКТА, ДЕЛИКТЕ ИЛИ ИНОЙ СИТУАЦИИ, ВОЗНИКШИМ ИЗ-ЗА ИСПОЛЬЗОВАНИЯ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫХ ДЕЙСТВИЙ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ. */

class MenuModel extends UMC_Model{
    
    function get_parents(){
        return $this->db->order_by('sort', 'ASC')->get_where('menu', array('parent_id' => 0))->result();
    }
    
    function get_groups(){
        return $this->db->get('user_groups')->result();
    }
    
    function get_fields_group($group_id){
        $sql = 'SELECT f.`field_id`, f.`name`, f.`in_cell` FROM {PRE}fields AS f
                    LEFT JOIN {PRE}fields_groups AS fg USING(`field_id`)
                    WHERE fg.`group_id` = "' . (int)$group_id . '"
                    ORDER BY `sort` ASC';
        $res = $this->db->query($sql)->result();
        $fields = array();
        $fields_cel = array();
        foreach($res as $row){
            if($row->in_cell == 0){
                $fields[] = $row;
            }else{
                $fields_cel[] = $row;
            }
        }

        return array_merge($fields, $fields_cel);
    }
    
    function get_menu(){
        $list = $this->db->order_by('sort', 'ASC')->get('menu');
        $menu = array();
        foreach($list->result() as $val){
            $menu[$val->parent_id][] = $val;
        }
        return $menu;
    }
    
    function update_sort_items($data){
        foreach($data as $key => $value){
            $sql = 'UPDATE {PRE}menu 
                    SET 
                        `sort` = ' . $this->db->escape($key) . '
                    WHERE 
                        `menu_id` = ' . $this->db->escape($value);
            $this->db->query($sql);
        }
        
        return true;
    }
    
    function get_menu_item($menu_id){
        return $this->db->get_where('menu', array('menu_id' => $menu_id))->row();
    }
    
    function get_menu_item_children($menu_id){
        return $this->db->select('COUNT(*) AS count')->get_where('menu', array('parent_id' => $menu_id))->row();
    }
}