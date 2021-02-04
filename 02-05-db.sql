/*
 Navicat Premium Data Transfer

 Source Server         : localhost_3306
 Source Server Type    : MySQL
 Source Server Version : 100413
 Source Host           : localhost:3306
 Source Schema         : isocial

 Target Server Type    : MySQL
 Target Server Version : 100413
 File Encoding         : 65001

 Date: 05/02/2021 00:14:40
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for calendar_event_users
-- ----------------------------
DROP TABLE IF EXISTS `calendar_event_users`;
CREATE TABLE `calendar_event_users`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `eventid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for calendar_events
-- ----------------------------
DROP TABLE IF EXISTS `calendar_events`;
CREATE TABLE `calendar_events`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `start` datetime(0) NOT NULL,
  `end` datetime(0) NOT NULL,
  `description` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `userid` int(11) NOT NULL,
  `pageid` int(11) NOT NULL,
  `location` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ci_sessions
-- ----------------------------
DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions`  (
  `id` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `data` blob NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `ci_sessions_timestamp`(`timestamp`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ci_sessions
-- ----------------------------
INSERT INTO `ci_sessions` VALUES ('bmnk8vpbls5d33ha9t45q64suaubsm5l', '127.0.0.1', 1612458788, 0x5F5F63695F6C6173745F726567656E65726174657C693A313631323435383738383B);
INSERT INTO `ci_sessions` VALUES ('k5g7htmsg1tvhu232kd647njt0i7ubr2', '127.0.0.1', 1612458811, 0x5F5F63695F6C6173745F726567656E65726174657C693A313631323435383738383B);

-- ----------------------------
-- Table structure for custom_fields
-- ----------------------------
DROP TABLE IF EXISTS `custom_fields`;
CREATE TABLE `custom_fields`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `options` varchar(2000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `required` int(11) NOT NULL,
  `profile` int(11) NOT NULL,
  `edit` int(11) NOT NULL,
  `help_text` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `register` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for email_templates
-- ----------------------------
DROP TABLE IF EXISTS `email_templates`;
CREATE TABLE `email_templates`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `hook` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of email_templates
-- ----------------------------
INSERT INTO `email_templates` VALUES (1, 'Forgot Your Password', '<p>Dear [NAME],<br />\r\n<br />\r\nSomeone (hopefully you) requested a password reset at [SITE_URL].<br />\r\n<br />\r\nTo reset your password, please follow the following link: [EMAIL_LINK]<br />\r\n<br />\r\nIf you did not reset your password, please kindly ignore this email.<br />\r\n<br />\r\nYours,<br />\r\n[SITE_NAME]</p>\r\n', 'forgot_password', 'english');
INSERT INTO `email_templates` VALUES (2, 'Email Activation', '<p>Dear [NAME],<br />\r\n<br />\r\nSomeone (hopefully you) has registered an account on [SITE_NAME] using this email address.<br />\r\n<br />\r\nPlease activate the account by following this link: [EMAIL_LINK]<br />\r\n<br />\r\nIf you did not register an account, please kindly ignore this email.<br />\r\n<br />\r\nYours,<br />\r\n[SITE_NAME]</p>\r\n', 'email_activation', 'english');
INSERT INTO `email_templates` VALUES (3, 'New Notification', 'Dear [NAME],<br /><br />\n\nYou have a new notification waiting for you at [SITE_NAME]. You can view it by logging into:<br /><br />\n\n[SITE_URL]<br /><br />\n\nYours,<br />\n[SITE_NAME]', 'new_notification', 'english');
INSERT INTO `email_templates` VALUES (4, 'Member Invite', 'Dear [EMAIL],<br /><br />\r\n\r\nYou have been invited to register at our site: <a href=\"[SITE_URL]\">[SITE_URl]</a>.<br /><br />\r\n\r\nPlease click the link above, or copy and paste it into your browser to register an account.<br /><br />\r\n\r\nThanks,<br />\r\n[SITE_NAME]', 'member_invite', 'english');

-- ----------------------------
-- Table structure for feed_hashtags
-- ----------------------------
DROP TABLE IF EXISTS `feed_hashtags`;
CREATE TABLE `feed_hashtags`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `hashtag` varchar(70) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for feed_item
-- ----------------------------
DROP TABLE IF EXISTS `feed_item`;
CREATE TABLE `feed_item`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `content` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  `imageid` int(11) NOT NULL,
  `videoid` int(11) NOT NULL,
  `likes` int(11) NOT NULL,
  `comments` int(11) NOT NULL,
  `location` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `user_flag` int(11) NOT NULL,
  `profile_userid` int(11) NOT NULL,
  `template` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `pageid` int(11) NOT NULL,
  `hide_profile` int(11) NOT NULL,
  `post_as` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `eventid` int(11) NOT NULL,
  `site_flag` int(11) NOT NULL,
  `pollid` int(11) NOT NULL,
  `blog_postid` int(11) NOT NULL,
  `share_postid` int(11) NOT NULL,
  `member_only` int(11) NOT NULL,
  `dislikes` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for feed_item_comment_likes
-- ----------------------------
DROP TABLE IF EXISTS `feed_item_comment_likes`;
CREATE TABLE `feed_item_comment_likes`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `commentid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for feed_item_comments
-- ----------------------------
DROP TABLE IF EXISTS `feed_item_comments`;
CREATE TABLE `feed_item_comments`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `postid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `comment` varchar(3000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  `likes` int(11) NOT NULL,
  `commentid` int(11) NOT NULL,
  `replies` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for feed_item_images
-- ----------------------------
DROP TABLE IF EXISTS `feed_item_images`;
CREATE TABLE `feed_item_images`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `postid` int(11) NOT NULL,
  `imageid` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for feed_item_poll_answers
-- ----------------------------
DROP TABLE IF EXISTS `feed_item_poll_answers`;
CREATE TABLE `feed_item_poll_answers`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `pollid` int(11) NOT NULL,
  `answer` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `votes` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for feed_item_poll_votes
-- ----------------------------
DROP TABLE IF EXISTS `feed_item_poll_votes`;
CREATE TABLE `feed_item_poll_votes`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `pollid` int(11) NOT NULL,
  `answerid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for feed_item_polls
-- ----------------------------
DROP TABLE IF EXISTS `feed_item_polls`;
CREATE TABLE `feed_item_polls`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `postid` int(11) NOT NULL,
  `question` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `votes` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for feed_item_subscribers
-- ----------------------------
DROP TABLE IF EXISTS `feed_item_subscribers`;
CREATE TABLE `feed_item_subscribers`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `postid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for feed_item_urls
-- ----------------------------
DROP TABLE IF EXISTS `feed_item_urls`;
CREATE TABLE `feed_item_urls`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `postid` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for feed_item_users
-- ----------------------------
DROP TABLE IF EXISTS `feed_item_users`;
CREATE TABLE `feed_item_users`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `postid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for feed_likes
-- ----------------------------
DROP TABLE IF EXISTS `feed_likes`;
CREATE TABLE `feed_likes`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `postid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for feed_tagged_users
-- ----------------------------
DROP TABLE IF EXISTS `feed_tagged_users`;
CREATE TABLE `feed_tagged_users`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `postid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for home_stats
-- ----------------------------
DROP TABLE IF EXISTS `home_stats`;
CREATE TABLE `home_stats`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `google_members` int(11) NOT NULL DEFAULT 0,
  `facebook_members` int(11) NOT NULL DEFAULT 0,
  `twitter_members` int(11) NOT NULL DEFAULT 0,
  `total_members` int(11) NOT NULL DEFAULT 0,
  `new_members` int(11) NOT NULL DEFAULT 0,
  `active_today` int(11) NOT NULL DEFAULT 0,
  `timestamp` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of home_stats
-- ----------------------------
INSERT INTO `home_stats` VALUES (1, 0, 0, 0, 1, 1, 1, 1499160358);

-- ----------------------------
-- Table structure for ideologies
-- ----------------------------
DROP TABLE IF EXISTS `ideologies`;
CREATE TABLE `ideologies`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ideology` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ideologies
-- ----------------------------
INSERT INTO `ideologies` VALUES (1, 'Ideology1', 'icons/b09be2a16724bf5dd50d3dfda0703363.png', 1);
INSERT INTO `ideologies` VALUES (2, 'ideology2', 'icons/8c9bb542e946ef31305560403a79231b.jpg', 1);
INSERT INTO `ideologies` VALUES (3, 'edited ideology', 'icons/b66fe9e7fc8f96cf9b6beb5144e7ffde.png', 1);
INSERT INTO `ideologies` VALUES (4, 'new ideology', 'icons/7b7b3ecdd46505351f0ebdb264b6a97f.png', 1);

-- ----------------------------
-- Table structure for ideology_answers
-- ----------------------------
DROP TABLE IF EXISTS `ideology_answers`;
CREATE TABLE `ideology_answers`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ideology_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ideology_answers
-- ----------------------------
INSERT INTO `ideology_answers` VALUES (1, 1, 1, 'Yes, I like apple very much.');
INSERT INTO `ideology_answers` VALUES (2, 2, 1, 'No, I don\'t like.');
INSERT INTO `ideology_answers` VALUES (3, 3, 1, 'Yes, I like it.');
INSERT INTO `ideology_answers` VALUES (4, 1, 2, 'I like white color.');
INSERT INTO `ideology_answers` VALUES (5, 2, 2, 'I like light colors. Such as light green, light yellow.');
INSERT INTO `ideology_answers` VALUES (6, 3, 2, 'I like dark colors.');
INSERT INTO `ideology_answers` VALUES (7, 1, 3, 'I am from US');
INSERT INTO `ideology_answers` VALUES (8, 2, 3, 'Canada');
INSERT INTO `ideology_answers` VALUES (9, 3, 3, 'I am from Africa');
INSERT INTO `ideology_answers` VALUES (10, 1, 4, 'I am 20 years old');
INSERT INTO `ideology_answers` VALUES (11, 2, 4, '21-30 years');
INSERT INTO `ideology_answers` VALUES (12, 3, 4, '31-');

-- ----------------------------
-- Table structure for ideology_questions
-- ----------------------------
DROP TABLE IF EXISTS `ideology_questions`;
CREATE TABLE `ideology_questions`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `active` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 25 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ideology_questions
-- ----------------------------
INSERT INTO `ideology_questions` VALUES (2, 'What\'s your favorite color?', '{\"1\":\"White color\",\"2\":\"I like light colors\",\"3\":\"I like dark colors\",\"4\":\"I like yellow.\"}', 1);
INSERT INTO `ideology_questions` VALUES (3, 'Where are you from?', '{\"1\":\"I am from US\", \"2\":\"United Kingdom\", \"3\":\"Africa\"}', 1);
INSERT INTO `ideology_questions` VALUES (4, 'How old are you?', '{\"1\":\"I am 21-30 years old\", \"2\":\"31-40\", \"3\":\"41-50\"}', 1);
INSERT INTO `ideology_questions` VALUES (5, 'What\'s your gender?', '{\"1\":\"Male\", \"2\":\"Female\"}', 1);
INSERT INTO `ideology_questions` VALUES (6, 'How long have you been web dev?', '{\"1\":\"More than 5 years\", \"2\":\"About one months ago\", \"3\":\"1-5 years\"}', 1);
INSERT INTO `ideology_questions` VALUES (7, 'Do you like web programming?', '{\"1\":\"Yes\", \"2\":\"No\"}', 1);
INSERT INTO `ideology_questions` VALUES (9, 'Other question2', '{\"1\":\"Y\",\"2\":\"N\"}', 0);
INSERT INTO `ideology_questions` VALUES (24, 'Other question3', '[]', 0);

-- ----------------------------
-- Table structure for invites
-- ----------------------------
DROP TABLE IF EXISTS `invites`;
CREATE TABLE `invites`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `expires` int(11) NOT NULL,
  `expire_upon_use` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `user_registered` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `bypass_register` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ip_block
-- ----------------------------
DROP TABLE IF EXISTS `ip_block`;
CREATE TABLE `ip_block`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IP` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `timestamp` int(11) NOT NULL DEFAULT 0,
  `reason` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ipn_log
-- ----------------------------
DROP TABLE IF EXISTS `ipn_log`;
CREATE TABLE `ipn_log`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `data` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `timestamp` int(11) NOT NULL DEFAULT 0,
  `IP` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for live_chat
-- ----------------------------
DROP TABLE IF EXISTS `live_chat`;
CREATE TABLE `live_chat`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `title` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `last_reply_userid` int(11) NOT NULL,
  `last_replyid` int(11) NOT NULL,
  `last_reply_timestamp` int(11) NOT NULL,
  `posts` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for live_chat_messages
-- ----------------------------
DROP TABLE IF EXISTS `live_chat_messages`;
CREATE TABLE `live_chat_messages`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `chatid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `message` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for live_chat_users
-- ----------------------------
DROP TABLE IF EXISTS `live_chat_users`;
CREATE TABLE `live_chat_users`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `chatid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  `unread` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for login_attempts
-- ----------------------------
DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE `login_attempts`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IP` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `username` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `count` int(11) NOT NULL DEFAULT 0,
  `timestamp` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for page_categories
-- ----------------------------
DROP TABLE IF EXISTS `page_categories`;
CREATE TABLE `page_categories`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of page_categories
-- ----------------------------
INSERT INTO `page_categories` VALUES (1, 'Default', 'default');

-- ----------------------------
-- Table structure for page_invites
-- ----------------------------
DROP TABLE IF EXISTS `page_invites`;
CREATE TABLE `page_invites`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `pageid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `fromid` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for page_users
-- ----------------------------
DROP TABLE IF EXISTS `page_users`;
CREATE TABLE `page_users`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `pageid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `roleid` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for pages
-- ----------------------------
DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `pageviews` int(11) NOT NULL,
  `likes` int(11) NOT NULL,
  `profile_header` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `profile_avatar` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `posting_status` int(11) NOT NULL,
  `location` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `nonmembers_view` int(11) NOT NULL,
  `members` int(11) NOT NULL,
  `allow_share` int(11) NOT NULL,
  `pay_to_join` int(11) NOT NULL,
  `pay_to_userid` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for password_reset
-- ----------------------------
DROP TABLE IF EXISTS `password_reset`;
CREATE TABLE `password_reset`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT 0,
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `timestamp` int(11) NOT NULL DEFAULT 0,
  `IP` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for payment_logs
-- ----------------------------
DROP TABLE IF EXISTS `payment_logs`;
CREATE TABLE `payment_logs`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT 0,
  `amount` decimal(10, 2) NOT NULL DEFAULT 0,
  `timestamp` int(11) NOT NULL DEFAULT 0,
  `email` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `processor` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `hash` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for payment_plans
-- ----------------------------
DROP TABLE IF EXISTS `payment_plans`;
CREATE TABLE `payment_plans`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `hexcolor` varchar(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fontcolor` varchar(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `cost` decimal(10, 2) NOT NULL DEFAULT 0,
  `days` int(11) NOT NULL DEFAULT 0,
  `sales` int(11) NOT NULL DEFAULT 0,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `icon` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of payment_plans
-- ----------------------------
INSERT INTO `payment_plans` VALUES (2, 'BASIC', '65E0EB', 'FFFFFF', 3.00, 30, 8, 'This is the basic plan which gives you a introduction to our Premium Plans', 'glyphicon glyphicon-heart-empty');
INSERT INTO `payment_plans` VALUES (3, 'Professional', '55CD7B', 'FFFFFF', 7.99, 90, 3, 'Get all the benefits of basic at a cheaper price and gain content for longer.', 'glyphicon glyphicon-piggy-bank');
INSERT INTO `payment_plans` VALUES (4, 'LIFETIME', 'EE5782', 'FFFFFF', 300.00, 0, 1, 'Become a premium member for life and have access to all our premium content.', 'glyphicon glyphicon-gift');

-- ----------------------------
-- Table structure for profile_comments
-- ----------------------------
DROP TABLE IF EXISTS `profile_comments`;
CREATE TABLE `profile_comments`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `profileid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for promoted_posts
-- ----------------------------
DROP TABLE IF EXISTS `promoted_posts`;
CREATE TABLE `promoted_posts`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `postid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `pageviews` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `cost` decimal(10, 2) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for relationship_requests
-- ----------------------------
DROP TABLE IF EXISTS `relationship_requests`;
CREATE TABLE `relationship_requests`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `friendid` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `relationship_status` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for reports
-- ----------------------------
DROP TABLE IF EXISTS `reports`;
CREATE TABLE `reports`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `pageid` int(11) NOT NULL,
  `reason` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  `fromid` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for reset_log
-- ----------------------------
DROP TABLE IF EXISTS `reset_log`;
CREATE TABLE `reset_log`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IP` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `timestamp` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for rotation_ads
-- ----------------------------
DROP TABLE IF EXISTS `rotation_ads`;
CREATE TABLE `rotation_ads`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `advert` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `pageviews` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `cost` decimal(10, 2) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for security_questions
-- ----------------------------
DROP TABLE IF EXISTS `security_questions`;
CREATE TABLE `security_questions`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of security_questions
-- ----------------------------
INSERT INTO `security_questions` VALUES (1, 'I am question', 1);
INSERT INTO `security_questions` VALUES (2, 'What\'s the street name you live?', 1);
INSERT INTO `security_questions` VALUES (3, 'Where are you from?', 1);

-- ----------------------------
-- Table structure for site_layouts
-- ----------------------------
DROP TABLE IF EXISTS `site_layouts`;
CREATE TABLE `site_layouts`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `layout_path` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of site_layouts
-- ----------------------------
INSERT INTO `site_layouts` VALUES (1, 'Basic', 'layout/themes/layout.php');
INSERT INTO `site_layouts` VALUES (2, 'Titan', 'layout/themes/titan_layout.php');
INSERT INTO `site_layouts` VALUES (3, 'Dark Fire', 'layout/themes/dark_fire_layout.php');
INSERT INTO `site_layouts` VALUES (4, 'Light Blue', 'layout/themes/light_blue_layout.php');

-- ----------------------------
-- Table structure for site_settings
-- ----------------------------
DROP TABLE IF EXISTS `site_settings`;
CREATE TABLE `site_settings`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `site_name` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `site_desc` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `upload_path` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `upload_path_relative` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `site_email` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `site_logo` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'logo.png',
  `register` int(11) NOT NULL,
  `disable_captcha` int(11) NOT NULL,
  `date_format` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `avatar_upload` int(11) NOT NULL DEFAULT 1,
  `file_types` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `twitter_consumer_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `twitter_consumer_secret` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `disable_social_login` int(11) NOT NULL,
  `facebook_app_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `facebook_app_secret` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `google_client_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `google_client_secret` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `file_size` int(11) NOT NULL,
  `paypal_email` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `paypal_currency` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'USD',
  `payment_enabled` int(11) NOT NULL,
  `payment_symbol` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '$',
  `global_premium` int(11) NOT NULL,
  `install` int(11) NOT NULL DEFAULT 1,
  `login_protect` int(11) NOT NULL,
  `activate_account` int(11) NOT NULL,
  `default_user_role` int(11) NOT NULL,
  `secure_login` int(11) NOT NULL,
  `stripe_secret_key` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `stripe_publish_key` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `google_recaptcha` int(11) NOT NULL,
  `google_recaptcha_secret` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `google_recaptcha_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `logo_option` int(11) NOT NULL,
  `layout` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `profile_comments` int(11) NOT NULL,
  `avatar_width` int(11) NOT NULL,
  `avatar_height` int(11) NOT NULL,
  `cache_time` int(11) NOT NULL,
  `checkout2_secret` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `checkout2_accountno` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `user_display_type` int(11) NOT NULL,
  `page_slugs` int(11) NOT NULL,
  `calendar_picker_format` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `disable_chat` int(11) NOT NULL,
  `enable_google_ads_pages` int(11) NOT NULL,
  `enable_google_ads_feed` int(11) NOT NULL,
  `enable_rotation_ads_feed` int(11) NOT NULL,
  `enable_rotation_ads_pages` int(11) NOT NULL,
  `credit_price_pageviews` int(11) NOT NULL,
  `rotation_ad_alert_user` int(11) NOT NULL,
  `enable_promote_post` int(11) NOT NULL,
  `resize_avatar` int(11) NOT NULL,
  `verified_cost` decimal(10, 2) NOT NULL,
  `enable_verified_buy` int(11) NOT NULL,
  `enable_verified_requests` int(11) NOT NULL DEFAULT 1,
  `public_profiles` int(11) NOT NULL,
  `public_pages` int(11) NOT NULL,
  `public_blogs` int(11) NOT NULL,
  `enable_blogs` int(11) NOT NULL DEFAULT 1,
  `limit_max_photos` int(11) NOT NULL,
  `limit_max_photos_post` int(11) NOT NULL,
  `enable_dislikes` int(11) NOT NULL,
  `enable_google_maps` int(11) NOT NULL,
  `google_maps_api_key` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `limit_words` int(11) NOT NULL DEFAULT 10,
  `limit_edits` int(11) NOT NULL DEFAULT 3,
  `limit_max_videos` int(11) NOT NULL,
  `limit_max_videos_post` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of site_settings
-- ----------------------------
INSERT INTO `site_settings` VALUES (1, 'Social Network', 'Welcome to Social Network', 'E://PROJECT2/OnGoingProject/conOrNotCon/Conspiracy-social-app/uploads', 'uploads', 'test@test.com', 'logo.png', 0, 1, 'd/m/Y', 1, 'gif|png|jpg|jpeg', '', '', 0, '', '', '', '', 10028, '', 'USD', 1, '$', 0, 0, 1, 0, 5, 0, '', '', 0, '', '', 0, 'layout/themes/titan_layout.php', 1, 200, 200, 3600, '', '', 0, 0, 'Y/m/d H:i', 0, 0, 0, 0, 0, 1, 1, 1, 1, 5.40, 1, 1, 1, 1, 1, 1, 50, 8, 1, 0, '', 20, 3, 30, 3);

-- ----------------------------
-- Table structure for user_albums
-- ----------------------------
DROP TABLE IF EXISTS `user_albums`;
CREATE TABLE `user_albums`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  `feed_album` int(11) NOT NULL,
  `images` int(11) NOT NULL,
  `pageid` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_blog_posts
-- ----------------------------
DROP TABLE IF EXISTS `user_blog_posts`;
CREATE TABLE `user_blog_posts`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `blogid` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `body` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `last_updated` int(11) NOT NULL,
  `image` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `views` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_blog_subscribers
-- ----------------------------
DROP TABLE IF EXISTS `user_blog_subscribers`;
CREATE TABLE `user_blog_subscribers`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `blogid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_blogs
-- ----------------------------
DROP TABLE IF EXISTS `user_blogs`;
CREATE TABLE `user_blogs`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `userid` int(11) NOT NULL,
  `description` varchar(2000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_custom_fields
-- ----------------------------
DROP TABLE IF EXISTS `user_custom_fields`;
CREATE TABLE `user_custom_fields`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `fieldid` int(11) NOT NULL,
  `value` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_data
-- ----------------------------
DROP TABLE IF EXISTS `user_data`;
CREATE TABLE `user_data`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `twitter` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `facebook` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `google` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `linkedin` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_events
-- ----------------------------
DROP TABLE IF EXISTS `user_events`;
CREATE TABLE `user_events`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IP` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `event` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `timestamp` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_friend_requests
-- ----------------------------
DROP TABLE IF EXISTS `user_friend_requests`;
CREATE TABLE `user_friend_requests`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `friendid` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_friends
-- ----------------------------
DROP TABLE IF EXISTS `user_friends`;
CREATE TABLE `user_friends`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `friendid` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_group_users
-- ----------------------------
DROP TABLE IF EXISTS `user_group_users`;
CREATE TABLE `user_group_users`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `groupid` int(11) NOT NULL DEFAULT 0,
  `userid` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_groups
-- ----------------------------
DROP TABLE IF EXISTS `user_groups`;
CREATE TABLE `user_groups`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `default` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user_groups
-- ----------------------------
INSERT INTO `user_groups` VALUES (1, 'Default Group', 1);

-- ----------------------------
-- Table structure for user_images
-- ----------------------------
DROP TABLE IF EXISTS `user_images`;
CREATE TABLE `user_images`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `file_name` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `file_type` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `extension` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `file_size` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `file_url` varchar(2000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `albumid` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_notifications
-- ----------------------------
DROP TABLE IF EXISTS `user_notifications`;
CREATE TABLE `user_notifications`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `url` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `message` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `fromid` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_role_permissions
-- ----------------------------
DROP TABLE IF EXISTS `user_role_permissions`;
CREATE TABLE `user_role_permissions`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `classname` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `hook` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user_role_permissions
-- ----------------------------
INSERT INTO `user_role_permissions` VALUES (1, 'ctn_308', 'ctn_308', 'admin', 'admin');
INSERT INTO `user_role_permissions` VALUES (2, 'ctn_309', 'ctn_309', 'admin', 'admin_settings');
INSERT INTO `user_role_permissions` VALUES (3, 'ctn_310', 'ctn_310', 'admin', 'admin_members');
INSERT INTO `user_role_permissions` VALUES (4, 'ctn_311', 'ctn_311', 'admin', 'admin_payment');
INSERT INTO `user_role_permissions` VALUES (5, 'ctn_33', 'ctn_33', 'banned', 'banned');
INSERT INTO `user_role_permissions` VALUES (6, 'ctn_430', 'ctn_431', 'site', 'live_chat');
INSERT INTO `user_role_permissions` VALUES (7, 'ctn_432', 'ctn_435', 'page', 'page_creator');
INSERT INTO `user_role_permissions` VALUES (8, 'ctn_433', 'ctn_436', 'page', 'page_admin');
INSERT INTO `user_role_permissions` VALUES (9, 'ctn_434', 'ctn_437', 'page', 'post_admin');

-- ----------------------------
-- Table structure for user_roles
-- ----------------------------
DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE `user_roles`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `admin` int(11) NOT NULL DEFAULT 0,
  `admin_settings` int(11) NOT NULL DEFAULT 0,
  `admin_members` int(11) NOT NULL DEFAULT 0,
  `admin_payment` int(11) NOT NULL DEFAULT 0,
  `banned` int(11) NOT NULL,
  `live_chat` int(11) NOT NULL,
  `page_creator` int(11) NOT NULL,
  `page_admin` int(11) NOT NULL,
  `post_admin` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user_roles
-- ----------------------------
INSERT INTO `user_roles` VALUES (1, 'SuperAdmin', 1, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `user_roles` VALUES (2, 'DeputyAdmin', 0, 0, 1, 0, 0, 0, 0, 0, 0);
INSERT INTO `user_roles` VALUES (3, 'Admin Settings', 0, 1, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `user_roles` VALUES (4, 'Admin Payments', 0, 0, 0, 1, 0, 0, 0, 0, 0);
INSERT INTO `user_roles` VALUES (5, 'Member', 0, 0, 0, 0, 0, 1, 1, 0, 0);
INSERT INTO `user_roles` VALUES (6, 'Banned', 0, 0, 0, 0, 1, 0, 0, 0, 0);

-- ----------------------------
-- Table structure for user_saved_posts
-- ----------------------------
DROP TABLE IF EXISTS `user_saved_posts`;
CREATE TABLE `user_saved_posts`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `postid` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_videos
-- ----------------------------
DROP TABLE IF EXISTS `user_videos`;
CREATE TABLE `user_videos`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `file_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `userid` int(11) NOT NULL,
  `extension` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `file_size` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `youtube_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `password` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `IP` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `username` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `first_name` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `last_name` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `avatar` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default.png',
  `joined` int(11) NOT NULL DEFAULT 0,
  `joined_date` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `online_timestamp` int(11) NOT NULL DEFAULT 0,
  `oauth_provider` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `oauth_id` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `oauth_token` varchar(1500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `oauth_secret` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email_notification` int(11) NOT NULL DEFAULT 1,
  `aboutme` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `points` decimal(10, 2) NOT NULL DEFAULT 0,
  `premium_time` int(11) NOT NULL DEFAULT 0,
  `user_role` int(11) NOT NULL DEFAULT 0,
  `premium_planid` int(11) NOT NULL DEFAULT 0,
  `active` int(11) NOT NULL DEFAULT 1,
  `activate_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `profile_comments` int(11) NOT NULL DEFAULT 1,
  `profile_views` int(11) NOT NULL,
  `address_1` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `address_2` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `zipcode` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `noti_count` int(11) NOT NULL,
  `profile_header` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default_header.png',
  `location_from` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `location_live` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `friends` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `pages` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
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
  `verified` int(11) NOT NULL,
  `ideology` int(11) UNSIGNED NULL DEFAULT NULL,
  `old_ideology` int(11) UNSIGNED NULL DEFAULT NULL,
  `ideology_answers` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `security_question_id` int(11) NULL DEFAULT NULL,
  `security_answer` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `security_answered` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (3, 'admin@gmail.com', '$2a$12$HMH8Pq5U4uIKiJSKrONeteVbvE3WrKYuJs4moGEGyEEgqJF8X5wbW', '1d0499e7cef8e4f5739b9ea6b96fc467', '127.0.0.1', 'admin', 'Admin', 'User', 'default.png', 1612458428, '2-2021', 1612458801, '', '', '', '', 1, '', 0.00, 0, 1, 0, 1, '', 1, 0, '', '', '', '', '', '', 0, 'default_header.png', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 0);

-- ----------------------------
-- Table structure for verified_requests
-- ----------------------------
DROP TABLE IF EXISTS `verified_requests`;
CREATE TABLE `verified_requests`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `about` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
