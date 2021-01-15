<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_1") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li class="active"><?php echo lang("ctn_671") ?></li>
</ol>


<hr>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open(site_url("admin/ad_settings_pro"), array("class" => "form-horizontal")) ?>

<div class="form-group">
    <label for="name-in" class="col-sm-2 control-label"><?php echo lang("ctn_672") ?></label>
    <div class="col-sm-10">
    	<input type="checkbox" id="name-in" name="enable_google_ads_feed" value="1" <?php if($this->settings->info->enable_google_ads_feed) echo "checked" ?>>
    	<span class="help-block"><?php echo lang("ctn_673") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-2 control-label"><?php echo lang("ctn_674") ?></label>
    <div class="col-sm-10">
      <input type="checkbox" id="name-in" name="enable_google_ads_pages" value="1" <?php if($this->settings->info->enable_google_ads_pages) echo "checked" ?>>
      <span class="help-block"><?php echo lang("ctn_675") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-2 control-label"><?php echo lang("ctn_676") ?></label>
    <div class="col-sm-10">
      <?php echo lang("ctn_677") ?>: <strong>application/views/home/google_ads.php</strong>. <?php echo lang("ctn_678") ?>
    </div>
</div>
<hr>
<div class="form-group">
    <label for="name-in" class="col-sm-2 control-label"><?php echo lang("ctn_679") ?></label>
    <div class="col-sm-10">
      <input type="checkbox" id="name-in" name="enable_rotation_ads_feed" value="1" <?php if($this->settings->info->enable_rotation_ads_feed) echo "checked" ?>>
      <span class="help-block"><?php echo lang("ctn_680") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-2 control-label"><?php echo lang("ctn_681") ?></label>
    <div class="col-sm-10">
      <input type="checkbox" id="name-in" name="enable_rotation_ads_pages" value="1" <?php if($this->settings->info->enable_rotation_ads_pages) echo "checked" ?>>
      <span class="help-block"><?php echo lang("ctn_682") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-2 control-label"><?php echo lang("ctn_683") ?></label>
    <div class="col-sm-10">
      <input type="checkbox" id="name-in" name="enable_promote_post" value="1" <?php if($this->settings->info->enable_promote_post) echo "checked" ?>>
      <span class="help-block"><?php echo lang("ctn_684") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-2 control-label"><?php echo lang("ctn_685") ?></label>
    <div class="col-sm-10">
      <input type="text" id="name-in" class="form-control" name="credit_price_pageviews" value="<?php echo $this->settings->info->credit_price_pageviews ?>">
      <span class="help-block"><?php echo lang("ctn_686") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-2 control-label"><?php echo lang("ctn_687") ?></label>
    <div class="col-sm-10">
      <input type="text" id="name-in" class="form-control" name="rotation_ad_alert_user" value="<?php echo $username ?>">
      <span class="help-block"><?php echo lang("ctn_688") ?></span>
    </div>
</div>
<hr>
<div class="form-group">
    <label for="name-in" class="col-sm-2 control-label"><?php echo lang("ctn_689") ?></label>
    <div class="col-sm-10">
      <input type="checkbox" id="name-in" name="enable_verified_buy" value="1" <?php if($this->settings->info->enable_verified_buy) echo "checked" ?>>
      <span class="help-block"><?php echo lang("ctn_690") ?></span>
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-2 control-label"><?php echo lang("ctn_691") ?></label>
    <div class="col-sm-10">
      <input type="text" id="name-in" class="form-control" name="verified_cost" value="<?php echo $this->settings->info->verified_cost ?>">
    </div>
</div>
<div class="form-group">
    <label for="name-in" class="col-sm-2 control-label"><?php echo lang("ctn_692") ?></label>
    <div class="col-sm-10">
      <input type="checkbox" id="name-in" name="enable_verified_requests" value="1" <?php if($this->settings->info->enable_verified_requests) echo "checked" ?>>
      <span class="help-block"><?php echo lang("ctn_693") ?> <a href="<?php echo site_url("user_settings") ?>"><?php echo lang("ctn_156") ?></a> <?php echo lang("ctn_275") ?>.</span>
    </div>
</div>


<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_13") ?>" />
<?php echo form_close() ?>

</div>
</div>
</div>