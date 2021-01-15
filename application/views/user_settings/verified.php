<div class="row">

<div class="col-md-3">
<?php include(APPPATH . "views/user_settings/sidebar.php"); ?>
</div>

 <div class="col-md-9">


<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-ok"></span> <?php echo lang("ctn_743") ?></div>
    <div class="db-header-extra">
</div>
</div>

<div class="panel panel-default">
<div class="panel-body">
<p class="panel-subheading"><?php echo lang("ctn_743") ?></p>
<?php echo form_open_multipart(site_url("user_settings/verified_pro"), array("class" => "form-horizontal")) ?>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-4 control-label"><?php echo lang("ctn_745") ?></label>
	    <div class="col-sm-8">
	     <textarea name="about" rows="8" class="form-control"></textarea>
	     <span class="help-text"><?php echo lang("ctn_746") ?></span>
	    </div>
	</div>
	<input type="submit" name="s" value="<?php echo lang("ctn_743") ?>" class="btn btn-primary form-control" />
<?php echo form_close() ?>

	</div>
</div>



</div>

</div>
</div>
