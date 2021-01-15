<?php if($post->comments > 5 && (isset($hide_prev) && !$hide_prev))  : ?>
<a href="javascript: void(0)" onclick="load_previous_comments(<?php echo $post->ID ?>, <?php echo $page+5 ?>, this)" class="small-text"><?php echo lang("ctn_514") ?></a>
<?php endif; ?>
<?php foreach($com as $r) : ?>
  <?php
$r->comment = $this->common->replace_user_tags($r->comment);
?>
<div class="feed-comment-m clearfix" id="feed-comment-area-<?php echo $r->ID ?>">
  <div class="feed-comment-m-part1">
    <a href="#">
      <img src="<?php echo base_url() ?>/<?php echo $this->settings->info->upload_path_relative ?>/<?php echo $r->avatar ?>" class="user-icon">
    </a>
  </div>
  <div class="feed-comment-m-part2">
   <a href="<?php echo site_url("profile/" . $r->username) ?>"><?php echo $r->first_name ?> <?php echo $r->last_name ?></a> <?php if($r->verified) : ?><img src="<?php echo base_url() ?>images/verified_badge.png" width="14" data-placement="top" data-toggle="tooltip" title="<?php echo lang("ctn_720") ?>"><?php endif; ?> <?php echo $r->comment ?>
   <p class="small-text"><a href="javascript: void(0)" class="<?php if($r->commentlikeid) : ?>active-comment-like<?php endif; ?>" onclick="like_comment(<?php echo $r->ID ?>)" id="comment-like-link-<?php echo $r->ID ?>"><?php echo lang("ctn_337") ?></a> - <a href="javascript: void(0)" onclick="load_comment_replies(<?php echo $r->ID ?>)"><?php echo lang("ctn_480") ?> <span id="feed-reply-comments-<?php echo $r->ID ?>"><?php if($r->replies > 0) : ?>(<?php echo $r->replies ?>)<?php endif ?></span></a> <span class="" id="comment-like-<?php echo $r->ID ?>"><?php if($r->likes > 0) : ?>- <span class="glyphicon glyphicon-thumbs-up" id=""></span> <?php echo $r->likes ?><?php endif; ?></span> <?php if($r->userid == $this->user->info->ID || ($this->common->has_permissions(array("admin", "post_admin"), $this->user)) ) : ?>- [<a href="javascript: void(0)" onclick="delete_comment(<?php echo $r->ID ?>)">X</a>]<?php endif; ?> - <?php echo $this->common->get_time_string_simple($this->common->convert_simple_time($r->timestamp)) ?></p>
   <div id="feed-comment-reply-<?php echo $r->ID ?>" class="feed-comment-reply">
   <?php echo lang("ctn_515") ?>
   </div>
  </div>
</div>
<?php endforeach; ?>