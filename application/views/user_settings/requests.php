<div class="row">

<div class="col-md-3">
<?php include(APPPATH . "views/user_settings/sidebar.php"); ?>
</div>

 <div class="col-md-9">


<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_640") ?></div>
    <div class="db-header-extra">
</div>
</div>

<table class="table table-bordered table-hover table-striped">
<tr class="table-header"><td><?php echo lang("ctn_608") ?></td><td><?php echo lang("ctn_627") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
<?php foreach($requests->result() as $r) : ?>
<tr><td><?php echo $this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp, "first_name" => $r->first_name, "last_name" => $r->last_name)) ?></td><td><?php echo date($this->settings->info->date_format, $r->timestamp ) ?></td><td><a href="<?php echo site_url("user_settings/friend_request/1/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-success btn-xs"><?php echo lang("ctn_623") ?></a> <a href="<?php echo site_url("user_settings/friend_request/0/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs"><?php echo lang("ctn_624") ?></a></td></tr>
<?php endforeach; ?>

</table>

</div>

</div>
</div>
