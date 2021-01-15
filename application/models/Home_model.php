<?php

class Home_Model extends CI_Model 
{

	public function get_home_stats() 
	{
		return $this->db->get("home_stats");
	}

	public function update_home_stats($stats) 
	{
		$this->db->where("ID", 1)->update("home_stats", array(
			"google_members" => $stats->google_members,
			"facebook_members" => $stats->facebook_members,
			"twitter_members" => $stats->twitter_members,
			"total_members" => $stats->total_members,
			"new_members" => $stats->new_members,
			"active_today" => $stats->active_today,
			"timestamp" => time()
			)
		);
	}

	public function get_email_template($id) 
	{
		return $this->db->where("ID", $id)->get("email_templates");
	}

	public function get_email_template_hook($hook, $language) 
	{
		return $this->db->where("hook", $hook)
			->where("language", $language)->get("email_templates");
	}

	public function get_random_ad() 
	{
		return $this->db->where("pageviews >", 0)->where("status", 2)->order_by("RAND()")->get("rotation_ads");
	}

	public function decrease_ad_pageviews($id) 
	{
		$this->db->where("ID", $id)->set("pageviews", "pageviews-1", FALSE)->update("rotation_ads");
	}

	public function add_rotation_ad($data) 
	{
		$this->db->insert("rotation_ads", $data);
	}

	public function add_promoted_post($data) 
	{
		$this->db->insert("promoted_posts", $data);
	}

	public function get_promoted_post_by_postid($postid) 
	{
		return $this->db->where("postid", $postid)->get("promoted_posts");
	}

}

?>