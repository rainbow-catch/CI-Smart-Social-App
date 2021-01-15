    <div class="row">
        <div class="col-md-2 sidebar-block" id="homepage-links">

        <ul>
        <li <?php if($type == 0) : ?>class="active"<?php endif; ?>><a href="<?php echo site_url("home") ?>"><span class="glyphicon glyphicon-home" style="color: #4490f6"></span> <?php echo lang("ctn_481") ?></a></li>
        <li><a href="<?php echo site_url("profile/" . $this->user->info->username) ?>"><span class="glyphicon glyphicon-user sidebaricon" style="color: #4490f6"></span> <?php echo lang("ctn_200") ?></a></li>
        <li><a href="<?php echo site_url("chat") ?>"><span class="glyphicon glyphicon-envelope sidebaricon" style="color: #4490f6"></span> <?php echo lang("ctn_482") ?></a></li>
        <?php if($this->settings->info->enable_blogs) : ?>
          <li><a href="<?php echo site_url("blog/your") ?>"><span class="glyphicon glyphicon-pencil sidebaricon" style="color: #4490f6"></span> <?php echo lang("ctn_780") ?></a></li>
        <?php endif; ?>
        <li><a href="<?php echo site_url("user_settings") ?>"><span class="glyphicon glyphicon-cog sidebaricon" style="color: #4490f6"></span> <?php echo lang("ctn_156") ?></a></li>
        </ul>

        <p class="sidebar-title"><?php echo lang("ctn_525") ?></p>
        <ul>
        <li><a href="<?php echo site_url("profile/albums/" . $this->user->info->ID) ?>"><span class="glyphicon glyphicon-picture sidebaricon" style="color: #4490f6"></span> <?php echo lang("ctn_483") ?></a></li>
        <li><a href="<?php echo site_url("pages/your") ?>"><span class="glyphicon glyphicon-duplicate sidebaricon" style="color: #4490f6"></span> <?php echo lang("ctn_484") ?></a></li>
        <?php if($this->settings->info->enable_blogs) : ?>
          <li><a href="<?php echo site_url("blog/new_posts") ?>"><span class="glyphicon glyphicon-pencil sidebaricon" style="color: #4490f6"></span> <?php echo lang("ctn_772") ?></a></li>
        <?php endif; ?>
        <li <?php if($type == 2) : ?>class="active"<?php endif; ?>><a href="<?php echo site_url("home/index/2") ?>"><span class="glyphicon glyphicon-list-alt sidebaricon" style="color: #4490f6"></span> <?php echo lang("ctn_485") ?></a></li>
        <?php if($this->settings->info->payment_enabled) : ?>
        <li><a href="<?php echo site_url("funds") ?>"><span class="glyphicon glyphicon-piggy-bank sidebaricon" style="color: #4490f6"></span> <?php echo lang("ctn_250") ?></a></li>
        <?php endif; ?>
        </ul>
        <?php if($this->common->has_permissions(array("admin", "admin_members", "admin_payment", "admin_settings", "post_admin", "page_admin"), $this->user)) : ?>
          <p class="sidebar-title"><?php echo lang("ctn_35") ?></p>
          <ul>
        <?php endif; ?>
        <?php if($this->common->has_permissions(array("admin", "admin_members", "admin_payment", "admin_settings"), $this->user)) : ?>
        <li><a href="<?php echo site_url("admin") ?>"><span class="glyphicon glyphicon-tower sidebaricon" style="color: #4490f6"></span> <?php echo lang("ctn_35") ?></a></li>
        <?php endif; ?>
        <?php if($this->common->has_permissions(array("admin", "post_admin"), $this->user)) : ?>
          <li <?php if($type == 4) : ?>class="active"<?php endif; ?>><a href="<?php echo site_url("home/index/4") ?>"><span class="glyphicon glyphicon-tower sidebaricon" style="color: #4490f6"></span> <?php echo lang("ctn_486") ?></a></li>
        <?php endif; ?>
        <?php if($this->common->has_permissions(array("admin", "page_admin"), $this->user)) : ?>
          <li><a href="<?php echo site_url("pages/all") ?>"><span class="glyphicon glyphicon-tower sidebaricon" style="color: #4490f6"></span> <?php echo lang("ctn_487") ?></a></li>
        <?php endif; ?>
        <?php if($this->common->has_permissions(array("admin", "admin_members", "admin_payment", "admin_settings", "post_admin", "page_admin"), $this->user)) : ?>
        </ul>
      <?php endif; ?>

        <hr>

        <p class="sidebar-title">
        <?php echo lang("ctn_526") ?></p>
        <ul>
          <?php foreach($hashtags->result() as $r) : ?>
            <li><a href="<?php echo site_url("home/index/1/" . $r->hashtag) ?>">#<?php echo $r->hashtag ?></a></li>
          <?php endforeach; ?>
        </ul>
        </div>
        <div class="col-md-6">
 <?php include(APPPATH . "views/feed/editor.php"); ?>

<div id="home_posts">

</div>


  </div>

        <div class="col-md-4" id="homepage-stuff">
        
        <div class="page-block">
          <div class="page-block-inner" style="background: url(<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative . "/" . $this->user->info->profile_header ?>) center center; background-size: cover;">
          <div class="page-block-avatar">
          <img src="<?php echo base_url() ?>/<?php echo $this->settings->info->upload_path_relative ?>/<?php echo $this->user->info->avatar ?>">
          </div> 
          <div class="page-block-info">
          <a href="<?php echo site_url("profile/" . $this->user->info->username) ?>"><?php echo $this->user->info->first_name ?> <?php echo $this->user->info->last_name ?></a>
          </div>
          </div>
        </div>

        <?php if($this->settings->info->enable_google_ads_feed) : ?>
          <div class="page-block half-separator">
            <div class="page-block-page clearfix">
            <?php include(APPPATH . "/views/home/google_ads.php"); ?>
          </div>
          </div>
        <?php endif; ?>

        <?php if($this->settings->info->enable_rotation_ads_feed) : ?>
          <?php include(APPPATH . "/views/home/rotation_ads.php"); ?>
        <?php endif; ?>

        <div class="page-block half-separator">
         <div class="page-block-title"><?php echo lang("ctn_527") ?></div>
         <?php foreach($users->result() as $r) : ?>
          <div class="page-block-page clearfix">
            <div class="pull-left" style="margin-right: 15px;">
              <img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $r->avatar ?>" width="40">
            </div>
            <div class="pull-left" style="padding-top: 8px;">
              <a href="<?php echo site_url("profile/" . $r->username) ?>"><?php echo $r->first_name ?> <?php echo $r->last_name ?></a>
              
            </div>
          </div>
         <?php endforeach; ?>
        </div>


        <div class="page-block half-separator">
         <div class="page-block-title"><?php echo lang("ctn_528") ?></div>
         <?php foreach($pages->result() as $r) : ?>
          <?php 
          if(!empty($r->slug)) {
            $slug = $r->slug;
          } else {
            $slug = $r->ID;
          } ?>
         	<div class="page-block-page clearfix">
         		<div class="pull-left" style="margin-right: 5px;">
         			<img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $r->profile_avatar ?>" width="40">
         		</div>
         		<div class="pull-left">
         			<a href="<?php echo site_url("pages/view/" . $slug) ?>"><?php echo $r->name ?></a>
         			<p class="small-text faded-icon"><?php echo $r->members ?> Members</p>
         		</div>
         	</div>
         <?php endforeach; ?>
        </div>

        </div>
      </div>

<script type="text/javascript">
var global_page = 0;
var hide_prev = 0;



$(document).ready(function() {
  load_posts();

});

function load_posts_wrapper() 
{
  load_posts();
}

<?php if($type == 0) : ?>
function load_posts() 
{
  $.ajax({
    url: global_base_url + 'feed/load_home_posts',
    type: 'GET',
    data: {
    },
    success: function(msg) {
      $('#home_posts').html(msg);
      $('#home_posts').jscroll({
        nextSelector : '.load_next'
      });
     
    }
  })
}
<?php elseif($type == 1) : ?>
function load_posts() 
{
  $.ajax({
    url: global_base_url + 'feed/load_hashtag_posts',
    type: 'GET',
    data: {
      hashtag : "<?php echo $hashtag ?>",
    },
    success: function(msg) {
      $('#home_posts').html(msg);
      $('#home_posts').jscroll({
          nextSelector : '.load_next'
      });
    }
  })
}
<?php elseif($type == 2) : ?>
function load_posts() 
{
  $.ajax({
    url: global_base_url + 'feed/load_saved_posts',
    type: 'GET',
    data: {
    },
    success: function(msg) {
      $('#home_posts').html(msg);
      $('#home_posts').jscroll({
          nextSelector : '.load_next'
      });
    }
  })
}
<?php elseif($type == 3) : ?>
var commentid = <?php echo $commentid ?>;
var replyid = <?php echo $replyid ?>;
function load_posts() 
{
  $.ajax({
    url: global_base_url + 'feed/load_single_post/<?php echo $postid ?>',
    type: 'GET',
    data: {
    },
    success: function(msg) {
      $('#home_posts').html(msg);
      if(commentid > 0) {
        // Load comment up
        load_single_comment(<?php echo $postid ?>,commentid, replyid);
      }
    }
  })
}
<?php elseif($type == 4) : ?>
function load_posts() 
{
  $.ajax({
    url: global_base_url + 'feed/load_all_posts',
    type: 'GET',
    data: {
    },
    success: function(msg) {
      $('#home_posts').html(msg);
      $('#home_posts').jscroll({
          nextSelector : '.load_next'
      });
    }
  })
}
<?php endif; ?>
</script>