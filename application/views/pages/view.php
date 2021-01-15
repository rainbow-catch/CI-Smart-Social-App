  <script type="text/javascript">
$(document).ready(function() {
  load_posts(<?php echo $page->ID ?>);
});

function load_posts_wrapper() 
{
  load_posts(<?php echo $page->ID ?>);
}

function load_posts(pageid) 
{
  $.ajax({
    url: global_base_url + 'feed/load_page_posts/' + pageid,
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


 <div class="profile-header" style="background: url(<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative . "/" . $page->profile_header ?>) center center; background-size: cover;">
 <div class="profile-header-avatar">
	<img src="<?php echo base_url() ?>/<?php echo $this->settings->info->upload_path_relative ?>/<?php echo $page->profile_avatar ?>">
 </div>
 <div class="profile-header-options">
  <?php if( (isset($member) && $member != null && $member->roleid == 1) || ($this->common->has_permissions(array("admin", "page_admin"), $this->user)) ) : ?> 
<a href="<?php echo site_url("pages/edit_page/" . $page->ID) ?>" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-cog"></span></a> <a href="<?php echo site_url("pages/delete_page/" . $page->ID . "/" . $this->security->get_csrf_hash()) ?>" onclick="return confirm('<?php echo lang("ctn_551") ?>')" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></a>
<?php endif; ?>
 </div>
 <div class="profile-header-name">
<?php echo $page->name ?>
 </div>
 </div>
 <div class="profile-header-bar clearfix">
 <ul>
  <li class="active"><a href="<?php echo site_url("pages/view/" . $slug) ?>"><?php echo lang("ctn_552") ?></a></li>
  <li><a href="<?php echo site_url("pages/members/" . $slug) ?>"><?php echo lang("ctn_21") ?></a></li>
  <li><a href="<?php echo site_url("pages/albums/" . $slug) ?>"><?php echo lang("ctn_483") ?></a></li>
  <li><a href="<?php echo site_url("pages/events/" . $slug) ?>"><?php echo lang("ctn_553") ?></a></li>
 </ul>

 <div class="pull-right profile-friend-box">
  <?php if($this->user->loggedin) : ?>
  <?php if($member == null) : ?>
    <?php if($page->pay_to_join > 0) : ?>
    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#joinModal"><?php echo lang("ctn_554") ?></button>
    <?php else : ?>
    <a href="<?php echo site_url("pages/join_page/" . $page->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_554") ?></a>
    <?php endif; ?>
  <?php else : ?>
    <a href="<?php echo site_url("pages/leave_page/" . $page->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-ok"></span> <?php echo lang("ctn_34") ?></a> 
  <?php endif; ?>
<?php endif; ?>
  <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#reportModal" title="<?php echo lang("ctn_578") ?>"><span class="glyphicon glyphicon-flag"></span></button>
 </div>
 </div>

 <div class="row separator">
 <div class="col-md-4">

 	<div class="page-block">
 	
 	<div class="page-block-title">
 	<span class="glyphicon glyphicon-home"></span> <?php echo $page->name ?>
 	</div>
 	<div class="page-block-intro">
 	<?php echo $page->description ?>
 	</div>
  <hr>
 <?php if(isset($page->location) && !empty($page->location)) : ?>
    <div class="page-block-tidbit">
    <span class="glyphicon glyphicon-map-marker"></span> <?php echo $page->location ?>
    </div>
  <?php endif; ?>
   <?php if(isset($page->email) && !empty($page->email)) : ?>
    <div class="page-block-tidbit">
    <span class="glyphicon glyphicon-envelope"></span> <?php echo $page->email ?>
    </div>
  <?php endif; ?>
  <?php if(isset($page->phone) && !empty($page->phone)) : ?>
    <div class="page-block-tidbit">
    <span class="glyphicon glyphicon-phone"></span> <?php echo $page->phone ?>
    </div>
  <?php endif; ?>
  <?php if(isset($page->website) && !empty($page->website)) : ?>
    <div class="page-block-tidbit">
    <span class="glyphicon glyphicon-link"></span> <a href="<?php echo $page->website ?>"><?php echo $page->website ?></a>
    </div>
  <?php endif; ?>

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
          <span class="glyphicon glyphicon-user"></span> <a href="<?php echo site_url("pages/members/" . $slug) ?>"><?php echo lang("ctn_21") ?></a>
        </div>
        <div class="page-block-tidbit">
       <?php foreach($users->result() as $r) : ?>
          <div class="profile-friend-area">
          <p><img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $r->avatar ?>" width="40"></p>
          <p><a href="<?php echo site_url("profile/" . $r->username) ?>"><?php echo $r->first_name ?> <?php echo $r->last_name ?></a></p>
          </div>
        <?php endforeach; ?>
        </div>

    </div>

    <div class="page-block half-separator">
        <div class="page-block-title">
          <span class="glyphicon glyphicon-picture"></span> <a href="<?php echo site_url("pages/albums/" . $slug) ?>"><?php echo lang("ctn_483") ?></a>
        </div>
        <div class="page-block-tidbit">
          <?php foreach($albums->result() as $r) : ?>
            <div class="profile-album-area">
            <?php if(isset($r->file_name)) : ?>
              <a href="<?php echo site_url("pages/view_album/" . $r->ID) ?>"><img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $r->file_name ?>"></a>
            <?php else : ?>
              <a href="<?php echo site_url("pages/view_album/" . $r->ID) ?>"><img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/default_album.png"></a>
            <?php endif; ?>
            <p><a href="<?php echo site_url("pages/view_album/" . $r->ID) ?>"><?php echo $r->name ?></a></p>
            </div>
          <?php endforeach; ?>
        </div>

    </div>

    <div class="page-block half-separator">
        <div class="page-block-title">
          <span class="glyphicon glyphicon-calendar"></span> <a href="<?php echo site_url("pages/events/" . $slug) ?>"><?php echo lang("ctn_553") ?></a>
        </div>
        <div class="page-block-tidbit">
         <?php foreach($events->result() as $r) : ?>
          <div class="page-event">
            <p class="page-event-title"><a href="<?php echo site_url("pages/view_event/" . $r->ID) ?>"><?php echo $r->title ?></a></p>
            <p><span class="glyphicon glyphicon-calendar"></span> <?php echo $r->start ?> ~ <?php echo $r->end ?> </p>
          </div>

        <?php endforeach; ?>

        </div>

    </div>

 </div>

 <div class="col-md-8">
  <?php if($this->user->loggedin) : ?>
  <?php if($member != null && $member->roleid == 1) : ?>
    <?php
    $postAsDefault = "page";
    $postAs = $page->name;
    $postAsImg = $page->profile_avatar;

     ?>
  <?php endif; ?>
  <?php 
  // Page defaults
  $editor_placeholder = lang("ctn_579") . " " . $page->name . "'s ".lang("ctn_552")." ...";
  $target_type = "page_profile";
  $targetid = $page->ID;
  $pageid = $page->ID;
  ?>

  <?php if( ($page->posting_status == 0 && $member != null && $member->roleid) || ($page->posting_status == 1 && $member != null) || ($page->posting_status == 2) || ( $this->common->has_permissions(array("admin", "page_admin"), $this->user) ) ) : ?>
  <?php // only show editor if [admins can only post || members can only post || anyone can post || Is page admin/admin role] ?>
 	
 <?php include(APPPATH . "views/feed/editor.php"); ?>
<?php endif; ?>
<?php endif; ?>

<div id="home_posts">

</div>

</div>
 </div>

 </div>
</div>
   <?php echo form_open(site_url("pages/report_page/" . $page->ID)) ?>
 <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-flag"></span> <?php echo lang("ctn_578") ?> <?php echo $page->name ?></h4>
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

 <div class="modal fade" id="joinModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-flag"></span> <?php echo lang("ctn_628") ?> <?php echo $page->name ?></h4>
      </div>
      <div class="modal-body ui-front form-horizontal">
         <p><?php echo lang("ctn_827") ?> <strong><?php echo $page->pay_to_join ?> <?php echo lang("ctn_350") ?></strong>. <?php echo lang("ctn_828") ?> <a href="<?php echo site_url("funds") ?>"><?php echo lang("ctn_250") ?></a> <?php echo lang("ctn_275") ?>.</p>
         <p><?php echo lang("ctn_276") ?> <strong><?php echo $this->user->info->points ?> <?php echo lang("ctn_350") ?></strong>.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <a href="<?php echo site_url("pages/join_page/" . $page->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_554") ?></a>
      </div>
    </div>
  </div>
</div>