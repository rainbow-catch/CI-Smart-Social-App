<?php echo form_open(site_url("pages/edit_member_pro/" . $user->ID)) ?>
<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-person"></span> <?php echo lang("ctn_22") ?></h4>
      </div>
      <div class="modal-body ui-front form-horizontal">
          <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_568") ?></label>
                    <div class="col-md-8">
                        <select name="roleid" class="form-control">
                          <option value="0"><?php echo lang("ctn_34") ?></option>
                          <option value="1" <?php if($user->roleid == 1) echo "selected" ?>><?php echo lang("ctn_35") ?></option>
                        </select>
                    </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_13") ?>">
      </div>
    </div>
    <?php echo form_close () ?>