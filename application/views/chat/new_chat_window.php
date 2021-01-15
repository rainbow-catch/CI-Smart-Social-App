<div class="chat-body-wrap">
<div id="chat-body-errors"></div>
<p><?php echo lang("ctn_475") ?></p>
<p class="ui-front"><input type="text" name="name" class="form-control" placeholder="<?php echo lang("ctn_25") ?> ..." id="start_chat_username" <?php if(isset($username) && !empty($username)) : ?>value="<?php echo $username ?>"<?php endif; ?>></p>

<p><input type="text" name="reply" class="form-control" id="start_chat_message" placeholder="<?php echo lang("ctn_476") ?> ..."></p>

<p><input type="button" class="btn btn-default btn-sm form-control" value="<?php echo lang("ctn_477") ?>" id="start_chat_button"></p>

</div>