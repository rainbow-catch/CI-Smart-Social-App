<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/19/2021
 * Time: 8:27 AM
 */

class Security_Model extends CI_Model
{
    public function get_questions()
    {
        return $this->db->get("security_questions");
    }

    public function get_question_by_id($id){
        return $this->db->where('ID', $id)->get("security_questions");
    }

    public function update_question($id, $data){
        $this->db->where('ID', $id)->update('security_questions', [
            'question'=>$data['question'],
            'active'=>$data['active'],
        ]);
    }

    public function add_question($data){
        $this->db->insert('security_questions', [
            'question'=>$data['question'],
            'active'=>$data['active'],
        ]);
    }

    public function delete_question($id){
        $this->db->where("ID", $id)->delete("security_questions");
    }
}

