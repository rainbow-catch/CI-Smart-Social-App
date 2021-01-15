<?php
$r->content = $this->common->convert_links($r->content);
$r->content = $this->common->replace_user_tags($r->content);
$r->content = $this->common->replace_hashtags($r->content);
$r->content = $this->common->convert_smiles($r->content);
$script = '';

if($r->post_as == "page") {
	$r->avatar = $r->page_avatar;
	$r->first_name = $r->page_name;
	$r->last_name = "";
	if(!empty($r->page_slug)) {
		$slug = $r->page_slug;
	} else {
		$slug = $r->pageid;
	}
	$url = site_url("pages/view/" . $slug);
} else {
	$url = site_url("profile/" . $r->username);
}
if(isset($r->p_username)) {
	$r->avatar = $r->p_avatar;
}
?>
<div class="feed-wrapper" id="feed-post-<?php echo $r->ID ?>">
<div class="feed-header clearfix">
<div class="feed-header-user">
<img src="<?php echo base_url() ?>/<?php echo $this->settings->info->upload_path_relative ?>/<?php echo $r->avatar ?>" class="user-icon-big">
</div>
<div class="feed-header-info">
	<p><?php echo lang("ctn_825") ?></p>
</div>
</div>
<div class="feed-content">
<p><i><?php echo lang("ctn_826") ?></i></p>
</div>
<div class="feed-content-stats">
<a href="#" onclick="get_post_likes(<?php echo $r->ID ?>)" class="feed-stat <?php if($r->likes <= 0) : ?>nodisplay<?php endif; ?>" id="likes-click-<?php echo $r->ID ?>"><span class="glyphicon glyphicon-thumbs-up"></span> <span id="feed-likes-<?php echo $r->ID ?>"> <?php echo $r->likes ?></span></a> <a href="javascript:void(0)" onclick="load_comments(<?php echo $r->ID ?>)" class="<?php if($r->comments <= 0) : ?>nodisplay<?php endif; ?>"><span class="glyphicon glyphicon-comment"></span> <span id="feed-comments-<?php echo $r->ID ?>"> <?php echo $r->comments ?></span></a>
</div>
<div class="feed-footer">
</div>
<div class="feed-comment-area" id="feed-comment-<?php echo $r->ID ?>">

</div>
</div>
<?php if(!empty($script)) : ?>
<script type="text/javascript">
			$(document).ready(function() {
				<?php echo $script ?>
			});
</script>
<?php endif; ?>