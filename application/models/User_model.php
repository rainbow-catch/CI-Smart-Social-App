<?php

class User_Model extends CI_Model 
{

	public function getUser($email, $pass) 
	{
		return $this->db->select("ID")
		->where("email", $email)->where("password", $pass)->get("users");
	}

	public function get_user_by_id($userid) 
	{
		return $this->db->where("ID", $userid)->get("users");
	}

	public function get_user_by_username($username) 
	{
		return $this->db->where("username", $username)->get("users");
	}

	public function delete_user($id) 
	{
		$this->db->where("ID", $id)->delete("users");
	}

	public function get_new_members($limit) 
	{
		return $this->db->select("email, username, joined, oauth_provider, 
			avatar")
		->order_by("ID", "DESC")->limit($limit)->get("users");
	}

	public function get_registered_users_date($month, $year) 
	{
		$s= $this->db->where("joined_date", $month . "-" . $year)->select("COUNT(*) as num")->get("users");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_oauth_count($provider) 
	{
		$s= $this->db->where("oauth_provider", $provider)->select("COUNT(*) as num")->get("users");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_total_members_count() 
	{
		$s= $this->db->select("COUNT(*) as num")->get("users");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_active_today_count() 
	{
		$s= $this->db->where("online_timestamp >", time() - 3600*24)->select("COUNT(*) as num")->get("users");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_new_today_count() 
	{
		$s= $this->db->where("joined >", time() - 3600*24)->select("COUNT(*) as num")->get("users");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_online_count() 
	{
		$s= $this->db->where("online_timestamp >", time() - 60*15)->select("COUNT(*) as num")->get("users");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_members($datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"users.username",
			"users.first_name",
			"users.last_name",
			"user_roles.name"
			)
		);

		return $this->db->select("users.username, users.email, users.first_name, 
			users.last_name, users.ID, users.joined, users.oauth_provider,
			users.user_role, users.online_timestamp, users.avatar,
			user_roles.name as user_role_name")
		->join("user_roles", "user_roles.ID = users.user_role", 
				 	"left outer")
		->limit($datatable->length, $datatable->start)
		->get("users");
	}

	public function get_members_admin($datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"users.username",
			"users.first_name",
			"users.last_name",
			"user_roles.name",
			"users.email"
			)
		);

		return $this->db->select("users.username, users.email, users.first_name, 
			users.last_name, users.ID, users.joined, users.oauth_provider,
			users.user_role, users.online_timestamp, users.avatar,
			user_roles.name as user_role_name")
		->join("user_roles", "user_roles.ID = users.user_role", 
				 	"left outer")
		->limit($datatable->length, $datatable->start)
		->get("users");
	}

	public function get_members_by_search($search) 
	{
		return $this->db->select("users.username, users.first_name, 
			users.last_name, users.ID, users.joined, users.oauth_provider,
			users.user_role, user_roles.name as user_role_name")
		->join("user_roles", "user_roles.ID = users.user_role", 
				 	"left outer")
		->limit(20)
		->like("users.username", $search)
		->get("users");
	}

	public function search_by_username($search) 
	{
		return $this->db->select("users.username, users.email, users.first_name, 
			users.last_name, users.ID, users.joined, users.oauth_provider,
			users.user_role, user_roles.name as user_role_name")
		->join("user_roles", "user_roles.ID = users.user_role", 
				 	"left outer")
		->limit(20)
		->like("users.username", $search)
		->get("users");
	}

	public function search_by_email($search) 
	{
		return $this->db->select("users.username, users.email, users.first_name, 
			users.last_name, users.ID, users.joined, users.oauth_provider,
			users.user_role, user_roles.name as user_role_name")
		->join("user_roles", "user_roles.ID = users.user_role", 
				 	"left outer")
		->limit(20)
		->like("users.email", $search)
		->get("users");
	}

	public function search_by_first_name($search) 
	{
		return $this->db->select("users.username, users.email, users.first_name, 
			users.last_name, users.ID, users.joined, users.oauth_provider,
			users.user_role, user_roles.name as user_role_name")
		->join("user_roles", "user_roles.ID = users.user_role", 
				 	"left outer")
		->limit(20)
		->like("users.first_name", $search)
		->get("users");
	}

	public function search_by_last_name($search) 
	{
		return $this->db->select("users.username, users.email, users.first_name, 
			users.last_name, users.ID, users.joined, users.oauth_provider,
			users.user_role, user_roles.name as user_role_name")
		->join("user_roles", "user_roles.ID = users.user_role", 
				 	"left outer")
		->limit(20)
		->like("users.last_name", $search)
		->get("users");
	}

	public function get_notifications($userid) 
    {
    	return $this->db
    		->where("user_notifications.userid", $userid)
    		->select("users.ID as userid, users.username, users.avatar,
    			user_notifications.timestamp, user_notifications.message,
    			user_notifications.url, user_notifications.ID, 
    			user_notifications.status")
    		->join("users", "users.ID = user_notifications.fromid")
    		->limit(5)
    		->order_By("user_notifications.ID", "DESC")
    		->get("user_notifications");
    }

    public function get_notifications_unread($userid) 
    {
    	return $this->db
    		->where("user_notifications.userid", $userid)
    		->select("users.ID as userid, users.username, users.avatar,
    			user_notifications.timestamp, user_notifications.message,
    			user_notifications.url, user_notifications.ID, 
    			user_notifications.status")
    		->join("users", "users.ID = user_notifications.fromid")
    		->limit(5)
    		->where("user_notifications.status", 0)
    		->order_By("user_notifications.ID", "DESC")
    		->get("user_notifications");
    }

    public function get_notification($id, $userid) 
    {
    	return $this->db
    		->where("user_notifications.userid", $userid)
    		->where("user_notifications.ID", $id)
    		->select("users.ID as userid, users.username, users.avatar,
    			user_notifications.timestamp, user_notifications.message,
    			user_notifications.url, user_notifications.ID, 
    			user_notifications.status")
    		->join("users", "users.ID = user_notifications.fromid")
    		->order_By("user_notifications.ID", "DESC")
    		->get("user_notifications");
    }

    public function get_notifications_all($userid, $datatable) 
    {
    	$datatable->db_order();

		$datatable->db_search(array(
			"users.username",
			"user_notifications.message",
			)
		);

    	return $this->db
    		->where("user_notifications.userid", $userid)
    		->select("users.ID as userid, users.username, users.avatar,
    			users.online_timestamp,
    			user_notifications.timestamp, user_notifications.message,
    			user_notifications.url, user_notifications.ID, 
    			user_notifications.status")
    		->join("users", "users.ID = user_notifications.fromid")
    		->limit($datatable->length, $datatable->start)
    		->order_By("user_notifications.ID", "DESC")
    		->get("user_notifications");
    }

    public function get_notifications_all_fp($userid, $page, $max=10) 
    {
    	return $this->db
    		->where("user_notifications.userid", $userid)
    		->select("users.ID as userid, users.username, users.avatar,
    			users.online_timestamp,
    			user_notifications.timestamp, user_notifications.message,
    			user_notifications.url, user_notifications.ID, 
    			user_notifications.status")
    		->join("users", "users.ID = user_notifications.fromid")
    		->limit($max, $page)
    		->order_By("user_notifications.ID", "DESC")
    		->get("user_notifications");
    }

    public function get_notifications_all_total($userid) 
    {
    	$s = $this->db
    		->where("user_notifications.userid", $userid)
    		->select("COUNT(*) as num")
    		->get("user_notifications");
    	$r = $s->row();
    	if(isset($r->num)) return $r->num;
    	return 0;
    }

    public function add_notification($data) 
    {
    	if(isset($data['email']) && isset($data['email_notification']) 
    		&& $data['email_notification']) {
	    	// Send Email
	    	$subject = $this->settings->info->site_name . lang("ctn_670");
	    	
	    	if(isset($data['username'])) {
				$username = $data['username'] . ",";
			} else {
				$username = lang("ctn_339");
			}

			if(!isset($_COOKIE['language'])) {
				// Get first language in list as default
				$lang = $this->config->item("language");
			} else {
				$lang = $this->common->nohtml($_COOKIE["language"]);
			}

			// Send Email
			$this->load->model("home_model");
			$email_template = $this->home_model->get_email_template_hook("new_notification", $lang);
			if($email_template->num_rows() == 0) {
				$this->template->error(lang("error_48"));
			}
			$email_template = $email_template->row();

			$email_template->message = $this->common->replace_keywords(array(
				"[NAME]" => $username,
				"[SITE_URL]" => site_url(),
				"[SITE_NAME]" =>  $this->settings->info->site_name
				),
			$email_template->message);

			$this->common->send_email($subject,
				 $email_template->message, $data['email']);
		}
		unset($data['email']);
		unset($data['email_notification']);
		unset($data['username']);
    	$this->db->insert("user_notifications", $data);
    }

    public function update_notification($id, $data) 
    {
    	$this->db->where("ID", $id)->update("user_notifications", $data);
    }

    public function update_user_notifications($userid, $data) 
    {
    	$this->db->where("userid", $userid)
    		->update("user_notifications", $data);
    }

    public function increment_field($userid, $field, $amount) 
    {
    	$this->db->where("ID", $userid)
    		->set($field, $field . '+' . $amount, FALSE)->update("users");
    }

    public function decrement_field($userid, $field, $amount) 
    {
    	$this->db->where("ID", $userid)
    		->set($field, $field . '-' . $amount, FALSE)->update("users");
    }

	public function update_user($userid, $data) {
		$this->db->where("ID", $userid)->update("users", $data);
	}

	public function check_block_ip() 
	{
		$s = $this->db->where("IP", $_SERVER['REMOTE_ADDR'])->get("ip_block");
		if($s->num_rows() == 0) return false;
		return true;
	}

	public function get_user_groups($userid) 
	{
		return $this->db->where("user_group_users.userid", $userid)
			->select("user_groups.name,user_groups.ID as groupid,
				user_group_users.userid")
			->join("user_groups", "user_groups.ID = user_group_users.groupid")
			->get("user_group_users");
	}

	public function check_user_in_group($userid, $groupid) 
	{
		$s = $this->db->where("userid", $userid)->where("groupid", $groupid)
			->get("user_group_users");
		if($s->num_rows() == 0) return 0;
		return 1;
	}

	public function get_default_groups() 
	{
		return $this->db->where("default", 1)->get("user_groups");
	}

	public function add_user_to_group($userid, $groupid) 
	{
		$this->db->insert("user_group_users", array(
			"userid" => $userid, 
			"groupid" => $groupid
			)
		);
	}

	public function add_points($userid, $points) 
	{
        $this->db->where("ID", $userid)
        	->set("points", "points+$points", FALSE)->update("users");
    }

    public function get_verify_user($code, $username) 
    {
    	return $this->db
    		->where("activate_code", $code)
    		->where("username", $username)
    		->get("users");
    }

    public function get_user_event($request) 
    {
    	return $this->db->where("IP", $_SERVER['REMOTE_ADDR'])
    		->where("event", $request)
    		->order_by("ID", "DESC")
    		->get("user_events");
    }

    public function add_user_event($data) 
    {
    	$this->db->insert("user_events", $data);
    }

    public function get_custom_fields($data) 
	{
		if(isset($data['register'])) {
			$this->db->where("register", 1);
		}
		return $this->db->get("custom_fields");
	}

	public function add_custom_field($data) 
	{
		$this->db->insert("user_custom_fields", $data);
	}

	public function get_custom_fields_answers($data, $userid) 
	{
		if(isset($data['edit'])) {
			$this->db->where("custom_fields.edit", 1);
		}
		return $this->db
			->select("custom_fields.ID, custom_fields.name, custom_fields.type,
				custom_fields.required, custom_fields.help_text,
				custom_fields.options,
				user_custom_fields.value")
			->join("user_custom_fields", "user_custom_fields.fieldid = custom_fields.ID
			 AND user_custom_fields.userid = " . $userid, "LEFT OUTER")
			->get("custom_fields");

	}

	public function get_user_cf($fieldid, $userid)
	{
		return $this->db
			->where("fieldid", $fieldid)
			->where("userid", $userid)
			->get("user_custom_fields");
	}

	public function update_custom_field($fieldid, $userid, $value) 
	{
		$this->db->where("fieldid", $fieldid)
			->where("userid", $userid)
			->update("user_custom_fields", array("value" => $value));
	}

	public function get_payment_logs($userid, $datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"users.username",
			"payment_logs.email"
			)
		);
		return $this->db
			->where("payment_logs.userid", $userid)
			->select("users.ID as userid, users.username, users.email,
			users.avatar, users.online_timestamp,
			payment_logs.email, payment_logs.amount, payment_logs.timestamp, 
			payment_logs.ID, payment_logs.processor")
			->join("users", "users.ID = payment_logs.userid")
			->limit($datatable->length, $datatable->start)
			->get("payment_logs");
	}

	public function get_total_payment_logs_count($userid) 
	{
		$s= $this->db
			->where("userid", $userid)
			->select("COUNT(*) as num")->get("payment_logs");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_profile_comments($userid, $page) 
	{
		return $this->db
			->where("profile_comments.profileid", $userid)
			->select("profile_comments.ID, profile_comments.comment,
				profile_comments.userid, profile_comments.timestamp,
				profile_comments.profileid, profile_comments.userid,
				users.username, users.avatar, users.online_timestamp")
			->join("users", "users.ID = profile_comments.userid")
			->limit(5, $page)
			->order_by("profile_comments.ID", "DESC")
			->get("profile_comments");
	}

	public function add_profile_comment($data) 
	{
		$this->db->insert("profile_comments", $data);
	}

	public function get_profile_comment($id) 
	{
		return $this->db->where("ID", $id)->get("profile_comments");
	}

	public function delete_profile_comment($id) 
	{
		$this->db->where("ID", $id)->delete("profile_comments");
	}

	public function get_total_profile_comments($userid) 
	{
		$s = $this->db
			->where("profile_comments.profileid", $userid)
			->select("COUNT(*) as num")
			->get("profile_comments");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function increase_profile_views($userid) 
	{
		$this->db->where("ID", $userid)->set("profile_views", "profile_views+1", FALSE)->update("users");
	}

	public function add_user_data($data) 
	{
		$this->db->insert("user_data", $data);
	}

	public function update_user_data($id, $data) 
	{
		$this->db->where("ID", $id)->update("user_data", $data);
	}

	public function get_user_data($userid) 
	{
		return $this->db->where("userid", $userid)->get("user_data");
	}

	public function get_user_role($roleid) 
    {
    	return $this->db->where("ID", $roleid)->get("user_roles");
    }

	public function get_users_with_permissions($permissions) 
	{

		foreach($permissions as $p) {
			$this->db->or_where("user_roles." . $p, 1);
		}

		return $this->db
			->select("users.ID as userid, users.username, users.email, users.first_name,
				users.last_name, users.online_timestamp")
			->join("user_roles", "user_roles.ID = users.user_role")
			->get("users");
	}

	public function get_all_user_groups() 
	{
		return $this->db->get("user_groups");
	}

	public function get_user_group($id) 
	{
		return $this->db->where("ID", $id)->get("user_groups");
	}

	public function get_user_friends($userid, $limit) 
	{
		return $this->db
			->select("users.username, users.first_name, users.last_name,
				users.avatar, users.online_timestamp, users.ID as friendid")
			->where("user_friends.userid", $userid)
			->join("users", "users.ID = user_friends.friendid")
			->order_by("users.online_timestamp", "DESC")
			->limit($limit)
			->get("user_friends");
	}

	public function get_usernames($username) 
    {
    	return $this->db->like("username", $username)->limit(10)->get("users");
    }

    public function get_user_by_name($query) 
    {
    	return $this->db->like("first_name", $query)->or_like("last_name", $query)->limit(10)->get("users");
    }

    public function get_names($name) 
    {
    	return $this->db->like("first_name", $name)->or_like("last_name", $name)->limit(10)->get("users");
    }

    public function get_friend_names($name, $userid) 
    {
    	return $this->db
    		->where("user_friends.userid", $userid)
    		->group_start()
    		->like("users.first_name", $name)
    		->or_like("users.last_name", $name)
    		->group_end()
    		->select("users.ID, users.username, users.first_name, users.last_name,
    			users.email, users.online_timestamp, users.avatar")
    		->join("users", "users.ID = user_friends.friendid")
    		->limit(10)
    		->get("user_friends");
    } 

    public function get_user_friend($userid, $friendid) 
    {
    	return $this->db
    		->where("userid", $userid)
    		->where("friendid", $friendid)
    		->get("user_friends");
    }

    public function check_friend_request($userid, $friendid) 
    {
    	return $this->db
    		->where("userid", $userid)
    		->where("friendid", $friendid)
    		->get("user_friend_requests");
    }

    public function add_friend_request($data) 
    {
    	$this->db->insert("user_friend_requests", $data);
    }

    public function get_friend_requests($userid) 
    {
    	return $this->db
    		->select("user_friend_requests.ID, user_friend_requests.timestamp,
    			users.ID as userid, users.avatar, users.first_name, users.last_name,
    			users.online_timestamp, users.username")
    		->join("users", "users.ID = user_friend_requests.userid")
    		->where("user_friend_requests.friendid", $userid)
    		->get("user_friend_requests");
    }

    public function get_friend_request($id, $userid) 
    {
    	return $this->db->where("user_friend_requests.ID", $id)
    		->where("user_friend_requests.friendid", $userid)
    		->select("user_friend_requests.ID, users.ID as userid, users.first_name,
    			users.last_name, users.avatar, users.online_timestamp,
    			users.email, users.email_notification, users.username")
    		->join("users", "users.ID = user_friend_requests.userid")
    		->get("user_friend_requests");
    }

    public function delete_friend_request($id) 
    {
    	$this->db->where("ID", $id)->delete("user_friend_requests");
    }

    public function add_friend($data) 
    {
    	$this->db->insert("user_friends", $data);
    }

    public function get_user_friends_sample($userid) 
    {
    	return $this->db->where("user_friends.userid", $userid)
    		->select("users.username, users.first_name, users.last_name, users.avatar,
    			users.online_timestamp, users.ID as userid,
    			user_friends.ID")
    		->join("users", "users.ID = user_friends.friendid")
    		->limit(6)
    		->get("user_friends");
    }

    public function get_total_friends_count($userid) 
    {
    	$s = $this->db
    		->where("user_friends.userid", $userid)
    		->select("COUNT(*) as num")
    		->join("users", "users.ID = user_friends.friendid")
    		->get("user_friends");
    	$r = $s->row();
    	if(isset($r->num)) return $r->num;
    	return 0;
    }

    public function get_user_friends_dt($userid, $datatable) 
    {
    	$datatable->db_order();

		$datatable->db_search(array(
			"users.username",
			"users.first_name",
			"users.last_name"
			),
			true // Cache query
		);
		$this->db
			->where("user_friends.userid", $userid)
			->select("users.ID as userid, users.username, users.email,
			users.avatar, users.online_timestamp,
			users.first_name, users.last_name,
			user_friends.timestamp, user_friends.ID")
			->join("users", "users.ID = user_friends.friendid");
		return $datatable->get("user_friends");
    }

    public function get_user_friend_id($id, $userid) 
    {
    	return $this->db->where("ID", $id)->where("userid", $userid)->get("user_friends");
    }

    public function delete_friend($userid, $friendid) 
    {
    	$this->db->where("userid", $userid)->where("friendid", $friendid)->delete("user_friends");
    	$this->db->where("userid", $friendid)->where("friendid", $userid)->delete("user_friends");
    }

    public function add_report($data) 
    {
    	$this->db->insert("reports", $data);
    }

    public function add_relationship_request($data) 
    {
    	$this->db->insert("relationship_requests", $data);
    }

    public function get_relationship_request($userid) 
    {
    	return $this->db
    		->where("relationship_requests.friendid", $userid)
    		->select("relationship_requests.ID, users.first_name,
    		users.last_name, users.username")
    		->join("users", "users.ID = relationship_requests.userid")
    		->get("relationship_requests");
    }

    public function get_relationship_request_invites($userid) 
    {
    	return $this->db
    		->where("relationship_requests.userid", $userid)
    		->select("relationship_requests.ID, relationship_requests.relationship_status,
    			users.first_name,
    		users.last_name, users.username")
    		->join("users", "users.ID = relationship_requests.friendid")
    		->get("relationship_requests");
    }

    public function get_relationship_request_id($id) 
    {
    	return $this->db->where("ID", $id)->get("relationship_requests");
    }

    public function delete_relationship_request($id) 
    {
    	$this->db->where("ID", $id)->delete("relationship_requests");
    }

    public function check_relationship_request($userid, $friendid) 
    {
    	return $this->db->where("userid", $userid)->where("friendid", $friendid)->get("relationship_requests");
    }

    public function delete_relationship_request_from_user($userid) 
    {
    	$this->db->where("userid", $userid)->delete("relationship_requests");
    }

    public function get_newest_users($userid) 
    {
    	return $this->db->where("ID !=", $userid)->limit(5)->order_by("ID", "DESC")->get("users");
    }

    public function increase_posts($userid) 
    {
    	$this->db->where("ID", $userid)->set("post_count", "post_count+1", FALSE)->update("users");
    }

    public function decrease_posts($userid) 
    {
    	$this->db->where("ID", $userid)->set("post_count", "post_count-1", FALSE)->update("users");
    }

    public function delete_notification($id) 
    {
    	$this->db->where("ID", $id)->delete("user_notifications");
    }

    public function add_verified_request($data) 
    {
    	$this->db->insert("verified_requests", $data);
    }

    public function get_verified_request($userid) 
    {
    	return $this->db->where("userid", $userid)->get("verified_requests");
    }


}

?>