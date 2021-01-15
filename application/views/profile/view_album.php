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
 	<li><a href="<?php echo site_url("profile/" . $user->username) ?>"><?php echo lang("ctn_200") ?></a></li>
 	<li><a href="<?php echo site_url("profile/friends/" . $user->ID) ?>"><?php echo lang("ctn_493") ?></a></li>
 	<li class="active"><a href="<?php echo site_url("profile/albums/" . $user->ID) ?>"><?php echo lang("ctn_483") ?></a></li>
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
 </div>
 </div>

<div class="white-area-content separator">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo $album->name ?></div>
    <div class="db-header-extra form-inline"> 

<?php if( ($this->user->loggedin && $album->userid == $this->user->info->ID) || $this->common->has_permissions(array("admin","admin_members"), $this->user)) : ?>
<input type="button" class="btn btn-primary btn-sm" value="<?php echo lang("ctn_581") ?>" data-toggle="modal" data-target="#addModal"> <input type="button" class="btn btn-primary btn-sm" value="<?php echo lang("ctn_582") ?>" data-toggle="modal" data-target="#addMultiModal">
<?php endif; ?>

</div>
</div>

<p><?php echo $album->description ?></p>

<hr>

<?php if($images->num_rows() == 0) : ?>
<p><?php echo lang("ctn_583") ?> <a href="javascript:void(0)" data-toggle="modal" data-target="#addModal"><?php echo lang("ctn_584") ?></a> <?php echo lang("ctn_585") ?></p>
<?php else : ?>
  <div>
  <ul class="album-images">
<?php foreach($images->result() as $r) : ?>
<li class="album-image">
<?php if(isset($r->file_name)) : ?>
    <img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $r->file_name ?>" width="140" alt="<?php echo $r->name . "<br>" . $r->description ?>">
  <?php else : ?>
    <img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/default_album.png" width="140" alt="<?php echo $r->name . "<br>" . $r->description ?>">
  <?php endif; ?>
  <p><?php echo $r->name ?></p>
  <?php if( ($this->user->loggedin && $album->userid == $this->user->info->ID) || $this->common->has_permissions(array("admin","admin_members"), $this->user)) : ?>
  <div class="album-image-options">
    <a href="javascript:void(0)" onclick="edit_image(<?php echo $r->ID ?>)" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-cog"></span></a> <a href="<?php echo site_url("profile/delete_image/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></a>
  </div>
<?php endif; ?>
</li>
<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>

<div class="align-center">
<?php echo $this->pagination->create_links() ?>
</div>


</div>

</div>
</div>

<?php if( ($this->user->loggedin && $album->userid == $this->user->info->ID) || $this->common->has_permissions(array("admin","admin_members"), $this->user)) : ?>
 <?php echo form_open_multipart(site_url("profile/add_photo/" . $album->ID)) ?>
 <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-picture"></span> <?php echo lang("ctn_586") ?></h4>
      </div>
      <div class="modal-body ui-front form-horizontal">
          <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_81") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="name">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_271") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="description">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_556") ?></label>
                    <div class="col-md-8">
                        <?php echo $album->name ?>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_499") ?></label>
                    <div class="col-md-8">
                        <input type="file" class="form-control" name="image_file">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_500") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="image_url" placeholder="http://www ...">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_574") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" class="form-control" name="feed_post" value="1" checked>
                        <span class="help-area"><?php echo lang("ctn_587") ?></span>
                    </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_584") ?>">
      </div>
    </div>
  </div>
</div>
<?php echo form_close() ?>


 <?php echo form_open_multipart(site_url("profile/add_multi_photo/" . $album->ID)) ?>
 <div class="modal fade" id="addMultiModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-picture"></span> <?php echo lang("ctn_588") ?></h4>
      </div>
      <div class="modal-body ui-front form-horizontal">
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_556") ?></label>
                    <div class="col-md-8">
                        <?php echo $album->name ?>
                    </div>
            </div>
            <div id="multi">
	            <div class="form-group">
	                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_499") ?></label>
	                    <div class="col-md-8">
	                        <input type="file" class="form-control" name="image_file_1">
	                    </div>
	            </div>
	            <div class="form-group">
	                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_500") ?></label>
	                    <div class="col-md-8">
	                        <input type="text" class="form-control" name="image_url_1" placeholder="http://www ...">
	                    </div>
	            </div>
	        </div>
	        <input type="hidden" id="amount" name="amount" value="1">
          <?php if($this->settings->info->limit_max_photos_post > 0) : ?>
            <p><?php echo lang("ctn_833") ?>: <?php echo $this->settings->info->limit_max_photos_post ?></p>
          <?php endif; ?>
	        <input type="button" class="btn btn-primary btn-sm" value="<?php echo lang("ctn_589") ?>" onclick="add_photo()">

          <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_574") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" class="form-control" name="feed_post" value="1" checked>
                        <span class="help-area"><?php echo lang("ctn_587") ?></span>
                    </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_584") ?>">
      </div>
    </div>
  </div>
</div>
<?php echo form_close() ?>


 <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" id="edit-album">
     
    </div>
  </div>
</div>
<?php endif; ?>


<script type="text/javascript">
function add_photo() 
{
	var id = parseInt($('#amount').val());
	id = id + 1;
	$('#amount').val(id);

	var html = '<div class="form-group">'
	                    +'<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_499") ?> '+id+'</label>'
	                    +'<div class="col-md-8">'
	                        +'<input type="file" class="form-control" name="image_file_'+id+'">'
	                    +'</div>'
	            +'</div>'
	            +'<div class="form-group">'
	                    +'<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_500") ?> '+id+'</label>'
	                    +'<div class="col-md-8">'
	                        +'<input type="text" class="form-control" name="image_url_'+id+'" placeholder="http://www ...">'
	                    +'</div>'
	            +'</div>';
	$('#multi').append(html);
}
</script>