<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-piggy-bank"></span> <?php echo lang("ctn_250") ?></div>
    <div class="db-header-extra"> <a href="<?php echo site_url("funds/payment_log") ?>" class="btn btn-info btn-sm"><?php echo lang("ctn_388") ?></a> <a href="funds/spend" class="btn btn-success btn-sm"><?php echo lang("ctn_731") ?></a>
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("funds") ?>"><?php echo lang("ctn_250") ?></a></li>
  <li><a href="<?php echo site_url("funds/spend") ?>"><?php echo lang("ctn_731") ?></a></li>
  <li class="active"><?php echo lang("ctn_738") ?></li>
</ol>

<p><?php echo lang("ctn_732") ?>: <strong><?php echo number_format($this->user->info->points,2) ?></strong></p>

<hr>

<p><?php echo lang("ctn_739") ?></p>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open(site_url("funds/submit_ad_pro"), array("class" => "form-horizontal")) ?>
<div class="form-group">
                    <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_81") ?></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="email-in" name="name" value="">
                    </div>
            </div>
           <div class="form-group">
                    <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_708") ?></label>
                    <div class="col-md-9">
                        <textarea name="advert" id="advert"></textarea>
                    </div>
            </div>
            <div class="form-group">
                    <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_561") ?></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="pageviews" id="pageviews" value="1000">
                        <span class="help-block"><?php echo lang("ctn_728") ?><br /><br /><?php echo lang("ctn_729") ?>: <strong><?php echo $this->settings->info->credit_price_pageviews ?> <?php echo lang("ctn_350") ?></strong><br /><br /><?php echo lang("ctn_730") ?>: <strong><span id="pageviews_cost"><?php echo $this->settings->info->credit_price_pageviews ?></span> <?php echo lang("ctn_350") ?></strong> </span>
                    </div>
            </div>
            
<input type="submit" class="btn btn-primary btn-sm form-control" value="<?php echo lang("ctn_740") ?>" />
<?php echo form_close() ?>
</div>
</div>

</div>
<script type="text/javascript">
$(document).ready(function() {
CKEDITOR.replace('advert', { height: '150'});
$('#pageviews').on("change", function() {
	var cost = <?php echo $this->settings->info->credit_price_pageviews ?>;
	var val = $('#pageviews').val();
	var total = parseFloat(val/1000);
	var total_cost = parseFloat(total * cost);

	$('#pageviews_cost').text(total_cost);
});
});
</script>