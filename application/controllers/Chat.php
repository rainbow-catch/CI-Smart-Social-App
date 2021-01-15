<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chat extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("chat_model");

		if(!$this->user->loggedin) $this->template->error(lang("error_1"));

		if(
			!$this->common->has_permissions(array("admin", "live_chat"), 
				$this->user)) {
			$this->template->error(lang("error_2"));
		}

		$this->template->set_layout("client/themes/titan.php");
	}

	public function load_new_chat() 
	{
		$username = $this->common->nohtml($this->input->get("username"));
		$this->template->loadAjax("chat/new_chat_window.php", array(
			"username" => $username
			),1
		);
	}

	public function load_multi_chat() 
	{
		$this->template->loadAjax("chat/new_chat_window_multi.php", array(
			),1
		);
	}

	public function chat_with($userid) 
	{
		// Look for a chat with this user (one on one)
		$friendid = intval($userid);
		$chats = $this->chat_model->get_user_chats($this->user->info->ID);

		$chatid = 0;
		foreach($chats->result() as $r) {
			$user_count = $this->chat_model->get_user_count($r->chatid);
			if($user_count == 2) {
				// Look for friend
				$friend = $this->chat_model->get_chat_user($r->chatid, $friendid);
				if($friend->num_rows() > 0) {
					$chatid = $r->chatid;
					break;
				}
			}
		}

		// Good
		$data= array(
			"chatid" => $chatid
		);

		echo json_encode($data);
		exit();
	}

	public function load_empty_chat($userid) 
	{
		$user = $this->user_model->get_user_by_id($userid);
		if($user->num_rows() == 0) {
			$this->template->errori(lang("error_85"));
		}
		$user = $user->row();
		$title = "Chat with " . $user->username;
		$this->template->loadAjax("chat/empty_chat.php", array(
			"title" => $title,
			"userid" => $user->ID
			),1
		);
	}

	public function get_active_chats() 
	{
		$chats = $this->chat_model->get_active_chats($this->user->info->ID);

		$view = $this->load->view("chat/active_chats.php", array(
			"chats" => $chats
			), 
		TRUE);

		$active_chats = array();
		foreach($chats->result() as $r) {
			$active_chats[] = $r->ID;
		}

		$data = array(
			"view" => $view,
			"active_chats" => $active_chats
		);
		echo json_encode($data);
		exit();
	}

	public function get_active_chat($chatid) 
	{
		$chatid = intval($chatid);
		$chat = $this->chat_model->get_live_chat($chatid);
		if($chat->num_rows() == 0) {
			$this->template->jsonError(lang("error_86"));
		}
		$chat = $chat->row();

		// Check user is a member
		$member = $this->chat_model->get_chat_user($chatid, $this->user->info->ID);
		if($member->num_rows() == 0) {
			$this->template->jsonError(lang("error_87"));
		}
		$member = $member->row();

		$this->chat_model->update_chat_user($member->ID, array(
			"active" => 1
			)
		);

		if(!empty($chat->title)) {
			$member->title = $chat->title;
		}
		

		// Good
		$data= array(
			"chatid" => $chatid,
			"title" => $member->title,
		);

		echo json_encode($data);
		exit();
	}

	public function get_chat_messages($chatid) 
	{
		$chatid = intval($chatid);
		$chat = $this->chat_model->get_live_chat($chatid);
		if($chat->num_rows() == 0) {
			$this->template->jsonError(lang("error_86"));
		}
		$chat = $chat->row();

		// Check user is a member
		$member = $this->chat_model->get_chat_user($chatid, $this->user->info->ID);
		if($member->num_rows() == 0) {
			$this->template->jsonError(lang("error_87"));
		}
		$member = $member->row();

		// Only mark chat unread if window is active
		if($member->unread && $member->active) {
			$member->unread = 0;
			$this->chat_model->update_chat_user($member->ID, array(
				"unread" => 0
				)
			);
		}
		

		$msgs = array();
		$limit = 5;
		$last_reply_id = 0;
		$messages = $this->chat_model->get_chat_messages($chatid, $limit);
		foreach($messages->result() as $m) {
			$msgs[] = $m;
			if($last_reply_id == 0) {
				$last_reply_id = $m->ID;
			}
		}

		$msgs = array_reverse($msgs);

		$messages_template = $this->load->view("chat/chat_messages.php", array(
			"msgs" => $msgs,
			"chat" => $chat,
			"last_reply_id" => $last_reply_id
			), 
		TRUE);

		$data = array(
			"messages_template" => $messages_template,
			"unread" => $member->unread,
			"chatid" => $chatid,
			"title" => $member->title
		);
		echo json_encode($data);
		exit();
	}

	public function get_all_chat_messages() 
	{
		$chats = $this->chat_model->get_active_chats($this->user->info->ID);

		$chat_windows = array();
		foreach($chats->result() as $r) {
			$c = array();

			// Only mark chat unread if window is active
			if($r->unread && $r->active) {
				$r->unread = 0;
				$this->chat_model->update_chat_user($r->chatuserid, array(
					"unread" => 0
					)
				);
			}

			// If a chat title is set, replace the users
			if(!empty($r->lc_title)) {
				$r->title = $r->lc_title;
			}

			// chat data
			$c['title'] = $r->title;
			$c['chatid'] = $r->ID;
			$c['unread'] = $r->unread;
			$c['active'] = $r->active;

			// Get chat messages
			$msgs = array();
			$limit = 5;
			$last_reply_id = 0;
			$messages = $this->chat_model->get_chat_messages($r->ID, $limit);
			foreach($messages->result() as $m) {
				$msgs[] = $m;
				if($last_reply_id == 0) {
					$last_reply_id = $m->ID;
				}
			}

			$msgs = array_reverse($msgs);

			$messages_template = $this->load->view("chat/chat_messages.php", array(
				"msgs" => $msgs,
				"chat" => $r,
				"last_reply_id" => $last_reply_id
				), 
			TRUE);

			// Store template
			$c['messages_template'] = $messages_template;

			// Chat bubble template
			$c['chat_bubble_template'] = $this->load->view("chat/chat_bubble.php", array(
				"chat" => $r,
				), 
			TRUE);

			// Add Chat to array
			$chat_windows[] = $c;
		}

		$chat = $this->chat_model->get_notification_count($this->user->info->ID);

		echo json_encode(array("chats" => $chat_windows, "noti_count" => $chat));
		exit();
	}

	public function close_active_chat($chatid) 
	{
		$chatid = intval($chatid);
		$chat = $this->chat_model->get_live_chat($chatid);
		if($chat->num_rows() == 0) {
			$this->template->jsonError(lang("error_86"));
		}
		$chat = $chat->row();

		// Check user is a member
		$member = $this->chat_model->get_chat_user($chatid, $this->user->info->ID);
		if($member->num_rows() == 0) {
			$this->template->jsonError(lang("error_87"));
		}
		$member = $member->row();


		$this->chat_model->update_chat_user($member->ID, array(
			"active" => 0
			)
		);

		if(!empty($chat->title)) {
			$member->title = $chat->title;
		}

		// Good
		$data= array(
			"chatid" => $chatid,
			"title" => $member->title,
			"unread" => $member->unread
		);

		echo json_encode($data);
		exit();
	}

	public function hide_chat($chatid) 
	{
		$chatid = intval($chatid);
		$chat = $this->chat_model->get_live_chat($chatid);
		if($chat->num_rows() == 0) {
			$this->template->jsonError(lang("error_86"));
		}
		$chat = $chat->row();

		// Check user is a member
		$member = $this->chat_model->get_chat_user($chatid, $this->user->info->ID);
		if($member->num_rows() == 0) {
			$this->template->jsonError(lang("error_87"));
		}
		$member = $member->row();


		$this->chat_model->update_chat_user($member->ID, array(
			"active" => 2
			)
		);

		if(!empty($chat->title)) {
			$member->title = $chat->title;
		}

		// Good
		$data= array(
			"chatid" => $chatid,
			"title" => $member->title,
			"unread" => $member->unread
		);

		echo json_encode($data);
		exit();
	}

	public function delete_chat($chatid) 
	{
		$chatid = intval($chatid);
		$chat = $this->chat_model->get_live_chat($chatid);
		if($chat->num_rows() == 0) {
			$this->template->jsonError(lang("error_86"));
		}
		$chat = $chat->row();

		// Check user is a member
		$member = $this->chat_model->get_chat_user($chatid, $this->user->info->ID);
		if($member->num_rows() == 0) {
			$this->template->jsonError(lang("error_87"));
		}
		$member = $member->row();

		$this->chat_model->delete_chat_user($member->ID);

		// Delete chat if no users left
		$users = $this->chat_model->get_chat_users($chatid);
		if($users->num_rows() > 0) {
			// Post a message that the user left the convo
			$replyid= $this->chat_model->add_chat_message(array(
				"chatid" => $chatid,
				"userid" => $this->user->info->ID,
				"message" => "<i><strong>".lang("ctn_641")."</strong></i>",
				"timestamp" => time()
				)
			);

			// Update all chat users of unread message
			$this->chat_model->update_chat_users($chatid, array(
				"unread" => 1
				)
			);

			$this->chat_model->update_chat($chatid, array(
				"last_replyid" => $replyid,
				"last_reply_timestamp" => time(),
				"last_reply_userid" => $this->user->info->ID,
				"posts" => $chat->posts + 1
				)
			);
		} else {
			$this->chat_model->delete_chat($chatid);
		}

		// Good
		$data= array(
			"chatid" => $chatid,
			"title" => $member->title,
			"unread" => $member->unread
		);

		echo json_encode($data);
		exit();
	}

	public function start_new_chat() 
	{
		$username = $this->common->nohtml($this->input->get("username"));
		$title = $this->common->nohtml($this->input->get("title"));

		$message = $this->common->nohtml($this->input->get("message"));
		if(empty($message)) {
			$this->template->jsonError(lang("error_88"));
		}

		// Check for multiple usernames
		$username_old = $username;
		$username_old = explode(",", $username_old);
		$usernames = array();
		foreach($username_old as $u) {
			$u = trim($u);
			if($u == $this->user->info->username) {
				$this->template->jsonError(lang("error_89"));
			}
			$usernames[] = $u;
		}

		if(count($usernames) > 1) {

			// Validate all users
			$users = array();
			foreach($usernames as $u) {
				// Get user
				$user = $this->user_model->get_user_by_username($u);
				if($user->num_rows() == 0) { 
					$this->template->jsonError(lang("error_85") . $u);
				}
				$user = $user->row();
				$flags = $this->check_friend($this->user->info->ID, $user->ID);

				if($user->chat_option && !$flags['friend_flag']) {
					$this->template->jsonError(lang("error_90"). $user->first_name . " " . $user->last_name . lang("error_91"));
				}
				$users[] = $user->ID;
			}

			$users = array_unique($users);

			if(empty($title)) {
				$title = lang("ctn_642");
			}

			// Create Chat
			$chatid = $this->chat_model->add_new_chat(array(
				"userid" => $this->user->info->ID,
				"timestamp" => time(),
				"title" => $title,
				"posts" => 1
				)
			);

			// Get message
			$replyid = $this->chat_model->add_chat_message(array(
				"chatid" => $chatid,
				"userid" => $this->user->info->ID,
				"message" => $message,
				"timestamp" => time()
				)
			);

			$this->chat_model->update_chat($chatid, array(
				"last_replyid" => $replyid,
				"last_reply_timestamp" => time(),
				"last_reply_userid" => $this->user->info->ID,
				)
			);

			// Add all users
			// Add current user
			$this->chat_model->add_chat_user(array(
				"userid" => $this->user->info->ID,
				"chatid" => $chatid,
				"title" => $title
				)
			);

			foreach($users as $uid) {
				$this->chat_model->add_chat_user(array(
					"userid" => $uid,
					"chatid" => $chatid,
					"unread" => 1
					)
				);
			}

		} else {
			// One on one chat
			// Get user
			$user = $this->user_model->get_user_by_username($username);
			if($user->num_rows() == 0) { 
				$this->template->jsonError(lang("error_85"));
			}
			$user = $user->row();

			$flags = $this->check_friend($this->user->info->ID, $user->ID);

			if($user->chat_option && !$flags['friend_flag']) {
				$this->template->jsonError(lang("error_90"). $user->first_name . " " . $user->last_name . lang("error_91"));
			}

			
			$title = lang("ctn_643") . " <strong>" . $user->username . "</strong>";
			$title2= lang("ctn_643") . " <strong>" . $this->user->info->username . "</strong>";
			



			// Create Chat
			$chatid = $this->chat_model->add_new_chat(array(
				"userid" => $this->user->info->ID,
				"timestamp" => time(),
				"posts" => 1
				)
			);

			// Get message
			$replyid = $this->chat_model->add_chat_message(array(
				"chatid" => $chatid,
				"userid" => $this->user->info->ID,
				"message" => $message,
				"timestamp" => time()
				)
			);

			$this->chat_model->update_chat($chatid, array(
				"last_replyid" => $replyid,
				"last_reply_timestamp" => time(),
				"last_reply_userid" => $this->user->info->ID
				)
			);


			// Add Members
			$this->chat_model->add_chat_user(array(
				"userid" => $this->user->info->ID,
				"chatid" => $chatid,
				"title" => $title
				)
			);

			$this->chat_model->add_chat_user(array(
				"userid" => $user->ID,
				"chatid" => $chatid,
				"title" => $title2,
				"unread" => 1
				)
			);
		}

		$data = array(
			"success" => 1,
			"chatid" => $chatid
			);
		echo json_encode($data);
		exit();
	}

	public function send_chat_message($chatid) 
	{
		if($chatid == 0) {
			// New chat
			$userid = intval($this->input->get("userid"));
			if($userid == 0) $this->template->jsonError(lang("error_86"));
			$user = $this->user_model->get_user_by_id($userid);
			if($user->num_rows() > 0) {
				$user = $user->row();
				$_GET['username'] = $user->username;
			} else {
				$this->template->jsonError(lang("error_86"));
			}

			// Create chat
			$this->start_new_chat();

			$data = array(
				"success" => 1,
				"chatid" => 0
				);
			echo json_encode($data);
			exit();
		} else {
			$chatid = intval($chatid);
			$chat = $this->chat_model->get_live_chat($chatid);
			if($chat->num_rows() == 0) {
				$this->template->jsonError(lang("error_86"));
			}
			$chat = $chat->row();

			// Check user is a member
			$member = $this->chat_model->get_chat_user($chatid, $this->user->info->ID);
			if($member->num_rows() == 0) {
				$this->template->jsonError(lang("error_87"));
			}
			$member = $member->row();

			$message = $this->common->nohtml($this->input->get("message"));
			$hash = $this->common->nohtml($this->input->get("hash"));

			if($hash != $this->security->get_csrf_hash()) {
				$this->template->jsonError(lang("error_6"));
			}

			if(empty($message)) {
				$this->template->jsonError(lang("error_88"));
			}

			$replyid = $this->chat_model->add_chat_message(array(
				"chatid" => $chatid,
				"userid" => $this->user->info->ID,
				"message" => $message,
				"timestamp" => time()
				)
			);

			// Update all chat users of unread message
			$this->chat_model->update_chat_users($chatid, array(
				"unread" => 1
				)
			);

			$this->chat_model->update_chat($chatid, array(
				"last_replyid" => $replyid,
				"last_reply_timestamp" => time(),
				"last_reply_userid" => $this->user->info->ID,
				"posts" => $chat->posts + 1
				)
			);

			$data = array(
				"success" => 1,
				"chatid" => $chatid
				);
			echo json_encode($data);
			exit();
		}
	}

	public function index($default_mail=0, $page = 0) 
	{

		$this->template->loadExternal(
			'<script type="text/javascript" src="'
			.base_url().'scripts/custom/mail.js" /></script>
			'
		);
		$page = intval($page);

		$mail = $this->chat_model->get_user_mail($this->user->info->ID, $page);
		$default_mail = intval($default_mail);

		if($default_mail == 0 && $mail->num_rows() > 0) {
			$df = $mail->row();
			$default_mail = $df->ID;
			if($df->posts % 5 == 0) {
				$page = floor($df->posts/5) * 5;
				$page = $page - 5;
			} else {
				$page = floor($df->posts/5) * 5;
			}
		}

		// * Pagination *//
		$this->load->library('pagination');
		$config['base_url'] = site_url("chat/index/0/");
		$config['total_rows'] = $this->chat_model
			->get_total_mail_count($this->user->info->ID);
		$config['per_page'] = 8;
		$config['uri_segment'] = 4;
		include (APPPATH . "/config/page_config.php");

		$this->pagination->initialize($config); 
		
		$this->template->loadContent("chat/index.php", array(
			"mail" => $mail,
			"default_mail" => $default_mail,
			"page" => $page	
			)
		);
	}

	public function view_mail($id, $page=0) 
	{
		$id = intval($id);
		$page = intval($page);
		$chatid = $id;
		$chat = $this->chat_model->get_live_chat($chatid);
		if($chat->num_rows() == 0) {
			$this->template->jsonError(lang("error_86"));
		}
		$chat = $chat->row();

		// Check user is a member
		$member = $this->chat_model->get_chat_user($chatid, $this->user->info->ID);
		if($member->num_rows() == 0) {
			$this->template->jsonError(lang("error_87"));
		}
		$member = $member->row();

		$replies = $this->chat_model->get_mail_replies($id, $page);

		// * Pagination *//
		$this->load->library('pagination');
		$config['base_url'] = site_url("chat/view_mail/" . $id);
		$config['total_rows'] = $this->chat_model
			->get_total_mail_replies_count($id);
		$config['per_page'] = 5;
		$config['uri_segment'] = 4;
		include (APPPATH . "/config/page_config2.php");

		$this->pagination->initialize($config); 

		$replies_array = array();
		foreach($replies->result() as $r) {
			$replies_array[] = $r;
		}

		$replies = $replies_array;

		$this->template->loadAjax("chat/view_mail.php", array(	
			"mail" => $chat,
			"replies" => $replies,
			"page" => $page
			), 0
		);
	}

	public function delete_chat_message($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$message = $this->chat_model->get_chat_message($id);
		if($message->num_rows() == 0) {
			$this->template->error(lang("error_188"));
		}
		$message = $message->row();

		$chatid = $message->chatid;
		$chat = $this->chat_model->get_live_chat($chatid);
		if($chat->num_rows() == 0) {
			$this->template->error(lang("error_86"));
		}
		$chat = $chat->row();

		// Delete Message if user made it
		if($this->user->info->ID != $message->userid && 
			!$this->common->has_permissions(array("admin"), $this->user)) {
			$this->template->error(lang("error_256"));
		}

		$this->chat_model->update_chat($chatid, array(
			"posts" => $chat->posts - 1
			)
		);

		// Delete
		$this->chat_model->delete_chat_message($id);
		$this->session->set_flashdata("globalmsg", lang("success_52"));
		redirect(site_url("chat"));

	}

	public function load_active_users() 
	{
		$users = $this->user_model->get_online_users();
		$this->template->loadAjax("chat/online_users.php", array(
			"users" => $users
			),1
		);
	}

	public function edit_chat($chatid) 
	{
		$chatid = intval($chatid);
		$chat = $this->chat_model->get_live_chat($chatid);
		if($chat->num_rows() == 0) {
			$this->template->error(lang("error_86"));
		}
		$chat = $chat->row();

		// Check user is a member
		$member = $this->chat_model->get_chat_user($chatid, $this->user->info->ID);
		if($member->num_rows() == 0) {
			$this->template->error(lang("error_87"));
		}
		$member = $member->row();

		// Check
		if($chat->userid != $this->user->info->ID) {
			// Check for admin
			if(!$this->common->has_permissions(array("admin"), $this->user)) {
				$this->template->error(lang("error_2"));
			}
		}

		// Get all chat users
		$users = $this->chat_model->get_chat_users($chatid);

		$this->template->loadContent("chat/edit_chat.php", array(
			"chat" => $chat,
			"users" => $users
			)
		);
	}

	public function edit_chat_pro($chatid) 
	{
		$chatid = intval($chatid);
		$chat = $this->chat_model->get_live_chat($chatid);
		if($chat->num_rows() == 0) {
			$this->template->error(lang("error_86"));
		}
		$chat = $chat->row();

		// Check user is a member
		$member = $this->chat_model->get_chat_user($chatid, $this->user->info->ID);
		if($member->num_rows() == 0) {
			$this->template->error(lang("error_87"));
		}
		$member = $member->row();

		// Check
		if($chat->userid != $this->user->info->ID) {
			// Check for admin
			if(!$this->common->has_permissions(array("admin"), $this->user)) {
				$this->template->error(lang("error_2"));
			}
		}

		$title = $this->common->nohtml($this->input->post("title"));

		$this->chat_model->update_chat($chatid, array(
			"title" => $title
			)
		);

		// Update all chat users of unread message
		$this->chat_model->update_chat_users($chatid, array(
			"title" => $title
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_53"));
		redirect(site_url("chat"));
	}

	public function remove_from_chat($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}

		$id = intval($id);
		$user = $this->chat_model->get_chat_user_id($id);
		if($user->num_rows() == 0) {
			$this->template->error(lang("error_86"));
		}
		$user = $user->row();

		$chatid = $user->chatid;
		$chat = $this->chat_model->get_live_chat($chatid);
		if($chat->num_rows() == 0) {
			$this->template->error(lang("error_86"));
		}
		$chat = $chat->row();

		// Check
		if($chat->userid != $this->user->info->ID) {
			// Check for admin
			if(!$this->common->has_permissions(array("admin"), $this->user)) {
				$this->template->error(lang("error_2"));
			}
		}

		// Get message
		$replyid = $this->chat_model->add_chat_message(array(
			"chatid" => $chatid,
			"userid" => $user->userid,
			"message" => "<strong><i>".lang("ctn_641")."</i></strong>",
			"timestamp" => time()
			)
		);

		// Update all chat users of unread message
		$this->chat_model->update_chat_users($chatid, array(
			"unread" => 1
			)
		);

		$this->chat_model->update_chat($chatid, array(
			"last_replyid" => $replyid,
			"last_reply_timestamp" => time(),
			"last_reply_userid" => $user->userid,
			"posts" => $chat->posts + 1
			)
		);

		// Delete
		$this->chat_model->delete_chat_user($id);
		$this->session->set_flashdata("globalmsg", lang("success_54"));
		redirect(site_url("chat/edit_chat/" . $chatid));

	}

	public function add_user($chatid) 
	{
		$chatid = intval($chatid);
		$chat = $this->chat_model->get_live_chat($chatid);
		if($chat->num_rows() == 0) {
			$this->template->error(lang("error_86"));
		}
		$chat = $chat->row();

		// Check user is a member
		$member = $this->chat_model->get_chat_user($chatid, $this->user->info->ID);
		if($member->num_rows() == 0) {
			$this->template->error(lang("error_87"));
		}
		$member = $member->row();

		$userid = intval($this->input->post("userid"));
		// One on one chat
		// Get user
		if(!empty($username)) {
			$user = $this->user_model->get_user_by_username($username);
			if($user->num_rows() == 0) { 
				$this->template->error(lang("error_85"));
			}
		} else {
			$user = $this->user_model->get_user_by_id($userid);
			if($user->num_rows() == 0) { 
				$this->template->error(lang("error_85"));
			}
		}
		$user = $user->row();

		$flags = $this->check_friend($this->user->info->ID, $user->ID);

		if($user->chat_option && !$flags['friend_flag']) {
			$this->template->error(lang("error_92"));
		}

		// Check user isn't already a member
		$member = $this->chat_model->get_chat_user($chatid, $user->ID);
		if($member->num_rows() > 0) {
			$this->template->error(lang("error_93"));
		}

		// Add
		// Get message
		$replyid = $this->chat_model->add_chat_message(array(
			"chatid" => $chatid,
			"userid" => $user->ID,
			"message" => "<strong><i>".lang("ctn_645")."</i></strong>",
			"timestamp" => time()
			)
		);

		if(!empty($chat->title)) {
			$title = $chat->title;
		} else {
			$title = lang("ctn_643") . " <strong>" . $this->user->info->username . "</strong>";
		}

		// Add all users
		// Add current user
		$this->chat_model->add_chat_user(array(
			"userid" => $user->ID,
			"chatid" => $chatid,
			"title" => $title
			)
		);

		// Update all chat users of unread message
		$this->chat_model->update_chat_users($chatid, array(
			"unread" => 1
			)
		);

		$this->chat_model->update_chat($chatid, array(
			"last_replyid" => $replyid,
			"last_reply_timestamp" => time(),
			"last_reply_userid" => $user->ID,
			"posts" => $chat->posts + 1
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_55"));
		redirect(site_url("chat"));
	}

	public function reply($id) 
	{
		$id = intval($id);
		$chatid = $id;
		$chat = $this->chat_model->get_live_chat($chatid);
		if($chat->num_rows() == 0) {
			$this->template->jsonError(lang("error_86"));
		}
		$chat = $chat->row();

		// Check user is a member
		$member = $this->chat_model->get_chat_user($chatid, $this->user->info->ID);
		if($member->num_rows() == 0) {
			$this->template->jsonError(lang("error_87"));
		}
		$member = $member->row();

		$reply = $this->lib_filter->go($this->input->post("reply"));
		if(empty($reply)) $this->template->error(lang("error_88"));

		$replyid = $this->chat_model->add_chat_message(array(
			"chatid" => $chatid,
			"userid" => $this->user->info->ID,
			"message" => $reply,
			"timestamp" => time()
			)
		);

		// Update all chat users of unread message
		$this->chat_model->update_chat_users($chatid, array(
			"unread" => 1
			)
		);

		$this->chat_model->update_chat($chatid, array(
			"last_replyid" => $replyid,
			"last_reply_timestamp" => time(),
			"last_reply_userid" => $this->user->info->ID,
			"posts" => $chat->posts + 1
			)
		);


		$this->session->set_flashdata("globalmsg", lang("success_56"));
		redirect(site_url("chat"));
	}

	public function compose() 
	{
		$this->template->loadAjax("chat/compose.php", array()
		);
	}

	public function compose_pro() 
	{
		$title = $this->common->nohtml($this->input->post("title"));
		$username = $this->common->nohtml($this->input->post("username"));
		$userid = intval($this->input->post("userid"));

		$reply = $this->lib_filter->go($this->input->post("reply"));

		if(empty($reply)) $this->template->error(lang("error_88"));


		// One on one chat
		// Get user
		if(!empty($username)) {
			$user = $this->user_model->get_user_by_username($username);
			if($user->num_rows() == 0) { 
				$this->template->error(lang("error_85"));
			}
		} else {
			$user = $this->user_model->get_user_by_id($userid);
			if($user->num_rows() == 0) { 
				$this->template->error(lang("error_85"));
			}
		}
		$user = $user->row();

		$flags = $this->check_friend($this->user->info->ID, $user->ID);

		if($user->chat_option && !$flags['friend_flag']) {
			$this->template->error(lang("error_92"));
		}

		if(empty($title)) {
			$chat_title = "";
			$title = lang("ctn_643") . " <strong>" . $user->username . "</strong>";
			$title2= lang("ctn_643") . " <strong>" . $this->user->info->username . "</strong>";
		} else {
			$title2 = $title;
			$chat_title = $title;
		}

		// Create Chat
		$chatid = $this->chat_model->add_new_chat(array(
			"userid" => $this->user->info->ID,
			"timestamp" => time(),
			"posts" => 1,
			"title" => $chat_title
			)
		);

		// Get message
		$replyid = $this->chat_model->add_chat_message(array(
			"chatid" => $chatid,
			"userid" => $this->user->info->ID,
			"message" => $reply,
			"timestamp" => time()
			)
		);

		$this->chat_model->update_chat($chatid, array(
			"last_replyid" => $replyid,
			"last_reply_timestamp" => time(),
			"last_reply_userid" => $this->user->info->ID
			)
		);


		// Add Members
		$this->chat_model->add_chat_user(array(
			"userid" => $this->user->info->ID,
			"chatid" => $chatid,
			"title" => $title
			)
		);

		$this->chat_model->add_chat_user(array(
			"userid" => $user->ID,
			"chatid" => $chatid,
			"title" => $title2,
			"unread" => 1
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_57"));
		redirect(site_url("chat"));
	}

	private function check_friend($userid, $friendid) 
	{
		// check user is friend
		$friend_flag = 0;
		$request_flag = 0;
		$friend = $this->user_model->get_user_friend($userid, $friendid);
		if($friend->num_rows() > 0) {
			// Friends
			$friend_flag = 1;
		} else {
			// Check for a request
			$request = $this->user_model->check_friend_request($userid, $friendid);
			if($request->num_rows() > 0) {
				// Request sent
				$request_flag = 1;
			}
		}

		return array("friend_flag" => $friend_flag, "request_flag" => $request_flag);
	}

	public function load_chats($page = 0) 
	{
		$page = intval($page);
		$mail = $this->chat_model->get_user_mail($this->user->info->ID, $page);
		$this->template->loadAjax("chat/ajax_chats.php", array(
			"mail" => $mail
			),0
		);	
	}

	public function delete_chat_pro($chatid, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$chatid = intval($chatid);
		$chat = $this->chat_model->get_live_chat($chatid);
		if($chat->num_rows() == 0) {
			$this->template->error(lang("error_86"));
		}
		$chat = $chat->row();

		// Check user is a member
		$member = $this->chat_model->get_chat_user($chatid, $this->user->info->ID);
		if($member->num_rows() == 0) {
			$this->template->error(lang("error_87"));
		}
		$member = $member->row();

		$this->chat_model->delete_chat_user($member->ID);

		// Delete chat if no users left
		$users = $this->chat_model->get_chat_users($chatid);
		if($users->num_rows() > 0) {
			// Post a message that the user left the convo
			$replyid= $this->chat_model->add_chat_message(array(
				"chatid" => $chatid,
				"userid" => $this->user->info->ID,
				"message" => "<i><strong>".lang("ctn_641")."</strong></i>",
				"timestamp" => time()
				)
			);

			// Update all chat users of unread message
			$this->chat_model->update_chat_users($chatid, array(
				"unread" => 1
				)
			);

			$this->chat_model->update_chat($chatid, array(
				"last_replyid" => $replyid,
				"last_reply_timestamp" => time(),
				"last_reply_userid" => $this->user->info->ID,
				"posts" => $chat->posts + 1
				)
			);
		} else {
			$this->chat_model->delete_chat($chatid);
		}

		$this->session->set_flashdata("globalmsg", lang("success_52"));
		redirect(site_url("chat"));
	}

	public function check_notifications() 
	{
		if(!$this->user->loggedin) {
			return 0;
		}
		// look for any chat's with the user
		$chat = $this->chat_model->get_notification_count($this->user->info->ID);

		echo json_encode(array("noti_count" => $chat));
		exit();
	}

	public function search() 
	{
		$this->template->loadExternal(
			'<script type="text/javascript" src="'
			.base_url().'scripts/custom/mail.js" /></script>
			'
		);

		$search = $this->common->nohtml($this->input->post("search"));
		if(empty($search)) {
			$this->template->error(lang("error_135"));
		}

		$mail = $this->chat_model->get_user_mail_search($this->user->info->ID,
			$search);
		$default_mail = 0;

		if($default_mail == 0 && $mail->num_rows() > 0) {
			$df = $mail->row();
			$default_mail = $df->ID;
		}
		

		$this->template->loadContent("chat/index.php", array(
			"mail" => $mail,
			"default_mail" => $default_mail,
			"search" => $search	
			)
		);
	}

}

?>