<?php echo form_open_multipart(site_url("profile/edit_image_pro/" . $image->ID)) ?>
<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-picture"></span> <?php echo lang("ctn_567") ?></h4>
      </div>
      <div class="modal-body ui-front form-horizontal">
          <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_81") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="name" value="<?php echo $image->name ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_271") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="description" value="<?php echo $image->description ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_556") ?></label>
                    <div class="col-md-8">
                        <select name="albumid" class="form-control">
                          <?php foreach($albums->result() as $r) : ?>
                            <option value="<?php echo $r->ID ?>" <?php if($r->ID == $image->albumid) echo "selected" ?>><?php echo $r->name ?></option>
                          <?php endforeach; ?>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_499") ?></label>
                    <div class="col-md-8">
                        <?php if(!empty($image->file_name )) : ?>
                          <img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $image->file_name ?>" class="preview-image">
                        <?php endif; ?>
                        <input type="file" class="form-control" name="image_file">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_500") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="image_url" placeholder="http://www ..." value="<?php echo $image->file_url ?>">
                    </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_13") ?>">
      </div>
    </div>
    <?php echo form_close () ?>