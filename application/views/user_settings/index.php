<script src="<?php echo base_url();?>scripts/custom/get_usernames.js"></script>
<div class="row">

<div class="col-md-3">
<?php include(APPPATH . "views/user_settings/sidebar.php"); ?>
</div>

 <div class="col-md-9">


<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-cog"></span> <?php echo lang("ctn_224") ?></div>
    <div class="db-header-extra">
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li class="active"><?php echo lang("ctn_224") ?></li>
</ol>

<p><?php echo lang("ctn_226") ?></p>

<hr>

<div class="panel panel-default">
<div class="panel-body">
<p class="panel-subheading"><?php echo lang("ctn_227") ?></p>
<?php echo form_open_multipart(site_url("user_settings/pro"), array("class" => "form-horizontal")) ?>
		<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_228") ?></label>
	    <div class="col-sm-10">
	      <a href="<?php echo site_url("profile/" . $this->user->info->username) ?>"><?php echo $this->user->info->username ?></a>
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_229") ?></label>
	    <div class="col-sm-10">
	    <img src="<?php echo base_url() ?>/<?php echo $this->settings->info->upload_path_relative ?>/<?php echo $this->user->info->avatar ?>" />
	    <?php if($this->settings->info->avatar_upload) : ?>
	     	<input type="file" name="userfile" /> 
	     <?php endif; ?>
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_615") ?></label>
	    <div class="col-sm-10">
	    <img src="<?php echo base_url() ?>/<?php echo $this->settings->info->upload_path_relative ?>/<?php echo $this->user->info->profile_header ?>" width="100%" />
	    <?php if($this->settings->info->avatar_upload) : ?>
	     	<input type="file" name="userfile_profile" /> 
	     <?php endif; ?>
	    </div>
	</div>
    <div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_230") ?></label>
	    <div class="col-sm-10">
	      <input type="email" class="form-control" name="email" value="<?php echo $this->user->info->email ?>">
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_231") ?></label>
	    <div class="col-sm-10">
	      <input type="text" class="form-control" name="first_name" value="<?php echo $this->user->info->first_name ?>">
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_232") ?></label>
	    <div class="col-sm-10">
	      <input type="text" class="form-control" name="last_name" value="<?php echo $this->user->info->last_name ?>">
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_233") ?></label>
	    <div class="col-sm-10">
	      <textarea class="form-control" name="aboutme" rows="8"><?php echo nl2br($this->user->info->aboutme) ?></textarea>
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_616") ?></label>
	    <div class="col-sm-10">
	      <input type="text" class="form-control map_name" name="location_from" value="<?php echo $this->user->info->location_from ?>">
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_617") ?></label>
	    <div class="col-sm-10">
	      <input type="text" class="form-control map_name" name="location_live" value="<?php echo $this->user->info->location_live ?>">
	    </div>
	</div>
	<div class="form-group" id="relationship_part">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_618") ?></label>
	    <div class="col-sm-10">
	      <select name="relationship_status" id="relationship" class="form-control">
	      	<option value="0"><?php echo lang("ctn_46") ?></option>
	      	<option value="1" <?php if($this->user->info->relationship_status == 1) echo "selected" ?>><?php echo lang("ctn_609") ?></option>
	      	<option value="2" <?php if($this->user->info->relationship_status == 2) echo "selected" ?>><?php echo lang("ctn_610") ?></option>
	      	<option value="3" <?php if($this->user->info->relationship_status == 3) echo "selected" ?>><?php echo lang("ctn_619") ?></option>
	      </select>
	      <?php if($request->num_rows() > 0) : ?>
	      	<?php $request = $request->row(); ?>
	      <p><?php echo lang("ctn_620") ?> <a href="<?php echo site_url("profile/" . $request->username) ?>"><?php echo $request->first_name . " " . $request->last_name ?></a> (<a href="<?php echo site_url("user_settings/cancel_request/" . $request->ID . "/" . $this->security->get_csrf_hash()) ?>"><?php echo lang("ctn_621") ?></a>)</p>
	      <?php endif; ?>
	      <?php foreach($requests->result() as $r) : ?>
	      	<?php
	      	if($r->relationship_status == 2) {
	      		$relationship = lang("ctn_610");
	      	} elseif($r->relationship_status == 3) {
	      		$relationship = lang("ctn_619");
	      	}
	      	?>
	      	<p><?php echo lang("ctn_622") ?> <a href=""><?php echo $r->first_name . " " . $r->last_name ?></a>(<?php echo $relationship ?>) - <a href="<?php echo site_url("user_settings/relationship_request/" . $r->ID . "/1/" . $this->security->get_csrf_hash()) ?>"><?php echo lang("ctn_623") ?></a> - <a href="<?php echo site_url("user_settings/relationship_request/" . $r->ID . "/0/" . $this->security->get_csrf_hash()) ?>"><?php echo lang("ctn_624") ?></a></p>
	      <?php endforeach; ?>
	    </div>
	</div>
	<div class="form-group <?php if(empty($relationship_user)) : ?>nodisplay<?php endif; ?>" id="relationship_user">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_625") ?></label>
	    <div class="col-sm-10">
	      <input type="text" name="relationship_user" class="form-control" id="name-search" value="<?php echo $relationship_user ?>">
	      <input type="hidden" name="userid" id="userid-search">
	    </div>
	</div>
	<p class="panel-subheading"><?php echo lang("ctn_390") ?></p>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_391") ?></label>
	    <div class="col-sm-10">
	      <input type="text" name="address_1" class="form-control" value="<?php echo $this->user->info->address_1 ?>">
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_392") ?></label>
	    <div class="col-sm-10">
	      <input type="text" name="address_2" class="form-control" value="<?php echo $this->user->info->address_2 ?>">
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_393") ?></label>
	    <div class="col-sm-10">
	      <input type="text" name="city" class="form-control" value="<?php echo $this->user->info->city ?>">
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_394") ?> </label>
	    <div class="col-sm-10">
	      <input type="text" name="state" class="form-control" value="<?php echo $this->user->info->state ?>">
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_395") ?></label>
	    <div class="col-sm-10">
	      <input type="text" name="zipcode" class="form-control" value="<?php echo $this->user->info->zipcode ?>">
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_396") ?></label>
	    <div class="col-sm-10">
	      <input type="text" name="country" class="form-control" value="<?php echo $this->user->info->country ?>">
	    </div>
	</div>
	<?php foreach($fields->result() as $r) : ?>
	  		<div class="form-group">

			    <label for="name-in" class="col-sm-2 control-label"><?php echo $r->name ?> <?php if($r->required) : ?>*<?php endif; ?></label>
			    <div class="col-sm-10">
			    	<?php if($r->type == 0) : ?>
			    		<input type="text" class="form-control" id="name-in" name="cf_<?php echo $r->ID ?>" value="<?php echo $r->value ?>">
			    	<?php elseif($r->type == 1) : ?>
			    		<textarea name="cf_<?php echo $r->ID ?>" rows="8" class="form-control"><?php echo $r->value ?></textarea>
			    	<?php elseif($r->type == 2) : ?>
			    		 <?php $options = explode(",", $r->options); ?>
			    		 <?php $values = array_map('trim', (explode(",", $r->value))); ?>
			            <?php if(count($options) > 0) : ?>
			                <?php foreach($options as $k=>$v) : ?>
			                <div class="form-group"><input type="checkbox" name="cf_cb_<?php echo $r->ID ?>_<?php echo $k ?>" value="1" <?php if(in_array($v,$values)) echo "checked" ?>> <?php echo $v ?></div>
			                <?php endforeach; ?>
			            <?php endif; ?>
			    	<?php elseif($r->type == 3) : ?>
			    		<?php $options = explode(",", $r->options); ?>
			    		
			            <?php if(count($options) > 0) : ?>
			                <?php foreach($options as $k=>$v) : ?>
			                <div class="form-group"><input type="radio" name="cf_radio_<?php echo $r->ID ?>" value="<?php echo $k ?>" <?php if($r->value == $v) echo "checked" ?>> <?php echo $v ?></div>
			                <?php endforeach; ?>
			            <?php endif; ?>
			    	<?php elseif($r->type == 4) : ?>
			    		<?php $options = explode(",", $r->options); ?>
			            <?php if(count($options) > 0) : ?>
			                <select name="cf_<?php echo $r->ID ?>" class="form-control">
			                <?php foreach($options as $k=>$v) : ?>
			                <option value="<?php echo $k ?>" <?php if($r->value == $v) echo "selected" ?>><?php echo $v ?></option>
			                <?php endforeach; ?>
			                </select>
			            <?php endif; ?>
			    	<?php endif; ?>
			    	<span class="help-text"><?php echo $r->help_text ?></span>
			    </div>
	  	</div>
	<?php endforeach; ?>
	<p><?php echo lang("ctn_351") ?></p>
	
	<p class="panel-subheading"><?php echo lang("ctn_234") ?></p>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_235") ?></label>
	    <div class="col-sm-10">
	      <input type="checkbox" name="enable_email_notification" value="1" <?php if($this->user->info->email_notification) echo "checked" ?>>
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_424") ?></label>
	    <div class="col-sm-10">
	      <input type="checkbox" name="profile_comments" value="1" <?php if($this->user->info->profile_comments) echo "checked" ?>>
	    </div>
	</div>
	 <input type="submit" name="s" value="<?php echo lang("ctn_236") ?>" class="btn btn-primary form-control" />
<?php echo form_close() ?>
</div>
</div>
</div>


</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$('#relationship').on("change", function() {
		var status = $('#relationship').val();
		if(status == 2 || status == 3) {
			$('#relationship_user').fadeIn(10);
		} else {
			$('#relationship_user').fadeOut(10);
		}
	});
});
</script>