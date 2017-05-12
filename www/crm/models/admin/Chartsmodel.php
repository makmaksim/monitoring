<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* Copyright © 2015 г. Коротков Е.А.

Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми «Программное Обеспечение»), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение копий Программного Обеспечения, а также лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:

Запрещено изменять (модернизировать, удалять, скрывать любыми средствами) ссылку на Веб-сайт разработчика данного Програмного Обеспечения.
Данное Программное Обеспечение является бесплатным только на стадии разработки, пользователь имеет право бесплатного пользования только версиями данного Программное Обеспечение без пометки "final" и "release".Запрещено продавать копии, а также модернизированные версии данного Програмного Обеспечения
Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.

ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ ГАРАНТИИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ, НО НЕ ОГРАНИЧИВАЯСЬ ИМИ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО КАКИМ-ЛИБО ИСКАМ, ЗА УЩЕРБ ИЛИ ПО ИНЫМ ТРЕБОВАНИЯМ, В ТОМ ЧИСЛЕ, ПРИ ДЕЙСТВИИ КОНТРАКТА, ДЕЛИКТЕ ИЛИ ИНОЙ СИТУАЦИИ, ВОЗНИКШИМ ИЗ-ЗА ИСПОЛЬЗОВАНИЯ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫХ ДЕЙСТВИЙ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ. */

class ChartsModel extends UMC_Model{
    
    function get_groups(){
        return $this->db->order_by('sort', 'ASC')->get('user_groups')->result();
    }
    
    function get_charts(){
        $charts = $this->db->get('charts')->result();
        foreach($charts as $key => $chart){
            $charts[$key]->fields = $this->get_fields($chart->group_id);
        }
        
        return $charts;
    }
    
    function get_fields($group_id){
        $sql = 'SELECT f.* FROM {PRE}fields AS f
                    JOIN {PRE}fields_groups AS fg USING(`field_id`)
                    WHERE fg.`group_id` = "' . (int)$group_id . '"
                    ORDER BY f.`sort` ASC';
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
    
    function insert_new_chart($post){
        $this->db->insert('charts', $post);
        return TRUE;
    }
    
    function update_chart($post){
        $this->db->where('id', $post['id'])
             ->set('name', $post['name'])
             ->set('description', $post['description'])
             ->set('status', $post['status'])
             ->set('group_id', $post['group_id'])
             ->set('params', serialize($post['params']))
             ->update('charts');
        return TRUE;             
    }
}