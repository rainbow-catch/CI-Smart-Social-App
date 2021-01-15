<div class="friends-padding-content">
<p class="friends-title"><?php echo lang("ctn_488") ?></p>
</div>
<?php if($this->user->loggedin) : ?>
<?php $friends = $this->user_model->get_user_friends($this->user->info->ID, 10); ?>

<?php foreach($friends->result() as $r) : ?>
<div class="friends-bar-box clearfix clickable" onclick="chat_with(<?php echo $r->friendid ?>)">
<div class="friends-bar-user">
<?php echo $this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)) ?>
</div>
<div class="friends-bar-user-info">
<?php if($this->settings->info->user_display_type) : ?>
<?php echo $r->first_name ?> <?php echo $r->last_name ?>
<?php else : ?>
<?php echo $r->username ?>
<?php endif; ?>
<p class="friends-bar-last-online"><?php echo $this->common->get_time_string_simple($this->common->convert_simple_time($r->online_timestamp)) ?></p>
</div>
</div>
<?php endforeach; ?>
<?php endif; ?>