<?php

defined('BASEPATH') or exit('No direct script access allowed');

class File_model extends App_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    // public function get($id = '')
    // {
    //     $this->db->where('staffid', get_staff_user_id());

    //     if (is_numeric($id)) {
    //         $this->db->where('todoid', $id);

    //         return $this->db->get(db_prefix().'todos')->row();
    //     }

    //     return $this->db->get(db_prefix().'todos')->result_array();
    // }

    /**
     * Add new user todo
     * @param mixed $data todo $_POST data
     */
    public function add($data)
    {
        $this->db->insert(db_prefix().'invoice_attachments', $data);
        return $this->db->insert_id();
    }


    /**
     * Delete todo
     * @param  mixed $id todo id
     * @return boolean
     */
    // public function delete_todo_item($id)
    // {
    //     $this->db->where('todoid', $id);
    //     $this->db->where('staffid', get_staff_user_id());
    //     $this->db->delete(db_prefix().'todos');
    //     if ($this->db->affected_rows() > 0) {
    //         return true;
    //     }

    //     return false;
    // }

}
