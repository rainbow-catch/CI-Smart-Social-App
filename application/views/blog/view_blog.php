<div class="row">
        <div class="col-md-12">

             <div class="white-area-content top-margin">
                <div class="blog-post-wrapper align-center">

                        <h1><a href="<?php echo site_url("blog/view_blog/". $blog->ID) ?>"><?php echo $blog->title ?></a></h1>
                        <p><?php echo $blog->description ?></p>
                        <?php if($check && $check->num_rows() == 0) : ?>
                        <p><a href="<?php echo site_url("blog/subscribe/" . $blog->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_820") ?></a></p>
                        <?php else : ?>
                        <p><a href="<?php echo site_url("blog/unsubscribe/" . $blog->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-sm"><?php echo lang("ctn_821") ?></a></p>
                        <?php endif; ?>

                </div>
            </div>

            <h2><?php echo lang("ctn_787") ?></h2>


        		<?php foreach($posts->result() as $post) : ?>
                    <div class="white-area-content top-margin">
        		<div class="blog-post-wrapper clearfix">

        			<div class="blog-post-image">
                        <div class="blog-post-user-icon">
                            <?php echo $this->common->get_user_display(array("username" => $post->username, "avatar" => $post->avatar, "online_timestamp" => $post->online_timestamp)) ?>
                        </div>
                        <?php if(!empty($post->image)) : ?>
            				<img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $post->image ?>" class="blog-post-thumb-main">
                        <?php endif; ?>
        			</div>
        			<div class="blog-post-info">
        				<a href="<?php echo site_url("blog/view/". $post->ID) ?>"><?php echo $post->title ?></a>
                        <?php
                           $summary = strip_tags($post->body);
                           $summary = substr($summary, 0, 180);
                        ?>
        				<p><?php echo $summary ?> ...</p>

                        <p><a href="<?php echo site_url("blog/view/". $post->ID) ?>"><?php echo lang("ctn_414") ?></a></p>
        			</div>

        		</div>
            </div>
        	<?php endforeach; ?>


            <?php if($total_posts >= 10) : ?>
                <div class="white-area-content top-margin align-center">
                    <?php echo $this->pagination->create_links() ?>
                </div>
            <?php endif; ?>

        </div>
    </div>