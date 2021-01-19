<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-star"></span> <?php echo lang("ctn_1") ?></div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li class="active"><?php echo lang("ctn_799") ?></li>
</ol>

 <div class="panel panel-default">
                <div class="panel-body">
 <?php echo form_open_multipart(site_url("admin/edit_ideology_question_pro/" . $question->ID), array("class" => "form-horizontal")) ?>
                    <div class="form-group">
                        <label for="email-in" class="col-md-3 label-heading">Question</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="email-in" name="question" value="<?=$question->question?>" required>
                            <span class="help-block">Here, please type question.</span>
                        </div>
                    </div>

                    <h5>Followings are available ideologies, and you can input answers to all or part of these inputs.</h5>
                    <br/>
                    <?php $answers=(array) json_decode($question->answers);?>
                    <?php foreach($ideologies as $ID => $ideology) {?>
                        <div class="form-group">
                            <label for="icon" class="col-md-3 label-heading"><?=$ideology?></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="answers[<?=$ID?>]" value="<?=$answers[$ID]?? null?>">
                            </div>
                        </div>
                    <?php }?>
                    <div class="form-group">
                        <label for="email-in" class="col-md-3 label-heading">Active</label>
                        <div class="col-md-9">
                            <select class="form-control" name="active">
                                <option value="1" <?php if($question->active) echo "selected"?>>Active</option>
                                <option value="0" <?php if(!$question->active) echo "selected"?>>Inactive</option>
                            </select>
                            <span class="help-block">Active/Disable</span>
                        </div>
                    </div>
                    <input type="submit" class="btn btn-primary form-control" value="Save" />

                    <?php echo form_close() ?>
                </div>
         </div>
    </div>

</div>
