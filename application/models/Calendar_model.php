<?php

class Calendar_Model extends CI_Model 
{

	public function get_events($start, $end, $pageid) 
	{
		return $this->db->where("pageid", $pageid)
			->group_start()
			->where("start >=", $start)->or_where("end <=", $end)
			->group_end()
			->get("calendar_events");
	}

	public function add_event($data) 
	{
		$this->db->insert("calendar_events", $data);
		return $this->db->insert_id();
	}

	public function get_event($id) 
	{
		return $this->db->where("ID", $id)->get("calendar_events");
	}

	public function update_event($id, $data) 
	{
		$this->db->where("ID", $id)->update("calendar_events", $data);
	}

	public function delete_event($id) 
	{
		$this->db->where("ID", $id)->delete("calendar_events");
	}

	public function get_all_events($start, $end) 
	{
		return $this->db->where("start >=", $start)->where("end <=", $end)->get("calendar_events");
	}

	public function get_events_sample($pageid, $start) 
	{
		return $this->db->where("start >=", $start)->where("pageid", $pageid)->order_by("ID")->limit(5)->get("calendar_events");
	}

	public function get_event_user($id, $userid) 
	{
		return $this->db->where("eventid", $id)->where("userid", $userid)->get("calendar_event_users");
	}

	public function add_event_user($data) 
	{
		$this->db->insert("calendar_event_users", $data);
	}

	public function delete_event_user($id) 
	{
		$this->db->where("ID", $id)->delete("calendar_event_users");
	}

	public function get_event_user_count($id) 
	{
		return $this->db->where("eventid", $id)
			->from("calendar_event_users")->count_all_results();
	}

	public function get_event_users($id, $type) 
	{
		return $this->db->where("calendar_event_users.status", $type)->where("calendar_event_users.eventid", $id)
			->select("users.username, users.first_name, users.last_name,
				users.online_timestamp, users.avatar, users.ID as userid,
				calendar_event_users.ID")
			->join("users", "users.ID = calendar_event_users.userid")
			->get("calendar_event_users");
	}

	public function get_event_user_id($id) 
	{
		return $this->db->where("ID", $id)->get("calendar_event_users");
	}

}


?>