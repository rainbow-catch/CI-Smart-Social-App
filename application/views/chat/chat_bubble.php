<div class="active_chat_bubble" onclick="load_active_chat(<?php echo $chat->ID ?>)" id="active_chat_bubble_<?php echo $chat->ID ?>">
	<?php if($chat->unread) : ?><span class="badge-chat small-text"><?php echo lang("ctn_458") ?></span><?php endif; ?> <?php echo $chat->title ?> 
</div>