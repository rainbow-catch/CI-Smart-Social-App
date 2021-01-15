<div class="row">
        <div class="col-md-12">

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