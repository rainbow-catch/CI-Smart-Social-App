<ul class="newnav nav nav-sidebar">
           <?php if($this->user->loggedin && isset($this->user->info->user_role_id) && 
           ($this->user->info->admin || $this->user->info->admin_settings || $this->user->info->admin_members || $this->user->info->admin_payment)

           ) : ?>
              <li id="admin_sb">
                <a data-toggle="collapse" data-parent="#admin_sb" href="#admin_sb_c" class="collapsed <?php if(isset($activeLink['admin'])) echo "active" ?>" >
                  <span class="glyphicon glyphicon-wrench sidebar-icon sidebar-icon-red"></span> <?php echo lang("ctn_157") ?>
                  <span class="plus-sidebar"><span class="glyphicon <?php if(isset($activeLink['admin'])) : ?>glyphicon-menu-down<?php else : ?>glyphicon-menu-right<?php endif; ?>"></span></span>
                </a>
                <div id="admin_sb_c" class="panel-collapse collapse sidebar-links-inner <?php if(isset($activeLink['admin'])) echo "in" ?>">
                  <ul class="inner-sidebar-links">
                    <?php if($this->user->info->admin || $this->user->info->admin_settings) : ?>
                      <li class="<?php if(isset($activeLink['admin']['settings'])) echo "active" ?>"><a href="<?php echo site_url("admin/settings") ?>"><?php echo lang("ctn_158") ?></a></li>
                      <li class="<?php if(isset($activeLink['admin']['social_settings'])) echo "active" ?>"><a href="<?php echo site_url("admin/social_settings") ?>"> <?php echo lang("ctn_159") ?></a></li>
                      <li class="<?php if(isset($activeLink['admin']['ad_settings'])) echo "active" ?>"><a href="<?php echo site_url("admin/ad_settings") ?>"> <?php echo lang("ctn_671") ?></a></li>
                      <li class="<?php if(isset($activeLink['admin']['rotation_ads'])) echo "active" ?>"><a href="<?php echo site_url("admin/rotation_ads") ?>"> <?php echo lang("ctn_706") ?></a></li>
                      <li class="<?php if(isset($activeLink['admin']['promoted_posts'])) echo "active" ?>"><a href="<?php echo site_url("admin/promoted_posts") ?>"> <?php echo lang("ctn_705") ?></a></li>
                      <li class="<?php if(isset($activeLink['admin']['verified_requests'])) echo "active" ?>"><a href="<?php echo site_url("admin/verified_requests") ?>"> <?php echo lang("ctn_694") ?></a></li>
                      <li class="<?php if(isset($activeLink['admin']['page_categories'])) echo "active" ?>"><a href="<?php echo site_url("admin/page_categories") ?>"> <?php echo lang("ctn_529") ?></a></li>
                      <li class="<?php if(isset($activeLink['admin']['blogs'])) echo "active" ?>"><a href="<?php echo site_url("admin/blogs") ?>"> <?php echo lang("ctn_792") ?></a></li>
                      <li class="<?php if(isset($activeLink['admin']['blog_posts'])) echo "active" ?>"><a href="<?php echo site_url("admin/blog_posts") ?>"> <?php echo lang("ctn_793") ?></a></li>
                      <li class="<?php if(isset($activeLink['admin']['limits'])) echo "active" ?>"><a href="<?php echo site_url("admin/limits") ?>"><?php echo lang("ctn_815") ?></a></li>
                    <?php endif; ?>
                    <?php if($this->user->info->admin || $this->user->info->admin_members) : ?>
                    <li class="<?php if(isset($activeLink['admin']['members'])) echo "active" ?>"><a href="<?php echo site_url("admin/members") ?>"> <?php echo lang("ctn_160") ?></a></li>
                    <li class="<?php if(isset($activeLink['admin']['invites'])) echo "active" ?>"><a href="<?php echo site_url("admin/invites") ?>"> Invites</a></li>
                    <li class="<?php if(isset($activeLink['admin']['custom_fields'])) echo "active" ?>"><a href="<?php echo site_url("admin/custom_fields") ?>"> <?php echo lang("ctn_346") ?></a></li>
                    <li class="<?php if(isset($activeLink['admin']['reports'])) echo "active" ?>"><a href="<?php echo site_url("admin/reports") ?>"> <?php echo lang("ctn_530") ?></a></li>
                    <?php endif; ?>
                    <?php if($this->user->info->admin) : ?>
                    <li class="<?php if(isset($activeLink['admin']['user_roles'])) echo "active" ?>"><a href="<?php echo site_url("admin/user_roles") ?>"> <?php echo lang("ctn_316") ?></a></li>
                    <?php endif; ?>
                    <?php if($this->user->info->admin || $this->user->info->admin_members) : ?>
                    <li class="<?php if(isset($activeLink['admin']['user_groups'])) echo "active" ?>"><a href="<?php echo site_url("admin/user_groups") ?>"> <?php echo lang("ctn_161") ?></a></li>
                    <li class="<?php if(isset($activeLink['admin']['ipblock'])) echo "active" ?>"><a href="<?php echo site_url("admin/ipblock") ?>"> <?php echo lang("ctn_162") ?></a></li>
                    <?php endif; ?>
                    <?php if($this->user->info->admin) : ?>
                      <li class="<?php if(isset($activeLink['admin']['email_templates'])) echo "active" ?>"><a href="<?php echo site_url("admin/email_templates") ?>"> <?php echo lang("ctn_163") ?></a></li>
                    <?php endif; ?>
                    <?php if($this->user->info->admin || $this->user->info->admin_members) : ?>
                      <li class="<?php if(isset($activeLink['admin']['email_members'])) echo "active" ?>"><a href="<?php echo site_url("admin/email_members") ?>"> <?php echo lang("ctn_164") ?></a></li>
                    <?php endif; ?>
                    <?php if($this->user->info->admin || $this->user->info->admin_payment) : ?>
                      <li class="<?php if(isset($activeLink['admin']['payment_settings'])) echo "active" ?>"><a href="<?php echo site_url("admin/payment_settings") ?>"> <?php echo lang("ctn_246") ?></a></li>
                      <li class="<?php if(isset($activeLink['admin']['payment_logs'])) echo "active" ?>"><a href="<?php echo site_url("admin/payment_logs") ?>"> <?php echo lang("ctn_288") ?></a></li>
                    <?php endif; ?>
                  </ul>
                </div>
              </li>
            <?php endif; ?>
            <li class="<?php if(isset($activeLink['home']['general'])) echo "active" ?>"><a href="<?php echo site_url() ?>"><span class="glyphicon glyphicon-home sidebar-icon sidebar-icon-blue"></span> <?php echo lang("ctn_154") ?> <span class="sr-only">(current)</span></a></li>
            <li class="<?php if(isset($activeLink['members']['general'])) echo "active" ?>"><a href="<?php echo site_url("members") ?>"><span class="glyphicon glyphicon-user sidebar-icon sidebar-icon-green"></span> <?php echo lang("ctn_155") ?></a></li>
            <li class="<?php if(isset($activeLink['settings']['general'])) echo "active" ?>"><a href="<?php echo site_url("user_settings") ?>"><span class="glyphicon glyphicon-cog sidebar-icon sidebar-icon-pink"></span> <?php echo lang("ctn_156") ?></a></li>
            
      
          </ul>