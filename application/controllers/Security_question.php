<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/19/2021
 * Time: 8:24 AM
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Security_question extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("security_model");
    }

    public function index()
    {
        $this->template->loadContent("security_question", array(
                "questions" => $this->security_model->get_questions()
            )
        );
    }
    public function confirm_password(){
        $cp = $this->user->getPassword();
        if(!empty($cp)) {
            $phpass = new PasswordHash(12, false);
            if (!$phpass->CheckPassword($this->input->post('password'), $cp)) {
                $this->template->error("You entered the wrong password");
            }
            else{
                $this->user->reset_security_answered(1);
                return redirect(site_url("user_settings"));
            }
        }
        else
            $this->template->error("Oops. Something goes wrong.");
    }
    public function confirm_answer(){
        if($this->input->post('answer')==$this->user->info->security_answer){
            $this->user->reset_security_answered(1);
            return redirect(site_url("user_settings"));
        }
        else
            $this->template->error("Wrong answer! Please try again.");
    }
}
