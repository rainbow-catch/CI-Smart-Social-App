<div class="row">

<div class="col-md-3">
<?php include(APPPATH . "views/user_settings/sidebar.php"); ?>
</div>

 <div class="col-md-9">


<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-glass"></span> <?php echo lang("ctn_422") ?></div>
    <div class="db-header-extra">
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("user_settings") ?>"><?php echo lang("ctn_224") ?></a></li>
  <li class="active"><?php echo lang("ctn_422") ?></li>
</ol>

<div class="panel panel-default">
  	<div class="panel-body">
  	<?php echo form_open(site_url("user_settings/social_networks_pro"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_426") ?></label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" name="twitter" value="<?php echo $user_data->twitter ?>">
			    </div>
			</div>
			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_427") ?></label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" name="facebook" value="<?php echo $user_data->facebook ?>">
			    </div>
			</div>
			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_428") ?></label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" name="google" value="<?php echo $user_data->google ?>">
			    </div>
			</div>
			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_429") ?></label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" name="linkedin" value="<?php echo $user_data->linkedin ?>">
			    </div>
			</div>
			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_425") ?></label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" name="website" value="<?php echo $user_data->website ?>">
			    </div>
			</div>
			
			 <input type="submit" name="s" value="<?php echo lang("ctn_13") ?>" class="btn btn-primary form-control" />
    <?php echo form_close() ?>
    </div>
    </div>


    <div class="panel panel-default">
  	<div class="panel-body">
  	<h2><?php echo lang("ctn_837") ?></h2>
  	<p><?php echo lang("ctn_838") ?></p>

  	<?php if($this->user->info->oauth_provider) : ?>
  	<?php if($this->user->info->oauth_provider == "twitter") : ?>	
  		<p>Twitter - <a href="<?php echo site_url("user_settings/deauth/" . $this->security->get_csrf_hash()) ?>"><?php echo lang("ctn_839") ?></a></p>
  	<?php endif; ?>
  	<?php if($this->user->info->oauth_provider == "google") : ?>	
  		<p>Google - <a href="<?php echo site_url("user_settings/deauth/" . $this->security->get_csrf_hash()) ?>"><?php echo lang("ctn_839") ?></a></p>
  	<?php endif; ?>
  	<?php if($this->user->info->oauth_provider == "facebook") : ?>	
  		<p>Facebook - <a href="<?php echo site_url("user_settings/deauth/" . $this->security->get_csrf_hash()) ?>"><?php echo lang("ctn_839") ?></a></p>
  	<?php endif; ?>
  	<?php endif; ?>
    </div>
    </div>

</div>

</div>
</div>