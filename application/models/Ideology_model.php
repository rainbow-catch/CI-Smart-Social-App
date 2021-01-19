<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/16/2021
 * Time: 5:05 AM
 */


class Ideology_Model extends CI_Model
{

    public function get_ideologies()
    {
        return $this->db->get("ideologies");
    }

    public function get_ideology_by_id($id){
        return $this->db->where('ID', $id)->get('ideologies');
    }

    public function update_ideology($id, $data){
        $this->db->where('ID', $id)->update('ideologies', [
            'ideology'=>$data['ideology'],
            'icon'=>$data['icon'],
            'active'=>$data['active'],
        ]);
    }

    public function add_ideology($data){
        $this->db->insert('ideologies', [
            'ideology'=>$data['ideology'],
            'icon'=>$data['icon'],
            'active'=>$data['active'],
        ]);
    }

    public function delete_ideology($id){
        $this->db->where("ID", $id)->delete("ideologies");
    }


    public function get_questions()
    {
        return $this->db->get("ideology_questions")->result_array();
    }

    public function get_ideology_question_by_id($id){
        return $this->db->where('ID', $id)->get('ideology_questions');
    }

    public function update_ideology_question($id, $data){
        $this->db->where('ID', $id)->update('ideology_questions', [
            'question'=>$data['question'],
            'answers'=>$data['answers'],
            'active'=>$data['active'],
        ]);
    }

    public function add_ideology_question($data){
        $this->db->insert('ideology_questions', [
            'question'=>$data['question'],
            'answers'=>$data['answers'],
            'active'=>$data['active'],
        ]);
    }

    public function delete_ideology_question($id){
        $this->db->where("ID", $id)->delete("ideology_questions");
    }
}
