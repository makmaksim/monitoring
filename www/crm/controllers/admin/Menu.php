<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Copyright © 2015 г. Коротков Е.А.

Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми «Программное Обеспечение»), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение копий Программного Обеспечения, а также лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:

Запрещено изменять (модернизировать, удалять, скрывать любыми средствами) ссылку на Веб-сайт разработчика данного Програмного Обеспечения.
Данное Программное Обеспечение является бесплатным только на стадии разработки, пользователь имеет право бесплатного пользования только версиями данного Программное Обеспечение без пометки "final" и "release".Запрещено продавать копии, а также модернизированные версии данного Програмного Обеспечения
Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.

ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ ГАРАНТИИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ, НО НЕ ОГРАНИЧИВАЯСЬ ИМИ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО КАКИМ-ЛИБО ИСКАМ, ЗА УЩЕРБ ИЛИ ПО ИНЫМ ТРЕБОВАНИЯМ, В ТОМ ЧИСЛЕ, ПРИ ДЕЙСТВИИ КОНТРАКТА, ДЕЛИКТЕ ИЛИ ИНОЙ СИТУАЦИИ, ВОЗНИКШИМ ИЗ-ЗА ИСПОЛЬЗОВАНИЯ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫХ ДЕЙСТВИЙ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ. */

class Menu extends UMC_Controller{
    
    private $types_item = array();
    
    function __construct(){
        parent::__construct();
        $this->load->model('admin/menumodel', 'model', true);
        $this->lang->load('menu');
        $this->types_item = array(
            lang('seperator_type'),
            lang('userlest_type'),
            lang('celllist_type')
        );
    }
    
    public function index(){
        $this->load->helper('header');
        
        $data = array();
        
        $data['menu'] = $this->model->get_menu();
        
        get_header($this);
        $this->load->view('admin/menu/menu', $data);
        get_footer($this);
    }
    
    public function get_new_item_form(){
        $data = array();
        $data['parents'] = $this->model->get_parents();
        $data['groups'] = $this->model->get_groups();
        $data['types_item'] = $this->types_item;
        $this->load->view('admin/menu/add_menu_item', $data);
    }
    
    public function get_edit_item_form(){
        $menu_id = $this->input->post('id');
        $data['parents'] = $this->model->get_parents();
        $data['groups'] = $this->model->get_groups();
        $data['item'] = $this->model->get_menu_item($menu_id);
        $data['fields'] = $this->model->get_fields_group($data['item']->group_id);
        $data['types_item'] = $this->types_item;
        $this->load->view('admin/menu/edit_menu_item', $data);
    }
    
    public function get_fields_group(){
        $group_id = $this->input->post('id');
        if($group_id){
            $fields = $this->model->get_fields_group($group_id);
        }else{
            $fields = array();
        }
        
        echo json_encode(array('error' => FALSE, 'fields' => $fields));

    }
    
    public function add_menu_item(){
        $post = $this->input->post();
        if(isset($post['fields'])){
            $post['fields'] = serialize($post['fields']);
        }else{
            $post['fields'] = serialize(array());
        }
        $this->model->db->insert('menu', $post);
        echo json_encode(array('error' => FALSE));
    }
    
    public function edit_menu_item(){
        $post = $this->input->post();
        $menu_id = $post['menu_id'];
        unset($post['menu_id']);
        if(isset($post['fields'])){
            $post['fields'] = serialize($post['fields']);
        }else{
            $post['fields'] = serialize(array());
        }
        $this->model->db->where('menu_id', $menu_id)->update('menu', $post);
        echo json_encode(array('error' => FALSE));
    }
    
    public function update_sort_items(){
        $this->model->update_sort_items($this->input->post('ids'));
        print_r('ok');
    }
    
    public function remove_item(){
        $menu_id = $this->input->post('id');
        $item_count = $this->model->get_menu_item_children($menu_id);
        if($item_count->count > 0){
            echo json_encode(array('error' => TRUE, 'mess' => lang('delete_child_items_error')));
        }else{
            $this->model->db->where('menu_id', $menu_id)->delete('menu');
            echo json_encode(array('error' => FALSE));
        }
    }
}