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
  <li><a href="<?php echo site_url("pages/view/" . $slug) ?>"><?php echo lang("ctn_552") ?></a></li>
  <li><a href="<?php echo site_url("pages/members/" . $slug) ?>"><?php echo lang("ctn_21") ?></a></li>
  <li><a href="<?php echo site_url("pages/albums/" . $slug) ?>"><?php echo lang("ctn_483") ?></a></li>
  <li class="active"><a href="<?php echo site_url("pages/events/" . $slug) ?>"><?php echo lang("ctn_553") ?></a></li>
 </ul>

 <div class="pull-right profile-friend-box">

 <?php if($member == null) : ?>
    <a href="<?php echo site_url("pages/join_page/" . $page->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_554") ?></a>
  <?php else : ?>
    <a href="<?php echo site_url("pages/leave_page/" . $page->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-ok"></span> <?php echo lang("ctn_34") ?></a> 
  <?php endif; ?>
  
 </div>
 </div>

 <div class="row separator">

  <div class="col-md-4">

  <div class="page-block">
  
  <div class="page-block-title">
  <span class="glyphicon glyphicon-globe"></span> <?php echo lang("ctn_590") ?>
  </div>
  <div class="page-block-intro page-event-big-text">
  <?php echo $attending_count ?>
  </div>
  <hr>

  <div class="page-block-tidbit">
  <p><input type="button" class="btn btn-primary form-control btn-sm" value="<?php echo lang("ctn_591") ?>" onclick="view_list(<?php echo $event->ID ?>,1)"></p>
  <p> <input type="button" class="btn btn-info form-control btn-sm" value="<?php echo lang("ctn_592") ?>" onclick="view_list(<?php echo $event->ID ?>,0)">
  </div>


  </div>

</div>

 <div class="col-md-8">

<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo $event->title ?></div>
    <div class="db-header-extra form-inline">

       <?php if($attending == null) : ?>
    <a href="<?php echo site_url("pages/join_event/" . $event->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_593") ?></a>
  <?php else : ?>
    <a href="<?php echo site_url("pages/leave_event/" . $event->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-ok"></span> <?php echo lang("ctn_594") ?></a> 
  <?php endif; ?>




</div>
</div>


<p><?php echo $event->description ?></p>
<p><span class="glyphicon glyphicon-time"></span> <?php echo $event->start ?> ~ <?php echo $event->end ?></p>
<?php if(!empty($event->location)) : ?>
<p><span class="glyphicon glyphicon-map-marker"></span> <?php echo $event->location ?></p>
<?php endif; ?>



</div>

 </div>
</div>
</div>
</div>

 <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" id="view-area">
     
    </div>
  </div>
</div>
<script type="text/javascript">
function view_list(eventid, type) 
{
  $.ajax({
    url: global_base_url + 'pages/view_event_users/' + eventid,
    type: 'GET',
    data: {
      type : type
    },
    success: function(msg) {
      $('#viewModal').modal('show');
      $('#view-area').html(msg);
    }
  })
}
</script>