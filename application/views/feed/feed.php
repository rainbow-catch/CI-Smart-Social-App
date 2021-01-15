<?php if(isset($promoted_posts)) : ?>
<?php foreach($promoted_posts->result() as $r) : ?>
	<?php
	$this->feed_model->decrease_post_pageviews($r->promoted_id);
	?>
<?php include("feed_single.php"); ?>
<?php endforeach; ?>
<?php endif; ?>
<?php foreach($posts->result() as $r) : ?>
	<?php if($r->member_only && isset($member) && !isset($member->ID)) : ?>
		<?php include("feed_hidden.php"); ?>
		<?php else : ?>
	<?php include("feed_single.php"); ?>
	<?php endif; ?>
<?php endforeach; ?>
<?php if(isset($a_url) && $posts->num_rows() > 0) : ?>
<a href="<?php echo $a_url ?>" class="load_next"><?php echo lang("ctn_512") ?></a>
<?php endif; ?>