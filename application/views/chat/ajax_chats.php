<?php if($mail->num_rows() > 0) : ?>
<?php foreach($mail->result() as $r) : ?>
<div class="notification-box-bit animation-fade clearfix <?php if($r->unread) : ?>active-noti<?php endif; ?>">
  <div class="notification-icon-bit">
    <?php echo $this->common->get_user_display(array("username" => $r->lr_username, "avatar" => $r->lr_avatar, "online_timestamp" => $r->lr_online_timestamp)) ?>
  </div>
  <div class="projects-text-bit small-text click" onclick="load_closed_window(<?php echo $r->ID ?>)">
    <a href="<?php echo site_url("profile/" . $r->username) ?>"><?php if($this->settings->info->user_display_type) : ?>
<?php echo $r->lr_first_name ?> <?php echo $r->lr_last_name ?>
<?php else : ?>
<?php echo $r->lr_username ?>
<?php endif; ?></a> <?php echo strip_tags($r->message) ?>
    <p class="notification-datestamp"><?php echo $this->common->get_time_string_simple($this->common->convert_simple_time($r->last_reply_timestamp)) ?></p>
  </div>
</div>
<?php endforeach; ?>
<?php else : ?>
<p><?php echo lang("ctn_460") ?></p>
<?php endif; ?>