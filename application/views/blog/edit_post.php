<div class="row">
        <div class="col-md-12">
        	<div class="white-area-content">
        		
        		<div class="db-header clearfix">
				    <div class="page-header-title"> <span class="glyphicon glyphicon-pencil"></span> <?php echo lang("ctn_780") ?>: <?php echo $blog->title ?></div>
				    <div class="db-header-extra form-inline"> 

                         <div class="form-group has-feedback no-margin">
                            <div class="input-group">
                            <input type="text" class="form-control input-sm" placeholder="<?php echo lang("ctn_336") ?>" id="form-search-input" />
                            <div class="input-group-btn">
                                <input type="hidden" id="search_type" value="0">
                                    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                                    <ul class="dropdown-menu small-text" style="min-width: 90px !important; left: -90px;">
                                      <li><a href="#" onclick="change_search(0)"><span class="glyphicon glyphicon-ok" id="search-like"></span> <?php echo lang("ctn_337") ?></a></li>
                                      <li><a href="#" onclick="change_search(1)"><span class="glyphicon glyphicon-ok nodisplay" id="search-exact"></span> <?php echo lang("ctn_338") ?></a></li>
                                      <li><a href="#" onclick="change_search(2)"><span class="glyphicon glyphicon-ok nodisplay" id="name-exact"></span> <?php echo lang("ctn_81") ?></a></li>
                                    </ul>
                                  </div><!-- /btn-group -->
                            </div>
                            </div>

                            <a href="<?php echo site_url("blog/add_post") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_781") ?></a> <a href="<?php echo site_url("blog/edit_blog/" . $blog->ID) ?>" class="btn btn-warning btn-sm"><?php echo lang("ctn_782") ?></a> <a href="<?php echo site_url("blog/delete_blog/" . $blog->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-sm"><?php echo lang("ctn_783") ?></a>
                            
				</div>
				</div>


                <?php echo form_open_multipart(site_url("blog/edit_post_pro/" . $post->ID), array("class" => "form-horizontal")) ?>
            <div class="panel panel-default">
            <div class="panel-body">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_767") ?></label>
                <div class="col-sm-10">
                    <input type="text" name="title" class="form-control" value="<?php echo $post->title ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_770") ?></label>
                <div class="col-sm-10">
                    <?php if(!empty($post->image)) : ?>
                        <img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $post->image ?>" class="blog-post-thumb">
                    <?php endif; ?>
                    <input type="file" name="userfile" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_506") ?></label>
                <div class="col-sm-10">
                    <textarea name="blog_post" id="post"><?php echo $post->body ?></textarea>
                </div>
            </div>
           <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_606") ?></label>
                <div class="col-sm-10">
                    <select name="status" class="form-control">
                      <option value="0"><?php echo lang("ctn_768") ?></option>
                      <option value="1" <?php if($post->status == 1) echo "selected" ?>><?php echo lang("ctn_769") ?></option>
                    </select>
                </div>
            </div>
            <?php if($post->timestamp == 0) : ?>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_784") ?></label>
                    <div class="col-sm-10">
                        <input type="checkbox" name="post_to_timeline" value="1" checked> <?php echo lang("ctn_53") ?>
                    </div>
                </div>
            <?php endif; ?>

            <input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_13") ?>">

            </div>
            </div>
            <?php echo form_close() ?>




        	</div>
        </div>
    </div>

        <script type="text/javascript">
CKEDITOR.replace('post', { height: '300'});

</script>