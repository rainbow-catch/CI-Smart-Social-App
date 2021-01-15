<div class="feed-comment-wrapper">
<div id="feed-comments-spot-<?php echo $post->ID ?>">
<?php include("feed_comments_single.php"); ?>
</div>
<div class="feed-comment-m clearfix">
  <div class="feed-comment-m-part1">
    <a href="#">
      <img src="<?php echo base_url() ?>/<?php echo $this->settings->info->upload_path_relative ?>/<?php echo $this->user->info->avatar ?>" class="user-icon">
    </a>
  </div>
  <div class="feed-comment-m-part2">
   <input type="text" class="form-control feed-comment-input" placeholder="<?php echo lang("ctn_513") ?> ..." data-id="<?php echo $post->ID ?>">
  </div>
</div>

</div>