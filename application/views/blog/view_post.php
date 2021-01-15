<div class="row">
        <div class="col-md-12">
        	<div class="white-area-content">

                <div class="blog-author">
                    <?php if($author) : ?>
                            <a href="<?php echo site_url("profile/" . $author->username) ?>"><img src="<?php echo base_url() ?>/<?php echo $this->settings->info->upload_path_relative ?>/<?php echo $author->avatar ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $author->first_name ?> <?php echo $author->last_name ?>"></a>
                    <?php endif; ?>
                </div>

                <div class="blog-date">
                    <?php echo lang("ctn_788") ?> <?php echo date($this->settings->info->date_format, $post->timestamp) ?>
                </div>
        		
        	 <h1 class="align-center blog-post-title"><?php echo $post->title ?></h1>
             <?php if(!empty($post->image)) : ?>
                <p class="align-center"><img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $post->image ?>"></p>
             <?php endif; ?>

             <?php echo $post->body ?>

             <hr>

             <p><?php echo lang("ctn_789") ?> <a href="<?php echo site_url("blog/view_blog/" . $blog->ID) ?>"><?php echo $blog->title ?></a>


        	</div>
        </div>
    </div>
