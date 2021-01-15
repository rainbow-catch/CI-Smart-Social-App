<div class="row">

<div class="col-md-3">
<?php include(APPPATH . "views/user_settings/sidebar.php"); ?>
</div>

 <div class="col-md-9">


<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-eye-open"></span> <?php echo lang("ctn_629") ?></div>
    <div class="db-header-extra">
</div>
</div>

<div class="panel panel-default">
<div class="panel-body">
<p class="panel-subheading"><?php echo lang("ctn_630") ?></p>
<?php echo form_open_multipart(site_url("user_settings/privacy_pro"), array("class" => "form-horizontal")) ?>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-4 control-label"><?php echo lang("ctn_631") ?></label>
	    <div class="col-sm-8">
	      <select name="profile_view" class="form-control">
	      	<option value="0"><?php echo lang("ctn_632") ?></option>
	      	<option value="1" <?php if($this->user->info->profile_view) echo "selected" ?>><?php echo lang("ctn_633") ?></option>
	      </select>
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-4 control-label"><?php echo lang("ctn_634") ?></label>
	    <div class="col-sm-8">
	      <select name="posts_view" class="form-control">
	      	<option value="0"><?php echo lang("ctn_632") ?></option>
	      	<option value="1" <?php if($this->user->info->posts_view) echo "selected" ?>><?php echo lang("ctn_633") ?></option>
	      </select>
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-4 control-label"><?php echo lang("ctn_635") ?></label>
	    <div class="col-sm-8">
	      <select name="post_profile" class="form-control">
	      	<option value="0"><?php echo lang("ctn_632") ?></option>
	      	<option value="1" <?php if($this->user->info->post_profile) echo "selected" ?>><?php echo lang("ctn_633") ?></option>
	      </select>
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-4 control-label"><?php echo lang("ctn_636") ?></label>
	    <div class="col-sm-8">
	      <select name="tag_user" class="form-control">
	      	<option value="0"><?php echo lang("ctn_632") ?></option>
	      	<option value="1" <?php if($this->user->info->tag_user) echo "selected" ?>><?php echo lang("ctn_633") ?></option>
	      </select>
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-4 control-label"><?php echo lang("ctn_637") ?></label>
	    <div class="col-sm-8">
	      <select name="allow_friends" class="form-control">
	      	<option value="0"><?php echo lang("ctn_53") ?></option>
	      	<option value="1" <?php if($this->user->info->allow_friends) echo "selected" ?>><?php echo lang("ctn_54") ?></option>
	      </select>
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-4 control-label"><?php echo lang("ctn_638") ?></label>
	    <div class="col-sm-8">
	      <select name="allow_pages" class="form-control">
	      	<option value="0"><?php echo lang("ctn_53") ?></option>
	      	<option value="1" <?php if($this->user->info->allow_pages) echo "selected" ?>><?php echo lang("ctn_54") ?></option>
	      </select>
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-4 control-label"><?php echo lang("ctn_639") ?></label>
	    <div class="col-sm-8">
	      <select name="chat_option" class="form-control">
	      	<option value="0"><?php echo lang("ctn_632") ?></option>
	      	<option value="1" <?php if($this->user->info->chat_option) echo "selected" ?>><?php echo lang("ctn_633") ?></option>
	      </select>
	    </div>
	</div>
	<input type="submit" name="s" value="<?php echo lang("ctn_236") ?>" class="btn btn-primary form-control" />
<?php echo form_close() ?>

	</div>
</div>



</div>

</div>
</div>
