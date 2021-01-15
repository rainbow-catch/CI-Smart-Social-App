
<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-calendar"></span> <?php echo lang("ctn_595") ?></h4>
      </div>
      <div class="modal-body ui-front form-horizontal">
          <?php if($type == 1) : ?>
            <h3><?php echo lang("ctn_596") ?></h3>
          <?php else : ?>
            <h3><?php echo lang("ctn_597") ?></h3>
          <?php endif; ?>
          <p><?php echo lang("ctn_575") ?>: <?php echo $event->title ?></p>
          <p><?php echo lang("ctn_598") ?>: <?php echo $attending_count ?></p>
          <p><?php echo lang("ctn_599") ?>: <?php if($attending) : ?><?php echo lang("ctn_598") ?><?php else : ?><?php echo lang("ctn_600") ?><?php endif; ?></p>
          <hr>
          <table class="table table-bordered table-hover table-striped">
            <tr class="table-header"><td><?php echo lang("ctn_339") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
            <?php foreach($users->result() as $r) : ?>
              <tr><td><?php echo $this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp, "first_name" => $r->first_name, "last_name" => $r->last_name)) ?></td><td><?php if($member->roleid == 1 || $this->common->has_permissions(array("admin", "page_admin"), $this->user)) : ?><a href="<?php echo site_url("pages/remove_event_user/" . $r->ID . "/". $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs"><?php echo lang("ctn_470") ?></a><?php endif; ?></td></tr>
            <?php endforeach; ?>
          </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
      </div>
    </div>