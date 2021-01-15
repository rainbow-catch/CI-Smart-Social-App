<div class="row">

<div class="col-md-3">
<?php include(APPPATH . "views/user_settings/sidebar.php"); ?>
</div>

 <div class="col-md-9">


<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-file"></span> <?php echo lang("ctn_626") ?></div>
    <div class="db-header-extra">
</div>
</div>

<table class="table table-bordered table-hover table-striped">
<tr class="table-header"><td><?php echo lang("ctn_347") ?></td><td><?php echo lang("ctn_552") ?></td><td><?php echo lang("ctn_608") ?></td><td><?php echo lang("ctn_627") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
<?php foreach($invites->result() as $r) : ?>
	<?php
	if(!empty($r->slug)) {
		$slug = $r->slug;
	} else {
		$slug = $r->pageid;
	}
	?>
<tr><td><a href="<?php echo site_url("pages/view/" . $slug) ?>"><img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $r->profile_avatar ?>" width="80"></a></td><td><a href="<?php echo site_url("pages/view/" . $slug) ?>"><?php echo $r->name ?></a></td><td><?php echo $this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp, "first_name" => $r->first_name, "last_name" => $r->last_name)) ?></td><td><?php echo date($this->settings->info->date_format, $r->timestamp ) ?></td><td><a href="<?php echo site_url("pages/join_page/" . $r->pageid . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-success btn-xs"><?php echo lang("ctn_628") ?></a> <a href="<?php echo site_url("user_settings/delete_page_invite/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs"><?php echo lang("ctn_624") ?></a></td></tr>
<?php endforeach; ?>

</table>

</div>

</div>
</div>
