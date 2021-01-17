<div class="row">

<div class="col-md-3">
<?php include(APPPATH . "views/user_settings/sidebar.php"); ?>
</div>

 <div class="col-md-9">


<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-eye-open"></span> <?php echo lang("ctn_851") ?></div>
    <div class="db-header-extra">
</div>
</div>

<div class="panel panel-default">
<div class="panel-body">
<p class="panel-subheading"><?php echo lang("ctn_852") ?></p>
<p class="panel-subheading">
    <?php
    echo "Your current ideology is <strong>".$this->user->info->ideology_name."</strong>.";
    if($this->user->info->old_ideology)
        echo "<br/> Old one is <strong>".$this->user->info->old_ideology_name."</strong>. And you can't change ideology any more.";
    else
        echo "<br/> You have one chance to change ideology."
    ?>
</p>
<?php echo form_open_multipart(site_url("user_settings/ideology_set"), array("class" => "form-horizontal"));
    foreach ($questions_and_answers as $qna) {
        $answers = explode(", ", $qna['question']['answers']); ?>

	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-4 control-label"><?php echo $qna['question']['question'] ?></label>
	    <div class="col-sm-8">
	      <select name="questions[]" class="form-control">
              <option value="" <?php if(!$qna['answer']) echo "selected" ?> >- select answer -</option>
	      	<?php for($index=0; $index<count($answers); $index++) { ?>
              <option value="<?=$index+1?>" <?php if($qna['answer']==$index+1) echo "selected" ?> >
                  <?=$answers[$index]?>
              </option>
	      	<?php }?>
	      </select>
	    </div>
	</div>
    <?php }?>
	<input type="submit" name="s" value="<?php echo lang("ctn_236") ?>" class="btn btn-primary form-control" />
<?php echo form_close() ?>

	</div>
</div>



</div>

</div>
</div>
