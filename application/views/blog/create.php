<div class="row">
        <div class="col-md-12">
        	<div class="white-area-content">
        		
        		<div class="db-header clearfix">
				    <div class="page-header-title"> <span class="glyphicon glyphicon-pencil"></span> <?php echo lang("ctn_785") ?></div>
				    <div class="db-header-extra form-inline"> 

                             
    				</div>
    				</div>


             <?php echo form_open_multipart(site_url("blog/create_pro"), array("class" => "form-horizontal")) ?>
                <div class="panel panel-default">
                <div class="panel-body">
                <p class="panel-subheading"><?php echo lang("ctn_786") ?></p>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_773") ?></label>
                    <div class="col-sm-10">
                        <input type="text" name="title" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_774") ?></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="description" value="">
                    </div>
                </div>
               <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_775") ?></label>
                    <div class="col-sm-10">
                        <select name="private" class="form-control">
                          <option value="0"><?php echo lang("ctn_539") ?></option>
                          <option value="1"><?php echo lang("ctn_633") ?></option>
                        </select>
                    </div>
                </div>

                <input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_785") ?>">

                </div>
                </div>
                <?php echo form_close() ?>




        	</div>
        </div>
    </div>