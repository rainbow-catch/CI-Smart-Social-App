<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-piggy-bank"></span> <?php echo lang("ctn_250") ?></div>
    <div class="db-header-extra"> <a href="<?php echo site_url("funds/payment_log") ?>" class="btn btn-info btn-sm"><?php echo lang("ctn_388") ?></a> <a href="funds/spend" class="btn btn-success btn-sm"><?php echo lang("ctn_731") ?></a>
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("funds") ?>"><?php echo lang("ctn_250") ?></a></li>
  <li class="active"><?php echo lang("ctn_731") ?></li>
</ol>

<p><?php echo lang("ctn_732") ?>: <strong><?php echo number_format($this->user->info->points,2) ?></strong></p>

<hr>

<div class="spend-block">
	<p><img src="<?php echo base_url() ?>images/pin.png"></p>
	<p><strong><?php echo lang("ctn_733") ?></strong></p>
	<p><?php echo lang("ctn_734") ?></p>
	<p><a href="<?php echo site_url("funds/submit_ad") ?>"><?php echo lang("ctn_735") ?></a></p>
</div>

<?php if($this->settings->info->enable_verified_buy) : ?>
<div class="spend-block">
	<p><img src="<?php echo base_url() ?>images/verified_large.png"></p>
	<p><strong><?php echo lang("ctn_736") ?></strong></p>
	<p><?php echo lang("ctn_737") ?></p>
	<p><a href="<?php echo site_url("funds/verified") ?>"><?php echo lang("ctn_735") ?></a></p>
</div>
<?php endif; ?>

</div>