<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Copyright © 2015 г. Коротков Е.А.

Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми «Программное Обеспечение»), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение копий Программного Обеспечения, а также лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:

Запрещено изменять (модернизировать, удалять, скрывать любыми средствами) ссылку на Веб-сайт разработчика данного Програмного Обеспечения.
Данное Программное Обеспечение является бесплатным только на стадии разработки, пользователь имеет право бесплатного пользования только версиями данного Программное Обеспечение без пометки "final" и "release".Запрещено продавать копии, а также модернизированные версии данного Програмного Обеспечения
Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.

ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ ГАРАНТИИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ, НО НЕ ОГРАНИЧИВАЯСЬ ИМИ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО КАКИМ-ЛИБО ИСКАМ, ЗА УЩЕРБ ИЛИ ПО ИНЫМ ТРЕБОВАНИЯМ, В ТОМ ЧИСЛЕ, ПРИ ДЕЙСТВИИ КОНТРАКТА, ДЕЛИКТЕ ИЛИ ИНОЙ СИТУАЦИИ, ВОЗНИКШИМ ИЗ-ЗА ИСПОЛЬЗОВАНИЯ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫХ ДЕЙСТВИЙ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ. */

class HeadersModel extends UMC_Model{

    function get_roups(){
        $sql = 'SELECT * FROM {PRE}user_groups ORDER BY `sort` ASC';
        $query = $this->db->query($sql);
        $groups = array();
        foreach($query->result() as $val){
            if($this->perms->{$val->unique}){
                $groups[] = $val;
            }
        }
        
        return $groups;
    }
    
    function get_in_cons($user_id){
        $query = $this->db->get_where('{PRE}consultant_operator', array('user_id' => (int)$user_id), 1);
        return $query->row();
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
    
    function get_menu(){
        $sql = 'SELECT m.*, g.`unique` FROM {PRE}menu AS m
                    LEFT JOIN {PRE}user_groups AS g USING(`group_id`)
                    ORDER BY m.`sort` ASC';
        $list = $this->db->query($sql);
        $menu = array();

        foreach($list->result() as $val){
            if(!$val->unique || $this->perms->{$val->unique}){
                $menu[$val->parent_id][] = $val;
            }
        }
        return $menu;
    }
    
}