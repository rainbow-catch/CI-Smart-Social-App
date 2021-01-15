<?php $script = ""; ?>
<?php foreach($chats->result() as $r) : ?>
	<?php
		if(!empty($r->lc_title)) {
			$r->title = $r->lc_title;
		}
	?>
	<?php if(!$r->active) : ?>
<div class="active_chat_bubble" onclick="load_active_chat(<?php echo $r->ID ?>)" id="active_chat_bubble_<?php echo $r->ID ?>">
	<?php if($r->unread) : ?><span class="badge-chat small-text"><?php echo lang("ctn_458") ?></span><?php endif; ?> <?php echo $r->title ?> 
</div>
<?php else : ?>
	<?php 
		$limit = 5;
		$messages = $this->chat_model->get_chat_messages($r->ID, $limit);
		$msgs = array();
		$last_reply_id = 0;
		foreach($messages->result() as $rs) {
			$msgs[] = $rs;
			if($last_reply_id == 0) {
				$last_reply_id = $rs->ID;
			}
		}
		$msgs =array_reverse($msgs);
		$window_id = "active_chat_window_" .$r->ID;
		$script .= '$("#'.$window_id.'").scrollTop($("#'.$window_id.'")[0].scrollHeight);';

	?>
<div class="active_chat_bubble active_chat_window" id="active_chat_bubble_<?php echo $r->ID ?>">
<div class="chat-top-bar"><?php echo $r->title ?> <div class="pull-right"> <span class="glyphicon glyphicon-minus click chat-icon" onclick="close_active_chat_window(<?php echo $r->ID ?>)"></span> <span class="glyphicon glyphicon-remove click chat-icon" onclick="hide_chat_window(<?php echo $r->ID ?>)"></span></div></div>
<div class="chat-chat-body" id="active_chat_window_<?php echo $r->ID ?>">
	<?php foreach($msgs as $msg) : ?>
		<div class="media chat-messages-block">
		  <div class="media-left">
		    <?php echo $this->common->get_user_display(array("username" => $msg->username, "avatar" => $msg->avatar, "online_timestamp" => $msg->online_timestamp)) ?>
		  </div>
		  <div class="media-body">
		    <span class="chat-user-title"><?php echo $msg->first_name ?> <?php echo $msg->last_name ?> (@<a href="<?php echo site_url("profile/" . $msg->username) ?>"><?php echo $msg->username ?></a>)</span><br />
		    <?php echo $msg->message ?>
		  	<br />
		  	<span class="tiny-text"><?php echo date($this->settings->info->date_format, $msg->timestamp); ?></span>
		  </div>
		</div>
	<?php endforeach; ?>
	<input type="hidden" id="last_reply_chatid_<?php echo $r->ID ?>" value="<?php echo $last_reply_id ?>">
</div>
<div class="chat-main-reply">
	<input type="text" name="reply" class="form-control" id="chat_input_message_<?php echo $r->ID ?>" placeholder="<?php echo lang("ctn_459") ?> ..." onkeypress="return wait_for_enter(event, <?php echo $r->ID ?>);">
</div>
</div>
<?php endif; ?>
<?php endforeach; ?>
<script type="text/javascript">
$(document).ready(function() {
	<?php echo $script ?>
});
</script>