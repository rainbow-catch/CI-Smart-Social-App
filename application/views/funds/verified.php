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
  <li class="active"><?php echo lang("ctn_695") ?></li>
</ol>

<p><?php echo lang("ctn_732") ?>: <strong><?php echo number_format($this->user->info->points,2) ?></strong></p>

<hr>

<p><?php echo lang("ctn_741") ?></p>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open(site_url("funds/verified_pro"), array("class" => "form-horizontal")) ?>
<div class="form-group">
                    <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_742") ?></label>
                    <div class="col-md-9">
                        <?php echo number_format($this->settings->info->verified_cost) ?> <?php echo lang("ctn_350") ?>
                    </div>
            </div>
      
<input type="submit" class="btn btn-primary btn-sm form-control" value="<?php echo lang("ctn_743") ?>" />
<?php echo form_close() ?>
</div>
</div>

</div>