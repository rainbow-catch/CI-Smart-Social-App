-- phpMyAdmin SQL Dump
-- version 4.6.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 29, 2020 at 09:54 AM
-- Server version: 5.5.62-0+deb8u1-log
-- PHP Version: 7.0.33-1~dotdeb+8.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";



--
-- Database: `dev_social`
--

-- --------------------------------------------------------

--
-- Table structure for table `calendar_events`
--

CREATE TABLE `calendar_events` (
  `ID` int(11) NOT NULL,
  `title` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `description` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `userid` int(11) NOT NULL,
  `pageid` int(11) NOT NULL,
  `location` varchar(500) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calendar_event_users`
--

CREATE TABLE `calendar_event_users` (
  `ID` int(11) NOT NULL,
  `eventid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_fields`
--

CREATE TABLE `custom_fields` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `options` varchar(2000) COLLATE utf8_unicode_ci NOT NULL,
  `required` int(11) NOT NULL,
  `profile` int(11) NOT NULL,
  `edit` int(11) NOT NULL,
  `help_text` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `register` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `ID` int(11) NOT NULL,
  `title` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `hook` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`ID`, `title`, `message`, `hook`, `language`) VALUES
(1, 'Forgot Your Password', '<p>Dear [NAME],<br />\r\n<br />\r\nSomeone (hopefully you) requested a password reset at [SITE_URL].<br />\r\n<br />\r\nTo reset your password, please follow the following link: [EMAIL_LINK]<br />\r\n<br />\r\nIf you did not reset your password, please kindly ignore this email.<br />\r\n<br />\r\nYours,<br />\r\n[SITE_NAME]</p>\r\n', 'forgot_password', 'english'),
(2, 'Email Activation', '<p>Dear [NAME],<br />\r\n<br />\r\nSomeone (hopefully you) has registered an account on [SITE_NAME] using this email address.<br />\r\n<br />\r\nPlease activate the account by following this link: [EMAIL_LINK]<br />\r\n<br />\r\nIf you did not register an account, please kindly ignore this email.<br />\r\n<br />\r\nYours,<br />\r\n[SITE_NAME]</p>\r\n', 'email_activation', 'english'),
(3, 'New Notification', 'Dear [NAME],<br /><br />\n\nYou have a new notification waiting for you at [SITE_NAME]. You can view it by logging into:<br /><br />\n\n[SITE_URL]<br /><br />\n\nYours,<br />\n[SITE_NAME]', 'new_notification', 'english'),
(4, 'Member Invite', 'Dear [EMAIL],<br /><br />\r\n\r\nYou have been invited to register at our site: <a href="[SITE_URL]">[SITE_URl]</a>.<br /><br />\r\n\r\nPlease click the link above, or copy and paste it into your browser to register an account.<br /><br />\r\n\r\nThanks,<br />\r\n[SITE_NAME]', 'member_invite', 'english');

-- --------------------------------------------------------

--
-- Table structure for table `feed_hashtags`
--

CREATE TABLE `feed_hashtags` (
  `ID` int(11) NOT NULL,
  `hashtag` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feed_item`
--

CREATE TABLE `feed_item` (
  `ID` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `content` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  `imageid` int(11) NOT NULL,
  `videoid` int(11) NOT NULL,
  `likes` int(11) NOT NULL,
  `comments` int(11) NOT NULL,
  `location` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `user_flag` int(11) NOT NULL,
  `profile_userid` int(11) NOT NULL,
  `template` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pageid` int(11) NOT NULL,
  `hide_profile` int(11) NOT NULL,
  `post_as` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `eventid` int(11) NOT NULL,
  `site_flag` int(11) NOT NULL,
  `pollid` int(11) NOT NULL,
  `blog_postid` int(11) NOT NULL,
  `share_postid` int(11) NOT NULL,
  `member_only` int(11) NOT NULL,
  `dislikes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feed_item_comments`
--

CREATE TABLE `feed_item_comments` (
  `ID` int(11) NOT NULL,
  `postid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `comment` varchar(3000) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  `likes` int(11) NOT NULL,
  `commentid` int(11) NOT NULL,
  `replies` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feed_item_comment_likes`
--

CREATE TABLE `feed_item_comment_likes` (
  `ID` int(11) NOT NULL,
  `commentid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feed_item_images`
--

CREATE TABLE `feed_item_images` (
  `ID` int(11) NOT NULL,
  `postid` int(11) NOT NULL,
  `imageid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feed_item_polls`
--

CREATE TABLE `feed_item_polls` (
  `ID` int(11) NOT NULL,
  `postid` int(11) NOT NULL,
  `question` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `votes` int(11) NOT NULL,
  `type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feed_item_poll_answers`
--

CREATE TABLE `feed_item_poll_answers` (
  `ID` int(11) NOT NULL,
  `pollid` int(11) NOT NULL,
  `answer` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `votes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feed_item_poll_votes`
--

CREATE TABLE `feed_item_poll_votes` (
  `ID` int(11) NOT NULL,
  `pollid` int(11) NOT NULL,
  `answerid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feed_item_subscribers`
--

CREATE TABLE `feed_item_subscribers` (
  `ID` int(11) NOT NULL,
  `postid` int(11) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feed_item_urls`
--

CREATE TABLE `feed_item_urls` (
  `ID` int(11) NOT NULL,
  `postid` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(1000) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feed_item_users`
--

CREATE TABLE `feed_item_users` (
  `ID` int(11) NOT NULL,
  `postid` int(11) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feed_likes`
--

CREATE TABLE `feed_likes` (
  `ID` int(11) NOT NULL,
  `postid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feed_tagged_users`
--

CREATE TABLE `feed_tagged_users` (
  `ID` int(11) NOT NULL,
  `postid` int(11) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `home_stats`
--

CREATE TABLE `home_stats` (
  `ID` int(11) NOT NULL,
  `google_members` int(11) NOT NULL DEFAULT '0',
  `facebook_members` int(11) NOT NULL DEFAULT '0',
  `twitter_members` int(11) NOT NULL DEFAULT '0',
  `total_members` int(11) NOT NULL DEFAULT '0',
  `new_members` int(11) NOT NULL DEFAULT '0',
  `active_today` int(11) NOT NULL DEFAULT '0',
  `timestamp` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `home_stats`
--

INSERT INTO `home_stats` (`ID`, `google_members`, `facebook_members`, `twitter_members`, `total_members`, `new_members`, `active_today`, `timestamp`) VALUES
(1, 0, 0, 0, 1, 1, 1, 1499160358);

-- --------------------------------------------------------

--
-- Table structure for table `invites`
--

CREATE TABLE `invites` (
  `ID` int(11) NOT NULL,
  `email` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `expires` int(11) NOT NULL,
  `expire_upon_use` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `user_registered` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `bypass_register` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ipn_log`
--

CREATE TABLE `ipn_log` (
  `ID` int(11) NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `IP` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ip_block`
--

CREATE TABLE `ip_block` (
  `ID` int(11) NOT NULL,
  `IP` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `reason` varchar(1000) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `live_chat`
--

CREATE TABLE `live_chat` (
  `ID` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `title` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `last_reply_userid` int(11) NOT NULL,
  `last_replyid` int(11) NOT NULL,
  `last_reply_timestamp` int(11) NOT NULL,
  `posts` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `live_chat_messages`
--

CREATE TABLE `live_chat_messages` (
  `ID` int(11) NOT NULL,
  `chatid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `live_chat_users`
--

CREATE TABLE `live_chat_users` (
  `ID` int(11) NOT NULL,
  `chatid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  `unread` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `ID` int(11) NOT NULL,
  `IP` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `username` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `count` int(11) NOT NULL DEFAULT '0',
  `timestamp` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `pageviews` int(11) NOT NULL,
  `likes` int(11) NOT NULL,
  `profile_header` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `profile_avatar` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `posting_status` int(11) NOT NULL,
  `location` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `nonmembers_view` int(11) NOT NULL,
  `members` int(11) NOT NULL,
  `allow_share` int(11) NOT NULL,
  `pay_to_join` int(11) NOT NULL,
  `pay_to_userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `page_categories`
--

CREATE TABLE `page_categories` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `page_categories`
--

INSERT INTO `page_categories` (`ID`, `name`, `description`) VALUES
(1, 'Default', 'default');

-- --------------------------------------------------------

--
-- Table structure for table `page_invites`
--

CREATE TABLE `page_invites` (
  `ID` int(11) NOT NULL,
  `pageid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `fromid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `page_users`
--

CREATE TABLE `page_users` (
  `ID` int(11) NOT NULL,
  `pageid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `roleid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset`
--

CREATE TABLE `password_reset` (
  `ID` int(11) NOT NULL,
  `userid` int(11) NOT NULL DEFAULT '0',
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `IP` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_logs`
--

CREATE TABLE `payment_logs` (
  `ID` int(11) NOT NULL,
  `userid` int(11) NOT NULL DEFAULT '0',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `email` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `processor` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hash` varchar(500) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_plans`
--

CREATE TABLE `payment_plans` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `hexcolor` varchar(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fontcolor` varchar(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `days` int(11) NOT NULL DEFAULT '0',
  `sales` int(11) NOT NULL DEFAULT '0',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `payment_plans`
--

INSERT INTO `payment_plans` (`ID`, `name`, `hexcolor`, `fontcolor`, `cost`, `days`, `sales`, `description`, `icon`) VALUES
(2, 'BASIC', '65E0EB', 'FFFFFF', '3.00', 30, 8, 'This is the basic plan which gives you a introduction to our Premium Plans', 'glyphicon glyphicon-heart-empty'),
(3, 'Professional', '55CD7B', 'FFFFFF', '7.99', 90, 3, 'Get all the benefits of basic at a cheaper price and gain content for longer.', 'glyphicon glyphicon-piggy-bank'),
(4, 'LIFETIME', 'EE5782', 'FFFFFF', '300.00', 0, 1, 'Become a premium member for life and have access to all our premium content.', 'glyphicon glyphicon-gift');

-- --------------------------------------------------------

--
-- Table structure for table `profile_comments`
--

CREATE TABLE `profile_comments` (
  `ID` int(11) NOT NULL,
  `profileid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `promoted_posts`
--

CREATE TABLE `promoted_posts` (
  `ID` int(11) NOT NULL,
  `postid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `pageviews` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `relationship_requests`
--

CREATE TABLE `relationship_requests` (
  `ID` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `friendid` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `relationship_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `ID` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `pageid` int(11) NOT NULL,
  `reason` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  `fromid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reset_log`
--

CREATE TABLE `reset_log` (
  `ID` int(11) NOT NULL,
  `IP` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `timestamp` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rotation_ads`
--

CREATE TABLE `rotation_ads` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `advert` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `pageviews` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `cost` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `site_layouts`
--

CREATE TABLE `site_layouts` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `layout_path` varchar(500) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `site_layouts`
--

INSERT INTO `site_layouts` (`ID`, `name`, `layout_path`) VALUES
(1, 'Basic', 'layout/themes/layout.php'),
(2, 'Titan', 'layout/themes/titan_layout.php'),
(3, 'Dark Fire', 'layout/themes/dark_fire_layout.php'),
(4, 'Light Blue', 'layout/themes/light_blue_layout.php');

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `ID` int(11) NOT NULL,
  `site_name` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `site_desc` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `upload_path` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `upload_path_relative` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `site_email` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `site_logo` varchar(1000) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'logo.png',
  `register` int(11) NOT NULL,
  `disable_captcha` int(11) NOT NULL,
  `date_format` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `avatar_upload` int(11) NOT NULL DEFAULT '1',
  `file_types` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `twitter_consumer_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `twitter_consumer_secret` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `disable_social_login` int(11) NOT NULL,
  `facebook_app_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `facebook_app_secret` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `google_client_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `google_client_secret` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_size` int(11) NOT NULL,
  `paypal_email` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `paypal_currency` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'USD',
  `payment_enabled` int(11) NOT NULL,
  `payment_symbol` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '$',
  `global_premium` int(11) NOT NULL,
  `install` int(11) NOT NULL DEFAULT '1',
  `login_protect` int(11) NOT NULL,
  `activate_account` int(11) NOT NULL,
  `default_user_role` int(11) NOT NULL,
  `secure_login` int(11) NOT NULL,
  `stripe_secret_key` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `stripe_publish_key` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `google_recaptcha` int(11) NOT NULL,
  `google_recaptcha_secret` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `google_recaptcha_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `logo_option` int(11) NOT NULL,
  `layout` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `profile_comments` int(11) NOT NULL,
  `avatar_width` int(11) NOT NULL,
  `avatar_height` int(11) NOT NULL,
  `cache_time` int(11) NOT NULL,
  `checkout2_secret` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `checkout2_accountno` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_display_type` int(11) NOT NULL,
  `page_slugs` int(11) NOT NULL,
  `calendar_picker_format` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `disable_chat` int(11) NOT NULL,
  `enable_google_ads_pages` int(11) NOT NULL,
  `enable_google_ads_feed` int(11) NOT NULL,
  `enable_rotation_ads_feed` int(11) NOT NULL,
  `enable_rotation_ads_pages` int(11) NOT NULL,
  `credit_price_pageviews` int(11) NOT NULL,
  `rotation_ad_alert_user` int(11) NOT NULL,
  `enable_promote_post` int(11) NOT NULL,
  `resize_avatar` int(11) NOT NULL,
  `verified_cost` decimal(10,2) NOT NULL,
  `enable_verified_buy` int(11) NOT NULL,
  `enable_verified_requests` int(11) NOT NULL DEFAULT '1',
  `public_profiles` int(11) NOT NULL,
  `public_pages` int(11) NOT NULL,
  `public_blogs` int(11) NOT NULL,
  `enable_blogs` int(11) NOT NULL DEFAULT '1',
  `limit_max_photos` int(11) NOT NULL,
  `limit_max_photos_post` int(11) NOT NULL,
  `enable_dislikes` int(11) NOT NULL,
  `enable_google_maps` int(11) NOT NULL,
  `google_maps_api_key` varchar(500) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`ID`, `site_name`, `site_desc`, `upload_path`, `upload_path_relative`, `site_email`, `site_logo`, `register`, `disable_captcha`, `date_format`, `avatar_upload`, `file_types`, `twitter_consumer_key`, `twitter_consumer_secret`, `disable_social_login`, `facebook_app_id`, `facebook_app_secret`, `google_client_id`, `google_client_secret`, `file_size`, `paypal_email`, `paypal_currency`, `payment_enabled`, `payment_symbol`, `global_premium`, `install`, `login_protect`, `activate_account`, `default_user_role`, `secure_login`, `stripe_secret_key`, `stripe_publish_key`, `google_recaptcha`, `google_recaptcha_secret`, `google_recaptcha_key`, `logo_option`, `layout`, `profile_comments`, `avatar_width`, `avatar_height`, `cache_time`, `checkout2_secret`, `checkout2_accountno`, `user_display_type`, `page_slugs`, `calendar_picker_format`, `disable_chat`, `enable_google_ads_pages`, `enable_google_ads_feed`, `enable_rotation_ads_feed`, `enable_rotation_ads_pages`, `credit_price_pageviews`, `rotation_ad_alert_user`, `enable_promote_post`, `resize_avatar`, `verified_cost`, `enable_verified_buy`, `enable_verified_requests`, `public_profiles`, `public_pages`, `public_blogs`, `enable_blogs`, `limit_max_photos`, `limit_max_photos_post`, `enable_dislikes`, `enable_google_maps`, `google_maps_api_key`) VALUES
(1, 'Social Network', 'Welcome to Social Network', '/home/public_html/uploads', 'uploads', 'test@test.com', 'logo.png', 0, 1, 'd/m/Y', 1, 'gif|png|jpg|jpeg', '', '', 0, '', '', '', '', 10028, '', 'USD', 1, '$', 0, 1, 1, 0, 5, 0, '', '', 0, '', '', 0, 'layout/themes/titan_layout.php', 1, 200, 200, 3600, '', '', 0, 0, 'Y/m/d H:i', 0, 0, 0, 0, 0, 1, 1, 1, 1, '5.40', 1, 1, 1, 1, 1, 1, 50, 8, 1, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `IP` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `username` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `first_name` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `last_name` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `avatar` varchar(1000) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default.png',
  `joined` int(11) NOT NULL DEFAULT '0',
  `joined_date` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `online_timestamp` int(11) NOT NULL DEFAULT '0',
  `oauth_provider` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `oauth_id` varchar(1000) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `oauth_token` varchar(1500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `oauth_secret` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email_notification` int(11) NOT NULL DEFAULT '1',
  `aboutme` varchar(1000) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `points` decimal(10,2) NOT NULL DEFAULT '0.00',
  `premium_time` int(11) NOT NULL DEFAULT '0',
  `user_role` int(11) NOT NULL DEFAULT '0',
  `premium_planid` int(11) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '1',
  `activate_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `profile_comments` int(11) NOT NULL DEFAULT '1',
  `profile_views` int(11) NOT NULL,
  `address_1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address_2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `zipcode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `noti_count` int(11) NOT NULL,
  `profile_header` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default_header.png',
  `location_from` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `location_live` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `friends` text COLLATE utf8_unicode_ci NOT NULL,
  `pages` text COLLATE utf8_unicode_ci NOT NULL,
  `profile_view` int(11) NOT NULL,
  `posts_view` int(11) NOT NULL,
  `post_profile` int(11) NOT NULL,
  `allow_friends` int(11) NOT NULL,
  `allow_pages` int(11) NOT NULL,
  `chat_option` int(11) NOT NULL,
  `relationship_status` int(11) NOT NULL,
  `relationship_userid` int(11) NOT NULL,
  `tag_user` int(11) NOT NULL,
  `post_count` int(11) NOT NULL,
  `verified` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_albums`
--

CREATE TABLE `user_albums` (
  `ID` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  `feed_album` int(11) NOT NULL,
  `images` int(11) NOT NULL,
  `pageid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_blogs`
--

CREATE TABLE `user_blogs` (
  `ID` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `userid` int(11) NOT NULL,
  `description` varchar(2000) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  `private` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_blog_posts`
--

CREATE TABLE `user_blog_posts` (
  `ID` int(11) NOT NULL,
  `blogid` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `body` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `last_updated` int(11) NOT NULL,
  `image` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `views` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_blog_subscribers`
--

CREATE TABLE `user_blog_subscribers` (
  `ID` int(11) NOT NULL,
  `blogid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_custom_fields`
--

CREATE TABLE `user_custom_fields` (
  `ID` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `fieldid` int(11) NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_data`
--

CREATE TABLE `user_data` (
  `ID` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `twitter` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `facebook` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `google` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `linkedin` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(500) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_events`
--

CREATE TABLE `user_events` (
  `ID` int(11) NOT NULL,
  `IP` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `event` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `timestamp` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_friends`
--

CREATE TABLE `user_friends` (
  `ID` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `friendid` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_friend_requests`
--

CREATE TABLE `user_friend_requests` (
  `ID` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `friendid` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

CREATE TABLE `user_groups` (
  `ID` int(11) NOT NULL,
  `name` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `default` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_groups`
--

INSERT INTO `user_groups` (`ID`, `name`, `default`) VALUES
(1, 'Default Group', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_group_users`
--

CREATE TABLE `user_group_users` (
  `ID` int(11) NOT NULL,
  `groupid` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_images`
--

CREATE TABLE `user_images` (
  `ID` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `file_name` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `file_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `extension` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `file_size` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `file_url` varchar(2000) COLLATE utf8_unicode_ci NOT NULL,
  `albumid` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(1000) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_notifications`
--

CREATE TABLE `user_notifications` (
  `ID` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `url` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `message` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `fromid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `admin` int(11) NOT NULL DEFAULT '0',
  `admin_settings` int(11) NOT NULL DEFAULT '0',
  `admin_members` int(11) NOT NULL DEFAULT '0',
  `admin_payment` int(11) NOT NULL DEFAULT '0',
  `banned` int(11) NOT NULL,
  `live_chat` int(11) NOT NULL,
  `page_creator` int(11) NOT NULL,
  `page_admin` int(11) NOT NULL,
  `post_admin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`ID`, `name`, `admin`, `admin_settings`, `admin_members`, `admin_payment`, `banned`, `live_chat`, `page_creator`, `page_admin`, `post_admin`) VALUES
(1, 'Admin', 1, 0, 0, 0, 0, 0, 0, 0, 0),
(2, 'Member Manager', 0, 0, 1, 0, 0, 0, 0, 0, 0),
(3, 'Admin Settings', 0, 1, 0, 0, 0, 0, 0, 0, 0),
(4, 'Admin Payments', 0, 0, 0, 1, 0, 0, 0, 0, 0),
(5, 'Member', 0, 0, 0, 0, 0, 1, 1, 0, 0),
(6, 'Banned', 0, 0, 0, 0, 1, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_role_permissions`
--

CREATE TABLE `user_role_permissions` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `classname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hook` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_role_permissions`
--

INSERT INTO `user_role_permissions` (`ID`, `name`, `description`, `classname`, `hook`) VALUES
(1, 'ctn_308', 'ctn_308', 'admin', 'admin'),
(2, 'ctn_309', 'ctn_309', 'admin', 'admin_settings'),
(3, 'ctn_310', 'ctn_310', 'admin', 'admin_members'),
(4, 'ctn_311', 'ctn_311', 'admin', 'admin_payment'),
(5, 'ctn_33', 'ctn_33', 'banned', 'banned'),
(6, 'ctn_430', 'ctn_431', 'site', 'live_chat'),
(7, 'ctn_432', 'ctn_435', 'page', 'page_creator'),
(8, 'ctn_433', 'ctn_436', 'page', 'page_admin'),
(9, 'ctn_434', 'ctn_437', 'page', 'post_admin');

-- --------------------------------------------------------

--
-- Table structure for table `user_saved_posts`
--

CREATE TABLE `user_saved_posts` (
  `ID` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `postid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_videos`
--

CREATE TABLE `user_videos` (
  `ID` int(11) NOT NULL,
  `file_name` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `file_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `userid` int(11) NOT NULL,
  `extension` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `file_size` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `youtube_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `verified_requests`
--

CREATE TABLE `verified_requests` (
  `ID` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `about` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `calendar_events`
--
ALTER TABLE `calendar_events`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `calendar_event_users`
--
ALTER TABLE `calendar_event_users`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indexes for table `custom_fields`
--
ALTER TABLE `custom_fields`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `feed_hashtags`
--
ALTER TABLE `feed_hashtags`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `feed_item`
--
ALTER TABLE `feed_item`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `feed_item_comments`
--
ALTER TABLE `feed_item_comments`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `feed_item_comment_likes`
--
ALTER TABLE `feed_item_comment_likes`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `feed_item_images`
--
ALTER TABLE `feed_item_images`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `feed_item_polls`
--
ALTER TABLE `feed_item_polls`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `feed_item_poll_answers`
--
ALTER TABLE `feed_item_poll_answers`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `feed_item_poll_votes`
--
ALTER TABLE `feed_item_poll_votes`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `feed_item_subscribers`
--
ALTER TABLE `feed_item_subscribers`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `feed_item_urls`
--
ALTER TABLE `feed_item_urls`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `feed_item_users`
--
ALTER TABLE `feed_item_users`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `feed_likes`
--
ALTER TABLE `feed_likes`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `feed_tagged_users`
--
ALTER TABLE `feed_tagged_users`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `home_stats`
--
ALTER TABLE `home_stats`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `invites`
--
ALTER TABLE `invites`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ipn_log`
--
ALTER TABLE `ipn_log`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ip_block`
--
ALTER TABLE `ip_block`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `live_chat`
--
ALTER TABLE `live_chat`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `live_chat_messages`
--
ALTER TABLE `live_chat_messages`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `live_chat_users`
--
ALTER TABLE `live_chat_users`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `page_categories`
--
ALTER TABLE `page_categories`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `page_invites`
--
ALTER TABLE `page_invites`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `page_users`
--
ALTER TABLE `page_users`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `password_reset`
--
ALTER TABLE `password_reset`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `payment_logs`
--
ALTER TABLE `payment_logs`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `payment_plans`
--
ALTER TABLE `payment_plans`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `profile_comments`
--
ALTER TABLE `profile_comments`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `promoted_posts`
--
ALTER TABLE `promoted_posts`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `relationship_requests`
--
ALTER TABLE `relationship_requests`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `reset_log`
--
ALTER TABLE `reset_log`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `rotation_ads`
--
ALTER TABLE `rotation_ads`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `site_layouts`
--
ALTER TABLE `site_layouts`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_albums`
--
ALTER TABLE `user_albums`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_blogs`
--
ALTER TABLE `user_blogs`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_blog_posts`
--
ALTER TABLE `user_blog_posts`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_blog_subscribers`
--
ALTER TABLE `user_blog_subscribers`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_custom_fields`
--
ALTER TABLE `user_custom_fields`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_data`
--
ALTER TABLE `user_data`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_events`
--
ALTER TABLE `user_events`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_friends`
--
ALTER TABLE `user_friends`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_friend_requests`
--
ALTER TABLE `user_friend_requests`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_groups`
--
ALTER TABLE `user_groups`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_group_users`
--
ALTER TABLE `user_group_users`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_images`
--
ALTER TABLE `user_images`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_role_permissions`
--
ALTER TABLE `user_role_permissions`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_saved_posts`
--
ALTER TABLE `user_saved_posts`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_videos`
--
ALTER TABLE `user_videos`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `verified_requests`
--
ALTER TABLE `verified_requests`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `calendar_events`
--
ALTER TABLE `calendar_events`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `calendar_event_users`
--
ALTER TABLE `calendar_event_users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `custom_fields`
--
ALTER TABLE `custom_fields`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `feed_hashtags`
--
ALTER TABLE `feed_hashtags`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `feed_item`
--
ALTER TABLE `feed_item`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `feed_item_comments`
--
ALTER TABLE `feed_item_comments`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `feed_item_comment_likes`
--
ALTER TABLE `feed_item_comment_likes`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `feed_item_images`
--
ALTER TABLE `feed_item_images`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `feed_item_polls`
--
ALTER TABLE `feed_item_polls`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `feed_item_poll_answers`
--
ALTER TABLE `feed_item_poll_answers`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `feed_item_poll_votes`
--
ALTER TABLE `feed_item_poll_votes`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `feed_item_subscribers`
--
ALTER TABLE `feed_item_subscribers`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `feed_item_urls`
--
ALTER TABLE `feed_item_urls`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `feed_item_users`
--
ALTER TABLE `feed_item_users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `feed_likes`
--
ALTER TABLE `feed_likes`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `feed_tagged_users`
--
ALTER TABLE `feed_tagged_users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `home_stats`
--
ALTER TABLE `home_stats`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `invites`
--
ALTER TABLE `invites`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ipn_log`
--
ALTER TABLE `ipn_log`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ip_block`
--
ALTER TABLE `ip_block`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `live_chat`
--
ALTER TABLE `live_chat`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `live_chat_messages`
--
ALTER TABLE `live_chat_messages`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `live_chat_users`
--
ALTER TABLE `live_chat_users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `page_categories`
--
ALTER TABLE `page_categories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `page_invites`
--
ALTER TABLE `page_invites`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `page_users`
--
ALTER TABLE `page_users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `password_reset`
--
ALTER TABLE `password_reset`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payment_logs`
--
ALTER TABLE `payment_logs`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payment_plans`
--
ALTER TABLE `payment_plans`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `profile_comments`
--
ALTER TABLE `profile_comments`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `promoted_posts`
--
ALTER TABLE `promoted_posts`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `relationship_requests`
--
ALTER TABLE `relationship_requests`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `reset_log`
--
ALTER TABLE `reset_log`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rotation_ads`
--
ALTER TABLE `rotation_ads`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `site_layouts`
--
ALTER TABLE `site_layouts`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_albums`
--
ALTER TABLE `user_albums`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_blogs`
--
ALTER TABLE `user_blogs`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_blog_posts`
--
ALTER TABLE `user_blog_posts`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_blog_subscribers`
--
ALTER TABLE `user_blog_subscribers`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_custom_fields`
--
ALTER TABLE `user_custom_fields`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_data`
--
ALTER TABLE `user_data`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_events`
--
ALTER TABLE `user_events`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_friends`
--
ALTER TABLE `user_friends`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_friend_requests`
--
ALTER TABLE `user_friend_requests`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_groups`
--
ALTER TABLE `user_groups`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `user_group_users`
--
ALTER TABLE `user_group_users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_images`
--
ALTER TABLE `user_images`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_notifications`
--
ALTER TABLE `user_notifications`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `user_role_permissions`
--
ALTER TABLE `user_role_permissions`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `user_saved_posts`
--
ALTER TABLE `user_saved_posts`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_videos`
--
ALTER TABLE `user_videos`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `verified_requests`
--
ALTER TABLE `verified_requests`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
