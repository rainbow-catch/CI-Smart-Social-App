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

}
