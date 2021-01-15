<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_1") ?></div>
    <div class="db-header-extra">
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li class="active"><?php echo lang("ctn_766") ?></li>
</ol>

     <?php echo form_open_multipart(site_url("admin/edit_blog_post_pro/" . $post->ID), array("class" => "form-horizontal")) ?>
            <div class="panel panel-default">
            <div class="panel-body">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_767") ?></label>
                <div class="col-sm-10">
                    <input type="text" name="title" class="form-control" value="<?php echo $post->title ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label"><?php echo lang("ctn_499") ?></label>
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
                      <option value="1" <?php if($post->status == 1) echo "selected" ?>><?php echo lang("ctn_768") ?></option>
                    </select>
                </div>
            </div>

            <input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_13") ?>">

            </div>
            </div>
            <?php echo form_close() ?>


</div>


        <script type="text/javascript">
CKEDITOR.replace('post', { height: '300'});

</script>