<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
	
		$this->template->loadData("activeLink", 
			array("home" => array("general" => 1)));
		$this->load->model("user_model");
		$this->load->model("home_model");
		$this->load->model("page_model");
		$this->load->model("feed_model");
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$this->template->set_error_view("error/login_error.php");
		$this->template->set_layout("client/themes/titan.php");
	}

	public function index($type = 0, $hashtag = "")
	{
		$type = intval($type);
		$hashtag = $this->common->nohtml($hashtag);
		

		$pages = $this->page_model->get_recent_pages();
		$hashtags = $this->feed_model->get_trending_hashtags(10);
		$users = $this->user_model->get_newest_users($this->user->info->ID);

		$postid = intval($this->input->get("postid"));
		$commentid = intval($this->input->get("commentid"));
		$replyid = intval($this->input->get("replyid"));

		$this->template->loadContent("home/index.php", array(
			"pages" => $pages,
			"users" => $users,
			"hashtags" => $hashtags,
			"type" => $type,
			"hashtag" => $hashtag,
			"postid" => $postid,
			"commentid" => $commentid,
			"replyid" => $replyid
			)
		);
	}

	public function get_user_friends() 
	{
		$query2 = $this->common->nohtml($this->input->get("term"));

		$char = substr($query2, 0, 1);
		if($char == "@") {
			$query2 = substr($query2, 1, strlen($query2));
			$users = $this->user_model->get_names($query2);

			$usersArr = array();
			foreach($users->result() as $user) {
				$u = new STDClass;
				$u->uid = $user->username;
				$u->value = $user->first_name . " " . $user->last_name;
				$usersArr[] = $u;
			}
			header('Content-Type: application/json');
			echo json_encode($usersArr);
			exit();
		} elseif($char == "#") {

		}
	}

	public function get_user_friends_v2() 
	{
		$query2 = $this->common->nohtml($this->input->get("term"));
		$users = $this->user_model->get_friend_names($query2, $this->user->info->ID);

		$usersArr = array();
		foreach($users->result() as $user) {
			$u = new STDClass;
			$u->id = $user->username;
			$u->text = $user->first_name . " " . $user->last_name;
			$usersArr[] = $u;
		}
		header('Content-Type: application/json');
		echo json_encode(array("results" => $usersArr));
		exit();
	}

	private function get_fresh_results($stats) 
	{
		$data = new STDclass;

		$data->google_members = $this->user_model->get_oauth_count("google");
		$data->facebook_members = $this->user_model->get_oauth_count("facebook");
		$data->twitter_members = $this->user_model->get_oauth_count("twitter");
		$data->total_members = $this->user_model->get_total_members_count();
		$data->new_members = $this->user_model->get_new_today_count();
		$data->active_today = $this->user_model->get_active_today_count();

		return $data;
	}

	public function change_language() 
	{	

		$languages = $this->config->item("available_languages");
		if(!isset($_COOKIE['language'])) {
			$lang = "";
		} else {
			$lang = $_COOKIE["language"];
		}
		$this->template->loadContent("home/change_language.php", array(
			"languages" => $languages,
			"user_lang" => $lang
			)
		);
	}

	public function change_language_pro() 
	{

		$lang = $this->common->nohtml($this->input->post("language"));
		$languages = $this->config->item("available_languages");
		
		if(!array_key_exists($lang, $languages)) {
			$this->template->error(lang("error_25"));
		}

		setcookie("language", $lang, time()+3600*7, "/");
		$this->session->set_flashdata("globalmsg", lang("success_14"));
		redirect(site_url());
	}

	public function get_usernames() 
	{
		$query = $this->common->nohtml($this->input->get("query"));

		if(!empty($query)) {
			$usernames = $this->user_model->get_usernames($query);
			if($usernames->num_rows() == 0) {
				echo json_encode(array());
			} else {
				$array = array();
				foreach($usernames->result() as $r) {
					$array[] = $r->username;
				}
				echo json_encode($array);
				exit();
			}
		} else {
			echo json_encode(array());
			exit();
		}
	}

	public function get_search_results() 
	{
		$query = $this->common->nohtml($this->input->get("query"));

		$array = array();
		if(!empty($query)) {
			$usernames = $this->user_model->get_user_by_name($query);
			if($usernames->num_rows() == 0) {
			} else {
				foreach($usernames->result() as $r) {
					$s = new STDClass;
					$s->label = $r->first_name ." " . $r->last_name;
					$s->type = "user";
					$s->value = $r->username;
					$s->avatar = base_url() . $this->settings->info->upload_path_relative . "/" . $r->avatar;
					$s->url = site_url("profile/" . $r->username);
					$array[] = $s;
				}
			}
			// Search pages
			$pages = $this->page_model->get_pages_by_name($query);
			if($pages->num_rows() == 0) {
			} else {
				foreach($pages->result() as $r) {
					if(!empty($r->slug)) {
						$slug = $r->slug;
					} else {
						$slug = $r->ID;
					}
					$s = new STDClass;
					$s->label = $r->name;
					$s->type = "page";
					$s->value = $r->name;
					$s->avatar = base_url() . $this->settings->info->upload_path_relative . "/" . $r->profile_avatar;
					$s->url = site_url("pages/view/" . $slug);
					$array[] = $s;
				}
			}
		}
		echo json_encode($array);
		exit();
	}

	public function get_names() 
	{
		$query = $this->common->nohtml($this->input->get("query"));

		if(!empty($query)) {
			$usernames = $this->user_model->get_names($query);
			if($usernames->num_rows() == 0) {
				echo json_encode(array());
			} else {
				$array = array();
				foreach($usernames->result() as $r) {
					$u = new STDClass();
					$u->label = $r->first_name ." ". $r->last_name;
					$u->value = $r->ID;
					$array[] = $u;
				}
				echo json_encode($array);
				exit();
			}
		} else {
			echo json_encode(array());
			exit();
		}
	}

	public function load_notifications() 
	{
		$notifications = $this->user_model
			->get_notifications($this->user->info->ID);
		$this->template->loadAjax("home/ajax_notifications.php", array(
			"notifications" => $notifications
			),0
		);	
	}

	public function load_notifications_unread() 
	{
		$notifications = $this->user_model
			->get_notifications_unread($this->user->info->ID);
		$this->template->loadAjax("home/ajax_notifications.php", array(
			"notifications" => $notifications
			),0
		);	
	}

	public function read_all_noti($hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		
		$this->user_model->update_user_notifications($this->user->info->ID, array(
			"status" => 1
			)
		);

		$this->user_model->update_user($this->user->info->ID, array(
			"noti_count" => 0
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_43"));
		redirect(site_url("home/notifications"));
	}

	public function load_notification($id)
	{
		$notification = $this->user_model
			->get_notification($id, $this->user->info->ID);
		if($notification->num_rows() == 0) {
			$this->template->error(lang("error_111"));
		}
		$noti = $notification->row();
		if(!$noti->status) {
			$this->user_model->update_notification($id, array(
				"status" => 1
				)
			);
			$this->user_model->update_user($this->user->info->ID, array(
				"noti_count" => $this->user->info->noti_count - 1
				)
			);
		}

		// redirect
		redirect(site_url($noti->url));
	}

	public function notifications() 
	{
		$this->template->loadContent("home/notifications.php", array(
			)
		);	
	}

	public function notification_read($id) 
	{
		$notification = $this->user_model
			->get_notification($id, $this->user->info->ID);
		if($notification->num_rows() == 0) {
			$this->template->error(lang("error_111"));
		}
		$noti = $notification->row();
		if(!$noti->status) {
			$this->user_model->update_notification($id, array(
				"status" => 1
				)
			);
			$this->user_model->update_user($this->user->info->ID, array(
				"noti_count" => $this->user->info->noti_count - 1
				)
			);
		}
		redirect(site_url("home/notifications"));
	}

	public function notification_unread($id) 
	{
		$notification = $this->user_model
			->get_notification($id, $this->user->info->ID);
		if($notification->num_rows() == 0) {
			$this->template->error(lang("error_111"));
		}
		$noti = $notification->row();
		if($noti->status) {
			$this->user_model->update_notification($id, array(
				"status" => 0
				)
			);
			$this->user_model->update_user($this->user->info->ID, array(
				"noti_count" => $this->user->info->noti_count + 1
				)
			);
		}
		redirect(site_url("home/notifications"));
	}

	public function delete_notification($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$notification = $this->user_model
			->get_notification($id, $this->user->info->ID);
		if($notification->num_rows() == 0) {
			$this->template->error(lang("error_113"));
		}
		$noti = $notification->row();
		if($noti->status) {
			
			$this->user_model->update_user($this->user->info->ID, array(
				"noti_count" => $this->user->info->noti_count + 1
				)
			);
		} else {
			$this->user_model->update_user($this->user->info->ID, array(
				"noti_count" => $this->user->info->noti_count - 1
				)
			);
		}

		$this->user_model->delete_notification($id);
		$this->session->set_flashdata("globalmsg", lang("success_101"));
		redirect(site_url("home/notifications"));
	}

	public function notifications_page() 
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("user_notifications.timestamp", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 2 => array(
				 	"user_notifications.timestamp" => 0
				 )
			)
		);
		$this->datatables->set_total_rows(
			$this->user_model
			->get_notifications_all_total($this->user->info->ID)
		);
		$notifications = $this->user_model
			->get_notifications_all($this->user->info->ID, $this->datatables);



		foreach($notifications->result() as $r) {
			$msg = '<a href="'.site_url("profile/" . $r->username).'">'.$r->username.'</a> ' . $r->message;
			$options = '<a href="'.site_url("home/notification_unread/" . $r->ID).'" class="btn btn-default btn-xs">'.lang("ctn_655").'</a>';
			if($r->status !=1) {
				$msg .=' <label class="label label-danger">'.lang("ctn_796").'</label>';
				$options = '<a href="'.site_url("home/notification_read/" . $r->ID).'" class="btn btn-info btn-xs">'.lang("ctn_656").'</a>';
			}

			$this->datatables->data[] = array(
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)),
				$msg,
				date($this->settings->info->date_format, $r->timestamp),
				$options . ' <a href="'.site_url("home/delete_notification/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></a> <a href="'.site_url("home/load_notification/" . $r->ID).'" class="btn btn-primary btn-xs">'.lang("ctn_657").'</a>'
			);
		}
		echo json_encode($this->datatables->process());
	}

}

?>