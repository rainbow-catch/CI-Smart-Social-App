<div class="row">

<div class="col-md-3">
<?php include(APPPATH . "views/user_settings/sidebar.php"); ?>
</div>

 <div class="col-md-9">

<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-question-sign"></span> Change Security Question</div>
    <div class="db-header-extra">
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("user_settings") ?>"><?php echo lang("ctn_224") ?></a></li>
  <li class="active">Change Security Question</li>
</ol>

<p>Please set your security question following.</p>

<hr>

	<div class="panel panel-default">
  	<div class="panel-body">
  	<?php echo form_open(site_url("user_settings/change_security_question_pro"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">Question</label>
			    <div class="col-sm-10">
			      <select class="form-control" name="question">
                      <option value="">- Select question -</option>
                      <?php foreach ($questions->result_array() as $question) { ?>
                          <option value="<?=$question['ID']?>"
                              <?=$this->user->info->security_question_id==$question['ID']? 'selected':''?>
                          >
                              <?=$question['question']?>
                          </option>
                      <?php }?>
                  </select>
			    </div>
			</div>
			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">Answer</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" name="answer" value="<?=$this->user->info->security_answer?>">
			    </div>
			</div>
			 <input type="submit" name="s" value="<?php echo lang("ctn_241") ?>" class="btn btn-primary form-control" />
    <?php echo form_close() ?>
    </div>
    </div>

    </div>


</div>
</div>
