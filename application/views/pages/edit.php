<script src="<?php echo base_url() ?>scripts/custom/get_usernames.js"></script>
<div class="row">
        <div class="col-md-12">
        	<div class="white-area-content">
        		
        		<div class="db-header clearfix">
				    <div class="page-header-title"> <span class="glyphicon glyphicon-file"></span> <?php echo lang("ctn_563") ?> <?php echo $page->name ?></div>
				    <div class="db-header-extra"> 
				</div>
				</div>

                <?php echo form_open_multipart(site_url("pages/edit_page_pro/" . $page->ID), array("class" => "form-horizontal")) ?>
                <div class="panel panel-default">
                <div class="panel-body">
                <p class="panel-subheading"><?php echo lang("ctn_564") ?></p>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_447") ?></label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" value="<?php echo $page->name ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_534") ?></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="description" value="<?php echo $page->description ?>">
                    </div>
                </div>
                <?php if(!$this->settings->info->page_slugs) : ?>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_535") ?></label>
                        <div class="col-sm-7">
                            <input type="text" name="slug" class="form-control" id="slug-check" value="<?php echo $page->slug ?>">
                            <span class="help-block"><?php echo lang("ctn_536") ?> <?php echo site_url("pages/view/") ?><strong>my-unique-slug</strong></span>
                        </div>
                        <div class="col-sm-3" id="slug-msg">

                        </div>
                    </div>
                <?php endif; ?>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_537") ?></label>
                    <div class="col-sm-10">
                        <select name="categoryid" class="form-control">
                            <?php foreach($categories->result() as $r) : ?>
                                <option value="<?php echo $r->ID ?>" <?php if($page->categoryid == $r->ID) echo "selected" ?>><?php echo $r->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_538") ?></label>
                    <div class="col-sm-10">
                        <select name="type" class="form-control">
                            <option value="0"><?php echo lang("ctn_539") ?></option>
                            <option value="1" <?php if($page->type == 1) echo "selected" ?>><?php echo lang("ctn_540") ?></option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_829") ?></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="pay_to_join" value="<?php echo $page->pay_to_join ?>">
                        <span class="help-block"><?php echo lang("ctn_830") ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_831") ?></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="pay_to_user" id="username-search" value="<?php if(isset($username_pay)) echo $username_pay ?>">
                        <span class="help-block"><?php echo lang("ctn_832") ?></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_541") ?></label>
                    <div class="col-sm-10">
                    <img src="<?php echo base_url() ?>/<?php echo $this->settings->info->upload_path_relative ?>/<?php echo $page->profile_avatar ?>" />
                    
                    <input type="file" name="userfile" /> 
                    
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_542") ?></label>
                    <div class="col-sm-10">
                    <img src="<?php echo base_url() ?>/<?php echo $this->settings->info->upload_path_relative ?>/<?php echo $page->profile_header ?>" width="100%" />
                        <input type="file" name="userfile_profile" /> 
                    </div>
                </div>
                <h4><?php echo lang("ctn_543") ?></h4>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_497") ?></label>
                    <div class="col-sm-10">
                        <input type="text" name="location" class="form-control map_name" value="<?php echo $page->location ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_24") ?></label>
                    <div class="col-sm-10">
                        <input type="text" name="email" class="form-control" value="<?php echo $page->email ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_544") ?></label>
                    <div class="col-sm-10">
                        <input type="text" name="phone" class="form-control" value="<?php echo $page->phone ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_545") ?></label>
                    <div class="col-sm-10">
                        <input type="text" name="website" class="form-control" value="<?php echo $page->website ?>">
                    </div>
                </div>
                <h4><?php echo lang("ctn_156") ?></h4>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_546") ?></label>
                    <div class="col-sm-10">
                    <select name="posting_status" class="form-control">
                        <option value="0"><?php echo lang("ctn_547") ?></option>
                        <option value="1" <?php if($page->posting_status == 1) echo "selected" ?>><?php echo lang("ctn_548") ?></option>
                        <option value="2" <?php if($page->posting_status == 2) echo "selected" ?>><?php echo lang("ctn_549") ?></option>
                    </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_550") ?></label>
                    <div class="col-sm-10">
                    <select name="nonmembers_view" class="form-control">
                        <option value="0"><?php echo lang("ctn_53") ?></option>
                        <option value="1" <?php if($page->nonmembers_view) echo "selected" ?>><?php echo lang("ctn_54") ?></option>
                    </select>
                    </div>
                </div>

                <input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_565") ?>">

                </div>
                </div>
                <?php echo form_close() ?>


        	</div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#slug-check').on("change", function() {
                var slug = $('#slug-check').val();
                $.ajax({
                    url: global_base_url + 'pages/check_slug',
                    type: 'GET',
                    data: {
                        slug : slug
                    },
                    dataType : 'json',
                    success: function(msg) {
                        if(msg.error) {
                            $('#slug-msg').html(msg.error_msg);
                            return;
                        }
                        if(msg.status == 0) {
                            $('#slug-msg').html(msg.status_msg);
                        } else if(msg.status == 1) {
                            $('#slug-msg').html(msg.status_msg);
                        }
                        return;
                    }
                })
            });
        });
    </script>