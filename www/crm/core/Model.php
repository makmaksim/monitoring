<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		EllisLab Dev Team
 * @copyright		Copyright (c) 2008 - 2014, EllisLab, Inc.
 * @copyright		Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

class CI_Model {

    public $perms;
    /**
     * Class constructor
     *
     * @return    void
     */
    public function __construct()
    {
        $this->get_perms();
        log_message('info', 'Model Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * __get magic
     *
     * Allows models to access CI's loaded classes using the same
     * syntax as controllers.
     *
     * @param    string    $key
     */
    public function __get($key)
    {
        // Debugging note:
        //    If you're here because you're getting an error message
        //    saying 'Undefined Property: system/core/Model.php', it's
        //    most likely a typo in your model code.
        return get_instance()->$key;
    }
    
    private function get_perms(){
        $sql = 'SELECT * FROM ' . $this->db->dbprefix . 'group_perms WHERE `group_id` = "' . (int)$this->session->userdata('group_id') . '"';
        $query = $this->db->query($sql);
        $this->perms = $query->row();        
    }

}

// END Model Class

/* End of file Model.php */
/* Location: ./system/core/Model.php */