<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("feed_model");
		$this->load->model("image_model");
		$this->load->model("page_model");

	}

	public function reset_hashtag() 
	{
		$this->db->update("feed_hashtags", array("count" => 0));
	}

}

?>