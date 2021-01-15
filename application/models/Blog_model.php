<?php

class Blog_Model extends CI_Model 
{

	public function get_user_blog($userid) 
	{
		return $this->db->where("userid", $userid)->get("user_blogs");
	}

	public function add_blog($data) 
	{
		$this->db->insert("user_blogs", $data);
	}

	public function delete_blog($id) 
	{
		$this->db->where("ID", $id)->delete("user_blogs");
	}

	public function update_blog($id, $data) 
	{
		$this->db->where("ID", $id)->update("user_blogs", $data);
	}

	public function get_blog($id) 
	{
		return $this->db->where("ID", $id)->get("user_blogs");
	}

	public function add_post($data) 
	{
		$this->db->insert("user_blog_posts", $data);
		return $this->db->insert_ID();
	}

	public function update_post($id, $data) 
	{
		$this->db->where("ID", $id)->update("user_blog_posts", $data);
	}

	public function delete_post($id) 
	{
		$this->db->where("ID", $id)->delete("user_blog_posts");
	}

	public function get_post($id) 
	{
		return $this->db->where("ID", $id)->get("user_blog_posts");
	}

	public function get_total_blog_posts($id) 
	{
		return $this->db->where("blogid", $id)->from("user_blog_posts")->count_all_results();
	}

	public function get_blog_posts($blogid, $datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"user_blog_posts.title",
			),
			true // Cache query
		);
		$this->db->where("user_blog_posts.blogid", $blogid);
		$this->db->select("user_blog_posts.title, user_blog_posts.ID, user_blog_posts.timestamp,
			user_blog_posts.last_updated, user_blog_posts.status, user_blog_posts.image, user_blog_posts.views");

		return $datatable->get("user_blog_posts");
	}

	public function delete_all_blog_posts($blogid) 
	{
		$this->db->where("blogid", $blogid)->delete("user_blog_posts");
	}

	public function get_new_public_posts($page) 
	{
		return $this->db->where("user_blog_posts.status", 1)->where("user_blogs.private", 0)
			->select("user_blog_posts.ID, user_blog_posts.title, user_blog_posts.body,
				user_blog_posts.status, user_blog_posts.last_updated, user_blog_posts.image,
				user_blogs.ID as blogid, user_blogs.userid,
				users.ID as userid, users.first_name, users.last_name, users.avatar,
				users.online_timestamp, users.username")
			->join("user_blogs", "user_blogs.ID = user_blog_posts.blogid")
			->join("users", "users.ID = user_blogs.userid")
			->limit(10, $page)
			->order_by("user_blog_posts.timestamp", "desc")
			->get("user_blog_posts");
	}

	public function get_total_public_posts() 
	{
		return $this->db->where("user_blog_posts.status", 1)->where("user_blogs.private", 0)
			->join("user_blogs", "user_blogs.ID = user_blog_posts.blogid")
			->join("users", "users.ID = user_blogs.userid")
			->from("user_blog_posts")->count_all_results();
	}

	public function get_total_blog_posts_published($blogid) 
	{
		return $this->db
			->where("user_blog_posts.blogid", $blogid)
			->where("user_blog_posts.status", 1)->from("user_blog_posts")
			->count_all_results();
	}

	public function get_blog_posts_published($blogid, $page) 
	{
		return $this->db
			->where("user_blog_posts.status", 1)->where("user_blog_posts.blogid", $blogid)
			->select("user_blog_posts.ID, user_blog_posts.title, user_blog_posts.body,
				user_blog_posts.status, user_blog_posts.last_updated, user_blog_posts.image,
				user_blogs.ID as blogid, user_blogs.userid,
				users.ID as userid, users.first_name, users.last_name, users.avatar,
				users.online_timestamp, users.username")
			->join("user_blogs", "user_blogs.ID = user_blog_posts.blogid")
			->join("users", "users.ID = user_blogs.userid")
			->limit(10, $page)
			->order_by("user_blog_posts.timestamp", "desc")
			->get("user_blog_posts");
	}

	public function check_user_subscriber($blogid, $userid) 
	{
		return $this->db->where("userid", $userid)->where("blogid" ,$blogid)->get("user_blog_subscribers");
	}

	public function add_subscriber($data) 
	{
		$this->db->insert("user_blog_subscribers", $data);
	}

	public function delete_subscriber($id) 
	{
		$this->db->where("ID", $id)->delete("user_blog_subscribers");
	}

	public function get_subscribers($id) 
	{
		return $this->db->where("user_blog_subscribers.blogid", $id)
			->select("users.ID as userid, users.username, users.email, users.email_notification,
				user_blog_subscribers.ID")
			->join("users", "users.ID = user_blog_subscribers.userid")
			->get("user_blog_subscribers");
	}

}


?>