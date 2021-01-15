<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Funds extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("funds_model");
		$this->load->model("home_model");

		if(!$this->user->loggedin) $this->template->error(lang("error_1"));

		$this->template->set_error_view("error/login_error.php");
		$this->template->set_layout("client/themes/titan.php");
	}

	public function index() 
	{
		$this->template->loadData("activeLink", 
			array("funds" => array("general" => 1)));
		if(!$this->settings->info->payment_enabled) {
			$this->template->error(lang("error_60"));
		}

		if(!empty($this->settings->info->stripe_secret_key) && !empty($this->settings->info->stripe_publish_key)) {
			// Stripe
			require_once(APPPATH . 'third_party/stripe/init.php');

			$stripe = array(
			  "secret_key"      => $this->settings->info->stripe_secret_key,
			  "publishable_key" => $this->settings->info->stripe_publish_key
			);

			\Stripe\Stripe::setApiKey($stripe['secret_key']);
		} else {
			$stripe = null;
		}

		$this->template->loadContent("funds/index.php", array(
			"stripe" => $stripe
			)
		);
	}

	public function spend() 
	{
		$this->template->loadContent("funds/spend.php", array(
			)
		);
	}

	public function verified() 
	{
		if(!$this->settings->info->enable_verified_buy) {
			$this->template->error(lang("error_166"));
		}
		$this->template->loadContent("funds/verified.php", array(
			)
		);
	}

	public function verified_pro() 
	{
		if(!$this->settings->info->enable_verified_buy) {
			$this->template->error(lang("error_166"));
		}

		if($this->user->info->verified) {
			$this->template->error(lang("error_167"));
		}

		$cost = $this->settings->info->verified_cost;

		if($this->user->info->points < $cost) {
			$this->template->error(lang("error_161") . $cost);
		}

		// Add
		$this->user_model->update_user($this->user->info->ID, array("verified" => 1,
			"points" => $this->user->info->points - $cost));

		$this->session->set_flashdata("globalmsg", lang("success_99"));
		redirect(site_url("funds/spend"));
	}

	public function submit_ad() 
	{
		$this->template->loadContent("funds/submit_ad.php", array(
			)
		);
	}

	public function submit_ad_pro() 
	{
		$name = $this->common->nohtml($this->input->post("name"));
		$advert = $this->lib_filter->go($this->input->post("advert"));
		$pageviews = intval($this->input->post("pageviews"));

		if(empty($name)) {
			$this->template->error(lang("error_168"));
		}

		if($pageviews < 1000) {
			$this->template->error(lang("error_160"));
		}

		// Cost
		$amount = floatval($pageviews/1000);
		$cost = $amount * $this->settings->info->credit_price_pageviews;

		if($this->user->info->points < $cost) {
			$this->template->error(lang("error_161") . $cost);
		}

		$this->home_model->add_rotation_ad(array(
			"name" => $name,
			"advert" => $advert,
			"pageviews" => $pageviews,
			"userid" => $this->user->info->ID,
			"timestamp" => time()
			)
		);

		$this->user_model->update_user($this->user->info->ID, array(
			"points" => $this->user->info->points - $cost
			)
		);

		// Alert admin
		if($this->settings->info->rotation_ad_alert_user > 0) {
			$user = $this->user_model->get_user_by_id($this->settings->info->rotation_ad_alert_user);
			if($user->num_rows() > 0) {
				$user = $user->row(); 

				$this->user_model->increment_field($user->ID, "noti_count", 1);
				$this->user_model->add_notification(array(
					"userid" => $user->ID,
					"url" => "admin/rotation_ads",
					"timestamp" => time(),
					"message" => lang("ctn_755") . " " . $this->user->info->username,
					"status" => 0,
					"fromid" => $this->user->info->ID,
					"username" => $user->username,
					"email" => $user->email,
					"email_notification" => $user->email_notification
					)
				);
			}
		}

		$this->session->set_flashdata("globalmsg", lang("success_100"));
		redirect(site_url("funds/submit_ad"));
	}

	public function payment_log() 
	{
		$this->template->loadContent("funds/payment_log.php", array(
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
			$this->user_model
				->get_total_payment_logs_count($this->user->info->ID)
		);
		$logs = $this->user_model->get_payment_logs($this->user->info->ID, $this->datatables);

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

	public function plans() 
	{
		$this->template->loadData("activeLink", 
			array("funds" => array("plans" => 1)));
		if(!$this->settings->info->payment_enabled) {
			$this->template->error(lang("error_60"));
		}

		$plans = $this->funds_model->get_plans();
		$this->template->loadContent("funds/plans.php", array(
			"plans" => $plans
			)
		);
	}

	public function buy_plan($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$plan = $this->funds_model->get_plan($id);
		if($plan->num_rows() == 0) $this->template->error(lang("error_61"));
		$plan = $plan->row();

		// Check user has dolla
		if($this->user->info->points < $plan->cost) {
			$this->template->error(lang("error_62"));
		}

		if($this->user->info->premium_time == -1) {
			$this->template->error(lang("error_63"));
		}

		if($plan->days > 0) {
			$premium_time = $this->user->info->premium_time;
			$time_added = (24*3600) * $plan->days;

			// Check to see if user currently has time.
			if($premium_time > time()) {
				// If plan does not equal current one, then we reset 
				// the timer 
				if($this->user->info->premium_planid != $plan->ID) {
					$premium_time = time() + $time_added;
				} else {
					$premium_time = $premium_time + $time_added;
				}
			} else {
				$premium_time = time() + $time_added;
			}
		} else {
			// Unlimited Time modifier
			$premium_time = -1;
		}

		$this->user->info->points = $this->user->info->points - $plan->cost;

		$this->user_model->update_user($this->user->info->ID, array(
			"premium_time" => $premium_time,
			"points" => $this->user->info->points,
			"premium_planid" => $plan->ID
			)
		);

		$this->funds_model->update_plan($id, array(
			"sales" => $plan->sales + 1
			)
		);

		$this->user_model->add_log(array(
			"userid" => $this->user->info->ID,
			"IP" => $_SERVER['REMOTE_ADDR'],
			"user_agent" => $_SERVER['HTTP_USER_AGENT'],
			"timestamp" => time(),
			"message" => lang("ctn_442") . $plan->name
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_28"));
		redirect(site_url("funds/plans"));
	}

}

?>