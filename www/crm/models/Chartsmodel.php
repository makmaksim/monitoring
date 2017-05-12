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
        $charts = $this->db->where('status', '1')->get('charts')->result();
        foreach($charts as $key => $chart){
            $params = unserialize($chart->params);
            $sql = 'SELECT * FROM {PRE}fields WHERE `field_id` = "' . (int)$params['order_field'] . '" LIMIT 1';
            $field = $this->db->query($sql)->row();
            switch($field->type){
                case 'text' :
                case 'user' :
                    $sql = 'SELECT `' . $field->unique . '` FROM {PRE}users_data AS ud
                                JOIN {PRE}users AS us ON us.`id` = ud.`user_id`
                                WHERE us.`group_id` = "' . (int)$chart->group_id . '"
                                GROUP BY `' . $field->unique . '`';
                    break;
                case 'date' :
                case 'datetime' :
                    $sql = 'SELECT `' . $field->unique . '` FROM {PRE}users_data  AS ud
                                JOIN {PRE}users AS us ON us.`id` = ud.`user_id`
                                WHERE us.`group_id` = "' . (int)$chart->group_id . '" AND 
                                      ud.`' . $field->unique . '` > NOW() - INTERVAL 7 DAY 
                                GROUP BY `' . $field->unique . '`';
                    break;
//                default :
//                    return FALSE;
            }
            
            $order_field = $this->db->query($sql)->result();

            $charts[$key]->names[0] = '"'.str_replace("\"", "", $field->name).'"';
            if($params['type'] && $order_field){
                foreach($order_field as $k => $of){
                    if($of->{$field->unique}){
                        if($field->type == 'date' || $field->type == 'datetime'){
                            $charts[$key]->res[$k][] = '"'.date(lang('date_format'), strtotime($of->{$field->unique})).'"';
                        }else{
                            $charts[$key]->res[$k][] = '"'.str_replace("\"", "", $of->{$field->unique}).'"';
                        }
                        foreach($params['list_fields'] as $f){
                            $sql = 'SELECT * FROM {PRE}fields WHERE `field_id` = "' . (int)$f . '" LIMIT 1';
                            $field_l = $this->db->query($sql)->row();
                            switch($field_l->type){
                                case 'text' :
                                    $sql = 'SELECT SUM(ud.`' . $field_l->unique . '`) AS `' . $field_l->unique . '` FROM {PRE}users_data AS ud
                                                JOIN {PRE}users AS us ON us.`id` = ud.`user_id`
                                                WHERE ud.`' . $field->unique . '` = ' . $this->db->escape($of->{$field->unique}) . ' AND
                                                      us.`group_id` = "' . (int)$chart->group_id . '"
                                                GROUP BY `' . $field->unique . '`';
                                    break;
                                case 'user' :
                                case 'date' :
                                case 'datetime' :
                                    $sql = 'SELECT COUNT(ud.`' . $field_l->unique . '`) AS `' . $field_l->unique . '` FROM {PRE}users_data AS ud
                                                JOIN {PRE}users AS us ON us.`id` = ud.`user_id`
                                                WHERE ud.`' . $field->unique . '` = ' . $this->db->escape($of->{$field->unique}) . ' AND
                                                      us.`group_id` = "' . (int)$chart->group_id . '"
                                                GROUP BY `' . $field->unique . '`';
                                    break;
        //                        default :
        //                            return FALSE;
                            }
                            $res = $this->db->query($sql)->row();
                            if($res){
                                $charts[$key]->res[$k][] = str_replace("\"", "", $res->{$field_l->unique});
                                $charts[$key]->names[$f] = '"'.str_replace("\"", "", $field_l->name).'"';
                            }
                        }
                    }
                }
            }            
        }
        return $charts;
    }
}