<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("image_model");
		$this->load->model("feed_model");
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

	public function index($username="") 
	{
		if(!$this->settings->info->public_profiles) {
			if(!$this->user->loggedin) {
				redirect(site_url("login"));
			}
		}
		$this->template->loadExternal(
			'
			<script type="text/javascript" src="'
			.base_url().'scripts/custom/profile.js" /></script>'
		);

		if(empty($username)) $this->template->error(lang("error_51"));
		$username = $this->common->nohtml($username);
		$user = $this->user_model->get_user_by_username($username);
		if($user->num_rows() == 0) $this->template->error(lang("error_52"));
		$user = $user->row();

		$role = $this->user_model->get_user_role($user->user_role);
		if($role->num_rows() == 0) {
			$role = lang("ctn_46");
		} else {
			$role = $role->row();
			$rolename = $role->name;
		}

		if(isset($role->banned)) {
        	if($role->banned) $this->template->error(lang("error_53"));
        }


		$groups = $this->user_model->get_user_groups($user->ID);
		$fields = $this->user_model->get_custom_fields_answers(array(
			"profile" => 1), $user->ID);

		// Update profile views
		$this->user_model->increase_profile_views($user->ID);

		$user_data = $this->user_model->get_user_data($user->ID);
		if($user_data->num_rows() == 0) {
			$user_data = null;
		} else {
			$user_data = $user_data->row();
		}

		if($this->user->loggedin) {
			// check user is friend
			$flags = $this->check_friend($this->user->info->ID, $user->ID);
		} else {
			$flags = array("friend_flag" => false, "request_flag" => false);
		}

		// If user is not logged in and friend only profile, no dice.
		if($user->profile_view == 1 && !$this->user->loggedin) {
			$user->profile_header = "empty.png";
			$user->avatar = "default.png";

			$this->template->loadContent("profile/empty.php", array(
				"user" => $user,
				"friend_flag" => $flags['friend_flag'],
				"request_flag" => $flags['request_flag'],
				), 1
			);
		}

		if($this->user->loggedin) {
			if($user->profile_view == 1 && $user->ID != $this->user->info->ID) {
				// Only let's friends view profile.
				if(!$flags['friend_flag']) {

					$user->profile_header = "empty.png";
					$user->avatar = "default.png";

					$this->template->loadContent("profile/empty.php", array(
						"user" => $user,
						"friend_flag" => $flags['friend_flag'],
						"request_flag" => $flags['request_flag'],
						), 1
					);
				}
			}
		}

		$relationship_user = null;
		if($user->relationship_userid > 0) {
			$usern = $this->user_model->get_user_by_id($user->relationship_userid);
			if($usern->num_rows() > 0) {
				$usern = $usern->row();
				$relationship_user = $usern;
			}
		}
		

		$friends = $this->user_model->get_user_friends_sample($user->ID);
		$albums = $this->image_model->get_user_albums_sample($user->ID);

		$this->template->loadContent("profile/index.php", array(
			"user" => $user,
			"groups" => $groups,
			"role" => $rolename,
			"fields" => $fields,
			"user_data" => $user_data,
			"friend_flag" => $flags['friend_flag'],
			"request_flag" => $flags['request_flag'],
			"friends" => $friends,
			"albums" => $albums,
			"post_count" => 0,
			"relationship_user" => $relationship_user
			)
		);
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

	public function albums($userid) 
	{
		$this->template->loadExternal(
			'
			<script type="text/javascript" src="'
			.base_url().'scripts/custom/profile.js" /></script>'
		);
		$userid = intval($userid);
		$user = $this->user_model->get_user_by_id($userid);
		if($user->num_rows() == 0) {
			$this->template->error(lang("error_85"));
		}
		$user = $user->row();

		if($this->user->loggedin) {
			// check user is friend
			$flags = $this->check_friend($this->user->info->ID, $user->ID);
		} else {
			$flags = array("friend_flag" => false, "request_flag" => false);
		}

		// If user is not logged in and friend only profile, no dice.
		if($user->profile_view == 1 && !$this->user->loggedin) {
			$user->profile_header = "empty.png";
			$user->avatar = "default.png";

			$this->template->loadContent("profile/empty.php", array(
				"user" => $user,
				"friend_flag" => $flags['friend_flag'],
				"request_flag" => $flags['request_flag'],
				), 1
			);
		}

		if($this->user->loggedin) {
			if($user->profile_view == 1 && $user->ID != $this->user->info->ID) {
				// Only let's friends view profile.
				if(!$flags['friend_flag']) {

					$user->profile_header = "empty.png";
					$user->avatar = "default.png";

					$this->template->loadContent("profile/empty.php", array(
						"user" => $user,
						"friend_flag" => $flags['friend_flag'],
						"request_flag" => $flags['request_flag'],
						), 1
					);
				}
			}
		}

		$this->template->loadContent("profile/albums.php", array(
			"user" => $user,
			"friend_flag" => $flags['friend_flag'],
			"request_flag" => $flags['request_flag'],
			)
		);

	}

	public function add_album($type=0) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$type = intval($type);
		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->common->nohtml($this->input->post("description"));

		if(empty($name)) {
			$this->template->error(lang("error_126"));
		}

		$this->image_model->add_album(array(
			"userid" => $this->user->info->ID,
			"name" => $name,
			"description" => $desc,
			"timestamp" => time()
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_68"));
		redirect(site_url("profile/albums/" . $this->user->info->ID));
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

		if($album->userid != $this->user->info->ID) {
			if(!$this->common->has_permissions(array("admin","admin_members"), $this->user)) {
				$this->template->errori(lang("error_138"));
			}
		}

		$this->template->loadAjax("profile/edit_album.php", array(
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

		if($album->userid != $this->user->info->ID) {
			if(!$this->common->has_permissions(array("admin","admin_members"), $this->user)) {
				$this->template->errori(lang("error_138"));
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
		redirect(site_url("profile/albums/" . $this->user->info->ID));
	}

	public function albums_page($userid) 
	{
		$userid = intval($userid);
		$user = $this->user_model->get_user_by_id($userid);
		if($user->num_rows() == 0) {
			$this->template->error(lang("error_85"));
		}
		$user = $user->row();

		if($this->user->loggedin) {
			// check user is friend
			$flags = $this->check_friend($this->user->info->ID, $user->ID);
		} else {
			$flags = array("friend_flag" => false, "request_flag" => false);
		}

		// If user is not logged in and friend only profile, no dice.
		if($user->profile_view == 1 && !$this->user->loggedin) {
			$user->profile_header = "empty.png";
			$user->avatar = "default.png";

			$this->template->loadContent("profile/empty.php", array(
				"user" => $user,
				"friend_flag" => $flags['friend_flag'],
				"request_flag" => $flags['request_flag'],
				), 1
			);
		}

		if($this->user->loggedin) {
			if($user->profile_view == 1 && $user->ID != $this->user->info->ID) {
				// Only let's friends view profile.
				if(!$flags['friend_flag']) {

					$user->profile_header = "empty.png";
					$user->avatar = "default.png";

					$this->template->loadContent("profile/empty.php", array(
						"user" => $user,
						"friend_flag" => $flags['friend_flag'],
						"request_flag" => $flags['request_flag'],
						), 1
					);
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
				->get_total_user_albums($user->ID)
		);
		$albums = $this->image_model->get_user_albums($user->ID, $this->datatables);

		foreach($albums->result() as $r) {
			if( ($this->user->loggedin && $user->ID == $this->user->info->ID) || $this->common->has_permissions(array("admin","admin_members"), $this->user)) {
				$options = '<a href="'.site_url("profile/view_album/" . $r->ID).'" class="btn btn-primary btn-xs">'.lang("ctn_657").'</a> <a href="javascript:void(0)" onclick="edit_album('.$r->ID.')" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("profile/delete_album/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></a>';
			} else {
				$options = '<a href="'.site_url("profile/view_album/" . $r->ID).'" class="btn btn-primary btn-xs">'.lang("ctn_657").'</a>';
			}
			if(isset($r->file_name)) {
				$image = '<img src="'. base_url() . $this->settings->info->upload_path_relative . '/' . $r->file_name .'" width="50">';
			} else {
				$image = '<img src="'. base_url() . $this->settings->info->upload_path_relative . '/default_album.png" width="50">';
			}
			$this->datatables->data[] = array(
				'<a href="'.site_url("profile/view_album/" . $r->ID).'">'.$image.'</a>',
				$r->name,
				$r->images,
				date($this->settings->info->date_format, $r->timestamp),
				$options
			);
		}
		echo json_encode($this->datatables->process());
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
			$this->template->error(lang("error_127"));
		}
		$album = $album->row();

		if($album->userid != $this->user->info->ID) {
			if(!$this->common->has_permissions(array("admin","admin_members"), $this->user)) {
				$this->template->error(lang("error_139"));
			}
		}

		if($album->feed_album) {
			$this->template->error(lang("error_128"));
		}

		$this->image_model->delete_album($id);
		$this->image_model->delete_album_images($id);

		$this->session->set_flashdata("globalmsg", lang("success_70"));
		redirect(site_url("profile/albums/" . $album->userid));
	}

	public function view_album($id, $page=0) 
	{
		if(!$this->settings->info->public_profiles) {
			if(!$this->user->loggedin) {
				redirect(site_url("login"));
			}
		}
		$page = intval($page);
		$this->template->loadExternal(
			'
			<script type="text/javascript" src="'
			.base_url().'scripts/custom/profile.js" /></script>
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

		$userid = $album->userid;
		$user = $this->user_model->get_user_by_id($userid);
		if($user->num_rows() == 0) {
			$this->template->error(lang("error_85"));
		}
		$user = $user->row();

		if($this->user->loggedin) {
			// check user is friend
			$flags = $this->check_friend($this->user->info->ID, $user->ID);
		} else {
			$flags = array("friend_flag" => false, "request_flag" => false);
		}

		// If user is not logged in and friend only profile, no dice.
		if($user->profile_view == 1 && !$this->user->loggedin) {
			$user->profile_header = "empty.png";
			$user->avatar = "default.png";

			$this->template->loadContent("profile/empty.php", array(
				"user" => $user,
				"friend_flag" => $flags['friend_flag'],
				"request_flag" => $flags['request_flag'],
				), 1
			);
		}

		if($this->user->loggedin) {
			if($user->profile_view == 1 && $user->ID != $this->user->info->ID) {
				// Only let's friends view profile.
				if(!$flags['friend_flag']) {

					$user->profile_header = "empty.png";
					$user->avatar = "default.png";

					$this->template->loadContent("profile/empty.php", array(
						"user" => $user,
						"friend_flag" => $flags['friend_flag'],
						"request_flag" => $flags['request_flag'],
						), 1
					);
				}
			}
		}

		$images = $this->image_model->get_album_images($album->ID, $page);

		$this->load->library('pagination');
		$config['base_url'] = site_url("profile/view_album/" . $id);
		$config['total_rows'] = $this->image_model
			->get_total_album_images($id);
		$config['per_page'] = 50;
		$config['uri_segment'] = 4;

		include (APPPATH . "/config/page_config.php");

		$this->pagination->initialize($config); 

		$this->template->loadContent("profile/view_album.php", array(
			"user" => $user,
			"album" => $album,
			"friend_flag" => $flags['friend_flag'],
			"request_flag" => $flags['request_flag'],
			"images" => $images
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

		$userid = $album->userid;

		if($userid != $this->user->info->ID) {
			if(!$this->common->has_permissions(array("admin","admin_members"), $this->user)) {
				$this->template->error(lang("error_138"));
			}
		}

		$user = $this->user_model->get_user_by_id($userid);
		if($user->num_rows() == 0) {
			$this->template->error(lang("error_85"));
		}
		$user = $user->row();

		$image_url = $this->common->nohtml($this->input->post("image_url"));
		$name = $this->common->nohtml($this->input->post("name"));
		$description = $this->common->nohtml($this->input->post("description"));
		$feed_post = intval($this->input->post("feed_post"));

		// Check photo limit
		if($this->settings->info->limit_max_photos > 0) {
			$count = $this->image_model->get_total_user_images($this->user->info->ID);
			if($count >= $this->settings->info->limit_max_photos) {
				$this->template->error(lang("error_186") . $this->settings->info->limit_max_photos);
			}
		}

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
				)
			);
			$this->user_model->increase_posts($this->user->info->ID);
		}

		$this->session->set_flashdata("globalmsg", lang("success_71"));
		redirect(site_url("profile/view_album/" . $id));
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

		$userid = $album->userid;
		$user = $this->user_model->get_user_by_id($userid);
		if($user->num_rows() == 0) {
			$this->template->error(lang("error_85"));
		}
		$user = $user->row();

		if($userid != $this->user->info->ID) {
			if(!$this->common->has_permissions(array("admin","admin_members"), $this->user)) {
				$this->template->error(lang("error_138"));
			}
		}

		$amount = intval($this->input->post("amount"));
		$this->load->library("upload");

		// Check photo limit
		if($this->settings->info->limit_max_photos > 0) {
			$count = $this->image_model->get_total_user_images($this->user->info->ID);
			if($count >= $this->settings->info->limit_max_photos) {
				$this->template->error(lang("error_186") . $this->settings->info->limit_max_photos);
			}
		}
		if($this->settings->info->limit_max_photos_post > 0) {
			if($amount > $this->settings->info->limit_max_photos_post) {
				$this->template->error(lang("error_191") . $this->settings->info->limit_max_photos_post);
			}
		}

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
				"template" => "album"
				)
			);
			$this->user_model->increase_posts($this->user->info->ID);

			foreach($files_added as $fileid) {
				$this->feed_model->add_feed_image(array(
					"postid" => $postid,
					"imageid" => $fileid
					)
				);
			}
		}


		$this->session->set_flashdata("globalmsg", lang("success_72"));
		redirect(site_url("profile/view_album/" . $id));
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

		if($image->userid != $this->user->info->ID) {
			if(!$this->common->has_permissions(array("admin","admin_members"), $this->user)) {
				$this->template->error(lang("error_140"));
			}
		}

		$albums = $this->image_model->get_user_albums_all($image->userid);

		$this->template->loadAjax("profile/edit_image.php", array(
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

		if($image->userid != $this->user->info->ID) {
			if(!$this->common->has_permissions(array("admin","admin_members"), $this->user)) {
				$this->template->error(lang("error_140"));
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

		if($album->userid != $this->user->info->ID) {
			$this->template->error(lang("error_138"));
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

                    $this->template->error(lang("error_95") . "<br /><br />" .
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
		redirect(site_url("profile/view_album/" . $albumid));

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

		if($image->userid != $this->user->info->ID) {
			if(!$this->common->has_permissions(array("admin","admin_members"), $this->user)) {
				$this->template->error(lang("error_140"));
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
			if($r->userid > 0) {
				$this->user_model->decrease_posts($r->userid);
				$this->feed_model->delete_post($r->ID);
			}
		}

		$this->session->set_flashdata("globalmsg", lang("success_74"));
		redirect(site_url("profile/view_album/" . $image->albumid));
	}

	public function friends($userid) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$this->template->loadExternal(
			'
			<script type="text/javascript" src="'
			.base_url().'scripts/custom/profile.js" /></script>'
		);
		$userid = intval($userid);
		$user = $this->user_model->get_user_by_id($userid);
		if($user->num_rows() == 0) {
			$this->template->error(lang("error_85"));
		}
		$user = $user->row();

		// check user is friend
		$flags = $this->check_friend($this->user->info->ID, $user->ID);

		if($user->profile_view == 1 && $user->ID != $this->user->info->ID) {
			// Only let's friends view profile.
			if(!$flags['friend_flag']) {

				$user->profile_header = "empty.png";
				$user->avatar = "default.png";

				$this->template->loadContent("profile/empty.php", array(
					"user" => $user,
					"friend_flag" => $flags['friend_flag'],
					"request_flag" => $flags['request_flag'],
					), 1
				);
			}
		}


		$this->template->loadContent("profile/friends.php", array(
			"user" => $user,
			"friend_flag" => $flags['friend_flag'],
			"request_flag" => $flags['request_flag'],
			)
		);
	}

	public function friends_page($userid) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$userid = intval($userid);
		$user = $this->user_model->get_user_by_id($userid);
		if($user->num_rows() == 0) {
			$this->template->error(lang("error_85"));
		}
		$user = $user->row();

		$flags = $this->check_friend($this->user->info->ID, $user->ID);

		if($user->profile_view == 1 && $user->ID != $this->user->info->ID) {
			// Only let's friends view profile.
			if(!$flags['friend_flag']) {

				$user->profile_header = "empty.png";
				$user->avatar = "default.png";

				$this->template->loadContent("profile/empty.php", array(
					"user" => $user,
					"friend_flag" => $flags['friend_flag'],
					"request_flag" => $flags['request_flag'],
					), 1
				);
			}
		}

		$this->load->library("datatables");

		$this->datatables->set_default_order("users.ID", "desc");

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
				 	"user_friends.timestamp" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->user_model
				->get_total_friends_count($user->ID)
		);
		$friends = $this->user_model->get_user_friends_dt($user->ID, $this->datatables);

		foreach($friends->result() as $r) {
			if($user->ID == $this->user->info->ID) {
				$options = '<a href="'.site_url("profile/deadd/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs">'.lang("ctn_470").'</a>';
			} else {
				$options = "";
			}
			$this->datatables->data[] = array(
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)),
				$r->username,
				$r->first_name,
				$r->last_name,
				date($this->settings->info->date_format, $r->timestamp),
				$options
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function deadd($id, $hash) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$id = intval($id);
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}

		$friend = $this->user_model->get_user_friend_id($id, $this->user->info->ID);
		if($friend->num_rows() == 0) {
			$this->template->error(lang("error_85"));
		}
		$friend = $friend->row();

		// Delete both
		$this->user_model->delete_friend($this->user->info->ID, $friend->friendid);

		// Update their friends
		$friends = unserialize($this->user->info->friends);

		$newfriends = array();
		foreach($friends as $id) {
			if($id != $friend->friendid) {
				$newfriends[] = $id;
			}
		}

		$this->user_model->update_user($this->user->info->ID, array(
			"friends" => serialize($newfriends)
			)
		);

		// Now our friend
		$user = $this->user_model->get_user_by_id($friend->friendid);
		if($user->num_rows() > 0) {
			$user = $user->row();
			$friends = unserialize($user->friends);

			$newfriends = array();
			foreach($friends as $id) {
				if($id != $this->user->info->ID) {
					$newfriends[] = $id;
				}
			}

			$this->user_model->update_user($friend->friendid, array(
				"friends" => serialize($newfriends)
				)
			);
		}

		$this->session->set_flashdata("globalmsg", lang("success_80"));
		redirect(site_url("profile/friends/" . $this->user->info->ID));
	}

	public function add_friend($userid) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$userid = intval($userid);
		$user = $this->user_model->get_user_by_id($userid);
		if($user->num_rows() == 0) {
			$this->template->jsonError(lang("error_85"));
		}
		$user = $user->row();

		if($userid == $this->user->info->ID) {
			$this->template->jsonError(lang("error_141"));
		}


		// Check they're not already friends
		$friend = $this->user_model->get_user_friend($this->user->info->ID, $user->ID);
		if($friend->num_rows() > 0) {
			$this->template->jsonError(lang("error_142"));
		}

		// Check user hasn't already sent a request
		$request = $this->user_model->check_friend_request($this->user->info->ID, $user->ID);
		if($request->num_rows() > 0) {
			$this->template->jsonError(lang("error_152"));
		}

		// Check other way round too
		$request = $this->user_model->check_friend_request($user->ID,$this->user->info->ID);
		if($request->num_rows() > 0) {
			$this->template->jsonError(lang("error_170"));
		}

		// Send request
		$this->user_model->add_friend_request(array(
			"userid" => $this->user->info->ID,
			"friendid" => $user->ID,
			"timestamp" => time()
			)
		);

		// Notification
		$this->user_model->increment_field($user->ID, "noti_count", 1);
		$this->user_model->add_notification(array(
			"userid" => $user->ID,
			"url" => "user_settings/friend_requests",
			"timestamp" => time(),
			"message" => $this->user->info->first_name . " " . $this->user->info->last_name . " " . lang("ctn_660"),
			"status" => 0,
			"fromid" => $this->user->info->ID,
			"username" => $user->username,
			"email" => $user->email,
			"email_notification" => $user->email_notification
			)
		);

		echo json_encode(array(
			"success" => 1,
			"message" => lang("ctn_661")
			)
		);
		exit();
	}

	public function report_profile($id) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$id = intval($id);
		$user = $this->user_model->get_user_by_id($id);
		if($user->num_rows() == 0) {
			$this->template->error(lang("error_85"));
		}
		$user = $user->row();

		$reason = $this->common->nohtml($this->input->post("reason"));

		if(empty($reason)) {
			$this->template->error(lang("error_137"));
		}

		$this->user_model->add_report(array(
			"userid" => $id,
			"timestamp" => time(),
			"reason" => $reason,
			"fromid" => $this->user->info->ID
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_81"));
		redirect(site_url("profile/" . $user->username));
	}

}

?>