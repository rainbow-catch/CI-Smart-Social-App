<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Settings 
{

	var $info=array();

	var $version = "1.5";

	public function __construct() 
	{
		$CI =& get_instance();
		$site = $CI->db->select("site_name,site_desc,site_email,
			upload_path_relative, upload_path, site_logo, register,
			 disable_captcha, date_format, avatar_upload, file_types,
			 twitter_consumer_key, twitter_consumer_secret, disable_social_login
			 , facebook_app_id, facebook_app_secret, google_client_id,
			 google_client_secret, file_size, paypal_email, paypal_currency,
			 payment_enabled, payment_symbol, global_premium, install,
			 login_protect, activate_account, default_user_role,
			 secure_login, stripe_secret_key, stripe_publish_key,
			 google_recaptcha, google_recaptcha_secret, google_recaptcha_key,
			 logo_option, layout, profile_comments, avatar_width, avatar_height,
			 cache_time, checkout2_secret, checkout2_accountno,
			 user_display_type, page_slugs, calendar_picker_format,
			 disable_chat, calendar_picker_format, enable_google_ads_pages,
			 enable_google_ads_feed, enable_rotation_ads_feed, 
			 enable_rotation_ads_pages, credit_price_pageviews, 
			 rotation_ad_alert_user, enable_promote_post, resize_avatar,
			 verified_cost, enable_verified_buy, enable_verified_requests,
			 public_profiles, public_pages, public_blogs, enable_blogs,
			 limit_max_photos, limit_max_photos_post, enable_dislikes,
			 google_maps_api_key, enable_google_maps")
		->where("ID", 1)
		->get("site_settings");
		
		if($site->num_rows() == 0) {
			$CI->template->error(
				"You are missing the site settings database row."
			);
		} else {
			$this->info = $site->row();
		}
	}

}

?>