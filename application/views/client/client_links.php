<div id="responsive-menu-links">
          <select name='link' OnChange="window.location.href=$(this).val();" class="form-control">
          <option value='<?php echo site_url("home") ?>'><?php echo lang("ctn_481") ?></option>
          <option value='<?php echo site_url("profile/" . $this->user->info->username) ?>'><?php echo lang("ctn_200") ?></option>
          <option value='<?php echo site_url("chat") ?>'><?php echo lang("ctn_482") ?></option>
          <option value='<?php echo site_url("user_settings") ?>'><?php echo lang("ctn_156") ?></option>
          <option value='<?php echo site_url("profile/albums/" . $this->user->info->ID) ?>'><?php echo lang("ctn_483") ?></option>
          <option value='<?php echo site_url("pages/your") ?>'><?php echo lang("ctn_484") ?></option>
          <option value='<?php echo site_url("home/index/2") ?>'><?php echo lang("ctn_485") ?></option>
          <?php if($this->common->has_permissions(array("admin", "admin_members", "admin_payment", "admin_settings"), $this->user)) : ?>
	          <option value='<?php echo site_url("admin") ?>'><?php echo lang("ctn_35") ?></option>
	      <?php endif; ?>
	      <?php if($this->common->has_permissions(array("admin", "post_admin"), $this->user)) : ?>
	          <option value='<?php echo site_url("home/index/4") ?>'><?php echo lang("ctn_486") ?></option>
	      <?php endif; ?>
	      <?php if($this->common->has_permissions(array("admin", "page_admin"), $this->user)) : ?>
	          <option value='<?php echo site_url("pages/all") ?>'><?php echo lang("ctn_487") ?></option>
	      <?php endif; ?>
      </select>
  </div>