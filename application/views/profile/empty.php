 <div class="row">
 <div class="col-md-12">


 <div class="profile-header" style="background: url(<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative . "/" . $user->profile_header ?>) center center; background-size: cover;">
 <div class="profile-header-avatar">
	<img src="<?php echo base_url() ?>/<?php echo $this->settings->info->upload_path_relative ?>/<?php echo $user->avatar ?>">
 </div>
 <div class="profile-header-name">
<?php echo $user->first_name ?> <?php echo $user->last_name ?>
 </div>
 </div>
 <div class="profile-header-bar clearfix">
 <ul>
  <li class="active"><a href="<?php echo site_url("profile/" . $user->username) ?>"><?php echo lang("ctn_200") ?></a></li>
  <li><a href="<?php echo site_url("profile/friends/" . $user->ID) ?>"><?php echo lang("ctn_493") ?></a></li>
  <li><a href="<?php echo site_url("profile/albums/" . $user->ID) ?>"><?php echo lang("ctn_483") ?></a></li>
 </ul>

 <div class="pull-right profile-friend-box">
  <?php if($this->user->loggedin ) : ?>
  <?php if($user->ID != $this->user->info->ID) : ?>
<?php if($friend_flag) : ?>
<button type="button" class="btn btn-success btn-sm" id="friend_button_<?php echo $user->ID ?>"><span class="glyphicon glyphicon-ok"></span> <?php echo lang("ctn_493") ?></button>
<?php else : ?>
<?php if($request_flag) : ?>
<button type="button" class="btn btn-success btn-sm disabled" id="friend_button_<?php echo $user->ID ?>"><?php echo lang("ctn_601") ?></button>
<?php else : ?>
<button type="button" class="btn btn-success btn-sm" onclick="add_friend(<?php echo $user->ID ?>)" id="friend_button_<?php echo $user->ID ?>"><?php echo lang("ctn_602") ?></button>
<?php endif; ?>
<?php endif; ?>
<?php endif; ?>
<?php endif; ?>
<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#reportModal" title="<?php echo lang("ctn_578") ?>"><span class="glyphicon glyphicon-flag"></span></button>
 </div>
 </div>

 <div class="row separator">
 <div class="col-md-4">

 	<div class="page-block">
 	
 	<div class="page-block-title">
 	<span class="glyphicon glyphicon-globe"></span> <?php echo lang("ctn_603") ?>
 	</div>
 	<div class="page-block-intro">

 	</div>


 	</div>


    <div class="page-block separator">
        <div class="page-block-title">
          <span class="glyphicon glyphicon-user"></span> <a href="<?php echo site_url("profile/friends/" . $user->ID) ?>"><?php echo lang("ctn_493") ?></a>
        </div>
        <div class="page-block-tidbit">
        
        </div>

    </div>

    <div class="page-block separator">
        <div class="page-block-title">
          <span class="glyphicon glyphicon-picture"></span> <a href="<?php echo site_url("profile/albums/" . $user->ID) ?>"><?php echo lang("ctn_483") ?></a>
        </div>
        <div class="page-block-tidbit">
          
        </div>

    </div>

 </div>

 <div class="col-md-8">
 

<div id="home_posts">

</div>


 </div>


 </div>
</div>
</div>

   <?php echo form_open(site_url("profile/report_profile/" . $user->ID)) ?>
 <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-flag"></span> <?php echo lang("ctn_578") ?> <?php echo $user->first_name ?> <?php echo $user->last_name ?></h4>
      </div>
      <div class="modal-body ui-front form-horizontal">
          <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_580") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="reason">
                    </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_578") ?>">
      </div>
    </div>
  </div>
</div>
<?php echo form_close() ?>