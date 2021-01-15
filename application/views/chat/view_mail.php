<script src="<?php echo base_url();?>scripts/custom/get_usernames.js"></script>
<script type="text/javascript">
$("#pagination2 a").on('click',function(e){
  var element = $(this);
   element.addClass(".active");
    e.preventDefault();
    $.ajax({
    url: jQuery(this).attr("href"),
    success: function(msg){
      element.addClass(".active");
      $('#mail-view').html(msg);
      CKEDITOR.replace('mail-reply-textarea', { height: '100'});
    }
    });
    return false;
});
</script>
<style type="text/css">
.pagination { margin: 0px; }
</style>
<div id="loading_spinner_mail">
      <span class="glyphicon glyphicon-refresh" id="ajspinner_mail"></span>
</div>

<div class="mail-header clearfix">
<?php echo $mail->title ?>

<div class="mail-header-timestamp"> <a href="<?php echo site_url("chat/edit_chat/" . $mail->ID) ?>" class="btn btn-xs btn-warning"><span class="glyphicon glyphicon-cog"></span></a>
<input type="button" class="btn btn-xs btn-info" value="Invite" data-toggle="modal" data-target="#inviteModal"> <a href="<?php echo site_url("chat/delete_chat_pro/" . $mail->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-remove"></span></a>
</div>
</div>
<div class="mail-reply clearfix">

<div class="mail-pagination small-text">
<?php echo $this->pagination->create_links() ?>
</div>
</div>

<?php foreach($replies as $r) : ?>
<div class="mail-reply clearfix">

<div class="mail-reply-avatar">
<?php echo $this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)) ?>
</div>
<div class="mail-reply-body">
<div class="mail-reply-timestamp">
<?php echo $this->common->get_time_string_simple($this->common->convert_simple_time($r->timestamp)) ?> <?php if($r->userid == $this->user->info->ID) : ?><a href="<?php echo site_url("chat/delete_chat_message/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span></a><?php endif; ?>
</div>
<p class="mail-reply-user"><a href="<?php echo site_url("profile/" . $r->username) ?>">
<?php if($this->settings->info->user_display_type) : ?>
<?php echo $r->first_name ?> <?php echo $r->last_name ?>
<?php else : ?>
<?php echo $r->username ?>
<?php endif; ?>
</a> <?php echo lang("ctn_479") ?> ...</p>
<div class="mail-reply-message"><?php echo $this->common->convert_smiles($r->message) ?></div>
</div>

</div>
<?php endforeach; ?>
<div class="mail-reply-textbox">
<?php echo form_open(site_url("chat/reply/" . $mail->ID)) ?>
<textarea name="reply" rows="5" id="mail-reply-textarea"></textarea>
<p class="mail-reply-button"><input type="submit" name="s" value="<?php echo lang("ctn_480") ?>" class="btn btn-primary form-control"></p>
<?php echo form_close() ?>
</div>

<?php echo form_open(site_url("chat/add_user/" . $mail->ID), array("class" => "form-horizontal")) ?>
<div class="modal fade" id="inviteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_469") ?></h4>
      </div>
      <div class="modal-body ui-front">
           <?php if($this->settings->info->user_display_type) : ?>
                <input type="text" class="form-control" placeholder="<?php echo lang("ctn_463") ?>" name="name" id="name-search">
                <input type="hidden" name="userid" id="userid-search">
          <?php else : ?>
              <input type="text" class="form-control" placeholder="<?php echo lang("ctn_464") ?>" name="username" id="username-search">
          <?php endif; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_469") ?>">
      </div>
    </div>
  </div>
</div>
<?php echo form_close() ?>