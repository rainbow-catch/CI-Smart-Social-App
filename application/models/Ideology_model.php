<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/16/2021
 * Time: 5:05 AM
 */


class Ideology_Model extends CI_Model
{

    public function get_questions()
    {
        return $this->db->get("ideology_questions")->result_array();
    }

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
}
