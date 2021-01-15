<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("image_model");
		$this->load->model("feed_model");
		$this->load->model("page_model");
		$this->load->model("calendar_model");
		$this->load->model("home_model");

		
		// If the user does not have premium. 
		// -1 means they have unlimited premium
		if($this->settings->info->global_premium && 
			($this->user->info->premium_time != -1 && 
				$this->user->info->premium_time < time()) ) {
			$this->session->set_flashdata("globalmsg", lang("success_29"));
			redirect(site_url("funds/plans"));
		}

		$this->template->set_layout("client/themes/titan.php");
	}

	public function index() 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$this->template->loadContent("pages/index.php", array(
			
			)
		);
	}

	public function your() 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$this->template->loadContent("pages/your.php", array(
			
			)
		);
	}

	public function all() 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
			$this->template->errori(lang("error_112"));
		}
		$this->template->loadContent("pages/all.php", array(
			
			)
		);
	}

	public function your_page($type=0) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$type = intval($type);
		$this->load->library("datatables");

		$this->datatables->set_default_order("pages.ID", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"pages.name" => 0
				 ),
				 1 => array(
				 	"pages.pageviews" => 0
				 ),
				 2 => array(
				 	"pages.members" => 0
				 ),
				 3 => array(
				 	"page_categories.name" => 0
				 ),

			)
		);

		if($type == 0) {
			$this->datatables->set_total_rows(
				$this->page_model
					->get_total_user_pages($this->user->info->ID)
			);
			$pages = $this->page_model->get_user_pages($this->user->info->ID, $this->datatables);
		} elseif($type == 1) {
			if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
				$this->template->errori(lang("error_112"));
			}
			$this->datatables->set_total_rows(
				$this->page_model
					->get_total_pages()
			);
			$pages = $this->page_model->get_all_pages($this->datatables);
		} elseif($type == 2) {
			
			$this->datatables->set_total_rows(
				$this->page_model
					->get_total_pages_public()
			);
			$pages = $this->page_model->get_all_pages_public($this->datatables);
		}



		foreach($pages->result() as $r) {

			if(empty($r->slug)) {
				$slug = $r->ID;
			} else {
				$slug = $r->slug;
			}

			$options = '';
			if( (isset($r->roleid) && $r->roleid == 1) || $this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
				$options = '<a href="' . site_url("pages/edit_page/" . $r->ID) .'" class="btn btn-warning btn-xs" title="'. lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a> <a href="' . site_url("pages/delete_page/" . $r->ID . "/" . $this->security->get_csrf_hash()) .'" onclick="return confirm(\'' . lang("ctn_86") . '\')" class="btn btn-danger btn-xs" title="'. lang("ctn_57") .'"><span class="glyphicon glyphicon-trash"></span></a>';
			}
			 
			$this->datatables->data[] = array(
				'<a href="'.site_url("pages/view/" . $slug).'">' . $r->name . '</a>',
				$r->pageviews,
				$r->members,
				$r->category_name,
				$options
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function add() 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		if(!$this->common->has_permissions(array("admin", "page_creator", "page_admin"), $this->user)) {
			$this->template->error(lang("error_113"));
		}
		$categories = $this->page_model->get_all_categories();

		$this->template->loadContent("pages/add.php", array(
			"categories" => $categories
			)
		);
	}

	public function add_pro() 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		if(!$this->common->has_permissions(array("admin", "page_creator", "page_admin"), $this->user)) {
			$this->template->error(lang("error_113"));
		}
		$name = $this->common->nohtml($this->input->post("name"));
		$slug = $this->common->nohtml($this->input->post("slug"));
		$type = intval($this->input->post("type"));
		$categoryid = intval($this->input->post("categoryid"));
		$description = $this->common->nohtml($this->input->post("description"));

		$posting_status = intval($this->input->post("posting_status"));
		$nonmembers_view = intval($this->input->post("nonmembers_view"));


		$location = $this->common->nohtml($this->input->post("location"));
		$email = $this->common->nohtml($this->input->post("email"));
		$phone = $this->common->nohtml($this->input->post("phone"));
		$website = $this->common->nohtml($this->input->post("website"));
		$pay_to_join = intval($this->input->post("pay_to_join"));
		$pay_to_user = $this->common->nohtml($this->input->post("pay_to_user"));

		$pay_to_userid = 0;
		if(!empty($pay_to_user)) {
			$user = $this->user_model->get_user_by_username($pay_to_user);
			if($user->num_rows() == 0) {
				$this->template->error(lang("error_189") . $pay_to_user);
			}
			$user = $user->row();
			$pay_to_userid = $user->ID;
		}

		if(empty($name)) {
			$this->template->error(lang("error_82"));
		}

		$category = $this->page_model->get_page_category($categoryid);
		if($category->num_rows() == 0) {
			$this->template->error(lang("error_114"));
		}

		if(!$this->settings->info->page_slugs) {
			// Slug no spaces
			if (!preg_match("/^[a-z0-9_-]+$/i", $slug)) {
				$this->template->error(lang("error_115"));
			}

			// check unique slug
			$page = $this->page_model->get_page_by_slug($slug);
			if($page->num_rows() > 0) {
				$this->template->error(lang("error_116"));
			}
		}

		$this->load->library("upload");


		if (isset($_FILES['userfile']) && $_FILES['userfile']['size'] > 0) {
				$this->upload->initialize(array( 
			       "upload_path" => $this->settings->info->upload_path,
			       "overwrite" => FALSE,
			       "max_filename" => 300,
			       "encrypt_name" => TRUE,
			       "remove_spaces" => TRUE,
			       "allowed_types" => "gif|png|jpg|jpeg",
			       "max_size" => $this->settings->info->file_size,
			       "max_width" => $this->settings->info->avatar_width,
			       "max_height" => $this->settings->info->avatar_height
			    ));

			    if (!$this->upload->do_upload("userfile")) {
			    	$this->template->error(lang("error_21")
			    	.$this->upload->display_errors());
			    }

			    $data = $this->upload->data();

			    $profile_avatar = $data['file_name'];
			} else {
				$profile_avatar= "default.png";
			}

			if (isset($_FILES['userfile_profile']) && $_FILES['userfile_profile']['size'] > 0) {
				$this->upload->initialize(array( 
			       "upload_path" => $this->settings->info->upload_path,
			       "overwrite" => FALSE,
			       "max_filename" => 300,
			       "encrypt_name" => TRUE,
			       "remove_spaces" => TRUE,
			       "allowed_types" => "gif|png|jpg|jpeg",
			       "max_size" => $this->settings->info->file_size
			    ));

			    if (!$this->upload->do_upload("userfile_profile")) {
			    	$this->template->error(lang("error_21")
			    	.$this->upload->display_errors());
			    }

			    $data = $this->upload->data();

			    $profile_header = $data['file_name'];
			} else {
				$profile_header = "default_header.png";
			}

		$pageid = $this->page_model->add_page(array(
			"name" => $name,
			"slug" => $slug,
			"type" => $type,
			"categoryid" => $categoryid,
			"timestamp" => time(),
			"profile_header" => $profile_header,
			"profile_avatar" => $profile_avatar,
			"description" => $description,
			"posting_status" => $posting_status,
			"location" => $location,
			"email" => $email,
			"phone" => $phone,
			"website" => $website,
			"nonmembers_view" => $nonmembers_view,
			"pay_to_join" => $pay_to_join,
			"pay_to_userid" => $pay_to_userid
			)
		);

		$this->page_model->add_page_user(array(
			"pageid" => $pageid,
			"userid" => $this->user->info->ID,
			"roleid" => 1
			)
		);

		$this->update_user_pages($pageid, $this->user->info->ID, true);

		$this->session->set_flashdata("globalmsg", lang("success_58"));
		redirect(site_url("pages/view/" . $pageid));
	}

	private function update_user_pages($pageid, $userid, $add) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$pages = unserialize($this->user->info->pages);

		if($add) {
			$pages[] = $pageid;

			$this->user_model->update_user($userid, array(
				"pages" => serialize($pages)
				)
			);

			$this->page_model->increment_page_members($pageid);
		} else {
			$newpages = array();
			foreach($pages as $id) {
				if($id != $pageid) {
					$newpages[] = $id;
				}
			}

			$this->user_model->update_user($userid, array(
				"pages" => serialize($newpages)
				)
			);

			$this->page_model->decrement_page_members($pageid);
		}
	}

	public function check_slug() 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$slug = $this->common->nohtml($this->input->get("slug"));

		// Slug no spaces
		if (!preg_match("/^[a-z0-9_-]+$/i", $slug)) {
			$this->template->jsonError(lang("error_115"));
		}

		// check unique slug
		$page = $this->page_model->get_page_by_slug($slug);
		if($page->num_rows() > 0) {
			$array = array("status" => 0, "status_msg" => "<span style='color: red;'>".lang("ctn_243")."</span>");
		} else {
			$array = array("status" => 0, "status_msg" => "<span style='color: green;'>".lang("ctn_244")."</span>");
		}

		echo json_encode($array);
		exit();
	}

	public function edit_page($id) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$id = intval($id);
		$page = $this->page_model->get_page($id);
		if($page->num_rows() == 0) {
			$this->template->error(lang("error_94"));
		}
		$page = $page->row();

		// Check user is a member of page
		$member = $this->page_model->get_page_user($id, $this->user->info->ID);
		if($member->num_rows() == 0) {
			// Check role
			if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
				$this->template->error(lang("error_117"));
			}
		} else {
			$member = $member->row();
			// Check role
			if($member->roleid != 1) {
				if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
					$this->template->error(lang("error_117"));
				}
			}
		}


		$categories = $this->page_model->get_all_categories();

		$username_pay = "";
		if($page->pay_to_userid > 0) {
			$user = $this->user_model->get_user_by_id($page->pay_to_userid);
			if($user->num_rows() > 0) {
				$user = $user->row();
				$username_pay = $user->username;
			}
		}

		$this->template->loadContent("pages/edit.php", array(
			"categories" => $categories,
			"page" => $page,
			"username_pay" => $username_pay
			)
		);
	}

	public function edit_page_pro($id) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$id = intval($id);
		$page = $this->page_model->get_page($id);
		if($page->num_rows() == 0) {
			$this->template->error(lang("error_94"));
		}
		$page = $page->row();

		// Check user is a member of page
		$member = $this->page_model->get_page_user($id, $this->user->info->ID);
		if($member->num_rows() == 0) {
			// Check role
			if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
				$this->template->error(lang("error_117"));
			}
		} else {
			$member = $member->row();
			// Check role
			if($member->roleid != 1) {
				if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
					$this->template->error(lang("error_117"));
				}
			}
		}

		$name = $this->common->nohtml($this->input->post("name"));
		$slug = $this->common->nohtml($this->input->post("slug"));
		$type = intval($this->input->post("type"));
		$categoryid = intval($this->input->post("categoryid"));
		$description = $this->common->nohtml($this->input->post("description"));
		$posting_status = intval($this->input->post("posting_status"));
		$nonmembers_view = intval($this->input->post("nonmembers_view"));

		$location = $this->common->nohtml($this->input->post("location"));
		$email = $this->common->nohtml($this->input->post("email"));
		$phone = $this->common->nohtml($this->input->post("phone"));
		$website = $this->common->nohtml($this->input->post("website"));

		$pay_to_join = intval($this->input->post("pay_to_join"));
		$pay_to_user = $this->common->nohtml($this->input->post("pay_to_user"));

		if(empty($name)) {
			$this->template->error(lang("error_82"));
		}


		$pay_to_userid = 0;
		if(!empty($pay_to_user)) {
			$user = $this->user_model->get_user_by_username($pay_to_user);
			if($user->num_rows() == 0) {
				$this->template->error(lang("error_189") . $pay_to_user);
			}
			$user = $user->row();
			$pay_to_userid = $user->ID;
		}

		$category = $this->page_model->get_page_category($categoryid);
		if($category->num_rows() == 0) {
			$this->template->error(lang("error_114"));
		}

		if(!$this->settings->info->page_slugs) {
			// Slug no spaces
			if (!preg_match("/^[a-z0-9_-]+$/i", $slug)) {
				$this->template->error(lang("error_115"));
			}

			if($slug != $page->slug) {
				// check unique slug
				$page = $this->page_model->get_page_by_slug($slug);
				if($page->num_rows() > 0) {
					$this->template->error(lang("error_116"));
				}
			}
		}

		$this->load->library("upload");


		if ($_FILES['userfile']['size'] > 0) {
				$this->upload->initialize(array( 
			       "upload_path" => $this->settings->info->upload_path,
			       "overwrite" => FALSE,
			       "max_filename" => 300,
			       "encrypt_name" => TRUE,
			       "remove_spaces" => TRUE,
			       "allowed_types" => "gif|png|jpg|jpeg",
			       "max_size" => $this->settings->info->file_size,
			       "max_width" => $this->settings->info->avatar_width,
			       "max_height" => $this->settings->info->avatar_height
			    ));

			    if (!$this->upload->do_upload("userfile")) {
			    	$this->template->error(lang("error_21")
			    	.$this->upload->display_errors());
			    }

			    $data = $this->upload->data();

			    $profile_avatar = $data['file_name'];
			} else {
				$profile_avatar= $page->profile_avatar;
			}

			if ($_FILES['userfile_profile']['size'] > 0) {
				$this->upload->initialize(array( 
			       "upload_path" => $this->settings->info->upload_path,
			       "overwrite" => FALSE,
			       "max_filename" => 300,
			       "encrypt_name" => TRUE,
			       "remove_spaces" => TRUE,
			       "allowed_types" => "gif|png|jpg|jpeg",
			       "max_size" => $this->settings->info->file_size
			    ));

			    if (!$this->upload->do_upload("userfile_profile")) {
			    	$this->template->error(lang("error_21")
			    	.$this->upload->display_errors());
			    }

			    $data = $this->upload->data();

			    $profile_header = $data['file_name'];
			} else {
				$profile_header = $page->profile_header;
			}

		$this->page_model->update_page($id, array(
			"name" => $name,
			"slug" => $slug,
			"type" => $type,
			"categoryid" => $categoryid,
			"profile_header" => $profile_header,
			"profile_avatar" => $profile_avatar,
			"description" => $description,
			"posting_status" => $posting_status,
			"location" => $location,
			"email" => $email,
			"phone" => $phone,
			"website" => $website,
			"nonmembers_view" => $nonmembers_view,
			"pay_to_join" => $pay_to_join,
			"pay_to_userid" => $pay_to_userid
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_59"));
		redirect(site_url("pages/view/" . $id));
	}

	public function delete_page($id, $hash) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$page = $this->page_model->get_page($id);
		if($page->num_rows() == 0) {
			$this->template->error(lang("error_94"));
		}
		$page = $page->row();

		// Check user is a member of page
		$member = $this->page_model->get_page_user($id, $this->user->info->ID);
		if($member->num_rows() == 0) {
			// Check role
			if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
				$this->template->error(lang("error_117"));
			}
		} else {
			$member = $member->row();
			// Check role
			if($member->roleid != 1) {
				if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
					$this->template->error(lang("error_117"));
				}
			}
		}

		$this->page_model->delete_page($id);
		$this->session->set_flashdata("globalmsg", lang("success_60"));
		redirect(site_url("pages/your"));
	}

	public function view($id) 
	{
		if(!$this->settings->info->public_pages) {
			if(!$this->user->loggedin) {
				redirect(site_url("login"));
			}
		}
		if(is_numeric($id)) {
			$id = intval($id);
			$page = $this->page_model->get_page($id);
		} else {
			$id = $this->common->nohtml($id);
			$page = $this->page_model->get_page_by_slug($id);
		}

		// Get page
		if($page->num_rows() == 0) {
			$this->template->error(lang("error_94"));
		}

		$page = $page->row();

		if($this->user->loggedin) {
			// Get page member
			$member = $this->page_model->get_page_user($page->ID, $this->user->info->ID);
			if($member->num_rows() == 0) {
				$member = null;
			} else {
				$member = $member->row();
			}
		} else {
			$member = null;
		}

		if($page->type == 1) {
			// Check user is a member
			if($member == null) {
				// Check user is a member of page

				if(!$this->user->loggedin) {
					$this->template->error(lang("error_102"));
				}
	
				// Check for page invite
				$invite = $this->page_model->get_page_invite($page->ID, $this->user->info->ID);
				if($invite->num_rows() ==0) {
					if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
						$this->template->error(lang("error_102"));
					}
				}
			}
		}

		// Pageview increase
		$this->page_model->update_page($page->ID, array(
			"pageviews" => $page->pageviews +1
			)
		);

		// Get member list (preview)
		$users = $this->page_model->get_page_users_preview($page->ID);

		// Get albums
		$albums = $this->image_model->get_page_albums_sample($page->ID);

		// Get upcoming events
		$startdt = new DateTime('now'); // setup a local datetime
		$startdt->setTimestamp(time()); // Set the date based on timestamp
		$format = $startdt->format('Y-m-d H:i:s');
		$events = $this->calendar_model->get_events_sample($page->ID, $format);

		$this->template->loadContent("pages/view.php", array(
			"page" => $page,
			"slug" => $id,
			"member" => $member,
			"users" => $users,
			"albums" => $albums,
			"events" => $events
			)
		);
	}

	public function events($id) 
	{
		if(!$this->settings->info->public_pages) {
			if(!$this->user->loggedin) {
				redirect(site_url("login"));
			}
		}
		if(is_numeric($id)) {
			$id = intval($id);
			$page = $this->page_model->get_page($id);
		} else {
			$id = $this->common->nohtml($id);
			$page = $this->page_model->get_page_by_slug($id);
		}

		// Get page
		if($page->num_rows() == 0) {
			$this->template->error(lang("error_94"));
		}

		$page = $page->row();

		if($this->user->loggedin) {
			// Get page member
			$member = $this->page_model->get_page_user($page->ID, $this->user->info->ID);
			if($member->num_rows() == 0) {
				$member = null;
			} else {
				$member = $member->row();
			}
		} else {
			$member = null;
		}

		if($page->nonmembers_view && $member == null) {
			$this->template->error(lang("error_118"));
		}

		if($page->type == 1) {
			// Check user is a member
			if($member == null) {
				if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
					$this->template->error(lang("error_102"));
				}
			}
		}

		$this->template->loadExternal(
			'<link rel="stylesheet" href="'.base_url().'scripts/libraries/datetimepicker/jquery.datetimepicker.css" />
			<script src="'.base_url().'scripts/libraries/datetimepicker/jquery.datetimepicker.full.min.js"></script>
			<link rel="stylesheet" href="'.base_url().'scripts/libraries/fullcalendar/fullcalendar.min.css" />
			<script src="'.base_url().'scripts/libraries/fullcalendar/lib/moment.min.js"></script>
			<script src="'.base_url().'scripts/libraries/fullcalendar/fullcalendar.min.js"></script>
			<script src="'.base_url().'scripts/libraries/fullcalendar/gcal.js"></script>
			<link rel="stylesheet" href="'.base_url().'styles/calendar.css" />'
		);

		$this->template->loadContent("pages/events.php", array(
			"page" => $page,
			"slug" => $id,
			"member" => $member,
			)
		);
	}

	public function get_events() 
	{
		if(!$this->settings->info->public_pages) {
			if(!$this->user->loggedin) {
				redirect(site_url("login"));
			}
		}
		$start = $this->common->nohtml($this->input->get("start"));
		$end = $this->common->nohtml($this->input->get("end"));
		$pageid = intval($this->input->get("pageid"));

		$startdt = new DateTime('now'); // setup a local datetime
		$startdt->setTimestamp($start); // Set the date based on timestamp
		$format = $startdt->format('Y-m-d H:i:s');

		$enddt = new DateTime('now'); // setup a local datetime
		$enddt->setTimestamp($end); // Set the date based on timestamp
		$format2 = $enddt->format('Y-m-d H:i:s');

		// Check page
		$page = $this->page_model->get_page($pageid);
		// Get page
		if($page->num_rows() == 0) {
			$this->template->jsonError(lang("error_94"));
		}

		$page = $page->row();

		if($this->user->loggedin) {
			// Get page member
			$member = $this->page_model->get_page_user($page->ID, $this->user->info->ID);
			if($member->num_rows() == 0) {
				$member = null;
			} else {
				$member = $member->row();
			}
		} else {
			$member = null;
		}

		if($page->nonmembers_view && $member == null) {
			$this->template->error(lang("error_118"));
		}

		if($page->type == 1) {
			// Check user is a member
			if($member == null) {
				if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
					$this->template->error(lang("error_102"));
				}
			}
		}

		$color = "#2faaf1";
		$project_name = "test";
		
		$events = $this->calendar_model->get_events($format, 
			$format2, $pageid);
		$data_events = array();
		foreach($events->result() as $r) { 
			$data_events[] = array(
				"id" => $r->ID,
				"title" => $r->title,
				"description" => $r->description,
				"end" => $r->end,
				"start" => $r->start,
				"color" => $color,
				"pageid" => $pageid,
				"project_name" => $project_name,
				"location" => $r->location,
				"url" => site_url("pages/view_event/" . $r->ID)
			);
		}

		echo json_encode(array("events" => $data_events));
		exit();
	}

	public function add_event($pageid) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$pageid = intval($pageid);
		// Check page
		$page = $this->page_model->get_page($pageid);
		// Get page
		if($page->num_rows() == 0) {
			$this->template->jsonError(lang("error_94"));
		}

		$page = $page->row();

		// Get page member
		// Check user is a member of page
		$member = $this->page_model->get_page_user($page->ID, $this->user->info->ID);
		if($member->num_rows() == 0) {
			// Check role
			if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
				$this->template->error(lang("error_117"));
			}
		} else {
			$member = $member->row();
			// Check role
			if($member->roleid != 1) {
				if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
					$this->template->error(lang("error_117"));
				}
			}
		}


		/* Our calendar data */
		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->common->nohtml($this->input->post("description"));
		$start_date = $this->common->nohtml($this->input->post("start_date"));
		$end_date = $this->common->nohtml($this->input->post("end_date"));	
		$location = $this->common->nohtml($this->input->post("location"));	
		$feed_post = intval($this->input->post("feed_post"));


		if(empty($name)) {
			$this->template->error(lang("error_119"));
		}

		if(!empty($start_date)) {
			$sd = DateTime::createFromFormat($this->settings->info->calendar_picker_format, $start_date);
			$start_date = $sd->format('Y-m-d H:i:s');
			$start_date_timestamp = $sd->getTimestamp();
		} else {
			$start_date = date("Y-m-d H:i:s", time());
			$start_date_timestamp = time();
		}

		if(!empty($end_date)) {
			$ed = DateTime::createFromFormat($this->settings->info->calendar_picker_format, $end_date);
			$end_date = $ed->format('Y-m-d H:i:s');
			$end_date_timestamp = $ed->getTimestamp();
		} else {
			$end_date = date("Y-m-d H:i:s", time());
			$end_date_timestamp = time();
		}

		$eventid = $this->calendar_model->add_event(array(
			"title" => $name,
			"description" => $desc,
			"start" => $start_date,
			"end" => $end_date,
			"userid" => $this->user->info->ID,
			"pageid" => $pageid,
			"location" => $location
			)
		);

		if($feed_post) {
			$postid = $this->feed_model->add_post(array(
				"userid" => $this->user->info->ID,
				"hide_profile" => 1,
				"post_as" => "page",
				"pageid" => $pageid,
				"content" => "",
				"timestamp" => time(),
				"eventid" => $eventid,
				"template" => "event"
				)
			);
		}

		$this->session->set_flashdata("globalmsg", lang("success_61"));
		redirect(site_url("pages/events/" . $pageid));
	}

	public function edit_event_pro() 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$eventid = intval($this->input->post("eventid"));
		$event = $this->calendar_model->get_event($eventid);
		if($event->num_rows() == 0) {
			$this->template->error(lang("error_76"));
		}

		$event = $event->row();

		// Check page
		$page = $this->page_model->get_page($event->pageid);
		// Get page
		if($page->num_rows() == 0) {
			$this->template->jsonError(lang("error_94"));
		}

		$page = $page->row();

		// Check user is a member of page
		$member = $this->page_model->get_page_user($page->ID, $this->user->info->ID);
		if($member->num_rows() == 0) {
			// Check role
			if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
				$this->template->error(lang("error_117"));
			}
		} else {
			$member = $member->row();
			// Check role
			if($member->roleid != 1) {
				if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
					$this->template->error(lang("error_117"));
				}
			}
		}

		/* Our calendar data */
		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->common->nohtml($this->input->post("description"));
		$start_date = $this->common->nohtml($this->input->post("start_date"));
		$end_date = $this->common->nohtml($this->input->post("end_date"));
		$delete = intval($this->input->post("delete"));
		$location = $this->common->nohtml($this->input->post("location"));

		if(!$delete) {
			if(empty($name)) {
				$this->template->error(lang("error_119"));
			}

			if(!empty($start_date)) {
				$sd = DateTime::createFromFormat($this->settings->info->calendar_picker_format, $start_date);
				$start_date = $sd->format('Y-m-d H:i:s');
				$start_date_timestamp = $sd->getTimestamp();
			} else {
				$start_date = date("Y-m-d\TH:i:s", time());
				$start_date_timestamp = time();
			}

			if(!empty($end_date)) {
				$ed = DateTime::createFromFormat($this->settings->info->calendar_picker_format, $end_date);
				$end_date = $ed->format('Y-m-d H:i:s');
				$end_date_timestamp = $ed->getTimestamp();
			} else {
				$this->template->error(lang("error_120"));
			}

			$this->calendar_model->update_event($eventid, array(
				"title" => $name,
				"description" => $desc,
				"start" => $start_date,
				"end" => $end_date,
				"location" => $location
				)
			);
			$this->session->set_flashdata("globalmsg", 
				lang("success_62"));
		} else {
			$this->calendar_model->delete_event($eventid);
			$this->session->set_flashdata("globalmsg", 
				lang("success_63"));
		}
		redirect(site_url("pages/events/" . $page->ID));
	}

	public function members($id) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		if(is_numeric($id)) {
			$id = intval($id);
			$page = $this->page_model->get_page($id);
		} else {
			$id = $this->common->nohtml($id);
			$page = $this->page_model->get_page_by_slug($id);
		}

		// Get page
		if($page->num_rows() == 0) {
			$this->template->error(lang("error_94"));
		}

		$page = $page->row();

		// Get page member
		$member = $this->page_model->get_page_user($page->ID, $this->user->info->ID);
		if($member->num_rows() == 0) {
			$member = null;
		} else {
			$member = $member->row();
		}

		if($page->nonmembers_view && $member == null) {
			$this->template->error(lang("error_118"));
		}

		if($page->type == 1) {
			// Check user is a member
			if($member == null) {
				if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
					$this->template->error(lang("error_102"));
				}
			}
		}

		$this->template->loadContent("pages/members.php", array(
			"page" => $page,
			"slug" => $id,
			"member" => $member
			)
		);
	}

	public function members_page($id) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$id = intval($id);
		$page = $this->page_model->get_page($id);
		// Get page
		if($page->num_rows() == 0) {
			$this->template->errori(lang("error_94"));
		}

		$page = $page->row();

		// Get page member
		$member = $this->page_model->get_page_user($id, $this->user->info->ID);
		if($member->num_rows() == 0) {
			$member = null;
		} else {
			$member = $member->row();
		}

		if($page->nonmembers_view && $member == null) {
			$this->template->error(lang("error_118"));
		}

		if($page->type == 1) {
			// Check user is a member
			if($member == null) {
				if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
					$this->template->error(lang("error_102"));
				}
			}
		}

		$this->load->library("datatables");

		$this->datatables->set_default_order("page_users.ID", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 1 => array(
				 	"users.username" => 0
				 ),
				 2 => array(
				 	"users.first_name" => 0
				 ),
				 3 => array(
				 	"users.last_name" => 0
				 ),
				 4 => array(
				 	"page_users.user_role" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->page_model
				->get_total_members($page->ID)
		);
		$users = $this->page_model->get_page_members_dt($page->ID, $this->datatables);

		foreach($users->result() as $r) {

			if($r->roleid == 1) {
				$user_role = lang("ctn_35");
			} elseif($r->roleid == 0) {
				$user_role = lang("ctn_34");
			}
			$options = "";
			if($member != null && $member->roleid == 1 || $this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
				$options = '<a href="javascript:void(0)" onclick="edit_member('.$r->ID.')" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("pages/remove_member/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></a>';
			}
			$this->datatables->data[] = array(
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)),
				$r->username,
				$r->first_name,
				$r->last_name,
				$user_role,
				$options
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function edit_member($id) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$id = intval($id);
		$user = $this->page_model->get_page_member($id);
		// Get page
		if($user->num_rows() == 0) {
			$this->template->error(lang("error_121"));
		}
		$user = $user->row();


		$page = $this->page_model->get_page($user->pageid);
		// Get page
		if($page->num_rows() == 0) {
			$this->template->error(lang("error_94"));
		}

		$page = $page->row();

		// Get page member
		$member = $this->page_model->get_page_user($page->ID, $this->user->info->ID);
		if($member->num_rows() == 0) {
			// Check role
			if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
				$this->template->error(lang("error_117"));
			}
		} else {
			$member = $member->row();
			// Check role
			if($member->roleid != 1) {
				if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
					$this->template->error(lang("error_117"));
				}
			}
		}

		$this->template->loadAjax("pages/edit_member.php", array(
			"user" => $user
			),1
		);
		exit();
	}

	public function edit_member_pro($id) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$id = intval($id);
		$user = $this->page_model->get_page_member($id);
		// Get page
		if($user->num_rows() == 0) {
			$this->template->error(lang("error_121"));
		}
		$user = $user->row();


		$page = $this->page_model->get_page($user->pageid);
		// Get page
		if($page->num_rows() == 0) {
			$this->template->error(lang("error_94"));
		}

		$page = $page->row();

		// Check user is a member of page
		$member = $this->page_model->get_page_user($page->ID, $this->user->info->ID);
		if($member->num_rows() == 0) {
			// Check role
			if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
				$this->template->error(lang("error_117"));
			}
		} else {
			$member = $member->row();
			// Check role
			if($member->roleid != 1) {
				if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
					$this->template->error(lang("error_117"));
				}
			}
		}

		$roleid = intval($this->input->post("roleid"));
		if($roleid > 1) {
			$this->template->error(lang("error_122"));
		}

		$this->page_model->update_page_user($id, array(
			"roleid" => $roleid
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_64"));
		redirect(site_url("pages/members/" . $page->ID));
	}

	public function join_page($id, $hash) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$page = $this->page_model->get_page($id);
		// Get page
		if($page->num_rows() == 0) {
			$this->template->error(lang("error_94"));
		}

		$page = $page->row();

		// Get page member
		$member = $this->page_model->get_page_user($id, $this->user->info->ID);
		if($member->num_rows() > 0) {
			$this->template->error(lang("error_123"));
		}

		// Check for invite
		$flag = 0;
		$invite = $this->page_model->get_page_invite($id, $this->user->info->ID);
		if($invite->num_rows() > 0) {
			$invite = $invite->row();
			$flag = 1;
			$this->page_model->delete_page_invite($invite->ID);
		}

		if($page->type == 1) {
			if(!$flag) {
				if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
					$this->template->error(lang("error_124"));
				}
			}
		}

		if($page->pay_to_join > 0) {
			if($this->user->info->points < $page->pay_to_join) {
				$this->template->error(lang("error_190") . $page->pay_to_join);
			}

			// Take away
			$this->user_model->update_user($this->user->info->ID, array(
				"points" => $this->user->info->points - $page->pay_to_join
				)
			);

			// Add to page admin
			if($page->pay_to_userid > 0) {

				$pay_to_user = $this->user_model->get_user_by_id($page->pay_to_userid);
				if($pay_to_user->num_rows() > 0) {
					$pay_to_user = $pay_to_user->row();
				

					$this->user_model->update_user($page->pay_to_userid, array(
						"points" => $pay_to_user->points + $page->pay_to_join
						)
					);

					// Send notification
					// Notification
					$this->user_model->increment_field($page->pay_to_userid, "noti_count", 1);
					$this->user_model->add_notification(array(
						"userid" => $page->pay_to_userid,
						"url" => "pages/view/" . $page->ID,
						"timestamp" => time(),
						"message" => $this->user->info->first_name . " " . $this->user->info->last_name . lang("ctn_836") . $page->pay_to_join,
						"status" => 0,
						"fromid" => $this->user->info->ID,
						"username" => $pay_to_user->username,
						"email" => $pay_to_user->email,
						"email_notification" => $pay_to_user->email_notification
						)
					);
				}
			}
		}

		// Add User
		$this->page_model->add_page_user(array(
			"pageid" => $id,
			"userid" => $this->user->info->ID,
			"roleid" => 0
			)
		);

		$this->update_user_pages($id, $this->user->info->ID, true);

		$this->session->set_flashdata("globalmsg", lang("success_65"));
		redirect(site_url("pages/view/" . $id));
	}

	public function remove_member($id, $hash) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$user = $this->page_model->get_page_member($id);
		// Get page
		if($user->num_rows() == 0) {
			$this->template->error(lang("error_121"));
		}
		$user = $user->row();


		$page = $this->page_model->get_page($user->pageid);
		// Get page
		if($page->num_rows() == 0) {
			$this->template->error(lang("error_94"));
		}

		$page = $page->row();

		// Get page member
		$member = $this->page_model->get_page_user($page->ID, $this->user->info->ID);
		if($member->num_rows() == 0) {
			// Check role
			if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
				$this->template->error(lang("error_117"));
			}
		} else {
			$member = $member->row();
			// Check role
			if($member->roleid != 1) {
				if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
					$this->template->error(lang("error_117"));
				}
			}
		}

		// Add User
		$this->page_model->delete_page_user($user->ID);

		$this->update_user_pages($page->ID, $user->ID, false);
		

		$this->session->set_flashdata("globalmsg", lang("success_66"));
		redirect(site_url("pages/members/" . $page->ID));
	}

	public function leave_page($id, $hash) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$page = $this->page_model->get_page($id);
		// Get page
		if($page->num_rows() == 0) {
			$this->template->error(lang("error_94"));
		}

		$page = $page->row();

		// Get page member
		$member = $this->page_model->get_page_user($id, $this->user->info->ID);
		if($member->num_rows() == 0) {
			$this->template->error(lang("error_125"));
		}
		$member = $member->row();

		// Add User
		$this->page_model->delete_page_user($member->ID);

		$this->update_user_pages($page->ID, $this->user->info->ID, false);
		

		$this->session->set_flashdata("globalmsg", lang("success_67"));
		redirect(site_url("pages/view/" . $id));
	}

	public function albums($id) 
	{
		if(!$this->settings->info->public_pages) {
			if(!$this->user->loggedin) {
				redirect(site_url("login"));
			}
		}
		if(is_numeric($id)) {
			$id = intval($id);
			$page = $this->page_model->get_page($id);
		} else {
			$id = $this->common->nohtml($id);
			$page = $this->page_model->get_page_by_slug($id);
		}

		// Get page
		if($page->num_rows() == 0) {
			$this->template->error(lang("error_94"));
		}

		$page = $page->row();

		if($this->user->loggedin) {
			// Get page member
			$member = $this->page_model->get_page_user($page->ID, $this->user->info->ID);
			if($member->num_rows() == 0) {
				$member = null;
			} else {
				$member = $member->row();
			}
		} else {
			$member = null;
		}

		if($page->nonmembers_view && $member == null) {
			$this->template->error(lang("error_118"));
		}

		if($page->type == 1) {
			// Check user is a member
			if($member == null) {
				if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
					$this->template->error(lang("error_102"));
				}
			}
		}

		$this->template->loadContent("pages/albums.php", array(
			"page" => $page,
			"member" => $member,
			"slug" => $id,
			)
		);

	}

	public function albums_page($id) 
	{
		if(!$this->settings->info->public_pages) {
			if(!$this->user->loggedin) {
				redirect(site_url("login"));
			}
		}
		$id = intval($id);
		$page = $this->page_model->get_page($id);

		// Get page
		if($page->num_rows() == 0) {
			$this->template->error(lang("error_94"));
		}

		$page = $page->row();

		if($this->user->loggedin) {
			// Get page member
			$member = $this->page_model->get_page_user($page->ID, $this->user->info->ID);
			if($member->num_rows() == 0) {
				$member = null;
			} else {
				$member = $member->row();
			}
		} else {
			$member = null;
		}

		if($page->type == 1) {
			// Check user is a member
			if($member == null) {
				if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
					$this->template->error(lang("error_102"));
				}
			}
		}

		$this->load->library("datatables");

		$this->datatables->set_default_order("user_albums.ID", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 1 => array(
				 	"user_albums.name" => 0
				 ),
				 2 => array(
				 	"user_albums.images" => 0
				 ),
				 3 => array(
				 	"user_albums.timestamp" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->image_model
				->get_total_page_albums($page->ID)
		);
		$albums = $this->image_model->get_page_albums($page->ID, $this->datatables);

		foreach($albums->result() as $r) {
			if($member != null && $member->roleid == 1) {
				$options = '<a href="'.site_url("pages/view_album/" . $r->ID).'" class="btn btn-primary btn-xs">'.lang("ctn_657").'</a> <a href="javascript:void(0)" onclick="edit_album('.$r->ID.')" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("pages/delete_album/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></a>';
			} else {
				$options = '<a href="'.site_url("pages/view_album/" . $r->ID).'" class="btn btn-primary btn-xs">'.lang("ctn_657").'</a>';
			}
			if(isset($r->file_name)) {
				$image = '<img src="'. base_url() . $this->settings->info->upload_path_relative . '/' . $r->file_name .'" width="50">';
			} else {
				$image = '<img src="'. base_url() . $this->settings->info->upload_path_relative . '/default_album.png" width="50">';
			}
			$this->datatables->data[] = array(
				'<a href="'.site_url("pages/view_album/" . $r->ID).'">'.$image.'</a>',
				$r->name,
				$r->images,
				date($this->settings->info->date_format, $r->timestamp),
				$options
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function add_album($id) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$id = intval($id);
		$page = $this->page_model->get_page($id);

		// Get page
		if($page->num_rows() == 0) {
			$this->template->error(lang("error_94"));
		}

		$page = $page->row();

		if(empty($page->slug)) {
			$slug = $id;
		} else {
			$slug = $page->slug;
		}

		// Get page member
		// Check user is a member of page
		$member = $this->page_model->get_page_user($page->ID, $this->user->info->ID);
		if($member->num_rows() == 0) {
			// Check role
			if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
				$this->template->error(lang("error_117"));
			}
		} else {
			$member = $member->row();
			// Check role
			if($member->roleid != 1) {
				if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
					$this->template->error(lang("error_117"));
				}
			}
		}

		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->common->nohtml($this->input->post("description"));

		if(empty($name)) {
			$this->template->error(lang("error_126"));
		}

		$this->image_model->add_album(array(
			"pageid" => $page->ID,
			"name" => $name,
			"description" => $desc,
			"timestamp" => time()
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_68"));
		redirect(site_url("pages/albums/" . $slug));
	}

	public function edit_album($id) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$id = intval($id);
		$album = $this->image_model->get_user_album($id);
		if($album->num_rows() == 0) {
			$this->template->errori(lang("error_127"));
		}
		$album = $album->row();

		$page = $this->page_model->get_page($album->pageid);

		// Get page
		if($page->num_rows() == 0) {
			$this->template->errori(lang("error_94"));
		}

		// Get page
		if($page->num_rows() == 0) {
			$this->template->error(lang("error_94"));
		}

		$page = $page->row();

		if(empty($page->slug)) {
			$slug = $id;
		} else {
			$slug = $page->slug;
		}

		// Check user is a member of page
		$member = $this->page_model->get_page_user($page->ID, $this->user->info->ID);
		if($member->num_rows() == 0) {
			// Check role
			if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
				$this->template->error(lang("error_117"));
			}
		} else {
			$member = $member->row();
			// Check role
			if($member->roleid != 1) {
				if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
					$this->template->error(lang("error_117"));
				}
			}
		}

		$this->template->loadAjax("pages/edit_album.php", array(
			"album" => $album
			),1
		);
		exit();
	}

	public function edit_album_pro($id) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$id = intval($id);
		$album = $this->image_model->get_user_album($id);
		if($album->num_rows() == 0) {
			$this->template->errori(lang("error_127"));
		}
		$album = $album->row();

		$page = $this->page_model->get_page($album->pageid);

		// Get page
		if($page->num_rows() == 0) {
			$this->template->error(lang("error_94"));
		}

		$page = $page->row();

		if(empty($page->slug)) {
			$slug = $id;
		} else {
			$slug = $page->slug;
		}

		// Check user is a member of page
		$member = $this->page_model->get_page_user($page->ID, $this->user->info->ID);
		if($member->num_rows() == 0) {
			// Check role
			if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
				$this->template->error(lang("error_117"));
			}
		} else {
			$member = $member->row();
			// Check role
			if($member->roleid != 1) {
				if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
					$this->template->error(lang("error_117"));
				}
			}
		}

		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->common->nohtml($this->input->post("description"));

		if(empty($name)) {
			$this->template->error(lang("error_126"));
		}

		$this->image_model->update_user_album($id, array(
			"name" => $name,
			"description" => $desc,
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_69"));
		redirect(site_url("pages/albums/" . $album->pageid));
	}

	public function delete_album($id, $hash) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$album = $this->image_model->get_user_album($id);
		if($album->num_rows() == 0) {
			$this->template->errori(lang("error_127"));
		}
		$album = $album->row();

		$page = $this->page_model->get_page($album->pageid);

		// Get page
		if($page->num_rows() == 0) {
			$this->template->error(lang("error_94"));
		}

		$page = $page->row();

		if(empty($page->slug)) {
			$slug = $id;
		} else {
			$slug = $page->slug;
		}

		// Check user is a member of page
		$member = $this->page_model->get_page_user($page->ID, $this->user->info->ID);
		if($member->num_rows() == 0) {
			// Check role
			if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
				$this->template->error(lang("error_117"));
			}
		} else {
			$member = $member->row();
			// Check role
			if($member->roleid != 1) {
				if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
					$this->template->error(lang("error_117"));
				}
			}
		}

		if($album->feed_album) {
			$this->template->error(lang("error_128"));
		}

		$this->image_model->delete_album($id);
		$this->image_model->delete_album_images($id);

		$this->session->set_flashdata("globalmsg", lang("success_70"));
		redirect(site_url("pages/albums/" . $album->pageid));
	}

	public function view_album($id, $p=0) 
	{
		if(!$this->settings->info->public_pages) {
			if(!$this->user->loggedin) {
				redirect(site_url("login"));
			}
		}
		$p = intval($p);
		$this->template->loadExternal(
			'
			<script type="text/javascript">
			$(document).ready(function() {
			$(".album-images").viewer();
			});
			</script>
			'
		);

		$id = intval($id);
		$album = $this->image_model->get_user_album($id);
		if($album->num_rows() == 0) {
			$this->template->error(lang("error_127"));
		}
		$album = $album->row();

		$page = $this->page_model->get_page($album->pageid);

		// Get page
		if($page->num_rows() == 0) {
			$this->template->error(lang("error_94"));
		}

		$page = $page->row();

		if(empty($page->slug)) {
			$slug = $page->ID;
		} else {
			$slug = $page->slug;
		}

		if($this->user->loggedin) {
			// Get page member
			$member = $this->page_model->get_page_user($page->ID, $this->user->info->ID);
			if($member->num_rows() == 0) {
				$member = null;
			} else {
				$member = $member->row();
			}
		} else {
			$member = null;
		}

		if($page->nonmembers_view && $member == null) {
			$this->template->error(lang("error_118"));
		} 

		if($page->type == 1) {
			// Check user is a member
			if($member == null) {
				if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
					$this->template->error(lang("error_102"));
				}
			}
		}

		$images = $this->image_model->get_album_images($album->ID, $p);

		$this->load->library('pagination');
		$config['base_url'] = site_url("pages/view_album/" . $id);
		$config['total_rows'] = $this->image_model
			->get_total_album_images($id);
		$config['per_page'] = 50;
		$config['uri_segment'] = 4;

		include (APPPATH . "/config/page_config.php");

		$this->pagination->initialize($config); 

		$this->template->loadContent("pages/view_album.php", array(
			"page" => $page,
			"member" => $member,
			"album" => $album,
			"images" => $images,
			"slug" => $slug
			)
		);
	}

	public function add_photo($id) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$id = intval($id);
		$album = $this->image_model->get_user_album($id);
		if($album->num_rows() == 0) {
			$this->template->error(lang("error_127"));
		}
		$album = $album->row();

		$page = $this->page_model->get_page($album->pageid);

		// Get page
		if($page->num_rows() == 0) {
			$this->template->error(lang("error_94"));
		}

		$page = $page->row();

		if(empty($page->slug)) {
			$slug = $page->ID;
		} else {
			$slug = $page->slug;
		}

		// Check user is a member of page
		$member = $this->page_model->get_page_user($page->ID, $this->user->info->ID);
		if($member->num_rows() == 0) {
			// Check role
			if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
				$this->template->error(lang("error_117"));
			}
		} else {
			$member = $member->row();
			// Check role
			if($member->roleid != 1) {
				if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
					$this->template->error(lang("error_117"));
				}
			}
		}

		$image_url = $this->common->nohtml($this->input->post("image_url"));
		$name = $this->common->nohtml($this->input->post("name"));
		$description = $this->common->nohtml($this->input->post("description"));
		$feed_post = intval($this->input->post("feed_post"));

		$fileid = 0;
		if(!empty($image_url)) {
			 $fileid = $this->image_model->add_image(array(
            	"file_url" => $image_url,
            	"userid" => $this->user->info->ID,
            	"timestamp" => time(),
            	"albumid" => $album->ID,
            	"name" => $name,
            	"description" => $description
            	)
            );
            // Update album count
            $this->image_model->increase_album_count($album->ID);

		} elseif(isset($_FILES['image_file']['size']) && $_FILES['image_file']['size'] > 0) {
			$this->load->library("upload");
			// Upload image
			$this->upload->initialize(array(
			   "upload_path" => $this->settings->info->upload_path,
		       "overwrite" => FALSE,
		       "max_filename" => 300,
		       "encrypt_name" => TRUE,
		       "remove_spaces" => TRUE,
		       "allowed_types" => "png|gif|jpeg|jpg",
		       "max_size" => $this->settings->info->file_size,
				)
			);

			if ( ! $this->upload->do_upload('image_file'))
            {
                    $error = array('error' => $this->upload->display_errors());

                    $this->template->jsonError(lang("error_95") . "<br /><br />" .
                    	 $this->upload->display_errors());
            }

            $data = $this->upload->data();

            $fileid = $this->image_model->add_image(array(
            	"file_name" => $data['file_name'],
            	"file_type" => $data['file_type'],
            	"extension" => $data['file_ext'],
            	"file_size" => $data['file_size'],
            	"userid" => $this->user->info->ID,
            	"timestamp" => time(),
            	"albumid" => $album->ID,
            	"name" => $name,
            	"description" => $description
            	)
            );
            // Update album count
            $this->image_model->increase_album_count($album->ID);
		} else {
			$this->template->error(lang("error_129"));
		}

		if($feed_post) {
			// Add a feed post
			$postid = $this->feed_model->add_post(array(
				"userid" => $this->user->info->ID,
				"content" => $description,
				"timestamp" => time(),
				"imageid" => $fileid,
				"post_as" => "page",
				"pageid" => $page->ID,
				"hide_profile" => 1
				)
			);
		}

		$this->session->set_flashdata("globalmsg", lang("success_71"));
		redirect(site_url("pages/view_album/" . $id));
	}

	public function add_multi_photo($id) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$id = intval($id);
		$album = $this->image_model->get_user_album($id);
		if($album->num_rows() == 0) {
			$this->template->error(lang("error_127"));
		}
		$album = $album->row();

		$page = $this->page_model->get_page($album->pageid);

		// Get page
		if($page->num_rows() == 0) {
			$this->template->error(lang("error_94"));
		}

		$page = $page->row();

		if(empty($page->slug)) {
			$slug = $id;
		} else {
			$slug = $page->slug;
		}

		// Check user is a member of page
		$member = $this->page_model->get_page_user($page->ID, $this->user->info->ID);
		if($member->num_rows() == 0) {
			// Check role
			if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
				$this->template->error(lang("error_117"));
			}
		} else {
			$member = $member->row();
			// Check role
			if($member->roleid != 1) {
				if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
					$this->template->error(lang("error_117"));
				}
			}
		}

		$amount = intval($this->input->post("amount"));
		$this->load->library("upload");

		$feed_post = intval($this->input->post("feed_post"));
		$files_added = array();
		for($i=0;$i<=$amount;$i++) {
			$image_url = $this->common->nohtml($this->input->post("image_url_" . $i));

			$fileid = 0;
			if(!empty($image_url)) {
				 $fileid = $this->image_model->add_image(array(
	            	"file_url" => $image_url,
	            	"userid" => $this->user->info->ID,
	            	"timestamp" => time(),
	            	"albumid" => $album->ID,
	            	"name" => $name,
	            	"description" => $description
	            	)
	            );
	            // Update album count
	            $this->image_model->increase_album_count($album->ID);

			} elseif(isset($_FILES['image_file_' . $i]['size']) && $_FILES['image_file_' . $i]['size'] > 0) {
	
				// Upload image
				$this->upload->initialize(array(
				   "upload_path" => $this->settings->info->upload_path,
			       "overwrite" => FALSE,
			       "max_filename" => 300,
			       "encrypt_name" => TRUE,
			       "remove_spaces" => TRUE,
			       "allowed_types" => "png|gif|jpeg|jpg",
			       "max_size" => $this->settings->info->file_size,
					)
				);

				if ( ! $this->upload->do_upload('image_file_' . $i))
	            {
	                    $error = array('error' => $this->upload->display_errors());

	                    $this->template->error(lang("error_95") . "<br /><br />" .
	                    	 $this->upload->display_errors());
	            }

	            $data = $this->upload->data();

	            $fileid = $this->image_model->add_image(array(
	            	"file_name" => $data['file_name'],
	            	"file_type" => $data['file_type'],
	            	"extension" => $data['file_ext'],
	            	"file_size" => $data['file_size'],
	            	"userid" => $this->user->info->ID,
	            	"timestamp" => time(),
	            	"albumid" => $album->ID,
	            	)
	            );
	            // Update album count
	            $this->image_model->increase_album_count($album->ID);
			}

			$files_added[] = $fileid;
		}


		if($feed_post) {
			// Add a feed post
			$postid = $this->feed_model->add_post(array(
				"userid" => $this->user->info->ID,
				"content" => "",
				"timestamp" => time(),
				"template" => "album",
				"post_as" => "page",
				"pageid" => $page->ID,
				"hide_profile" => 1
				)
			);

			foreach($files_added as $fileid) {
				$this->feed_model->add_feed_image(array(
					"postid" => $postid,
					"imageid" => $fileid
					)
				);
			}
		}


		$this->session->set_flashdata("globalmsg", lang("success_72"));
		redirect(site_url("pages/view_album/" . $id));
	}

	public function edit_image($id) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$id = intval($id);
		$image = $this->image_model->get_image($id);
		if($image->num_rows() == 0) {
			$this->template->error(lang("error_130"));
		}
		$image = $image->row();

		$page = $this->page_model->get_page($image->pageid);

		// Get page
		if($page->num_rows() == 0) {
			$this->template->error(lang("error_94"));
		}

		$page = $page->row();

		if(empty($page->slug)) {
			$slug = $id;
		} else {
			$slug = $page->slug;
		}

		// Check user is a member of page
		$member = $this->page_model->get_page_user($page->ID, $this->user->info->ID);
		if($member->num_rows() == 0) {
			// Check role
			if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
				$this->template->error(lang("error_117"));
			}
		} else {
			$member = $member->row();
			// Check role
			if($member->roleid != 1) {
				if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
					$this->template->error(lang("error_117"));
				}
			}
		}

		$albums = $this->image_model->get_page_albums_all($page->ID);

		$this->template->loadAjax("pages/edit_image.php", array(
			"image" => $image,
			"albums" => $albums
			),1
		);
		exit();
	}

	public function edit_image_pro($id) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$id = intval($id);
		$image = $this->image_model->get_image($id);
		if($image->num_rows() == 0) {
			$this->template->error(lang("error_130"));
		}
		$image = $image->row();

		$page = $this->page_model->get_page($image->pageid);

		// Get page
		if($page->num_rows() == 0) {
			$this->template->error(lang("error_94"));
		}

		$page = $page->row();

		if(empty($page->slug)) {
			$slug = $id;
		} else {
			$slug = $page->slug;
		}

		// Check user is a member of page
		$member = $this->page_model->get_page_user($page->ID, $this->user->info->ID);
		if($member->num_rows() == 0) {
			// Check role
			if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
				$this->template->error(lang("error_117"));
			}
		} else {
			$member = $member->row();
			// Check role
			if($member->roleid != 1) {
				if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
					$this->template->error(lang("error_117"));
				}
			}
		}

		$image_url = $this->common->nohtml($this->input->post("image_url"));
		$name = $this->common->nohtml($this->input->post("name"));
		$description = $this->common->nohtml($this->input->post("description"));
		$albumid = intval($this->input->post("albumid"));

		// Check for valid album
		$album = $this->image_model->get_user_album($albumid);
		if($album->num_rows() == 0) {
			$this->template->error(lang("error_127"));
		}
		$album = $album->row();

		if($album->pageid != $page->ID) {
			$this->template->error(lang("error_131"));
		}


		if($albumid != $image->albumid) {
			// Changing albums
			$this->image_model->increase_album_count($albumid);
			$this->image_model->decrease_album_count($image->albumid);
		}

		$fileid = 0;
		if(!empty($image_url)) {
			 $fileid = $this->image_model->update_image($id, array(
            	"file_url" => $image_url,
            	"albumid" => $album->ID,
            	"name" => $name,
            	"description" => $description
            	)
            );

		} elseif(isset($_FILES['image_file']['size']) && $_FILES['image_file']['size'] > 0) {

			$this->load->library("upload");
			// Upload image
			$this->upload->initialize(array(
			   "upload_path" => $this->settings->info->upload_path,
		       "overwrite" => FALSE,
		       "max_filename" => 300,
		       "encrypt_name" => TRUE,
		       "remove_spaces" => TRUE,
		       "allowed_types" => "png|gif|jpeg|jpg",
		       "max_size" => $this->settings->info->file_size,
				)
			);

			if ( ! $this->upload->do_upload('image_file'))
            {
                    $error = array('error' => $this->upload->display_errors());

                    $this->template->jsonError(lang("error_95") . "<br /><br />" .
                    	 $this->upload->display_errors());
            }

            $data = $this->upload->data();

            $fileid = $this->image_model->update_image($id, array(
            	"file_name" => $data['file_name'],
            	"file_type" => $data['file_type'],
            	"extension" => $data['file_ext'],
            	"file_size" => $data['file_size'],
            	"albumid" => $album->ID,
            	"name" => $name,
            	"description" => $description,
            	"file_url" => ""
            	)
            );
		} else {
			$fileid = $this->image_model->update_image($id, array(
				"name" => $name,
            	"description" => $description,
            	"albumid" => $album->ID,
				)
			);
		}

		$this->session->set_flashdata("globalmsg", lang("success_73"));
		redirect(site_url("pages/view_album/" . $albumid));

	}

	public function delete_image($id, $hash) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}

		$id = intval($id);
		$image = $this->image_model->get_image($id);
		if($image->num_rows() == 0) {
			$this->template->error(lang("error_130"));
		}
		$image = $image->row();

		$page = $this->page_model->get_page($image->pageid);

		// Get page
		if($page->num_rows() == 0) {
			$this->template->error(lang("error_94"));
		}

		$page = $page->row();

		if(empty($page->slug)) {
			$slug = $id;
		} else {
			$slug = $page->slug;
		}

		// Check user is a member of page
		$member = $this->page_model->get_page_user($page->ID, $this->user->info->ID);
		if($member->num_rows() == 0) {
			// Check role
			if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
				$this->template->error(lang("error_117"));
			}
		} else {
			$member = $member->row();
			// Check role
			if($member->roleid != 1) {
				if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
					$this->template->error(lang("error_117"));
				}
			}
		}

		// Delete
		if(!empty($image->file_url)) {
			$this->image_model->delete_image($id);
		} else {
			unlink($this->settings->info->upload_path . "/" . $image->file_name);
			$this->image_model->delete_image($id);
		}

		 $this->image_model->decrease_album_count($image->albumid);

		 // Delete any posts which the image are attached to
		$posts = $this->feed_model->get_posts_with_image($image->ID);
		foreach($posts->result() as $r) {
			$this->feed_model->delete_post($r->ID);
		}

		$this->session->set_flashdata("globalmsg", lang("success_74"));
		redirect(site_url("pages/view_album/" . $image->albumid));
	}

	public function view_event($eventid) 
	{
		if(!$this->settings->info->public_pages) {
			if(!$this->user->loggedin) {
				redirect(site_url("login"));
			}
		}
		$eventid = intval($eventid);
		$event = $this->calendar_model->get_event($eventid);
		if($event->num_rows() == 0) {
			$this->template->error(lang("error_76"));
		}

		$event = $event->row();

		// Check page
		$page = $this->page_model->get_page($event->pageid);
		// Get page
		if($page->num_rows() == 0) {
			$this->template->jsonError(lang("error_94"));
		}

		$page = $page->row();

		if(empty($page->slug)) {
			$slug = $page->ID;
		} else {
			$slug = $page->slug;
		}

		if($this->user->loggedin) {
			// Get page member
			$member = $this->page_model->get_page_user($page->ID, $this->user->info->ID);
			if($member->num_rows() == 0) {
				$member = null;
			} else {
				$member = $member->row();
			}
		} else {
			$member = null;
		}

		if($page->type == 1) {
			// Check user is a member
			if($member == null) {
				if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
					$this->template->error(lang("error_102"));
				}
			}
		}

		$attending=null;
		if($this->user->loggedin) {
			// Attending
			$attending = $this->calendar_model->get_event_user($eventid, $this->user->info->ID);
			if($attending->num_rows() == 0) {
				$attending = null;
			} else {
				$attending = $attending->row();
			}
		}

		$attending_count = $this->calendar_model->get_event_user_count($eventid);

		$this->template->loadContent("pages/view_event.php", array(
			"page" => $page,
			"member" => $member,
			"event" => $event,
			"slug" => $slug,
			"attending" => $attending,
			"attending_count" => $attending_count
			)
		);
	}

	public function view_event_users($eventid) 
	{
		if(!$this->user->loggedin) {
			$this->template->errori(lang("error_1"));
		}
		$eventid = intval($eventid);
		$type = intval($this->input->get("type"));
		$event = $this->calendar_model->get_event($eventid);
		if($event->num_rows() == 0) {
			$this->template->error(lang("error_76"));
		}

		$event = $event->row();

		// Check page
		$page = $this->page_model->get_page($event->pageid);
		// Get page
		if($page->num_rows() == 0) {
			$this->template->jsonError(lang("error_94"));
		}

		$page = $page->row();

		if(empty($page->slug)) {
			$slug = $page->ID;
		} else {
			$slug = $page->slug;
		}

		// Get page member
		$member = $this->page_model->get_page_user($page->ID, $this->user->info->ID);
		if($member->num_rows() == 0) {
			$member = null;
		} else {
			$member = $member->row();
		}

		if($page->type == 1) {
			// Check user is a member
			if($member == null) {
				if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
					$this->template->error(lang("error_102"));
				}
			}
		}

		// Attending
		$attending = $this->calendar_model->get_event_user($eventid, $this->user->info->ID);
		if($attending->num_rows() == 0) {
			$attending = null;
		} else {
			$attending = $attending->row();
		}

		$attending_count = $this->calendar_model->get_event_user_count($eventid);


		$users = $this->calendar_model->get_event_users($eventid, $type);
	

		$this->template->loadAjax("pages/view_event_users.php", array(
			"event" => $event,
			"attending" => $attending,
			"attending_count" => $attending_count,
			"users" => $users,
			"member" => $member,
			"type" => $type
			),1
		);
		exit();
	}

	public function remove_event_user($id, $hash) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}

		$id = intval($id);
		$eventUser = $this->calendar_model->get_event_user_id($id);
		if($eventUser->num_rows() == 0) {
			$this->template->error(lang("error_85"));
		}
		$eventUser =$eventUser->row();

		$eventid = $eventUser->eventid;
		$event = $this->calendar_model->get_event($eventid);
		if($event->num_rows() == 0) {
			$this->template->error(lang("error_132"));
		}

		$event = $event->row();

		// Check page
		$page = $this->page_model->get_page($event->pageid);
		// Get page
		if($page->num_rows() == 0) {
			$this->template->jsonError(lang("error_94"));
		}

		$page = $page->row();

		if(empty($page->slug)) {
			$slug = $page->ID;
		} else {
			$slug = $page->slug;
		}

		// Check user is a member of page
		$member = $this->page_model->get_page_user($page->ID, $this->user->info->ID);
		if($member->num_rows() == 0) {
			// Check role
			if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
				$this->template->error(lang("error_117"));
			}
		} else {
			$member = $member->row();
			// Check role
			if($member->roleid != 1) {
				if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
					$this->template->error(lang("error_117"));
				}
			}
		}

		$this->calendar_model->delete_event_user($id);
		$this->session->set_flashdata("globalmsg", lang("success_75"));
		redirect(site_url("pages/view_event/" . $event->ID));
	}

	public function join_event($eventid, $hash) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$eventid = intval($eventid);
		$event = $this->calendar_model->get_event($eventid);
		if($event->num_rows() == 0) {
			$this->template->error(lang("error_76"));
		}

		$event = $event->row();

		// Check page
		$page = $this->page_model->get_page($event->pageid);
		// Get page
		if($page->num_rows() == 0) {
			$this->template->jsonError(lang("error_94"));
		}

		$page = $page->row();

		if(empty($page->slug)) {
			$slug = $page->ID;
		} else {
			$slug = $page->slug;
		}

		// Get page member
		$member = $this->page_model->get_page_user($page->ID, $this->user->info->ID);
		if($member->num_rows() == 0) {
			if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
				$this->template->error(lang("error_125"));
			}
		}
		$member = $member->row();

		// Check attending
		$attending = $this->calendar_model->get_event_user($eventid, $this->user->info->ID);
		if($attending->num_rows() > 0) {
			$this->template->error(lang("error_133"));
		}

		$this->calendar_model->add_event_user(array(
			"userid" => $this->user->info->ID,
			"eventid" => $eventid,
			"status" => 1
			)
		);


		// Add a feed post
		$postid = $this->feed_model->add_post(array(
			"userid" => $this->user->info->ID,
			"content" => "",
			"timestamp" => time(),
			"eventid" => $eventid,
			"template" => "event_go"
			)
		);
		$this->user_model->increase_posts($this->user->info->ID);

		$this->session->set_flashdata("globalmsg", 
			lang("success_76"));
		redirect(site_url("pages/view_event/" . $eventid));
	}

	public function leave_event($eventid, $hash) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$eventid = intval($eventid);
		$event = $this->calendar_model->get_event($eventid);
		if($event->num_rows() == 0) {
			$this->template->error(lang("error_76"));
		}

		$event = $event->row();

		// Check page
		$page = $this->page_model->get_page($event->pageid);
		// Get page
		if($page->num_rows() == 0) {
			$this->template->jsonError(lang("error_94"));
		}

		$page = $page->row();

		if(empty($page->slug)) {
			$slug = $page->ID;
		} else {
			$slug = $page->slug;
		}

		// Check attending
		$attending = $this->calendar_model->get_event_user($eventid, $this->user->info->ID);
		if($attending->num_rows() == 0) {
			$this->template->error(lang("error_134"));
		}
		$attending = $attending->row();

		$this->calendar_model->delete_event_user($attending->ID);
		

		$this->session->set_flashdata("globalmsg", 
			lang("success_77"));
		redirect(site_url("pages/view_event/" . $eventid));
	}

	public function invite_user($id) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$id = intval($id);
		$page = $this->page_model->get_page($id);

		// Get page
		if($page->num_rows() == 0) {
			$this->template->error(lang("error_94"));
		}

		$page = $page->row();

		if(empty($page->slug)) {
			$slug = $id;
		} else {
			$slug = $page->slug;
		}

		// Check user is a member of page
		$member = $this->page_model->get_page_user($page->ID, $this->user->info->ID);
		if($member->num_rows() == 0) {
			// Check role
			if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
				$this->template->error(lang("error_117"));
			}
		} else {
			$member = $member->row();
			// Check role
			if($member->roleid != 1) {
				if(!$this->common->has_permissions(array("admin", "page_admin"), $this->user)) {
					$this->template->error(lang("error_117"));
				}
			}
		}

		$with_users = ($this->input->post("with_users"));
		$users = array();
		$user_flag = 0;
		if(is_array($with_users)) {
			foreach($with_users as $username) {
				$username = $this->common->nohtml($username);
				$user = $this->user_model->get_user_by_username($username);
				if($user->num_rows() > 0) {
					$user_flag = 1;
					$user = $user->row();
					if($user->allow_pages) {
						$this->template->error(lang("error_135").$user->first_name . " " . $user->last_name.lang("error_136"));
					}
					$users[] = $user;
				}
			}
		}

		if($user_flag) {
			foreach($users as $user) {
				// Check user is not already invited
				$invite = $this->page_model->get_page_invite($id, $user->ID);
				if($invite->num_rows() > 0) {
					continue;
				}

				// Invite
				$this->page_model->add_page_invite(array(
					"userid" => $user->ID,
					"pageid" => $id,
					"timestamp" => time(),
					"fromid" => $this->user->info->ID
					)
				);

				// Notification
				$this->user_model->increment_field($user->ID, "noti_count", 1);
				$this->user_model->add_notification(array(
					"userid" => $user->ID,
					"url" => "user_settings/page_invites",
					"timestamp" => time(),
					"message" => $this->user->info->first_name . " " . $this->user->info->last_name . " ".lang("ctn_658").": " . $page->name,
					"status" => 0,
					"fromid" => $this->user->info->ID,
					"username" => $user->username,
					"email" => $user->email,
					"email_notification" => $user->email_notification
					)
				);
			}
		}

		$this->session->set_flashdata("globalmsg", lang("success_78"));
		redirect(site_url("pages/members/" . $slug));
	}

	public function report_page($id) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$id = intval($id);
		$page = $this->page_model->get_page($id);
		if($page->num_rows() == 0) {
			$this->template->error(lang("error_94"));
		}
		$page = $page->row();

		$reason = $this->common->nohtml($this->input->post("reason"));

		if(empty($reason)) {
			$this->template->error(lang("error_137"));
		}

		if(empty($page->slug)) {
			$slug = $id;
		} else {
			$slug = $page->slug;
		}

		$this->user_model->add_report(array(
			"pageid" => $id,
			"timestamp" => time(),
			"reason" => $reason,
			"fromid" => $this->user->info->ID
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_79"));
		redirect(site_url("pages/view/" . $slug));
	}

}

?>