<?php

class Page_Model extends CI_Model 
{

	public function get_all_categories() 
	{
		return $this->db->get("page_categories");
	}

	public function get_page_by_slug($slug) 
	{
		return $this->db->where("slug", $slug)->get("pages");
	}

	public function get_page($id) 
	{
		return $this->db->where("ID", $id)->get("pages");
	}

	public function delete_page($id) 
	{
		$this->db->where("ID", $id)->delete("pages");
		$this->db->where("pageid", $id)->delete("page_users");
		$this->db->where("pageid", $id)->delete("page_invites");
		$this->db->where("pageid", $id)->delete("calendar_events");
		
		$albums = $this->db->where("pageid", $id)->get("user_albums");
		foreach($albums->result() as $r) {
			$this->db->where("ID", $r->ID)->delete("user_albums");
			$this->db->where("albumid", $r->ID)->delete("user_images");
		}
		$items = $this->db->where("pageid", $id)->get("feed_item");
		foreach($items->result() as $r) {
			$this->db->where("postid", $r->ID)->delete("feed_item_comments");
			$this->db->where("ID", $r->ID)->delete("feed_item");
		}

	}

	public function add_page($data) 
	{
		$this->db->insert("pages", $data);
		return $this->db->insert_ID();
	}

	public function add_page_user($data) 
	{
		$this->db->insert("page_users", $data);
	}

	public function get_page_category($id) 
	{
		return $this->db->where("ID", $id)->get("page_categories");
	}

	public function get_page_user($id, $userid) 
	{
		return $this->db->where("pageid", $id)
			->where("userid", $userid)->get("page_users");
	}

	public function get_page_member($id) 
	{
		return $this->db->where("ID", $id)->get("page_users");
	}

	public function update_page($id, $data) 
	{
		$this->db->where("ID", $id)->update("pages", $data);
	}

	public function get_total_user_pages($userid) 
	{
		$this->db->where("page_users.userid", $userid);
		$this->db->select("pages.ID");
		$this->db->join("pages", "pages.ID = page_users.pageid");
		$this->db->join("page_categories", "page_categories.ID = pages.categoryid");
		$this->db->group_by("pages.ID");
		return $this->db->from("page_users")->count_all_results();
	}

	public function get_user_pages($userid, $datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"pages.name",
			"page_categories.name"
			),
			true // Cache query
		);
		$this->db->where("page_users.userid", $userid);
		$this->db->select("pages.name, pages.pageviews, pages.likes, pages.ID,
			pages.slug, pages.timestamp, pages.members,
			page_categories.name as category_name,
			page_users.roleid");
		$this->db->join("pages", "pages.ID = page_users.pageid");
		$this->db->join("page_categories", "page_categories.ID = pages.categoryid");
		$this->db->group_by("pages.ID");

		return $datatable->get("page_users");

	}

	public function get_total_members($pageid) 
	{
		return $this->db->where("page_users.pageid", $pageid)
			->from("page_users")->count_all_results();
	}

	public function get_page_members_dt($pageid, $datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"users.username",
			"users.first_name",
			"users.last_name",
			),
			true // Cache query
		);
		$this->db->where("page_users.pageid", $pageid);
		$this->db->select("users.ID as userid, users.username, users.first_name,
			users.last_name, users.avatar, users.online_timestamp, 
			page_users.ID, page_users.roleid");
		$this->db->join("users", "users.ID = page_users.userid");

		return $datatable->get("page_users");

	}

	public function delete_page_user($id) 
	{
		$this->db->where("ID", $id)->delete("page_users");
	}

	public function update_page_user($id, $data) 
	{
		$this->db->where("ID", $id)->update("page_users", $data);
	}

	public function get_page_users_preview($id) 
	{
		return $this->db->where("page_users.pageid", $id)
			->select("users.ID as userid, users.username, users.avatar,
				users.first_name, users.last_name, users.online_timestamp,
				page_users.ID")
			->join("users", "users.ID = page_users.userid")
			->limit(6)
			->get("page_users");
	}

	public function add_page_invite($data) 
	{
		$this->db->insert("page_invites", $data);
	}

	public function get_page_invite($id, $userid) 
	{
		return $this->db->where("pageid", $id)
			->where("userid", $userid)->get("page_invites");
	}

	public function get_page_invites($userid) 
	{
		return $this->db->where("page_invites.userid", $userid)
			->select("pages.ID as pageid, pages.name, pages.profile_avatar,
				pages.slug,
				users.first_name, users.last_name, users.username, users.avatar,
				users.online_timestamp,
				page_invites.ID, page_invites.timestamp")
			->join("users", "users.ID = page_invites.fromid")
			->join("pages", "pages.ID = page_invites.pageid")
			->get("page_invites");
	}

	public function get_page_invite_id($id) 
	{
		return $this->db->where("ID", $id)->get("page_invites");
	}

	public function delete_page_invite($id) 
	{
		$this->db->where("ID", $id)->delete("page_invites");
	}

	public function get_recent_pages() 
	{
		return $this->db->where("type", 0)->order_by("ID", "DESC")->limit(5)->get("pages");
	}

	public function get_pages_by_name($query) 
	{
		return $this->db->like("name", $query)->limit(10)->get("pages");
	}

	public function increment_page_members($id) 
	{
		$this->db->where("ID", $id)->set("members", "members+1", FALSE)->update("pages");
	}

	public function decrement_page_members($id) 
	{
		$this->db->where("ID", $id)->set("members", "members-1", FALSE)->update("pages");
	}

	public function get_total_pages_public() 
	{
		return $this->db->where("type", 0)->from("pages")->count_all_results();
	}

	public function get_all_pages_public($datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"pages.name",
			"page_categories.name"
			),
			true // Cache query
		);

		$this->db->where("pages.type", 0)->select("pages.name, pages.pageviews, pages.likes, pages.ID,
			pages.slug, pages.timestamp, pages.members,
			page_categories.name as category_name");
		$this->db->join("page_categories", "page_categories.ID = pages.categoryid");

		return $datatable->get("pages");

	}

	public function get_total_pages() 
	{
		return $this->db->from("pages")->count_all_results();
	}

	public function get_all_pages($datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"pages.name",
			"page_categories.name"
			),
			true // Cache query
		);
		$this->db->select("pages.name, pages.pageviews, pages.likes, pages.ID,
			pages.slug, pages.timestamp, pages.members,
			page_categories.name as category_name");
		$this->db->join("page_categories", "page_categories.ID = pages.categoryid");

		return $datatable->get("pages");

	}

}

?>