<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_1") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li class="active"><?php echo lang("ctn_815") ?></li>
</ol>


<hr>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open(site_url("admin/limits_pro"), array("class" => "form-horizontal")) ?>


<div class="form-group">
    <label for="name-in" class="col-sm-2 control-label"><?php echo lang("ctn_816") ?></label>
    <div class="col-sm-10">
      <input type="text" id="name-in" class="form-control" name="limit_max_photos" value="<?php echo $this->settings->info->limit_max_photos ?>">
      <span class="help-block"><?php echo lang("ctn_817") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-2 control-label"><?php echo lang("ctn_818") ?></label>
    <div class="col-sm-10">
      <input type="text" id="name-in" class="form-control" name="limit_max_photos_post" value="<?php echo $this->settings->info->limit_max_photos_post ?>">
      <span class="help-block"><?php echo lang("ctn_819") ?></span>
    </div>
</div>

<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_13") ?>" />
<?php echo form_close() ?>

</div>
</div>
</div>