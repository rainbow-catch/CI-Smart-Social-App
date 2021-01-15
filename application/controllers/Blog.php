<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("image_model");
		$this->load->model("feed_model");
		$this->load->model("blog_model");
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

		if(!$this->settings->info->enable_blogs) {
			$this->template->error(lang("error_176"));
		}
	}

	public function index() 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$this->template->loadContent("blog/index.php", array(
			
			)
		);
	}

	public function your() 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}

		$blog = $this->blog_model->get_user_blog($this->user->info->ID);
		if($blog->num_rows() == 0) {
			$this->template->loadContent("blog/create.php", array(
				
				), 1
			);
		}
		$blog = $blog->row();

		$this->template->loadContent("blog/your.php", array(
			"blog" => $blog
			
			)
		);
	}

	public function your_page() 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$blog = $this->blog_model->get_user_blog($this->user->info->ID);
		if($blog->num_rows() == 0) {
			$this->template->error(lang("error_177"));
		}
		$blog = $blog->row();

		$this->load->library("datatables");

		$this->datatables->set_default_order("user_blog_posts.last_updated", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 1 => array(
				 	"user_blog_posts.title" => 0
				 ),
				 2 => array(
				 	"user_blog_posts.status" => 0
				 ),
				 3 => array(
				 	"user_blog_posts.last_updated" => 0
				 ),
				 4 => array(
				 	"user_blog_posts.views" => 0
				 ),

			)
		);

		
		$this->datatables->set_total_rows(
			$this->blog_model
				->get_total_blog_posts($blog->ID)
		);
		$posts = $this->blog_model->get_blog_posts($blog->ID, $this->datatables);
		



		foreach($posts->result() as $r) {
			
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

				$options = '<a href="' . site_url("blog/edit_post/" . $r->ID) .'" class="btn btn-warning btn-xs" title="'. lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a> <a href="' . site_url("blog/delete_post/" . $r->ID . "/" . $this->security->get_csrf_hash()) .'" onclick="return confirm(\'' . lang("ctn_86") . '\')" class="btn btn-danger btn-xs" title="'. lang("ctn_57") .'"><span class="glyphicon glyphicon-trash"></span></a>';
			 
			$this->datatables->data[] = array(
				$image,
				'<a href="'.site_url("blog/view/" . $r->ID).'">' . $r->title . '</a>',
				$status,
				$time,
				$r->views,
				$options
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function create_pro() 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$title = $this->common->nohtml($this->input->post("title"));
		$description = $this->common->nohtml($this->input->post("description"));

		$private = intval($this->input->post("private"));

		if(empty($title)) {
			$this->template->error(lang("error_172"));
		}

		$blog = $this->blog_model->get_user_blog($this->user->info->ID);
		if($blog->num_rows() > 0) {
			$this->template->error(lang("error_178"));
		}

		$this->blog_model->add_blog(array(
			"title" => $title,
			"description" => $description,
			"userid" => $this->user->info->ID,
			"private" => $private,
			"timestamp" => time()
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_107"));
		redirect(site_url("blog/your"));
	}

	public function edit_blog($id) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$id = intval($id);
		$blog = $this->blog_model->get_blog($id);
		if($blog->num_rows() == 0) {
			$this->template->error(lang("error_171"));
		}
		$blog = $blog->row();

		if($blog->userid != $this->user->info->ID) {
			$this->template->error(lang("error_179"));
		}

		$this->template->loadContent("blog/edit_blog.php", array(
			"blog" => $blog
			)
		);
	}

	public function edit_blog_pro($id) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$id = intval($id);
		$blog = $this->blog_model->get_blog($id);
		if($blog->num_rows() == 0) {
			$this->template->error(lang("error_171"));
		}
		$blog = $blog->row();

		if($blog->userid != $this->user->info->ID) {
			$this->template->error(lang("error_179"));
		}

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

		$this->session->set_flashdata("globalmsg", lang("success_108"));
		redirect(site_url("blog/your"));
	}

	public function delete_blog($id, $hash) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$blog = $this->blog_model->get_blog($id);
		if($blog->num_rows() == 0) {
			$this->template->error(lang("error_171"));
		}
		$blog = $blog->row();

		if($blog->userid != $this->user->info->ID) {
			$this->template->error(lang("error_179"));
		}

		$this->blog_model->delete_blog($id);
		// Delete posts
		$this->blog_model->delete_all_blog_posts($blog->ID);
		$this->session->set_flashdata("globalmsg", lang("success_109"));
		redirect(site_url("blog/your"));

	}

	public function add_post() 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$blog = $this->blog_model->get_user_blog($this->user->info->ID);
		if($blog->num_rows() == 0) {
			$this->template->error(lang("error_177"));
		}
		$blog = $blog->row();

		$this->template->loadContent("blog/add_post.php", array(
			"blog" => $blog
			)
		);
	}

	public function add_post_pro() 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$blog = $this->blog_model->get_user_blog($this->user->info->ID);
		if($blog->num_rows() == 0) {
			$this->template->error(lang("error_177"));
		}
		$blog = $blog->row();

		$title = $this->common->nohtml($this->input->post("title"));
		$status = intval($this->input->post("status"));
		$blog_post = $this->lib_filter->go($this->input->post("blog_post"));
		$post_to_timeline = intval($this->input->post("post_to_timeline"));

		if(empty($title)) {
			$this->template->error(lang("error_174"));
		}

		if(empty($blog_post)) {
			$this->template->error(lang("error_175"));
		}

		// Upload
		$this->load->library("upload");

		$blog_image = "";
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

		$time = 0;
		if($status == 1) {
			$time = time();
		}

		$postid = $this->blog_model->add_post(array(
			"title" => $title,
			"body" => $blog_post,
			"timestamp" => $time,
			"status" => $status,
			"image" => $blog_image,
			"blogid" => $blog->ID,
			"last_updated" => time()
			)
		);

		if($status == 1) {
			// Alert subs
			$subscribers = $this->blog_model->get_subscribers($blog->ID);
			foreach($subscribers->result() as $r) {
				// Send notification
				// Notification
				$this->user_model->increment_field($r->userid, "noti_count", 1);
				$this->user_model->add_notification(array(
					"userid" => $r->userid,
					"url" => "blog/view/" . $postid,
					"timestamp" => time(),
					"message" => lang("ctn_835") . $blog->title,
					"status" => 0,
					"fromid" => $this->user->info->ID,
					"username" => $r->username,
					"email" => $r->email,
					"email_notification" => $r->email_notification
					)
				);
			}
		}

		if($post_to_timeline && $status == 1) {
			// Make post
			// Add a feed post
			$postid = $this->feed_model->add_post(array(
				"userid" => $this->user->info->ID,
				"blog_postid" => $postid,
				"timestamp" => time(),
				"post_as" => "user",
				)
			);
		}

		$this->session->set_flashdata("globalmsg", lang("success_110"));
		redirect(site_url("blog/your"));

	}


	public function edit_post($id) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$id = intval($id);
		$post = $this->blog_model->get_post($id);
		if($post->num_rows() == 0) {
			$this->template->error(lang("error_173"));
		}
		$post = $post->row();

		$blog = $this->blog_model->get_user_blog($this->user->info->ID);
		if($blog->num_rows() == 0) {
			$this->template->error(lang("error_177"));
		}
		$blog = $blog->row();

		if($blog->ID != $post->blogid) {
			$this->template->error(lang("error_180"));
		}

		$this->template->loadContent("blog/edit_post.php", array(
			"blog" => $blog,
			"post" => $post
			)
		);


	}

	public function edit_post_pro($id) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$id = intval($id);
		$post = $this->blog_model->get_post($id);
		if($post->num_rows() == 0) {
			$this->template->error(lang("error_173"));
		}
		$post = $post->row();

		$blog = $this->blog_model->get_user_blog($this->user->info->ID);
		if($blog->num_rows() == 0) {
			$this->template->error(lang("error_177"));
		}
		$blog = $blog->row();

		if($blog->ID != $post->blogid) {
			$this->template->error(lang("error_180"));
		}

		$title = $this->common->nohtml($this->input->post("title"));
		$status = intval($this->input->post("status"));
		$blog_post = $this->lib_filter->go($this->input->post("blog_post"));
		$post_to_timeline = intval($this->input->post("post_to_timeline"));

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

		if($status == 1 && $post->timestamp == 0) {
			// Alert subs
			$subscribers = $this->blog_model->get_subscribers($blog->ID);
			foreach($subscribers->result() as $r) {
				// Send notification
				// Notification
				$this->user_model->increment_field($r->userid, "noti_count", 1);
				$this->user_model->add_notification(array(
					"userid" => $r->userid,
					"url" => "blog/view/" . $id,
					"timestamp" => time(),
					"message" => lang("ctn_835") . $blog->title,
					"status" => 0,
					"fromid" => $this->user->info->ID,
					"username" => $r->username,
					"email" => $r->email,
					"email_notification" => $r->email_notification
					)
				);
			}
		}

		if($post_to_timeline && $status == 1 && $post->timestamp == 0) {
			// Make post
			// Add a feed post
			$postid = $this->feed_model->add_post(array(
				"userid" => $this->user->info->ID,
				"blog_postid" => $id,
				"timestamp" => time(),
				"post_as" => "user",
				)
			);
		}

		$this->session->set_flashdata("globalmsg", lang("success_105"));
		redirect(site_url("blog/your"));
	}

	public function delete_post($id, $hash) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$post = $this->blog_model->get_post($id);
		if($post->num_rows() == 0) {
			$this->template->error(lang("error_173"));
		}
		$post = $post->row();

		$blog = $this->blog_model->get_user_blog($this->user->info->ID);
		if($blog->num_rows() == 0) {
			$this->template->error(lang("error_177"));
		}
		$blog = $blog->row();

		if($blog->ID != $post->blogid) {
			$this->template->error(lang("error_180"));
		}

		$this->blog_model->delete_post($id);
		$this->session->set_flashdata("globalmsg", lang("success_106"));
		redirect(site_url("blog/your"));

	}

	public function view($id) 
	{
		$id = intval($id);
		$post = $this->blog_model->get_post($id);
		if($post->num_rows() == 0) {
			$this->template->error(lang("error_173"));
		}
		$post = $post->row();

		$blog = $this->blog_model->get_blog($post->blogid);
		if($blog->num_rows() == 0) {
			$this->template->error(lang("error_177"));
		}
		$blog = $blog->row();

		if($post->status == 0) {
			// Check blog owner
			if($blog->ID != $post->blogid) {
				$this->template->error(lang("error_181"));
			}
		}

		$author = $this->user_model->get_user_by_id($blog->userid);
		if($author->num_rows() == 0) {
			$author = null;
		} else {
			$author = $author->row();
		}

		if($this->user->loggedin) {
			// check user is friend
			$flags = $this->check_friend($this->user->info->ID, $blog->userid);
		} else {
			$flags = array("friend_flag" => false, "request_flag" => false);
		}

		if(!$this->settings->info->public_blogs) {
			if(!$this->user->loggedin) {
				redirect(site_url("login"));
			}
		}
		if($blog->private && !$flags['friend_flag'] && $this->user->info->ID != $blog->userid) {
			$this->template->error(lang("error_182"));
		}

		$this->blog_model->update_post($id, array(
			"views" => $post->views + 1
			)
		);

		$this->template->loadContent("blog/view_post.php", array(
			"blog" => $blog,
			"post" => $post,
			"author" => $author
			)
		);
	}

	public function view_blog($id, $page=0) 
	{
		$id = intval($id);
		$blog = $this->blog_model->get_blog($id);
		if($blog->num_rows() == 0) {
			$this->template->error(lang("error_171"));
		}
		$blog = $blog->row();

		if($this->user->loggedin) {
			// check user is friend
			$flags = $this->check_friend($this->user->info->ID, $blog->userid);
			$check = $this->blog_model->check_user_subscriber($id, $this->user->info->ID);
		} else {
			$flags = array("friend_flag" => false, "request_flag" => false);
			$check = null;
		}

		if(!$this->settings->info->public_blogs) {
			if(!$this->user->loggedin) {
				redirect(site_url("login"));
			}
		}

		if($blog->private && !$flags['friend_flag'] && $this->user->info->ID != $blog->userid) {
			$this->template->error(lang("error_182"));
		}

		$total_posts = $this->blog_model
			->get_total_blog_posts_published($blog->ID);

		$page = intval($page);
		$posts = $this->blog_model->get_blog_posts_published($blog->ID, $page);

		$this->load->library('pagination');
		$config['base_url'] = site_url("blog/view_blog/" . $id);
		$config['total_rows'] = $total_posts;
		$config['per_page'] = 10;
		$config['uri_segment'] = 4;

		include (APPPATH . "/config/page_config.php");

		$this->pagination->initialize($config); 




		$this->template->loadContent("blog/view_blog.php", array(
			"blog" => $blog,
			"posts" => $posts,
			"total_posts" => $total_posts,
			"check" => $check
			)
		);
	}

	public function new_posts($page=0) 
	{
		$page = intval($page);
		$posts = $this->blog_model->get_new_public_posts($page);

		$total_posts = $this->blog_model
			->get_total_public_posts();

		$this->load->library('pagination');
		$config['base_url'] = site_url("blog/new_posts");
		$config['total_rows'] = $total_posts;
		$config['per_page'] = 10;
		$config['uri_segment'] = 3;

		include (APPPATH . "/config/page_config.php");

		$this->pagination->initialize($config); 

		$this->template->loadContent("blog/new_post.php", array(
			"posts" => $posts,
			"total_posts" => $total_posts
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

	public function subscribe($blogid, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$blogid = intval($blogid);
		$blog = $this->blog_model->get_blog($blogid);
		if($blog->num_rows() == 0) {
			$this->template->error(lang("error_171"));
		}
		$blog = $blog->row();

		if(!$this->user->loggedin) {
			$this->template->error(lang("error_183"));
		} 

		$check = $this->blog_model->check_user_subscriber($blogid, $this->user->info->ID);
		if($check->num_rows() > 0) {
			$this->template->error(lang("error_184"));
		}

		// Add
		$this->blog_model->add_subscriber(array(
			"blogid" => $blogid,
			"userid" => $this->user->info->ID,
			"timestamp" => time()
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_111"));
		redirect(site_url("blog/view_blog/" . $blogid));


	}

	public function unsubscribe($blogid, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error("Invalid Hash!");
		}
		$blogid = intval($blogid);
		$blog = $this->blog_model->get_blog($blogid);
		if($blog->num_rows() == 0) {
			$this->template->error(lang("error_171"));
		}
		$blog = $blog->row();

		if(!$this->user->loggedin) {
			$this->template->error(lang("error_183"));
		} 

		$check = $this->blog_model->check_user_subscriber($blogid, $this->user->info->ID);
		if($check->num_rows() == 0) {
			$this->template->error(lang("error_185"));
		}

		$check = $check->row();

		$this->blog_model->delete_subscriber($check->ID);

		$this->session->set_flashdata("globalmsg", lang("success_112"));
		redirect(site_url("blog/view_blog/" . $blogid));


	}

}

?>