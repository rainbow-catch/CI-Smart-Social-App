<?php foreach($msgs as $r) : ?>
		<div class="media chat-messages-block">
		  <div class="media-left">
		    <?php echo $this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)) ?>
		  </div>
		  <div class="media-body">
		    <span class="chat-user-title"><a href="<?php echo site_url("profile/" . $r->username) ?>"><?php echo $r->first_name ?> <?php echo $r->last_name ?></a></span><br />
		    <?php echo $this->common->convert_smiles($r->message) ?>
		  	<br />
		  	<span class="tiny-text"><?php echo date($this->settings->info->date_format, $r->timestamp); ?></span>
		  </div>
		</div>
	<?php endforeach; ?>
	<input type="hidden" id="last_reply_chatid_<?php echo $chat->ID ?>" value="<?php echo $last_reply_id ?>">