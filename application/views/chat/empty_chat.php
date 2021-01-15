<div class="active_chat_bubble active_chat_window" id="active_chat_bubble_0">
<div class="chat-top-bar"><?php echo $title ?> <div class="pull-right"><span class="glyphicon glyphicon-remove click chat-icon" onclick="hide_chat_window(0)"></span></div></div>
<div class="chat-chat-body" id="active_chat_window_0">
	
</div>
<div class="chat-main-reply">
	<input type="hidden" id="chat_hidden_userid" value="<?php echo $userid ?>">
	<input type="text" name="reply" class="form-control" id="chat_input_message_0" placeholder="<?php echo lang("ctn_459") ?> ..." onkeypress="return wait_for_enter(event, 0);">
</div>
</div>