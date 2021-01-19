<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/18/2021
 * Time: 10:10 PM
 */
?>
<script src="<?php echo base_url();?>scripts/libraries/sortable/Sortable.min.js"></script>
<div class="white-area-content">
    <div class="db-header clearfix">
        <div class="page-header-title"> <span class="glyphicon glyphicon-star"></span> <?php echo lang("ctn_1") ?></div>
        <div class="db-header-extra"><input type="button" class="btn btn-primary btn-sm" value="Add Ideology" data-toggle="modal" data-target="#memberModal" />
        </div>
    </div>

    <ol class="breadcrumb">
        <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
        <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
        <li class="active">Ideology Questions</li>
    </ol>

    <p>With Ideologies, you can easily classify other users.</p>

    <table class="table table-bordered">
        <tr class="table-header"><td>Question</td><td>Answer Count</td><td>Active</td><td>Options</td></tr>
        <?php foreach($questions as $r) { ?>
            <tr><td><?php echo $r['question'] ?></td>
                <td>
                    <?php $answers = json_decode($r['answers']);?>
                    <span><?=count(array_keys((array) $answers))?></span>
                </td>
                <td>
                    <span><?=$r['active']?"Active":"Inactive"?></span>
                </td>
                <td>
                    <a href="<?php echo site_url("admin/edit_ideology_question/" . $r['ID']) ?>" class="btn btn-warning btn-xs" title="<?php echo lang("ctn_55") ?>">
                        <span class="glyphicon glyphicon-cog"></span>
                    </a>
                    <a href="<?php echo site_url("admin/delete_ideology_question/" . $r['ID'] . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs" onclick="return confirm('<?php echo lang("ctn_317") ?>')" title="<?php echo lang("ctn_57") ?>">
                        <span class="glyphicon glyphicon-trash"></span>
                    </a>
                </td></tr>
        <?php } ?>
    </table>

    <div class="modal fade" id="memberModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add New Question</h4>
                </div>
                <div class="modal-body">

                    <?php echo form_open_multipart(site_url("admin/add_ideology_question_pro"), array("class" => "form-horizontal", "id" => "ideologies")) ?>

                    <div class="form-group">
                        <label for="email-in" class="col-md-3 label-heading">Question</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="email-in" name="question" required>
                        </div>
                    </div>

                    <hr/>
                    <h5>Followings are available ideologies, and you can input answers to all or part of these inputs.</h5>
                    <br/>
                    <?php foreach($ideologies as $ID => $ideology) {?>
                    <div class="form-group">
                        <label for="icon" class="col-md-3 label-heading"><?=$ideology?></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="answers[<?=$ID?>]" value="">
                        </div>
                    </div>
                    <?php }?>
                    <div class="form-group">
                        <label for="email-in" class="col-md-3 label-heading">Active</label>
                        <div class="col-md-9">
                            <select class="form-control" name="active">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <span class="help-block">Active/Disable</span>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="Submit" />
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>
