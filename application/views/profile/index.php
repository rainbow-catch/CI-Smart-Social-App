 <script type="text/javascript">
$(document).ready(function() {
  load_posts(<?php echo $user->ID ?>);
});

function load_posts_wrapper() 
{
  load_posts(<?php echo $user->ID ?>);
}

function load_posts(userid) 
{
  $.ajax({
    url: global_base_url + 'feed/load_user_posts/' + userid,
    type: 'GET',
    data: {

    },
    success: function(msg) {
      $('#home_posts').html(msg);
      $('#home_posts').jscroll({
          nextSelector : '.load_next'
      });
    }
  })
}
 </script>
 <div class="row">
 <div class="col-md-12">


 <div class="profile-header" style="background: url(<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative . "/" . $user->profile_header ?>) center center; background-size: cover;">
 <div class="profile-header-avatar">
	<img src="<?php echo base_url() ?>/<?php echo $this->settings->info->upload_path_relative ?>/<?php echo $user->avatar ?>">
 </div>
 <div class="profile-header-name">
<?php echo $user->first_name ?> <?php echo $user->last_name ?> <?php if($user->verified) : ?><img src="<?php echo base_url() ?>images/verified_badge.png" width="14" data-placement="top" data-toggle="tooltip" title="<?php echo lang("ctn_695") ?>"><?php endif; ?>
 </div>
 </div>
 <div class="profile-header-bar clearfix">
 <ul>
  <li class="active"><a href="<?php echo site_url("profile/" . $user->username) ?>"><?php echo lang("ctn_200") ?></a></li>
  <li><a href="<?php echo site_url("profile/friends/" . $user->ID) ?>"><?php echo lang("ctn_493") ?></a></li>
  <li><a href="<?php echo site_url("profile/albums/" . $user->ID) ?>"><?php echo lang("ctn_483") ?></a></li>
 </ul>

 <div class="pull-right profile-friend-box">
  <?php if($this->user->loggedin) : ?>
 	<?php if($user->ID != $this->user->info->ID) : ?>
<?php if($friend_flag) : ?>
<button type="button" class="btn btn-success btn-sm" id="friend_button_<?php echo $user->ID ?>"><span class="glyphicon glyphicon-ok"></span> <?php echo lang("ctn_493") ?></button>
<?php else : ?>
<?php if($request_flag) : ?>
<button type="button" class="btn btn-success btn-sm disabled" id="friend_button_<?php echo $user->ID ?>"><?php echo lang("ctn_601") ?></button>
<?php else : ?> 
  <?php if(!$user->allow_friends) : ?>
  <button type="button" class="btn btn-success btn-sm" onclick="add_friend(<?php echo $user->ID ?>)" id="friend_button_<?php echo $user->ID ?>"><?php echo lang("ctn_602") ?></button>
  <?php endif; ?>
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
    <div class="align-center">
      <div class="project-info project-block align-center">
      <p class="project-info-bit profile-big-text"><?php echo number_format($user->profile_views) ?></p>
      <p class="project-info-title"><?php echo lang("ctn_415") ?></p>
      </div>

      <div class="project-info project-block align-center">
      <p class="project-info-bit profile-big-text"><?php echo $user->post_count ?></p>
      <p class="project-info-title"><?php echo lang("ctn_605") ?></p>
      </div>

      <div class="project-info project-block align-center">
      <p class="project-info-bit profile-big-text">
        <?php if($user->online_timestamp > time() - (60*15)) : ?>
        <span class="profile-online"><?php echo lang("ctn_139") ?></span>
      <?php else : ?>
        <span class="profile-offline"><?php echo lang("ctn_335") ?></span>
      <?php endif; ?>
      </p>
      <p class="project-info-title"><?php echo lang("ctn_606") ?></p>
      </div>
    </div>
  </div>

 	<div class="page-block half-separator">
 	
 	<div class="page-block-title">
 	<span class="glyphicon glyphicon-globe"></span> <?php echo lang("ctn_603") ?>
 	</div>
 	<div class="page-block-intro">
 	<?php echo $user->aboutme ?>
 	</div>
  <hr>

  <?php if(isset($user->location_live) && !empty($user->location_live)) : ?>
    <div class="page-block-tidbit">
    <span class="glyphicon glyphicon-map-marker"></span> <?php echo lang("ctn_607") ?> <?php echo $user->location_live ?>
    </div>
  <?php endif; ?>
  <?php if(isset($user->location_from) && !empty($user->location_from)) : ?>
    <div class="page-block-tidbit">
    <span class="glyphicon glyphicon-home"></span> <?php echo lang("ctn_608") ?> <?php echo $user->location_from ?>
    </div>
  <?php endif; ?>
  <?php foreach($fields->result() as $r) : ?>
    <?php if($r->value) : ?>
      <div class="page-block-tidbit">
      <strong><?php echo $r->name ?></strong> <?php echo $r->value ?>
      </div>
    <?php endif; ?>
  <?php endforeach; ?>

  <?php if($user->relationship_status != 0) : ?>
    <div class="page-block-tidbit">
      <?php if($user->relationship_status == 1) : ?>
      <span class="glyphicon glyphicon-heart-empty"></span> <?php echo lang("ctn_609") ?>
    <?php elseif($user->relationship_status == 2) : ?>
      <span class="glyphicon glyphicon-heart"></span> <?php echo lang("ctn_610") ?> <?php if($relationship_user != null) : ?><?php echo lang("ctn_611") ?> <a href="<?php echo site_url("profile/" . $relationship_user->username) ?>"><?php echo $relationship_user->first_name ?> <?php echo $relationship_user->last_name ?></a><?php endif; ?>
    <?php elseif($user->relationship_status == 2) : ?>
      <span class="glyphicon glyphicon-heart"></span> <?php echo lang("ctn_612") ?> <?php if($relationship_user != null) : ?><?php echo lang("ctn_613") ?> <a href="<?php echo site_url("profile/" . $relationship_user->username) ?>"><?php echo $relationship_user->first_name ?> <?php echo $relationship_user->last_name ?></a><?php endif; ?>
    <?php endif; ?>
    </div>
  <?php endif; ?>

  <div class="align-center">

<?php if(isset($user_data) && $user_data->twitter) : ?>
<div class="project-info project-block align-center">
  <a href="https://twitter.com/<?php echo $this->security->xss_clean($user_data->twitter) ?>" >
    <img src="<?php echo base_url() ?>images/social/profile/twitter.png" height="20" class='social-icon' /></a>
</div>
<?php endif; ?>

<?php if(isset($user_data) && $user_data->facebook) : ?>
<div class="project-info project-block align-center">
  <a href="https://www.facebook.com/<?php echo $this->security->xss_clean($user_data->facebook) ?>" >
    <img src="<?php echo base_url() ?>images/social/profile/facebook.png" height="20" class='social-icon' /></a>
</div>
<?php endif; ?>

<?php if(isset($user_data) && $user_data->google) : ?>
<div class="project-info project-block align-center">
  <a href="https://plus.google.com/<?php echo $this->security->xss_clean($user_data->google) ?>" >
    <img src="<?php echo base_url() ?>images/social/profile/google.png" height="20" class='social-icon' /></a>
</div>
<?php endif; ?>

<?php if(isset($user_data) && $user_data->linkedin) : ?>
<div class="project-info project-block align-center">
  <a href="https://www.linkedin.com/in/<?php echo $this->security->xss_clean($user_data->google) ?>" >
    <img src="<?php echo base_url() ?>images/social/linkedin.png" height="20" class='social-icon' /></a>
</div>
<?php endif; ?>

<?php if(isset($user_data) && $user_data->website) : ?>
<div class="project-info project-block align-center">
  <a href="<?php echo $this->security->xss_clean($user_data->website) ?>" >
    <span class="glyphicon glyphicon-link"></span></a>
</div>
<?php endif; ?>

</div>

 	</div>

  <?php if($this->settings->info->enable_google_ads_pages) : ?>
          <div class="page-block half-separator">
            <div class="page-block-page clearfix">
            <?php include(APPPATH . "/views/home/google_ads.php"); ?>
          </div>
          </div>
        <?php endif; ?>

        <?php if($this->settings->info->enable_rotation_ads_pages) : ?>
            <?php include(APPPATH . "/views/home/rotation_ads.php"); ?>
        <?php endif; ?>


    <div class="page-block half-separator">
        <div class="page-block-title">
          <span class="glyphicon glyphicon-user"></span> <a href="<?php echo site_url("profile/friends/" . $user->ID) ?>"><?php echo lang("ctn_493") ?></a>
        </div>
        <div class="page-block-tidbit">
        <?php foreach($friends->result() as $r) : ?>
          <div class="profile-friend-area">
          <p><img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $r->avatar ?>" width="40"></p>
          <p><a href="<?php echo site_url("profile/" . $r->username) ?>"><?php echo $r->first_name ?> <?php echo $r->last_name ?></a></p>
          </div>
        <?php endforeach; ?>
        </div>

    </div>

    <div class="page-block half-separator">
        <div class="page-block-title">
          <span class="glyphicon glyphicon-picture"></span> <a href="<?php echo site_url("profile/albums/" . $user->ID) ?>"><?php echo lang("ctn_483") ?></a>
        </div>
        <div class="page-block-tidbit">
          <?php foreach($albums->result() as $r) : ?>
            <div class="profile-album-area">
            <?php if(isset($r->file_name)) : ?>
              <a href="<?php echo site_url("profile/view_album/" . $r->ID) ?>"><img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $r->file_name ?>"></a>
            <?php else : ?>
              <a href="<?php echo site_url("profile/view_album/" . $r->ID) ?>"><img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/default_album.png"></a>
            <?php endif; ?>
            <p><a href="<?php echo site_url("profile/view_album/" . $r->ID) ?>"><?php echo $r->name ?></a></p>
            </div>
          <?php endforeach; ?>
        </div>

    </div>

 </div>

 <div class="col-md-8">
  <?php if($this->user->loggedin) : ?>
  <?php if( ($user->post_profile && ($this->user->info->ID == $user->ID || $friend_flag)) || !$user->post_profile) : ?>
 	<?php 
  $editor_placeholder = lang("ctn_579") . " " . $user->first_name . "'s ".lang("ctn_614")." ...";
  $target_type = "user_profile";
  $targetid = $user->ID;

   ?>
 <?php include(APPPATH . "views/feed/editor.php"); ?>
<?php endif; ?>
<?php endif; ?>

<div id="home_posts">

</div>


 </div>


 </div>

</div>
</div>

<?php if($this->user->loggedin) : ?>
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
<?php endif; ?>