<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_1") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li class="active"><?php echo lang("ctn_710") ?></li>
</ol>


<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open(site_url("admin/edit_rotation_ad_pro/" . $ad->ID), array("class" => "form-horizontal")) ?>
	 <div class="form-group">
                    <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_81") ?></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="email-in" name="name" value="<?php echo $ad->name ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_606") ?></label>
                    <div class="col-md-9">
                        <select name="status" class="form-control">
                        <option value="0"><?php echo lang("ctn_701") ?></option>
                        <option value="1" <?php if($ad->status == 1) echo "selected" ?>><?php echo lang("ctn_702") ?></option>
                        <option value="2" <?php if($ad->status == 2) echo "selected" ?>><?php echo lang("ctn_703") ?></option>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_708") ?></label>
                    <div class="col-md-9">
                        <textarea name="advert" id="advert"><?php echo $ad->advert ?></textarea>
                    </div>
            </div>
            <div class="form-group">
                        <label for="password-in" class="col-md-3 label-heading"><?php echo lang("ctn_561") ?></label>
                        <div class="col-md-9">
                            <input type="text" name="pageviews" class="form-control" value="<?php echo $ad->pageviews ?>">
                            <span class="help-block"><?php echo lang("ctn_709") ?></span>
                        </div>
                </div>
<input type="submit" class="btn btn-primary btn-sm form-control" value="<?php echo lang("ctn_13") ?>" />
<?php echo form_close() ?>
</div>
</div>


</div>
<script type="text/javascript">
$(document).ready(function() {
CKEDITOR.replace('advert', { height: '150'});
});
</script>