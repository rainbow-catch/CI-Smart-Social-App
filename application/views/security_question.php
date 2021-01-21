<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/19/2021
 * Time: 8:21 AM
 */
?>
<div class="white-area-content">
    <div class="db-header clearfix">
        <div class="page-header-title"> <span class="glyphicon glyphicon-lock"></span> Change Password</div>
        <div class="db-header-extra">
        </div>
    </div>

    <p>You need to confirm security questions or password to visit setting page.</p>

    <hr>


    <div class="panel panel-default">
        <div class="panel-body">
            <?php if($this->user->info->security_answer) { ?>
                <?php echo form_open(site_url("security_question/confirm_answer"), array("class" => "form-horizontal")) ?>

                <p><?=$questions->result_array()[$this->user->info->security_question_id]['question']?></p>

                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Answer</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="answer">
                    </div>
                </div>
                <input type="submit" name="s" value="Submit" class="btn btn-primary form-control" />
                <?php echo form_close() ?>
            <?php }
            else { ?>
                <?php echo form_open(site_url("security_question/confirm_password"), array("class" => "form-horizontal")) ?>

                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" name="password">
                    </div>
                </div>
                <input type="submit" name="s" value="Submit" class="btn btn-primary form-control" />
                <?php echo form_close() ?>
            <?php }?>
        </div>
    </div>

</div>
