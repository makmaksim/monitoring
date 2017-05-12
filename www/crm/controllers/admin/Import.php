<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Copyright © 2015 г. Коротков Е.А.

Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми «Программное Обеспечение»), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение копий Программного Обеспечения, а также лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:

Запрещено изменять (модернизировать, удалять, скрывать любыми средствами) ссылку на Веб-сайт разработчика данного Програмного Обеспечения.
Данное Программное Обеспечение является бесплатным только на стадии разработки, пользователь имеет право бесплатного пользования только версиями данного Программное Обеспечение без пометки "final" и "release".Запрещено продавать копии, а также модернизированные версии данного Програмного Обеспечения
Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.

ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ ГАРАНТИИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ, НО НЕ ОГРАНИЧИВАЯСЬ ИМИ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО КАКИМ-ЛИБО ИСКАМ, ЗА УЩЕРБ ИЛИ ПО ИНЫМ ТРЕБОВАНИЯМ, В ТОМ ЧИСЛЕ, ПРИ ДЕЙСТВИИ КОНТРАКТА, ДЕЛИКТЕ ИЛИ ИНОЙ СИТУАЦИИ, ВОЗНИКШИМ ИЗ-ЗА ИСПОЛЬЗОВАНИЯ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫХ ДЕЙСТВИЙ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ. */

class Import extends UMC_Controller{
    
    public function index(){
        
        $this->lang->load('import');
        $this->load->model('admin/importmodel');
        $this->load->helper('header');
        
        $data['groups'] = $this->importmodel->get_all_groups();
        $data['fields'] = $this->importmodel->get_all_fields();
        
        get_header($this);
        $this->load->view('admin/import', $data);
        get_footer($this);
    }
    
    public function start_import(){

        $this->load->model('admin/importmodel');
        
        $config['upload_path'] = './temp/';
        $config['allowed_types'] = 'text|txt|csv';
        $config['max_size']    = '0';

        $this->load->library('upload', $config);

        if(! $this->upload->do_upload('importfile')){
            $mess[] = array('type' => 'error', 'message' => $this->upload->display_errors());
            
        }else{
            $post = $this->input->post();

            $filedata = $this->upload->data();
            $data_file = array();
            $row = 0;
            $i = 0;
            if (($handle = fopen($filedata['full_path'], 'r')) !== FALSE) {
                while (($data = fgetcsv($handle, 0, $post['separator'])) !== FALSE) {
                    $row++;
                    
                    foreach($post['field'] as $key => $val){
                        $arr[] = $this->importmodel->db->escape($data[$key]);
                    }
                    
                    $data_file[] = $arr;
                    unset($arr);
                    if($row == 30){
                                               
                        $this->importmodel->save_import_data($data_file, $post['group_id'], $post['field'], $row);
                        $row = 0;
                        unset($data_file);
                        $data_file = array();
                    }
                }
                
                $count = count($data_file);
                if($count){
                    $this->importmodel->save_import_data($data_file, $post['group_id'], $post['field'], $count);
                }
                $mess[] = array('type' => 'mess', 'message' => lang('count_rows_text') . $count);
                fclose($handle);
            }else{
                $mess[] = array('type' => 'error', 'message' => lang('file_not_read'));
            }
            
            
            
        }
        $this->session->set_userdata('mess', $mess);
        redirect('/admin/import');
        
    }
    
}