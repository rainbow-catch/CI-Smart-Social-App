<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model("admin_model");
		$this->load->model("user_model");

		if (!$this->user->loggedin) $this->template->error(lang("error_1"));
		if(!$this->common->has_permissions(array("admin", "admin_settings",
			"admin_members", "admin_payment"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
	}


	public function index()
	{
		$this->template->loadData("activeLink",
			array("admin" => array("general" => 1)));
		$this->template->loadContent("admin/index.php", array(
			)
		);

	}

	public function invites()
	{
		if(!$this->user->info->admin && !$this->user->info->admin_members) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink",
			array("admin" => array("invites" => 1)));
		$this->template->loadContent("admin/invites.php", array(
			)
		);

	}

	public function invite_page()
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("invites.ID", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"invites.email" => 0
				 ),
				 1 => array(
				 	"invites.status" => 0
				 ),
				 2 => array(
				 	"invites.code" => 0
				 ),
				 3 => array(
				 	"users.username" => 0
				 ),
				 4 => array(
				 	"invites.timestamp" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->admin_model
				->get_total_invites()
		);
		$invites = $this->admin_model->get_invites($this->datatables);

		foreach($invites->result() as $r) {

			if($r->status == 0) {
				$status = "Unused";
			} elseif($r->status == 1) {
				$status = "Used";
			} elseif($r->status == 2) {
				$status = "Expired";
			}

			if($r->expires > 0) {
				if($r->timestamp + ($r->expires * 3600) < time()) {
					$status = "Expired";
				}
			}

			$this->datatables->data[] = array(
				$r->email,
				$status,
				$r->code,
				$r->username,
				date($this->settings->info->date_format, $r->timestamp),
				'<a href="' . site_url("admin/edit_invite/" . $r->ID) .'" class="btn btn-warning btn-xs" title="'. lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a>  <a href="' . site_url("admin/delete_invite/" . $r->ID . "/" . $this->security->get_csrf_hash()) .'" onclick="return confirm(\'' . lang("ctn_86") . '\')" class="btn btn-danger btn-xs" title="'. lang("ctn_57") .'"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function add_invite_pro()
	{
		$this->load->model("home_model");
		$email = $this->common->nohtml($this->input->post("email"));
		$expires = intval($this->input->post("expires"));
		$expire_upon_use = intval($this->input->post("expire_upon_use"));
		$bypass_registration = intval($this->input->post("bypass_registration"));

		$code = $this->common->randomPassword();

		$this->admin_model->add_invite(array(
			"email" => $email,
			"expires" => $expires,
			"expire_upon_use" => $expire_upon_use,
			"bypass_register" => $bypass_registration,
			"timestamp" => time(),
			"status" => 0,
			"code" => $code
			)
		);

		// email
		if(!empty($email)) {
				if(!isset($_COOKIE['language'])) {
				// Get first language in list as default
				$lang = $this->config->item("language");
			} else {
				$lang = $this->common->nohtml($_COOKIE["language"]);
			}
			// Send Email
			$email_template = $this->home_model
				->get_email_template_hook("member_invite", $lang);
			if($email_template->num_rows() == 0) {
				$this->template->error(lang("error_48"));
			}
			$email_template = $email_template->row();

			$email_template->message = $this->common->replace_keywords(array(
				"[NAME]" => $email,
				"[SITE_URL]" => site_url("register/index/" . $code),
				"[SITE_NAME]" =>  $this->settings->info->site_name
				),
			$email_template->message);

			$this->common->send_email($email_template->title,
				 $email_template->message, $email);
		}

		$this->session->set_flashdata("globalmsg", lang("success_113"));
		redirect(site_url("admin/invites"));
	}

	public function edit_invite($id)
	{
		$id = intval($id);
		$invite = $this->admin_model->get_invite($id);
		if($invite->num_rows() == 0) {
			$this->template->error("Invalid Invite!");
		}
		$invite = $invite->row();

		$this->template->loadData("activeLink",
			array("admin" => array("invites" => 1)));
		$this->template->loadContent("admin/edit_invite.php", array(
			"invite" => $invite
			)
		);
	}

	public function edit_invite_pro($id)
	{
		$id = intval($id);
		$invite = $this->admin_model->get_invite($id);
		if($invite->num_rows() == 0) {
			$this->template->error(lang("error_187"));
		}
		$invite = $invite->row();

		$this->template->loadData("activeLink",
			array("admin" => array("invites" => 1)));

		$this->load->model("home_model");
		$email = $this->common->nohtml($this->input->post("email"));
		$expires = intval($this->input->post("expires"));
		$expire_upon_use = intval($this->input->post("expire_upon_use"));
		$bypass_registration = intval($this->input->post("bypass_registration"));

		$status = intval($this->input->post("status"));


		$this->admin_model->update_invite($id, array(
			"email" => $email,
			"expires" => $expires,
			"expire_upon_use" => $expire_upon_use,
			"bypass_register" => $bypass_registration,
			"status" => $status
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_114"));
		redirect(site_url("admin/invites"));
	}

	public function delete_invite($id, $hash)
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error("Invalid Hash!");
		}
		$id = intval($id);
		$invite = $this->admin_model->get_invite($id);
		if($invite->num_rows() == 0) {
			$this->template->error("Invalid Invite!");
		}

		$this->admin_model->delete_invite($id);
		$this->session->set_flashdata("globalmsg", lang("success_115"));
		redirect(site_url("admin/invites"));
	}

	public function limits()
	{
		if(!$this->user->info->admin && !$this->user->info->admin_settings) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink",
			array("admin" => array("limits" => 1)));
		$this->template->loadContent("admin/limits.php", array(
			)
		);

	}

	public function limits_pro()
	{
		if(!$this->user->info->admin && !$this->user->info->admin_settings) {
			$this->template->error(lang("error_2"));
		}
		$limit_max_photos = intval($this->input->post("limit_max_photos"));
		$limit_max_photos_post = intval($this->input->post("limit_max_photos_post"));

		$this->admin_model->updateSettings(
			array(
				"limit_max_photos" => $limit_max_photos,
				"limit_max_photos_post" => $limit_max_photos_post
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_13"));
		redirect(site_url("admin/limits"));
	}

	public function blogs()
	{
		$this->template->loadData("activeLink",
			array("admin" => array("blogs" => 1)));
		$this->template->loadContent("admin/blogs.php", array(
			)
		);

	}

	public function blog_page()
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("user_blogs.ID", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"user_blogs.title" => 0
				 ),
				 1 => array(
				 	"users.username" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->admin_model
				->get_total_blogs()
		);
		$blogs = $this->admin_model->get_blogs($this->datatables);

		foreach($blogs->result() as $r) {

			$this->datatables->data[] = array(
				$r->title,
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp, "first_name" => $r->first_name, "last_name" => $r->last_name)),
				'<a href="' . site_url("admin/edit_blog/" . $r->ID) .'" class="btn btn-warning btn-xs" title="'. lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a>  <a href="' . site_url("admin/delete_blog/" . $r->ID . "/" . $this->security->get_csrf_hash()) .'" onclick="return confirm(\'' . lang("ctn_86") . '\')" class="btn btn-danger btn-xs" title="'. lang("ctn_57") .'"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function edit_blog($id)
	{
		$this->load->model("blog_model");
		$id = intval($id);
		$blog = $this->blog_model->get_blog($id);
		if($blog->num_rows() == 0) {
			$this->template->error(lang("error_171"));
		}
		$blog = $blog->row();

		$this->template->loadData("activeLink",
			array("admin" => array("blogs" => 1)));
		$this->template->loadContent("admin/edit_blog.php", array(
			"blog" => $blog
			)
		);
	}

	public function edit_blog_pro($id)
	{
		$this->load->model("blog_model");
		$id = intval($id);
		$blog = $this->blog_model->get_blog($id);
		if($blog->num_rows() == 0) {
			$this->template->error(lang("error_171"));
		}
		$blog = $blog->row();

		$title = $this->common->nohtml($this->input->post("title"));
		$description = $this->common->nohtml($this->input->post("description"));

		$private = intval($this->input->post("private"));

		if(empty($title)) {
			$this->template->error(lang("error_172"));
		}


		$this->blog_model->update_blog($id, array(
			"title" => $title,
			"description" => $description,
			"private" => $private,
			"timestamp" => time()
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_103"));
		redirect(site_url("admin/blogs"));
	}

	public function delete_blog($id, $hash)
	{
		$this->load->model("blog_model");
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$blog = $this->blog_model->get_blog($id);
		if($blog->num_rows() == 0) {
			$this->template->error(lang("error_171"));
		}
		$blog = $blog->row();

		$this->blog_model->delete_blog($id);
		// Delete posts
		$this->blog_model->delete_all_blog_posts($blog->ID);
		$this->session->set_flashdata("globalmsg", lang("success_104"));
		redirect(site_url("admin/blogs"));

	}

	public function blog_posts()
	{
		$this->template->loadData("activeLink",
			array("admin" => array("blog_posts" => 1)));
		$this->template->loadContent("admin/blog_posts.php", array(
			)
		);
	}

	public function blog_post_page()
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("user_blog_posts.ID", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 1 => array(
				 	"user_blog_posts.title" => 0
				 ),
				 1 => array(
				 	"user_blog_posts.status" => 0
				 ),
				 2 => array(
				 	"user_blog_posts.views" => 0
				 ),
				 3 => array(
				 	"users.username" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->admin_model
				->get_total_blog_posts()
		);
		$blogs = $this->admin_model->get_blog_posts($this->datatables);

		foreach($blogs->result() as $r) {

			if($r->status == 0) {
				$status = lang("ctn_768");
			} elseif($r->status == 1) {
				$status = lang("ctn_794");
			}

			if(!empty($r->image)) {
				$image = '<img src="'.base_url() . $this->settings->info->upload_path_relative.'/' . $r->image.'" class="blog-post-thumb">';
			} else {
				$image = "";
			}

			if($r->timestamp > 0) {
				$time = date($this->settings->info->date_format, $r->timestamp);
			} else {
				$time = lang("ctn_795");
			}

			$this->datatables->data[] = array(
				$image,
				$r->title,
				$status,
				$r->views,
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp, "first_name" => $r->first_name, "last_name" => $r->last_name)),
				'<a href="' . site_url("admin/edit_blog_post/" . $r->ID) .'" class="btn btn-warning btn-xs" title="'. lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a>  <a href="' . site_url("admin/delete_blog_post/" . $r->ID . "/" . $this->security->get_csrf_hash()) .'" onclick="return confirm(\'' . lang("ctn_86") . '\')" class="btn btn-danger btn-xs" title="'. lang("ctn_57") .'"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function edit_blog_post($id)
	{
		$this->load->model("blog_model");
		$id = intval($id);
		$post = $this->blog_model->get_post($id);
		if($post->num_rows() == 0) {
			$this->template->error(lang("error_173"));
		}
		$post = $post->row();

		$this->template->loadData("activeLink",
			array("admin" => array("blog_posts" => 1)));
		$this->template->loadContent("admin/edit_blog_post.php", array(
			"post" => $post
			)
		);
	}

	public function edit_blog_post_pro($id)
	{
		$this->load->model("blog_model");
		$id = intval($id);
		$post = $this->blog_model->get_post($id);
		if($post->num_rows() == 0) {
			$this->template->error(lang("error_173"));
		}
		$post = $post->row();

		$this->template->loadData("activeLink",
			array("admin" => array("blog_posts" => 1)));

		$title = $this->common->nohtml($this->input->post("title"));
		$status = intval($this->input->post("status"));
		$blog_post = $this->lib_filter->go($this->input->post("blog_post"));

		if(empty($title)) {
			$this->template->error(lang("error_174"));
		}

		if(empty($blog_post)) {
			$this->template->error(lang("error_175"));
		}

		// Upload
		$this->load->library("upload");

		$blog_image = $post->image;
		if (isset($_FILES['userfile']) && $_FILES['userfile']['size'] > 0) {
			$this->upload->initialize(array(
		       "upload_path" => $this->settings->info->upload_path,
		       "overwrite" => FALSE,
		       "max_filename" => 300,
		       "encrypt_name" => TRUE,
		       "remove_spaces" => TRUE,
		       "allowed_types" => "gif|png|jpg|jpeg",
		       "max_size" => $this->settings->info->file_size,
		       "max_width" => 800,
		       "max_height" => 800
		    ));

		    if (!$this->upload->do_upload("userfile")) {
		    	$this->template->error(lang("error_21")
		    	.$this->upload->display_errors());
		    }

		    $data = $this->upload->data();

		    $blog_image = $data['file_name'];
		}

		if($status && $post->timestamp == 0) {
			$time = time();
		} else {
			$time = $post->timestamp;
		}

		$this->blog_model->update_post($id, array(
			"title" => $title,
			"body" => $blog_post,
			"status" => $status,
			"image" => $blog_image,
			"timestamp" => $time,
			"last_updated" => time()
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_105"));
		redirect(site_url("admin/blog_posts"));
	}

	public function delete_blog_post($id, $hash)
	{
		$this->load->model("blog_model");
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$post = $this->blog_model->get_post($id);
		if($post->num_rows() == 0) {
			$this->template->error(lang("error_173"));
		}
		$post = $post->row();

		$this->blog_model->delete_post($id);
		$this->session->set_flashdata("globalmsg", lang("success_106"));
		redirect(site_url("admin/blog_posts"));
	}

	public function verified_requests()
	{
		if(!$this->user->info->admin && !$this->user->info->admin_settings) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink",
			array("admin" => array("verified_requests" => 1)));
		$this->template->loadContent("admin/verified_requests.php", array(
			)
		);
	}

	public function verified_request_page()
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("verified_requests.ID", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"users.username" => 0
				 ),
				 1 => array(
				 	"verified_requests.about" => 0
				 ),
				 2 => array(
				 	"verified_requests.timestamp" => 0
				 ),
			)
		);

		$this->datatables->set_total_rows(
			$this->admin_model
				->get_total_verified_requests()
		);
		$req = $this->admin_model->get_verified_requests($this->datatables);

		foreach($req->result() as $r) {

			$this->datatables->data[] = array(
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp, "first_name" => $r->first_name, "last_name" => $r->last_name)),
				$r->about,
				date($this->settings->info->date_format, $r->timestamp),
				'<a href="'.site_url("admin/accept_verified/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-success btn-xs">'.lang("ctn_623").'</a> <a href="'.site_url("admin/reject_verified/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs">'.lang("ctn_624").'</a> '
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function reject_verified($id, $hash)
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$req = $this->admin_model->get_verified_request($id);
		if($req->num_rows() == 0) {
			$this->template->error(lang("error_155"));
		}
		$req = $req->row();

		// Set status to active
		$this->admin_model->delete_verified_request($id);

		$user = $this->user_model->get_user_by_id($req->userid);
		if($user->num_rows() > 0) {
			$user = $user->row();
			// Alert ad buyer
			$this->user_model->increment_field($user->ID, "noti_count", 1);
			$this->user_model->add_notification(array(
				"userid" => $user->ID,
				"url" => "user_settings",
				"timestamp" => time(),
				"message" => lang("ctn_747"),
				"status" => 0,
				"fromid" => $this->user->info->ID,
				"username" => $user->username,
				"email" => $user->email,
				"email_notification" => $user->email_notification
				)
			);
		}

		$this->session->set_flashdata("globalmsg", lang("success_88"));
		redirect(site_url("admin/verified_requests"));
	}

	public function accept_verified($id, $hash)
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$req = $this->admin_model->get_verified_request($id);
		if($req->num_rows() == 0) {
			$this->template->error(lang("error_155"));
		}
		$req = $req->row();

		// Set status to active
		$this->admin_model->delete_verified_request($id);

		$this->user_model->update_user($req->userid, array(
			"verified" => 1
			)
		);

		$user = $this->user_model->get_user_by_id($req->userid);
		if($user->num_rows() > 0) {
			$user = $user->row();
			// Alert ad buyer
			$this->user_model->increment_field($user->ID, "noti_count", 1);
			$this->user_model->add_notification(array(
				"userid" => $user->ID,
				"url" => "user_settings",
				"timestamp" => time(),
				"message" => lang("ctn_748"),
				"status" => 0,
				"fromid" => $this->user->info->ID,
				"username" => $user->username,
				"email" => $user->email,
				"email_notification" => $user->email_notification
				)
			);
		}

		$this->session->set_flashdata("globalmsg", lang("success_87"));
		redirect(site_url("admin/verified_requests"));
	}

	public function promoted_posts()
	{
		if(!$this->user->info->admin && !$this->user->info->admin_settings) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink",
			array("admin" => array("promoted_posts" => 1)));
		$this->template->loadContent("admin/promoted_posts.php", array(
			)
		);
	}

	public function promoted_post_page()
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("promoted_posts.ID", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"promoted_posts.postid" => 0
				 ),
				 1 => array(
				 	"promoted_posts.status" => 0
				 ),
				 2 => array(
				 	"promoted_posts.pageviews" => 0
				 ),
				 3 => array(
				 	"users.username" => 0
				 ),
				 4 => array(
				 	"promoted_posts.timestamp" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->admin_model
				->get_total_promoted_posts()
		);
		$ads = $this->admin_model->get_promoted_posts($this->datatables);

		foreach($ads->result() as $r) {

			$options = "";

			if($r->status == 0) {
				$status = lang("ctn_701");
				$options .= '<a href="'.site_url("admin/accept_post/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-success btn-xs">'.lang("ctn_623").'</a> <a href="'.site_url("admin/reject_post/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs">'.lang("ctn_624").'</a> ';
			} elseif($r->status == 1) {
				$status = lang("ctn_702");
			} elseif($r->status == 2) {
				$status = lang("ctn_703");
			}


			$this->datatables->data[] = array(
				'<a href="'.site_url("home/index/3?postid=" . $r->postid).'">'.lang("ctn_749").'</a>',
				$status,
				$r->pageviews,
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp, "first_name" => $r->first_name, "last_name" => $r->last_name)),
				date($this->settings->info->date_format, $r->timestamp),
				$options . '<a href="' . site_url("admin/edit_promoted_post/" . $r->ID) .'" class="btn btn-warning btn-xs" title="'. lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a>  <a href="' . site_url("admin/delete_promoted_post/" . $r->ID . "/" . $this->security->get_csrf_hash()) .'" onclick="return confirm(\'' . lang("ctn_86") . '\')" class="btn btn-danger btn-xs" title="'. lang("ctn_57") .'"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function edit_promoted_post($id)
	{
		$id = intval($id);
		$post = $this->admin_model->get_promoted_post($id);
		if($post->num_rows() == 0) {
			$this->template->error(lang("error_156"));
		}
		$post = $post->row();

		$this->template->loadData("activeLink",
			array("admin" => array("promoted_posts" => 1)));
		$this->template->loadContent("admin/edit_promoted_post.php", array(
			"post" => $post
			)
		);
	}

	public function edit_promoted_post_pro($id)
	{
		$id = intval($id);
		$post = $this->admin_model->get_promoted_post($id);
		if($post->num_rows() == 0) {
			$this->template->error(lang("error_156"));
		}
		$post = $post->row();

		$status = intval($this->input->post("status"));
		$pageviews = intval($this->input->post("pageviews"));

		$this->admin_model->update_promoted_post($id, array(
			"status" => $status,
			"pageviews" => $pageviews
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_89"));
		redirect(site_url("admin/promoted_posts"));
	}

	public function delete_promoted_post($id, $hash)
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$post = $this->admin_model->get_promoted_post($id);
		if($post->num_rows() == 0) {
			$this->template->error(lang("error_156"));
		}
		$post = $post->row();

		$this->admin_model->delete_promoted_post($id);
		$this->session->set_flashdata("globalmsg", lang("success_90"));
		redirect(site_url("admin/promoted_posts"));
	}

	public function accept_post($id, $hash)
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$post = $this->admin_model->get_promoted_post($id);
		if($post->num_rows() == 0) {
			$this->template->error(lang("error_156"));
		}
		$post = $post->row();

		// Set status to active
		$this->admin_model->update_promoted_post($id, array(
			"status" => 2,
			)
		);

		$user = $this->user_model->get_user_by_id($post->userid);
		if($user->num_rows() > 0) {
			$user = $user->row();
			// Alert ad buyer
			$this->user_model->increment_field($user->ID, "noti_count", 1);
			$this->user_model->add_notification(array(
				"userid" => $user->ID,
				"url" => "home/index/3?postid=" . $post->postid,
				"timestamp" => time(),
				"message" => lang("ctn_750"),
				"status" => 0,
				"fromid" => $this->user->info->ID,
				"username" => $user->username,
				"email" => $user->email,
				"email_notification" => $user->email_notification
				)
			);
		}

		$this->session->set_flashdata("globalmsg", lang("success_91"));
		redirect(site_url("admin/promoted_posts"));
	}

	public function reject_post($id, $hash)
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$post = $this->admin_model->get_promoted_post($id);
		if($post->num_rows() == 0) {
			$this->template->error(lang("error_156"));
		}
		$post = $post->row();

		// Calculate credits to give back
		$amount = floatval($post->pageviews/1000);
		$credits = $amount * $this->settings->info->credit_price_pageviews;

		// Set status to active
		$this->admin_model->delete_promoted_post($id);


		// Send notification
		$user = $this->user_model->get_user_by_id($post->userid);
		if($user->num_rows() > 0) {
			$user = $user->row();
			// Alert ad buyer
			$this->user_model->increment_field($user->ID, "noti_count", 1);
			$this->user_model->add_notification(array(
				"userid" => $user->ID,
				"url" => "home/index/3?postid=" . $post->postid,
				"timestamp" => time(),
				"message" => lang("ctn_751") . " " . $credits ." " . lang("ctn_350"),
				"status" => 0,
				"fromid" => $this->user->info->ID,
				"username" => $user->username,
				"email" => $user->email,
				"email_notification" => $user->email_notification
				)
			);

			// Update points
			$this->user_model->update_user($user->ID, array(
				"points" => $user->points + $credits
				)
			);
		}

		$this->session->set_flashdata("globalmsg", lang("success_92"));
		redirect(site_url("admin/promoted_posts"));
	}

	public function rotation_ads()
	{
		if(!$this->user->info->admin && !$this->user->info->admin_settings) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink",
			array("admin" => array("rotation_ads" => 1)));
		$this->template->loadContent("admin/rotation_ads.php", array(
			)
		);
	}

	public function rotation_ad_page()
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("rotation_ads.ID", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"rotation_ads.name" => 0
				 ),
				 1 => array(
				 	"rotation_ads.status" => 0
				 ),
				 2 => array(
				 	"rotation_ads.pageviews" => 0
				 ),
				 3 => array(
				 	"users.username" => 0
				 ),
				 4 => array(
				 	"rotation_ads.timestamp" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->admin_model
				->get_total_rotation_ads()
		);
		$ads = $this->admin_model->get_rotation_ads($this->datatables);

		foreach($ads->result() as $r) {

			$options = "";

			if($r->status == 0) {
				$status = lang("ctn_701");
				$options .= '<a href="'.site_url("admin/accept_ad/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-success btn-xs">'.lang("ctn_623").'</a> <a href="'.site_url("admin/reject_ad/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs">'.lang("ctn_624").'</a> ';
			} elseif($r->status == 1) {
				$status = lang("ctn_702");
			} elseif($r->status == 2) {
				$status = lang("ctn_703");
			}


			$this->datatables->data[] = array(
				$r->name,
				$status,
				$r->pageviews,
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp, "first_name" => $r->first_name, "last_name" => $r->last_name)),
				date($this->settings->info->date_format, $r->timestamp),
				$options . '<a href="' . site_url("admin/edit_rotation_ad/" . $r->ID) .'" class="btn btn-warning btn-xs" title="'. lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a>  <a href="' . site_url("admin/delete_rotation_ad/" . $r->ID . "/" . $this->security->get_csrf_hash()) .'" onclick="return confirm(\'' . lang("ctn_86") . '\')" class="btn btn-danger btn-xs" title="'. lang("ctn_57") .'"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function add_rotation_ad()
	{
		if(!$this->user->info->admin && !$this->user->info->admin_settings) {
			$this->template->error(lang("error_2"));
		}
		$name = $this->common->nohtml($this->input->post("name"));
		$advert = $this->lib_filter->go($this->input->post("advert"));
		$status = intval($this->input->post("status"));
		$pageviews = intval($this->input->post("pageviews"));

		if(empty($name)) {
			$this->template->error(lang("error_157"));
		}

		$this->admin_model->add_rotation_ad(array(
			"name" => $name,
			"advert" => $advert,
			"status" => $status,
			"pageviews" => $pageviews,
			"userid" => $this->user->info->ID,
			"timestamp" => time()
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_93"));
		redirect(site_url("admin/rotation_ads"));
	}

	public function edit_rotation_ad($id)
	{
		if(!$this->user->info->admin && !$this->user->info->admin_settings) {
			$this->template->error(lang("error_2"));
		}
		$id = intval($id);
		$ad = $this->admin_model->get_rotation_ad($id);
		if($ad->num_rows() == 0) {
			$this->template->error(lang("error_158"));
		}
		$ad = $ad->row();

		$this->template->loadData("activeLink",
			array("admin" => array("rotation_ads" => 1)));
		$this->template->loadContent("admin/edit_rotation_ad.php", array(
			"ad" => $ad
			)
		);
	}

	public function edit_rotation_ad_pro($id)
	{
		if(!$this->user->info->admin && !$this->user->info->admin_settings) {
			$this->template->error(lang("error_2"));
		}
		$id = intval($id);
		$ad = $this->admin_model->get_rotation_ad($id);
		if($ad->num_rows() == 0) {
			$this->template->error(lang("error_158"));
		}
		$ad = $ad->row();

		$name = $this->common->nohtml($this->input->post("name"));
		$advert = $this->lib_filter->go($this->input->post("advert"));
		$status = intval($this->input->post("status"));
		$pageviews = intval($this->input->post("pageviews"));

		if(empty($name)) {
			$this->template->error(lang("error_157"));
		}

		$this->admin_model->update_rotation_ad($id, array(
			"name" => $name,
			"advert" => $advert,
			"status" => $status,
			"pageviews" => $pageviews
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_94"));
		redirect(site_url("admin/rotation_ads"));
	}

	public function delete_rotation_ad($id)
	{
		if(!$this->user->info->admin && !$this->user->info->admin_settings) {
			$this->template->error(lang("error_2"));
		}
		$id = intval($id);
		$ad = $this->admin_model->get_rotation_ad($id);
		if($ad->num_rows() == 0) {
			$this->template->error(lang("error_158"));
		}

		$this->admin_model->delete_rotation_ad($id);
		$this->session->set_flashdata("globalmsg", lang("success_95"));
		redirect(site_url("admin/rotation_ads"));
	}

	public function accept_ad($id, $hash)
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$ad = $this->admin_model->get_rotation_ad($id);
		if($ad->num_rows() == 0) {
			$this->template->error(lang("error_158"));
		}
		$ad = $ad->row();

		// Set status to active
		$this->admin_model->update_rotation_ad($id, array(
			"status" => 2,
			)
		);

		$user = $this->user_model->get_user_by_id($ad->userid);
		if($user->num_rows() > 0) {
			$user = $user->row();
			// Alert ad buyer
			$this->user_model->increment_field($user->ID, "noti_count", 1);
			$this->user_model->add_notification(array(
				"userid" => $user->ID,
				"url" => "funds",
				"timestamp" => time(),
				"message" => lang("ctn_752"),
				"status" => 0,
				"fromid" => $this->user->info->ID,
				"username" => $user->username,
				"email" => $user->email,
				"email_notification" => $user->email_notification
				)
			);
		}

		$this->session->set_flashdata("globalmsg", lang("success_96"));
		redirect(site_url("admin/rotation_ads"));
	}

	public function reject_ad($id, $hash)
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$ad = $this->admin_model->get_rotation_ad($id);
		if($ad->num_rows() == 0) {
			$this->template->error(lang("error_158"));
		}
		$ad = $ad->row();

		// Calculate credits to give back
		$amount = floatval($ad->pageviews/1000);
		$credits = $amount * $this->settings->info->credit_price_pageviews;

		// Set status to active
		$this->admin_model->delete_rotation_ad($id);


		// Send notification
		$user = $this->user_model->get_user_by_id($ad->userid);
		if($user->num_rows() > 0) {
			$user = $user->row();
			// Alert ad buyer
			$this->user_model->increment_field($user->ID, "noti_count", 1);
			$this->user_model->add_notification(array(
				"userid" => $user->ID,
				"url" => "funds",
				"timestamp" => time(),
				"message" => lang("ctn_753") . " " . $credits ." " . lang("ctn_350"),
				"status" => 0,
				"fromid" => $this->user->info->ID,
				"username" => $user->username,
				"email" => $user->email,
				"email_notification" => $user->email_notification
				)
			);

			// Update points
			$this->user_model->update_user($user->ID, array(
				"points" => $user->points + $credits
				)
			);
		}

		$this->session->set_flashdata("globalmsg", lang("success_97"));
		redirect(site_url("admin/rotation_ads"));
	}

	public function ad_settings()
	{
		if(!$this->user->info->admin && !$this->user->info->admin_settings) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink",
			array("admin" => array("ad_settings" => 1)));

		$username = "";
		if($this->settings->info->rotation_ad_alert_user > 0) {
			$user = $this->user_model->get_user_by_id($this->settings->info->rotation_ad_alert_user);
			if($user->num_rows() == 0) {
				$username = "";
			} else {
				$user = $user->row();
				$username = $user->username;
			}
		}

		$this->template->loadContent("admin/ad_settings.php", array(
			"username" => $username
			)
		);
	}

	public function ad_settings_pro()
	{
		if(!$this->user->info->admin && !$this->user->info->admin_settings) {
			$this->template->error(lang("error_2"));
		}
		$enable_google_ads_feed =
			intval($this->input->post("enable_google_ads_feed"));
		$enable_google_ads_pages =
			intval($this->input->post("enable_google_ads_pages"));
		$enable_rotation_ads_feed =
			intval($this->input->post("enable_rotation_ads_feed"));
		$enable_rotation_ads_pages =
			intval($this->input->post("enable_rotation_ads_pages"));
		$credit_price_pageviews = intval($this->input->post("credit_price_pageviews"));
		$rotation_ad_alert_user = $this->common->nohtml($this->input->post("rotation_ad_alert_user"));
		$enable_promote_post = intval($this->input->post("enable_promote_post"));
		$enable_verified_buy = intval($this->input->post("enable_verified_buy"));
		$verified_cost = floatval($this->input->post("verified_cost"));
		$enable_verified_requests = intval($this->input->post("enable_verified_requests"));

		$userid = 0;
		if(!empty($rotation_ad_alert_user)) {
			$user = $this->user_model->get_user_by_username($rotation_ad_alert_user);
			if($user->num_rows() == 0) {
				$this->template->error(lang("error_52"));
			}
			$user = $user->row();
			$userid = $user->ID;
		}


		$this->admin_model->updateSettings(
			array(
				"enable_google_ads_feed" => $enable_google_ads_feed,
				"enable_google_ads_pages" => $enable_google_ads_pages,
				"enable_rotation_ads_feed" => $enable_rotation_ads_feed,
				"enable_rotation_ads_pages" => $enable_rotation_ads_pages,
				"credit_price_pageviews" => $credit_price_pageviews,
				"rotation_ad_alert_user" => $userid,
				"enable_promote_post" => $enable_promote_post,
				"enable_verified_buy" => $enable_verified_buy,
				"verified_cost" => $verified_cost,
				"enable_verified_requests" => $enable_verified_requests
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_13"));
		redirect(site_url("admin/ad_settings"));
	}

	public function reports()
	{
		$this->template->loadData("activeLink",
			array("admin" => array("reports" => 1)));
		$this->template->loadContent("admin/reports.php", array(
			)
		);
	}

	public function report_page()
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("reports.ID", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"page_categories.name" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->admin_model
				->get_total_reports()
		);
		$reports = $this->admin_model->get_reports($this->datatables);

		foreach($reports->result() as $r) {

			if(isset($r->reported_username)) {
				$report = $this->common->get_user_display(array("username" => $r->reported_username, "avatar" => $r->reported_avatar, "online_timestamp" => $r->reported_online_timestamp, "first_name" => $r->reported_first_name, "last_name" => $r->reported_last_name));
				$type = lang("ctn_339");
			} else {
				$report = "<a href=''>" . $r->page_name . "</a>";
				$type = lang("ctn_552");
			}

			$this->datatables->data[] = array(
				$report,
				$type,
				$r->reason,
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp, "first_name" => $r->first_name, "last_name" => $r->last_name)),
				date($this->settings->info->date_format, $r->timestamp),
				'<a href="' . site_url("admin/delete_report/" . $r->ID . "/" . $this->security->get_csrf_hash()) .'" onclick="return confirm(\'' . lang("ctn_86") . '\')" class="btn btn-danger btn-xs" title="'. lang("ctn_57") .'"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function delete_report($id, $hash)
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$report = $this->admin_model->get_report($id);
		if($report->num_rows() == 0) {
			$this->template->error(lang("error_81"));
		}
		$report = $report->row();

		$this->admin_model->delete_report($id);
		$this->session->set_flashdata("globalmsg", lang("success_48"));
		redirect(site_url("admin/reports"));
	}

	public function page_categories()
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_settings"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink",
			array("admin" => array("page_categories" => 1)));

		$this->template->loadContent("admin/page_categories.php", array(
			)
		);
	}

	public function page_categories_page()
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("page_categories.ID", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"page_categories.name" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->admin_model
				->get_total_page_categories()
		);
		$categories = $this->admin_model->get_page_categories($this->datatables);

		foreach($categories->result() as $r) {

			$this->datatables->data[] = array(
				$r->name,
				$r->description,
				'<a href="' . site_url("admin/edit_page_cat/" . $r->ID) .'" class="btn btn-warning btn-xs" title="'. lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a> <a href="' . site_url("admin/delete_page_cat/" . $r->ID . "/" . $this->security->get_csrf_hash()) .'" onclick="return confirm(\'' . lang("ctn_86") . '\')" class="btn btn-danger btn-xs" title="'. lang("ctn_57") .'"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function add_page_category()
	{
		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->common->nohtml($this->input->post("description"));

		if(empty($name)) {
			$this->template->error(lang("error_82"));
		}

		$this->admin_model->add_page_category(array(
			"name" => $name,
			"description" => $desc
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_49"));
		redirect(site_url("admin/page_categories"));
	}

	public function edit_page_cat($id) {
		if(!$this->common->has_permissions(array("admin",
			"admin_settings"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink",
			array("admin" => array("page_categories" => 1)));

		$id = intval($id);
		$category = $this->admin_model->get_page_category($id);
		if($category->num_rows() == 0) {
			$this->template->error(lang("error_83"));
		}
		$category = $category->row();

		$this->template->loadContent("admin/edit_page_category.php", array(
			"category" => $category
			)
		);
	}

	public function edit_page_cat_pro($id)
	{
		$id = intval($id);
		$category = $this->admin_model->get_page_category($id);
		if($category->num_rows() == 0) {
			$this->template->error(lang("error_83"));
		}
		$category = $category->row();

		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->common->nohtml($this->input->post("description"));

		if(empty($name)) {
			$this->template->error(lang("error_82"));
		}

		$this->admin_model->update_page_category($id, array(
			"name" => $name,
			"description" => $desc
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_50"));
		redirect(site_url("admin/page_categories"));
	}

	public function delete_page_cat($id, $hash)
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_settings"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink",
			array("admin" => array("page_categories" => 1)));
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$category = $this->admin_model->get_page_category($id);
		if($category->num_rows() == 0) {
			$this->template->error(lang("error_83"));
		}

		$this->admin_model->delete_page_category($id);
		$this->session->set_flashdata("globalmsg", lang("success_51"));
		redirect(site_url("admin/page_categories"));
	}

	public function custom_fields()
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_members"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink",
			array("admin" => array("custom_fields" => 1)));
		$fields = $this->admin_model->get_custom_fields(array());
		$this->template->loadContent("admin/custom_fields.php", array(
			"fields" => $fields
			)
		);

	}

	public function add_custom_field_pro()
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_members"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$name = $this->common->nohtml($this->input->post("name"));
		$type = intval($this->input->post("type"));
		$options = $this->common->nohtml($this->input->post("options"));
		$required = intval($this->input->post("required"));
		$edit = intval($this->input->post("edit"));
		$profile = intval($this->input->post("profile"));
		$help_text = $this->common->nohtml($this->input->post("help_text"));
		$register = intval($this->input->post("register"));

		if(empty($name)) {
			$this->template->error(lang("error_75"));
		}

		if($type < 0 || $type > 4) {
			$this->template->error(lang("error_76"));
		}

		// Add
		$this->admin_model->add_custom_field(array(
			"name" => $name,
			"type" => $type,
			"options" => $options,
			"required" => $required,
			"edit" => $edit,
			"profile" => $profile,
			"help_text" => $help_text,
			"register" => $register
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_37"));
		redirect(site_url("admin/custom_fields"));
	}

	public function edit_custom_field($id)
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_members"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink",
			array("admin" => array("custom_fields" => 1)));
		$id = intval($id);
		$field = $this->admin_model->get_custom_field($id);
		if($field->num_rows() == 0) {
			$this->template->error(lang("error_77"));
		}

		$field = $field->row();
		$this->template->loadContent("admin/edit_custom_field.php", array(
			"field" => $field
			)
		);
	}

	public function edit_custom_field_pro($id)
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_members"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$id = intval($id);
		$field = $this->admin_model->get_custom_field($id);
		if($field->num_rows() == 0) {
			$this->template->error(lang("error_77"));
		}

		$field = $field->row();

		$name = $this->common->nohtml($this->input->post("name"));
		$type = intval($this->input->post("type"));
		$options = $this->common->nohtml($this->input->post("options"));
		$required = intval($this->input->post("required"));
		$edit = intval($this->input->post("edit"));
		$profile = intval($this->input->post("profile"));
		$help_text = $this->common->nohtml($this->input->post("help_text"));
		$register = intval($this->input->post("register"));

		if(empty($name)) {
			$this->template->error(lang("error_75"));
		}

		if($type < 0 || $type > 4) {
			$this->template->error(lang("error_76"));
		}

		// Add
		$this->admin_model->update_custom_field($id, array(
			"name" => $name,
			"type" => $type,
			"options" => $options,
			"required" => $required,
			"edit" => $edit,
			"profile" => $profile,
			"help_text" => $help_text,
			"register" => $register
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_38"));
		redirect(site_url("admin/custom_fields"));
	}

	public function delete_custom_field($id, $hash)
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_members"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$field = $this->admin_model->get_custom_field($id);
		if($field->num_rows() == 0) {
			$this->template->error(lang("error_77"));
		}

		$this->admin_model->delete_custom_field($id);
		$this->session->set_flashdata("globalmsg", lang("success_39"));
		redirect(site_url("admin/custom_fields"));
	}

	public function premium_users($page=0)
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_payment"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink",
			array("admin" => array("premium_users" => 1)));

		$this->template->loadContent("admin/premium_users.php", array(
			)
		);
	}

	public function premium_users_page()
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("users.ID", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 3 => array(
				 	"payment_plans.name" => 0
				 ),
				 4 => array(
				 	"users.premium_time" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->admin_model
				->get_total_premium_users_count()
		);
		$users = $this->admin_model->get_premium_users($this->datatables);

		foreach($users->result() as $r) {
			  $time = $this->common->convert_time($r->premium_time);
			  unset($time['mins']);
			  unset($time['secs']);
			$this->datatables->data[] = array(
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)),
				$r->first_name . " " . $r->last_name,
				$r->email,
				$r->name,
				$this->common->get_time_string($time),
				date($this->settings->info->date_format, $r->joined),
				'<a href="' . site_url("admin/edit_member/" . $r->ID) .'" class="btn btn-warning btn-xs" title="'. lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a> <a href="' . site_url("admin/delete_member/" . $r->ID . "/" . $this->security->get_csrf_hash()) .'" onclick="return confirm(\'' . lang("ctn_86") . '\')" class="btn btn-danger btn-xs" title="'. lang("ctn_57") .'"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function user_roles()
	{
		if(!$this->user->info->admin) $this->template->error(lang("error_2"));
		$this->template->loadData("activeLink",
			array("admin" => array("user_roles" => 1)));
		$roles = $this->admin_model->get_user_roles();

		$permissions = $this->get_default_permissions();

		$this->template->loadContent("admin/user_roles.php", array(
			"roles" => $roles,
			"permissions" => $permissions
			)
		);
	}

	public function add_user_role_pro()
	{
		if(!$this->user->info->admin) $this->template->error(lang("error_2"));

		$name = $this->common->nohtml($this->input->post("name"));
		if (empty($name)) $this->template->error(lang("error_64"));

		$permissions = $this->get_default_permissions();

		$user_roles = $this->input->post("user_roles");
		foreach($user_roles as $ur) {
			$ur = intval($ur);
			foreach($permissions as $key => $p) {
				if($p['id'] == $ur) {
					$permissions[$key]['selected'] = 1;
				}
			}
		}

		$data = array();
		foreach($permissions as $k=>$v) {
			$data[$k] = $v['selected'];
		}
		$data['name'] = $name;

		$this->admin_model->add_user_role(
			$data
		);

		$this->session->set_flashdata("globalmsg", lang("success_30"));
		redirect(site_url("admin/user_roles"));
	}

	public function edit_user_role($id)
	{
		if(!$this->user->info->admin) $this->template->error(lang("error_2"));
		$id = intval($id);
		$role = $this->admin_model->get_user_role($id);
		if ($role->num_rows() == 0) $this->template->error(lang("error_65"));

		$role = $role->row();
		$this->template->loadData("activeLink",
			array("admin" => array("user_roles" => 1)));

		$permissions = $this->get_default_permissions();
		foreach($permissions as $k=>$v) {
			if($role->{$k}) {
				$permissions[$k]['selected'] = 1;
			}
		}

		$this->template->loadContent("admin/edit_user_role.php", array(
			"role" => $role,
			"permissions" => $permissions
			)
		);
	}

	private function get_default_permissions()
	{
		$urp = $this->admin_model->get_user_role_permissions();
		$permissions = array();
		foreach($urp->result() as $r) {
			$permissions[$r->hook] = array(
				"name" => lang($r->name),
				"desc" => lang($r->description),
				"id" => $r->ID,
				"class" => $r->classname,
				"selected" => 0
			);
		}
		return $permissions;
	}

	public function edit_user_role_pro($id)
	{
		if(!$this->user->info->admin) $this->template->error(lang("error_2"));
		$id = intval($id);
		$role = $this->admin_model->get_user_role($id);
		if ($role->num_rows() == 0) $this->template->error(lang("error_65"));

		$name = $this->common->nohtml($this->input->post("name"));
		if (empty($name)) $this->template->error(lang("error_64"));

		$permissions = $this->get_default_permissions();

		$user_roles = $this->input->post("user_roles");
		foreach($user_roles as $ur) {
			$ur = intval($ur);
			foreach($permissions as $key => $p) {
				if($p['id'] == $ur) {
					$permissions[$key]['selected'] = 1;
				}
			}
		}

		$data = array();
		foreach($permissions as $k=>$v) {
			$data[$k] = $v['selected'];
		}
		$data['name'] = $name;


		$this->admin_model->update_user_role($id,
			$data
		);
		$this->session->set_flashdata("globalmsg", lang("success_31"));
		redirect(site_url("admin/user_roles"));
	}

	public function delete_user_role($id, $hash)
	{
		if(!$this->user->info->admin) $this->template->error(lang("error_2"));
		if ($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$group = $this->admin_model->get_user_role($id);
		if ($group->num_rows() == 0) $this->template->error(lang("error_65"));

		$this->admin_model->delete_user_role($id);
		// Delete all user groups from member

		$this->session->set_flashdata("globalmsg", lang("success_32"));
		redirect(site_url("admin/user_roles"));
	}

	public function payment_logs($page = 0)
	{
		if(!$this->user->info->admin && !$this->user->info->admin_payment) {
			$this->template->error(lang("error_2"));
		}

		$page = intval($page);
		$this->template->loadData("activeLink",
			array("admin" => array("payment_logs" => 1)));

		$this->template->loadContent("admin/payment_logs.php", array(
			)
		);
	}

	public function payment_logs_page()
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("users.joined", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 2 => array(
				 	"payment_logs.amount" => 0
				 ),
				 3 => array(
				 	"payment_logs.timestamp" => 0
				 ),
				 4 => array(
				 	"payment_logs.processor" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->admin_model
				->get_total_payment_logs_count()
		);
		$logs = $this->admin_model->get_payment_logs($this->datatables);

		foreach($logs->result() as $r) {
			$this->datatables->data[] = array(
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)),
				$r->email,
				number_format($r->amount, 2),
				date($this->settings->info->date_format, $r->timestamp),
				$r->processor
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function payment_plans()
	{

		if(!$this->user->info->admin && !$this->user->info->admin_payment) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink",
			array("admin" => array("payment_plans" => 1)));
		$plans = $this->admin_model->get_payment_plans();

		$this->template->loadContent("admin/payment_plans.php", array(
			"plans" => $plans
			)
		);
	}

	public function add_payment_plan()
	{
		if(!$this->user->info->admin && !$this->user->info->admin_payment) {
			$this->template->error(lang("error_2"));
		}
		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->common->nohtml($this->input->post("description"));
		$cost = abs($this->input->post("cost"));
		$color = $this->common->nohtml($this->input->post("color"));
		$fontcolor = $this->common->nohtml($this->input->post("fontcolor"));
		$days = intval($this->input->post("days"));
		$icon = $this->common->nohtml($this->input->post("icon"));

		$this->admin_model->add_payment_plan(array(
			"name" => $name,
			"cost" => $cost,
			"hexcolor" => $color,
			"days" => $days,
			"description" => $desc,
			"fontcolor" => $fontcolor,
			"icon" => $icon
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_25"));
		redirect(site_url("admin/payment_plans"));
	}

	public function edit_payment_plan($id)
	{
		if(!$this->user->info->admin && !$this->user->info->admin_payment) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadExternal(
			'<script src="'.base_url().'scripts/libraries/jscolor.min.js"></script>'
		);
		$this->template->loadData("activeLink",
			array("admin" => array("payment_plans" => 1)));
		$id = intval($id);
		$plan = $this->admin_model->get_payment_plan($id);
		if($plan->num_rows() == 0) $this->template->error(lang("error_61"));

		$this->template->loadContent("admin/edit_payment_plan.php", array(
			"plan" => $plan->row()
			)
		);
	}

	public function edit_payment_plan_pro($id)
	{
		if(!$this->user->info->admin && !$this->user->info->admin_payment) {
			$this->template->error(lang("error_2"));
		}
		$id = intval($id);
		$plan = $this->admin_model->get_payment_plan($id);
		if($plan->num_rows() == 0) $this->template->error(lang("error_61"));

		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->common->nohtml($this->input->post("description"));
		$cost = abs($this->input->post("cost"));
		$color = $this->common->nohtml($this->input->post("color"));
		$fontcolor = $this->common->nohtml($this->input->post("fontcolor"));
		$days = intval($this->input->post("days"));
		$icon = $this->common->nohtml($this->input->post("icon"));

		$this->admin_model->update_payment_plan($id, array(
			"name" => $name,
			"cost" => $cost,
			"hexcolor" => $color,
			"days" => $days,
			"description" => $desc,
			"fontcolor" => $fontcolor,
			"icon" => $icon
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_26"));
		redirect(site_url("admin/payment_plans"));
	}

	public function delete_payment_plan($id, $hash)
	{
		if(!$this->user->info->admin && !$this->user->info->admin_payment) {
			$this->template->error(lang("error_2"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}

		$id = intval($id);
		$plan = $this->admin_model->get_payment_plan($id);
		if($plan->num_rows() == 0) $this->template->error(lang("error_61"));

		$this->admin_model->delete_payment_plan($id);
		$this->session->set_flashdata("globalmsg", lang("success_27"));
		redirect(site_url("admin/payment_plans"));
	}

	public function payment_settings()
	{
		if(!$this->user->info->admin && !$this->user->info->admin_payment) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink",
			array("admin" => array("payment_settings" => 1)));
		$this->template->loadContent("admin/payment_settings.php", array(
			)
		);
	}

	public function payment_settings_pro()
	{
		if(!$this->user->info->admin && !$this->user->info->admin_payment) {
			$this->template->error(lang("error_2"));
		}
		$paypal_email = $this->common->nohtml(
			$this->input->post("paypal_email"));
		$paypal_currency = $this->common->nohtml(
			$this->input->post("paypal_currency"));
		$payment_enabled = intval($this->input->post("payment_enabled"));
		$payment_symbol = $this->common->nohtml(
			$this->input->post("payment_symbol"));

		$stripe_secret_key = $this->common->nohtml($this->input->post("stripe_secret_key"));
		$stripe_publish_key = $this->common->nohtml($this->input->post("stripe_publish_key"));
		$checkout2_secret = $this->common->nohtml($this->input->post("checkout2_secret"));
		$checkout2_accountno = $this->common->nohtml($this->input->post("checkout2_accountno"));

		// update
		$this->admin_model->updateSettings(
			array(
				"paypal_email" => $paypal_email,
				"paypal_currency" => $paypal_currency,
				"payment_enabled" => $payment_enabled,
				"payment_symbol" => $payment_symbol,
				"stripe_secret_key" => $stripe_secret_key,
				"stripe_publish_key" => $stripe_publish_key,
				"checkout2_secret" => $checkout2_secret,
				"checkout2_accountno" => $checkout2_accountno
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_24"));
		redirect(site_url("admin/payment_settings"));

	}

	public function email_members()
	{
		if(!$this->user->info->admin && !$this->user->info->admin_members) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink",
			array("admin" => array("email_members" => 1)));
		$groups = $this->admin_model->get_user_groups();
		$this->template->loadContent("admin/email_members.php", array(
			"groups" => $groups
			)
		);
	}

	public function email_members_pro()
	{
		if(!$this->user->info->admin && !$this->user->info->admin_members) {
			$this->template->error(lang("error_2"));
		}
		$usernames = $this->common->nohtml($this->input->post("usernames"));
		$groupid = intval($this->input->post("groupid"));
		$title = $this->common->nohtml($this->input->post("title"));
		$message = $this->lib_filter->go($this->input->post("message"));

		if ($groupid == -1) {
			// All members
			$users = array();
			$usersc = $this->admin_model->get_all_users();
			foreach ($usersc->result() as $r) {
				$users[] = $r;
			}
		} else {
			$usernames = explode(",", $usernames);

			$users = array();
			foreach ($usernames as $username) {
				if (empty($username)) continue;
				$user = $this->user_model->get_user_by_username($username);
				if ($user->num_rows() == 0) {
					$this->template->error(lang("error_3") . $username);
				}
				$users[] = $user->row();
			}

			if ($groupid > 0) {
				$group = $this->admin_model->get_user_group($groupid);
				if ($group->num_rows() == 0) {
					$this->template->error(lang("error_4"));
				}

				$users_g = $this->admin_model->get_all_group_users($groupid);
				$cusers = $users;

				foreach ($users_g->result() as $r) {
					// Check for duplicates
					$skip = false;
					foreach ($cusers as $a) {
						if($a->userid == $r->userid) $skip = true;
					}
					if (!$skip) {
						$users[] = $r;
					}
				}
			}

		}

		foreach ($users as $r) {
			$this->common->send_email($title, $message, $r->email);
		}

		$this->session->set_flashdata("globalmsg", lang("success_1"));
		redirect(site_url("admin/email_members"));
	}

	public function user_groups()
	{
		if(!$this->user->info->admin && !$this->user->info->admin_members) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink",
			array("admin" => array("user_groups" => 1)));
		$groups = $this->admin_model->get_user_groups();
		$this->template->loadContent("admin/groups.php", array(
			"groups" => $groups
			)
		);
	}

	public function add_group_pro()
	{
		if(!$this->user->info->admin && !$this->user->info->admin_members) {
			$this->template->error(lang("error_2"));
		}
		$name = $this->common->nohtml($this->input->post("name"));
		$default = intval($this->input->post("default_group"));
		if (empty($name)) $this->template->error(lang("error_5"));


		$this->admin_model->add_group(
			array(
				"name" =>$name,
				"default" => $default,
				)
			);
		$this->session->set_flashdata("globalmsg", lang("success_2"));
		redirect(site_url("admin/user_groups"));
	}

	public function edit_group($id)
	{
		if(!$this->user->info->admin && !$this->user->info->admin_members) {
			$this->template->error(lang("error_2"));
		}
		$id = intval($id);
		$group = $this->admin_model->get_user_group($id);
		if ($group->num_rows() == 0) $this->template->error(lang("error_4"));

		$this->template->loadData("activeLink",
			array("admin" => array("user_groups" => 1)));

		$this->template->loadContent("admin/edit_group.php", array(
			"group" => $group->row()
			)
		);
	}

	public function edit_group_pro($id)
	{
		if(!$this->user->info->admin && !$this->user->info->admin_members) {
			$this->template->error(lang("error_2"));
		}
		$id = intval($id);
		$group = $this->admin_model->get_user_group($id);
		if ($group->num_rows() == 0) $this->template->error(lang("error_4"));

		$name = $this->common->nohtml($this->input->post("name"));
		$default = intval($this->input->post("default_group"));
		if (empty($name)) $this->template->error(lang("error_5"));

		$this->admin_model->update_group($id,
			array(
				"name" =>$name,
				"default" => $default
				)
		);
		$this->session->set_flashdata("globalmsg", lang("success_3"));
		redirect(site_url("admin/user_groups"));
	}

	public function delete_group($id, $hash)
	{
		if(!$this->user->info->admin && !$this->user->info->admin_members) {
			$this->template->error(lang("error_2"));
		}
		if ($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$group = $this->admin_model->get_user_group($id);
		if ($group->num_rows() == 0) $this->template->error(lang("error_4"));

		$this->admin_model->delete_group($id);
		// Delete all user groups from member
		$this->admin_model->delete_users_from_group($id);

		$this->session->set_flashdata("globalmsg", lang("success_4"));
		redirect(site_url("admin/user_groups"));
	}

	public function view_group($id, $page=0)
	{
		if(!$this->user->info->admin && !$this->user->info->admin_members) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink",
			array("admin" => array("user_groups" => 1)));
		$id = intval($id);
		$page = intval($page);
		$group = $this->admin_model->get_user_group($id);
		if ($group->num_rows() == 0) $this->template->error(lang("error_4"));

		$users = $this->admin_model->get_users_from_groups($id, $page);

		$this->load->library('pagination');
		$config['base_url'] = site_url("admin/view_group/" . $id);
		$config['total_rows'] = $this->admin_model
			->get_total_user_group_members_count($id);
		$config['per_page'] = 20;
		$config['uri_segment'] = 4;

		include (APPPATH . "/config/page_config.php");

		$this->pagination->initialize($config);

		$this->template->loadContent("admin/view_group.php", array(
			"group" => $group->row(),
			"users" => $users,
			"total_members" => $config['total_rows']
			)
		);

	}

	public function add_user_to_group_pro($id)
	{
		if(!$this->user->info->admin && !$this->user->info->admin_members) {
			$this->template->error(lang("error_2"));
		}
		$id = intval($id);
		$group = $this->admin_model->get_user_group($id);
		if ($group->num_rows() == 0) $this->template->error(lang("error_4"));

		$usernames = $this->common->nohtml($this->input->post("usernames"));
		$usernames = explode(",", $usernames);

		$users = array();
		foreach ($usernames as $username) {
			$user = $this->user_model->get_user_by_username($username);
			if($user->num_rows() == 0) $this->template->error(lang("error_3")
				. $username);
			$users[] = $user->row();
		}

		foreach ($users as $user) {
			// Check not already a member
			$userc = $this->admin_model->get_user_from_group($user->ID, $id);
			if ($userc->num_rows() == 0) {
				$this->admin_model->add_user_to_group($user->ID, $id);
			}
		}

		$this->session->set_flashdata("globalmsg", lang("success_5"));
		redirect(site_url("admin/view_group/" . $id));
	}

	public function remove_user_from_group($userid, $id, $hash)
	{
		if(!$this->user->info->admin && !$this->user->info->admin_members) {
			$this->template->error(lang("error_2"));
		}
		if ($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$userid = intval($userid);
		$group = $this->admin_model->get_user_group($id);
		if ($group->num_rows() == 0) $this->template->error(lang("error_4"));

		$user = $this->admin_model->get_user_from_group($userid, $id);
		if ($user->num_rows() == 0) $this->template->error(lang("error_7"));

		$this->admin_model->delete_user_from_group($userid, $id);
		$this->session->set_flashdata("globalmsg", lang("success_6"));
		redirect(site_url("admin/view_group/" . $id));
	}

	public function email_templates()
	{
		if(!$this->user->info->admin) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink",
			array("admin" => array("email_templates" => 1)));

		$languages = $this->config->item("available_languages");

		$this->template->loadContent("admin/email_templates.php", array(
			"languages" => $languages
			)
		);
	}

	public function email_template_page()
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("email_templates.ID", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"email_templates.title" => 0
				 ),
				 1 => array(
				 	"email_templates.hook" => 0
				 ),
				 2 => array(
				 	"email_templates.language" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->admin_model
				->get_total_email_templates()
		);
		$templates = $this->admin_model->get_email_templates($this->datatables);

		foreach($templates->result() as $r) {

			$this->datatables->data[] = array(
				$r->title,
				$r->hook,
				$r->language,
				'<a href="'.site_url("admin/edit_email_template/" . $r->ID).'" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("admin/delete_email_template/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_317").'\')" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_57").'"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function add_email_template()
	{
		$title = $this->common->nohtml($this->input->post("title"));
		$template = $this->lib_filter->go($this->input->post("template"));
		$hook = $this->common->nohtml($this->input->post("hook"));
		$language = $this->common->nohtml($this->input->post("language"));

		$this->admin_model->add_email_template(array(
			"title" => $title,
			"message" => $template,
			"hook" => $hook,
			"language" => $language
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_41"));
		redirect(site_url("admin/email_templates"));
	}

	public function edit_email_template($id)
	{
		if(!$this->user->info->admin) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink",
			array("admin" => array("email_templates" => 1)));
		$id = intval($id);

		$email_template = $this->admin_model->get_email_template($id);
		if ($email_template->num_rows() == 0) {
			$this->template->error(lang("error_8"));
		}

		$languages = $this->config->item("available_languages");

		$this->template->loadContent("admin/edit_email_template.php", array(
			"email_template" => $email_template->row(),
			"languages" => $languages
			)
		);
	}

	public function edit_email_template_pro($id)
	{
		if(!$this->user->info->admin) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink",
			array("admin" => array("email_templates" => 1)));
		$id = intval($id);
		$email_template = $this->admin_model->get_email_template($id);
		if ($email_template->num_rows() == 0) {
			$this->template->error(lang("error_8"));
		}

		$title = $this->common->nohtml($this->input->post("title"));
		$template = $this->lib_filter->go($this->input->post("template"));
		$hook = $this->common->nohtml($this->input->post("hook"));
		$language = $this->common->nohtml($this->input->post("language"));

		$this->admin_model->update_email_template($id, array(
			"title" => $title,
			"message" => $template,
			"hook" => $hook,
			"language" => $language
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_7"));
		redirect(site_url("admin/email_templates"));
	}

	public function delete_email_template($id, $hash)
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);

		$email_template = $this->admin_model->get_email_template($id);
		if ($email_template->num_rows() == 0) {
			$this->template->error(lang("error_8"));
		}

		$this->admin_model->delete_email_template($id);
		$this->session->set_flashdata("globalmsg", lang("success_42"));
		redirect(site_url("admin/email_templates"));
	}

	public function ipblock()
	{
		if(!$this->user->info->admin && !$this->user->info->admin_members) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink",
			array("admin" => array("ipblock" => 1)));

		$ipblock = $this->admin_model->get_ip_blocks();

		$this->template->loadContent("admin/ipblock.php", array(
			"ipblock" => $ipblock
			)
		);
	}

	public function add_ipblock()
	{
		if(!$this->user->info->admin && !$this->user->info->admin_members) {
			$this->template->error(lang("error_2"));
		}
		$ip = $this->common->nohtml($this->input->post("ip"));
		$reason = $this->common->nohtml($this->input->post("reason"));

		if (empty($ip)) $this->template->error(lang("error_10"));

		$this->admin_model->add_ipblock($ip, $reason);
		$this->session->set_flashdata("globalmsg", lang("success_8"));
		redirect(site_url("admin/ipblock"));
	}

	public function delete_ipblock($id)
	{
		if(!$this->user->info->admin && !$this->user->info->admin_members) {
			$this->template->error(lang("error_2"));
		}
		$id = intval($id);
		$ipblock = $this->admin_model->get_ip_block($id);
		if ($ipblock->num_rows() == 0) $this->template->error(lang("error_11"));

		$this->admin_model->delete_ipblock($id);
		$this->session->set_flashdata("globalmsg", lang("success_9"));
		redirect(site_url("admin/ipblock"));
	}

	public function members()
	{
		if(!$this->user->info->admin && !$this->user->info->admin_members) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink",
			array("admin" => array("members" => 1)));

		$user_roles = $this->admin_model->get_user_roles();

		$fields = $this->user_model->get_custom_fields(array("register"=>1));

		$this->template->loadContent("admin/members.php", array(
			"user_roles" => $user_roles,
			"fields" => $fields
			)
		);
	}

	public function members_page()
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("users.joined", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"users.username" => 0
				 ),
				 1 => array(
				 	"users.first_name" => 0
				 ),
				 2 => array(
				 	"users.last_name" => 0
				 ),
				 3 => array(
				 	"users.email" => 0
				 ),
				 4 => array(
				 	"user_roles.name" => 0
				 ),
				 5 => array(
				 	"users.joined" => 0
				 ),
				 6 => array(
				 	"users.oauth_provider" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->user_model
				->get_total_members_count()
		);
		$members = $this->user_model->get_members_admin($this->datatables);

		foreach($members->result() as $r) {
			if($r->oauth_provider == "google") {
				$provider = "Google";
			} elseif($r->oauth_provider == "twitter") {
				$provider = "Twitter";
			} elseif($r->oauth_provider == "facebook") {
				$provider = "Facebook";
			} else {
				$provider =  lang("ctn_196");
			}
			$this->datatables->data[] = array(
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp, "first_name" => $r->first_name, "last_name" => $r->last_name)),
				$r->first_name,
				$r->last_name,
				$r->email,
				$this->common->get_user_role($r),
				date($this->settings->info->date_format, $r->joined),
				$provider,
				'<a href="'.site_url("admin/edit_member/" . $r->ID).'" class="btn btn-warning btn-xs" title="'.lang("ctn_55").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("admin/delete_member/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_317").'\')" title="'.lang("ctn_57").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function member_user_groups($id)
	{
		if(!$this->common->has_permissions(array("admin", "admin_members"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink",
			array("admin" => array("members" => 1)));
		$id = intval($id);

		$member = $this->user_model->get_user_by_id($id);
		if ($member->num_rows() ==0 ) $this->template->error(lang("error_13"));

		$member = $member->row();

		// Groups
		$user_groups = $this->user_model->get_user_groups($id);
		$groups = $this->admin_model->get_user_groups();


		$this->template->loadContent("admin/member_user_groups.php", array(
			"member" => $member,
			"user_groups" => $user_groups,
			"groups" => $groups
			)
		);
	}

	public function add_member_to_group_pro($id)
	{
		if(!$this->common->has_permissions(array("admin", "admin_members"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$id = intval($id);

		$member = $this->user_model->get_user_by_id($id);
		if ($member->num_rows() ==0 ) $this->template->error(lang("error_13"));

		$member = $member->row();

		$groupid = intval($this->input->post("groupid"));

		$group = $this->admin_model->get_user_group($groupid);
		if ($group->num_rows() == 0) $this->template->error(lang("error_4"));

		$userc = $this->admin_model->get_user_from_group($id, $groupid);
		if ($userc->num_rows() > 0) {
			$this->template->error(lang("error_84"));
		}

		$this->admin_model->add_user_to_group($member->ID, $groupid);


		$this->session->set_flashdata("globalmsg", lang("success_5"));
		redirect(site_url("admin/member_user_groups/" . $id));

	}

	public function edit_member($id)
	{
		if(!$this->user->info->admin && !$this->user->info->admin_members) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink",
			array("admin" => array("members" => 1)));
		$id = intval($id);

		$member = $this->user_model->get_user_by_id($id);

		if ($member->num_rows() ==0 ) $this->template->error(lang("error_13"));
        $member=$member->row();
		$user_groups = $this->user_model->get_user_groups($id);
		$user_roles = $this->admin_model->get_user_roles();
		$fields = $this->user_model->get_custom_fields_answers(array(
			), $id);
        if($member->ideology){
            $ideology_icon = $this->common->show_ideology_icon($member->ideology);
        }
        else
            $ideology_icon = "Not selected";

        if($member->old_ideology)
            $ideology_icon = $this->common->show_ideology_icon($member->old_ideology)." <i class='glyphicon glyphicon-arrow-right'></i> ".$ideology_icon;

		$this->template->loadContent("admin/edit_member.php", array(
			"member" => $member,
			"user_groups" => $user_groups,
			"user_roles" => $user_roles,
			"fields" => $fields,
            "ideology_icon" => $ideology_icon
        ));
	}

	public function edit_member_pro($id)
	{
		if(!$this->user->info->admin && !$this->user->info->admin_members) {
			$this->template->error(lang("error_2"));
		}
		$id = intval($id);
		$fields = $this->user_model->get_custom_fields_answers(array(
			), $id);

		$member = $this->user_model->get_user_by_id($id);
		if ($member->num_rows() ==0 ) $this->template->error(lang("error_13"));

		$member = $member->row();

		$this->load->model("register_model");
		$email = $this->input->post("email", true);
		$first_name = $this->common->nohtml(
			$this->input->post("first_name", true));
		$last_name = $this->common->nohtml(
			$this->input->post("last_name", true));
		$pass = $this->common->nohtml(
			$this->input->post("password", true));
		$username = $this->common->nohtml(
			$this->input->post("username", true));
		$user_role = intval($this->input->post("user_role"));
		$aboutme = $this->common->nohtml($this->input->post("aboutme"));
		$points = abs($this->input->post("credits"));
		$active = intval($this->input->post("active"));
		$verified = intval($this->input->post("verified"));

		$address_1 = $this->common->nohtml($this->input->post("address_1"));
		$address_2 = $this->common->nohtml($this->input->post("address_2"));
		$city = $this->common->nohtml($this->input->post("city"));
		$state = $this->common->nohtml($this->input->post("state"));
		$zipcode = $this->common->nohtml($this->input->post("zipcode"));
		$country = $this->common->nohtml($this->input->post("country"));

		if (strlen($username) < 3) $this->template->error(lang("error_14"));

		if (!preg_match("/^[a-z0-9_]+$/i", $username)) {
			$this->template->error(lang("error_15"));
		}

		if ($username != $member->username) {
			if (!$this->register_model->check_username_is_free($username)) {
				 $this->template->error(lang("error_16"));
			}
		}

		if (!empty($pass)) {
			if (strlen($pass) <= 5) {
				 $this->template->error(lang("error_17"));
			}
			$pass = $this->common->encrypt($pass);
		} else {
			$pass = $member->password;
		}

		$this->load->helper('email');
		$this->load->library('upload');

		if (empty($email)) {
				$this->template->error(lang("error_18"));
		}

		if (!valid_email($email)) {
			$this->template->error(lang("error_19"));
		}

		if ($email != $member->email) {
			if (!$this->register_model->checkEmailIsFree($email)) {
				 $this->template->error(lang("error_20"));
			}
		}

		if($user_role != $member->user_role) {
			if(!$this->user->info->admin) {
				$this->template->error(lang("error_66"));
			}
		}
		if($user_role > 0) {
			$role = $this->admin_model->get_user_role($user_role);
			if($role->num_rows() == 0) $this->template->error(lang("error_65"));
		}

		if ($_FILES['userfile']['size'] > 0) {
				$this->upload->initialize(array(
			       "upload_path" => $this->settings->info->upload_path,
			       "overwrite" => FALSE,
			       "max_filename" => 300,
			       "encrypt_name" => TRUE,
			       "remove_spaces" => TRUE,
			       "allowed_types" => "gif|jpg|png|jpeg|",
			       "max_size" => 1000,
			       "max_width" => $this->settings->info->avatar_width,
			       "max_height" => $this->settings->info->avatar_height
			    ));

			    if (!$this->upload->do_upload()) {
			    	$this->template->error(lang("error_21")
			    	.$this->upload->display_errors());
			    }

			    $data = $this->upload->data();

			    $image = $data['file_name'];
			} else {
				$image= $member->avatar;
			}

		// Custom Fields
		// Process fields
		$answers = array();
		foreach($fields->result() as $r) {
			$answer = "";
			if($r->type == 0) {
				// Look for simple text entry
				$answer = $this->common->nohtml($this->input->post("cf_" . $r->ID));

				if($r->required && empty($answer)) {
					$this->template->error(lang("error_78") . $r->name);
				}
				// Add
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer
				);
			} elseif($r->type == 1) {
				// HTML
				$answer = $this->common->nohtml($this->input->post("cf_" . $r->ID));

				if($r->required && empty($answer)) {
					$this->template->error(lang("error_78") . $r->name);
				}
				// Add
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer
				);
			} elseif($r->type == 2) {
				// Checkbox
				$options = explode(",", $r->options);
				foreach($options as $k=>$v) {
					// Look for checked checkbox and add it to the answer if it's value is 1
					$ans = $this->common->nohtml($this->input->post("cf_cb_" . $r->ID . "_" . $k));
					if($ans) {
						if(empty($answer)) {
							$answer .= $v;
						} else {
							$answer .= ", " . $v;
						}
					}
				}

				if($r->required && empty($answer)) {
					$this->template->error(lang("error_78") . $r->name);
				}
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer
				);

			} elseif($r->type == 3) {
				// radio
				$options = explode(",", $r->options);
				if(isset($_POST['cf_radio_' . $r->ID])) {
					$answer = intval($this->common->nohtml($this->input->post("cf_radio_" . $r->ID)));

					$flag = false;
					foreach($options as $k=>$v) {
						if($k == $answer) {
							$flag = true;
							$answer = $v;
						}
					}
					if($r->required && !$flag) {
						$this->template->error(lang("error_78") . $r->name);
					}
					if($flag) {
						$answers[] = array(
							"fieldid" => $r->ID,
							"answer" => $answer
						);
					}
				}

			} elseif($r->type == 4) {
				// Dropdown menu
				$options = explode(",", $r->options);
				$answer = intval($this->common->nohtml($this->input->post("cf_" . $r->ID)));
				$flag = false;
				foreach($options as $k=>$v) {
					if($k == $answer) {
						$flag = true;
						$answer = $v;
					}
				}
				if($r->required && !$flag) {
					$this->template->error(lang("error_78") . $r->name);
				}
				if($flag) {
					$answers[] = array(
						"fieldid" => $r->ID,
						"answer" => $answer
					);
				}
			}
		}


		$this->user_model->update_user($id,
			array(
				"username" => $username,
				"email" => $email,
				"first_name" => $first_name,
				"last_name" => $last_name,
				"password" => $pass,
				"user_role" => $user_role,
				"avatar" => $image,
				"aboutme" => $aboutme,
				"points" => $points,
				"active" => $active,
				"address_1" => $address_1,
				"address_2" => $address_2,
				"city" => $city,
				"state" => $state,
				"zipcode" => $zipcode,
				"country" => $country,
				"verified" => $verified
				)
		);

		// Update CF
		// Add Custom Fields data
		foreach($answers as $answer) {
			// Check if field exists
			$field = $this->user_model->get_user_cf($answer['fieldid'], $id);
			if($field->num_rows() == 0) {
				$this->user_model->add_custom_field(array(
					"userid" => $id,
					"fieldid" => $answer['fieldid'],
					"value" => $answer['answer']
					)
				);
			} else {
				$this->user_model->update_custom_field($answer['fieldid'],
					$id, $answer['answer']);
			}
		}


		$this->session->set_flashdata("globalmsg", lang("success_10"));
		redirect(site_url("admin/members"));
	}

	public function add_member_pro()
	{
		if(!$this->user->info->admin && !$this->user->info->admin_members) {
			$this->template->error(lang("error_2"));
		}
		$this->load->model("register_model");
		$email = $this->input->post("email", true);
		$first_name = $this->common->nohtml(
			$this->input->post("first_name", true));
		$last_name = $this->common->nohtml(
			$this->input->post("last_name", true));
		$pass = $this->common->nohtml(
			$this->input->post("password", true));
		$pass2 = $this->common->nohtml(
			$this->input->post("password2", true));
		$captcha = $this->input->post("captcha", true);
		$username = $this->common->nohtml(
			$this->input->post("username", true));
		$user_role = intval($this->input->post("user_role"));

		if($user_role > 0) {
			$role = $this->admin_model->get_user_role($user_role);
			if($role->num_rows() == 0) $this->template->error(lang("error_65"));
			$role = $role->row();
			if($role->admin || $role->admin_members || $role->admin_settings
				|| $role->admin_payment) {
				if(!$this->user->info->admin) {
					$this->template->error(lang("error_67"));
				}
			}
		}


		if (strlen($username) < 3) $this->template->error(lang("error_14"));

		if (!preg_match("/^[a-z0-9_]+$/i", $username)) {
			$this->template->error(lang("error_15"));
		}

		if (!$this->register_model->check_username_is_free($username)) {
			 $this->template->error(lang("error_16"));
		}

		if ($pass != $pass2) $this->template->error(lang("error_22"));

		if (strlen($pass) <= 5) {
			 $this->template->error(lang("error_17"));
		}

		$this->load->helper('email');

		if (empty($email)) {
				$this->template->error(lang("error_18"));
		}

		if (!valid_email($email)) {
			$this->template->error(lang("error_19"));
		}

		if (!$this->register_model->checkEmailIsFree($email)) {
			 $this->template->error(lang("error_20"));
		}

		$fields = $this->user_model->get_custom_fields_answers(array(
			), 0);
		// Custom Fields
		// Process fields
		$answers = array();
		foreach($fields->result() as $r) {
			$answer = "";
			if($r->type == 0) {
				// Look for simple text entry
				$answer = $this->common->nohtml($this->input->post("cf_" . $r->ID));

				if($r->required && empty($answer)) {
					$fail = lang("error_158") . $r->name;
				}
				// Add
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer
				);
			} elseif($r->type == 1) {
				// HTML
				$answer = $this->common->nohtml($this->input->post("cf_" . $r->ID));

				if($r->required && empty($answer)) {
					$fail = lang("error_158") . $r->name;
				}
				// Add
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer
				);
			} elseif($r->type == 2) {
				// Checkbox
				$options = explode(",", $r->options);
				foreach($options as $k=>$v) {
					// Look for checked checkbox and add it to the answer if it's value is 1
					$ans = $this->common->nohtml($this->input->post("cf_cb_" . $r->ID . "_" . $k));
					if($ans) {
						if(empty($answer)) {
							$answer .= $v;
						} else {
							$answer .= ", " . $v;
						}
					}
				}

				if($r->required && empty($answer)) {
					$fail = lang("error_158") . $r->name;
				}
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer
				);

			} elseif($r->type == 3) {
				// radio
				$options = explode(",", $r->options);
				if(isset($_POST['cf_radio_' . $r->ID])) {
					$answer = intval($this->common->nohtml($this->input->post("cf_radio_" . $r->ID)));

					$flag = false;
					foreach($options as $k=>$v) {
						if($k == $answer) {
							$flag = true;
							$answer = $v;
						}
					}
					if($r->required && !$flag) {
						$fail = lang("error_158") . $r->name;
					}
					if($flag) {
						$answers[] = array(
							"fieldid" => $r->ID,
							"answer" => $answer
						);
					}
				}

			} elseif($r->type == 4) {
				// Dropdown menu
				$options = explode(",", $r->options);
				$answer = intval($this->common->nohtml($this->input->post("cf_" . $r->ID)));
				$flag = false;
				foreach($options as $k=>$v) {
					if($k == $answer) {
						$flag = true;
						$answer = $v;
					}
				}
				if($r->required && !$flag) {
					$fail = lang("error_158") . $r->name;
				}
				if($flag) {
					$answers[] = array(
						"fieldid" => $r->ID,
						"answer" => $answer
					);
				}
			}
		}

		if(!empty($fail)) {
			$this->template->error($fail);
		}

		$pass = $this->common->encrypt($pass);
		$this->register_model->add_user(array(
			"username" => $username,
			"email" => $email,
			"first_name" => $first_name,
			"last_name" => $last_name,
			"password" => $pass,
			"user_role" => $user_role,
			"IP" => $_SERVER['REMOTE_ADDR'],
			"joined" => time(),
			"joined_date" => date("n-Y"),
			"active" => 1
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_11"));
		redirect(site_url("admin/members"));

	}

	public function delete_member($id, $hash)
	{
		if(!$this->user->info->admin && !$this->user->info->admin_members) {
			$this->template->error(lang("error_2"));
		}
		if ($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$member = $this->user_model->get_user_by_id($id);
		if ($member->num_rows() ==0 ) $this->template->error(lang("error_13"));

		$this->user_model->delete_user($id);
		// Delete user from user group
		$this->admin_model->delete_user_from_all_groups($id);

		$this->session->set_flashdata("globalmsg", lang("success_12"));
		redirect(site_url("admin/members"));
	}

	public function social_settings()
	{
		if(!$this->user->info->admin && !$this->user->info->admin_settings) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink",
			array("admin" => array("social_settings" => 1)));
		$this->template->loadContent("admin/social_settings.php", array(
			)
		);
	}

	public function social_settings_pro()
	{
		if(!$this->user->info->admin && !$this->user->info->admin_settings) {
			$this->template->error(lang("error_2"));
		}
		$disable_social_login =
			intval($this->input->post("disable_social_login"));
		$twitter_consumer_key =
			$this->common->nohtml($this->input->post("twitter_consumer_key"));
		$twitter_consumer_secret =
			$this->common->nohtml($this->input->post("twitter_consumer_secret"));
		$facebook_app_id =
			$this->common->nohtml($this->input->post("facebook_app_id"));
		$facebook_app_secret =
			$this->common->nohtml($this->input->post("facebook_app_secret"));
		$google_client_id =
			$this->common->nohtml($this->input->post("google_client_id"));
		$google_client_secret =
			$this->common->nohtml($this->input->post("google_client_secret"));

		$this->admin_model->updateSettings(
			array(
				"disable_social_login" => $disable_social_login,
				"twitter_consumer_key" => $twitter_consumer_key,
				"twitter_consumer_secret" => $twitter_consumer_secret,
				"facebook_app_id" => $facebook_app_id,
				"facebook_app_secret"=> $facebook_app_secret,
				"google_client_id" => $google_client_id,
				"google_client_secret" => $google_client_secret,
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_13"));
		redirect(site_url("admin/social_settings"));
	}

	public function settings()
	{
		if(!$this->user->info->admin && !$this->user->info->admin_settings) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink",
			array("admin" => array("settings" => 1)));
		$roles = $this->admin_model->get_user_roles();
		$layouts = $this->admin_model->get_layouts();
		$this->template->loadContent("admin/settings.php", array(
			"roles" => $roles,
			"layouts" => $layouts
			)
		);
	}

	public function settings_pro()
	{
		if(!$this->user->info->admin && !$this->user->info->admin_settings) {
			$this->template->error(lang("error_2"));
		}
		$site_name = $this->common->nohtml($this->input->post("site_name"));
		$site_desc = $this->common->nohtml($this->input->post("site_desc"));
		$site_email = $this->common->nohtml($this->input->post("site_email"));
		$upload_path = $this->common->nohtml($this->input->post("upload_path"));
		$file_types = $this->common
			->nohtml($this->input->post("file_types"));
		$file_size = intval($this->input->post("file_size"));
		$upload_path_rel =
			$this->common->nohtml($this->input->post("upload_path_relative"));
		$register = intval($this->input->post("register"));
		$avatar_upload = intval($this->input->post("avatar_upload"));
		$disable_captcha = intval($this->input->post("disable_captcha"));
		$date_format = $this->common->nohtml($this->input->post("date_format"));
		$login_protect = intval($this->input->post("login_protect"));
		$activate_account = intval($this->input->post("activate_account"));
		$default_user_role = intval($this->input->post("default_user_role"));
		$secure_login = intval($this->input->post("secure_login"));
		$page_slugs = intval($this->input->post("page_slugs"));
		$disable_chat = intval($this->input->post("disable_chat"));

		$google_recaptcha = intval($this->input->post("google_recaptcha"));
		$google_recaptcha_secret = $this->common->nohtml($this->input->post("google_recaptcha_secret"));
		$google_recaptcha_key = $this->common->nohtml($this->input->post("google_recaptcha_key"));

		$logo_option = intval($this->input->post("logo_option"));

		$avatar_width = intval($this->input->post("avatar_width"));
		$avatar_height = intval($this->input->post("avatar_height"));
		$cache_time = intval($this->input->post("cache_time"));

		$user_display_type = intval($this->input->post("user_display_type"));
		$calendar_picker_format = $this->common->nohtml($this->input->post("calendar_picker_format"));
		$resize_avatar = intval($this->input->post("resize_avatar"));

		$public_profiles = intval($this->input->post("public_profiles"));
		$public_pages = intval($this->input->post("public_pages"));
		$public_blogs = intval($this->input->post("public_blogs"));
		$enable_blogs = intval($this->input->post("enable_blogs"));
		$enable_dislikes = intval($this->input->post("enable_dislikes"));
		$enable_google_maps = intval($this->input->post("enable_google_maps"));
		$google_maps_api_key = $this->common->nohtml($this->input->post("google_maps_api_key"));



		// Validate
		if (empty($site_name) || empty($site_email)) {
			$this->template->error(lang("error_23"));
		}
		$this->load->library("upload");

		if ($_FILES['userfile']['size'] > 0) {
			$this->upload->initialize(array(
		       "upload_path" => $this->settings->info->upload_path,
		       "overwrite" => FALSE,
		       "max_filename" => 300,
		       "encrypt_name" => TRUE,
		       "remove_spaces" => TRUE,
		       "allowed_types" => $this->settings->info->file_types,
		       "max_size" => 2000,
		       "xss_clean" => TRUE
		    ));

		    if (!$this->upload->do_upload()) {
		    	$this->template->error(lang("error_21")
		    	.$this->upload->display_errors());
		    }

		    $data = $this->upload->data();

		    $image = $data['file_name'];
		} else {
			$image= $this->settings->info->site_logo;
		}

		$this->admin_model->updateSettings(
			array(
				"site_name" => $site_name,
				"site_desc" => $site_desc,
				"upload_path" => $upload_path,
				"upload_path_relative" => $upload_path_rel,
				"site_logo"=> $image,
				"site_email" => $site_email,
				"register" => $register,
				"avatar_upload" => $avatar_upload,
				"file_types" => $file_types,
				"disable_captcha" => $disable_captcha,
				"date_format" => $date_format,
				"file_size" => $file_size,
				"login_protect" => $login_protect,
				"activate_account" => $activate_account,
				"default_user_role" => $default_user_role,
				"secure_login" => $secure_login,
				"google_recaptcha" => $google_recaptcha,
				"google_recaptcha_secret" => $google_recaptcha_secret,
				"google_recaptcha_key" => $google_recaptcha_key,
				"logo_option" => $logo_option,
				"avatar_height" => $avatar_height,
				"avatar_width" => $avatar_width,
				"cache_time" => $cache_time,
				"user_display_type" => $user_display_type,
				"page_slugs" => $page_slugs,
				"disable_chat" => $disable_chat,
				"calendar_picker_format" => $calendar_picker_format,
				"resize_avatar" => $resize_avatar,
				"public_profiles" => $public_profiles,
				"public_pages" => $public_pages,
				"public_blogs" => $public_blogs,
				"enable_blogs" => $enable_blogs,
				"enable_dislikes" => $enable_dislikes,
				"enable_google_maps" => $enable_google_maps,
				"google_maps_api_key" => $google_maps_api_key
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_13"));
		redirect(site_url("admin/settings"));
	}

}

?>
