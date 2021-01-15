 <div class="row">
 <div class="col-md-12">


 <div class="profile-header" style="background: url(<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative . "/" . $page->profile_header ?>) center center; background-size: cover;">
 <div class="profile-header-avatar">
	<img src="<?php echo base_url() ?>/<?php echo $this->settings->info->upload_path_relative ?>/<?php echo $page->profile_avatar ?>">
 </div>
 <div class="profile-header-options">
  <?php if( (isset($member) && $member != null && $member->roleid == 1) || ($this->common->has_permissions(array("admin", "page_admin"), $this->user)) ) : ?> 
<a href="<?php echo site_url("pages/edit_page/" . $page->ID) ?>" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-cog"></span></a> <a href="<?php echo site_url("pages/delete_page/" . $page->ID . "/" . $this->security->get_csrf_hash()) ?>" onclick="return confirm('<?php echo lang("ctn_551") ?>')" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></a>
<?php endif; ?>
 </div>
 <div class="profile-header-name">
<?php echo $page->name ?>
 </div>
 </div>
 <div class="profile-header-bar clearfix">
 <ul>
  <li><a href="<?php echo site_url("pages/view/" . $slug) ?>"><?php echo lang("ctn_552") ?></a></li>
  <li><a href="<?php echo site_url("pages/members/" . $slug) ?>"><?php echo lang("ctn_21") ?></a></li>
  <li><a href="<?php echo site_url("pages/albums/" . $slug) ?>"><?php echo lang("ctn_483") ?></a></li>
  <li class="active"><a href="<?php echo site_url("pages/events/" . $slug) ?>"><?php echo lang("ctn_553") ?></a></li>
 </ul>

 <div class="pull-right profile-friend-box">

 <?php if($member == null) : ?>
    <a href="<?php echo site_url("pages/join_page/" . $page->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_554") ?></a>
  <?php else : ?>
    <a href="<?php echo site_url("pages/leave_page/" . $page->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-ok"></span> <?php echo lang("ctn_34") ?></a> 
  <?php endif; ?>
  
 </div>
 </div>

 <div class="row separator">

 <div class="col-md-12">


<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-calendar"></span> <?php echo lang("ctn_553") ?></div>
    <div class="db-header-extra form-inline"> </div>
</div>


<div id="calendar">

</div>


</div>



</div>
 </div>

 </div>
</div>

<?php if( (isset($member) && $member != null && $member->roleid == 1) || ($this->common->has_permissions(array("admin", "page_admin"), $this->user)) ) : ?> 
<!-- Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-calendar"></span> <?php echo lang("ctn_569") ?></h4>
      </div>
      <div class="modal-body">
         <?php echo form_open(site_url("pages/add_event/" . $page->ID), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_570") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="name" value="">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_571") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="description">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_497") ?></label>
                    <div class="col-md-8">
                      <input type="text" name="location" id="map_name" class="form-control map_name">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_572") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control datetimepicker" name="start_date" id="start_date">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_573") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control datetimepicker" name="end_date" id="end_date">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_574") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" class="form-control" name="feed_post" value="1" checked>
                    </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_569") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>


<!-- Modal -->
<div class="modal fade" id="editEventModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-folder-open"></span> <?php echo lang("ctn_575") ?></h4>
      </div>
      <?php if($member != null && $member->roleid == 1) : ?>
      <div class="modal-body">
         <?php echo form_open(site_url("pages/edit_event_pro/"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_570") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="name" value="" id="event_name">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_575") ?></label>
                    <div class="col-md-8 ui-front">
                       <a href="<?php echo site_url("pages/view_event/") ?>" id="event_url"><?php echo lang("ctn_576") ?></a>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_271") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="description" id="event_desc">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_497") ?></label>
                    <div class="col-md-8">
                      <input type="text" name="location" id="map_name_d" class="form-control map_name">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_572") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control datetimepicker" name="start_date" id="event_start_date">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_573") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control datetimepicker" name="end_date" id="event_end_date">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_57") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="delete" value="1">
                    </div>
            </div>
            <input type="hidden" name="eventid" id="event_id" value="0" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_13") ?>">
        <?php echo form_close() ?>
      </div>
  <?php else : ?>
  	<div class="modal-body form-horizontal">
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_570") ?></label>
                    <div class="col-md-8 ui-front">
                        <span id="event_name"></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_575") ?></label>
                    <div class="col-md-8 ui-front">
                       <a href="<?php echo site_url("pages/view_event/") ?>" id="event_url"><?php echo lang("ctn_576") ?></a>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_271") ?></label>
                    <div class="col-md-8 ui-front">
                        <span id="event_desc"></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_497") ?></label>
                    <div class="col-md-8">
                     <span id="map_name_d"></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_572") ?></label>
                    <div class="col-md-8">
                        <span id="event_start_date"></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_573") ?></label>
                    <div class="col-md-8">
                        <span id="event_end_date"></span>
                    </div>
            </div>
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
      </div>
  <?php endif; ?>
    </div>
  </div>
</div>
<script tye="text/javascript">
$(document).ready(function() {
    // page is now ready, initialize the calendar...
    var date_last_clicked = null;
    $.fn.modal.Constructor.prototype.enforceFocus = function() {};
    $('.datetimepicker').datetimepicker({
      format : '<?php echo $this->settings->info->calendar_picker_format ?>'
    });

    var pageid = <?php echo $page->ID ?>;

    $('#calendar').fullCalendar({
      eventSources: [
        {
           events: function(start, end, timezone, callback) {
            $.ajax({
                url: global_base_url + 'pages/get_events/',
                dataType: 'json',
                data: {
                    // our hypothetical feed requires UNIX timestamps
                    start: start.unix(),
                    end: end.unix(),
                    pageid : pageid
                },
                success: function(msg) {
                    var events = msg.events;
                    callback(events);
                }
            });
          }
        }
      ],
      timezone: 'UTC',
      dayClick: function(date, jsEvent, view) {
          var start_date = moment(date).format('<?php echo $this->common->date_php_to_momentjs($this->settings->info->calendar_picker_format) ?>');
          $('#start_date').val(start_date);
          $('#end_date').val(start_date);
          date_last_clicked = $(this);
          $(this).css('background-color', '#bed7f3');
          $('#addEventModal').modal();
       },
       columnFormat: {
           'month' : 'ddd'
       },
       timeFormat: 'HH:mm',
       eventClick: function(event, jsEvent, view) {
       	 <?php if($member != null && $member->roleid == 1) : ?>
          $('#event_name').val(event.title);
          $('#event_desc').val(event.description);
           $('#map_name_d').val(event.location);
          $('#event_start_date').val(moment(event.start).format('<?php echo $this->common->date_php_to_momentjs($this->settings->info->calendar_picker_format) ?>'));
          if(event.end) {
            $('#event_end_date').val(moment(event.end).format('<?php echo $this->common->date_php_to_momentjs($this->settings->info->calendar_picker_format) ?>'));
          } else {
            $('#event_end_date').val(moment(event.start).format('<?php echo $this->common->date_php_to_momentjs($this->settings->info->calendar_picker_format) ?>'));
          }
          $('#event_id').val(event.id);
          
          $('#editEventModal').modal();
          if (event.url) {
              $('#event_url').attr("href", event.url);
              return false;
          }
          <?php else : ?>
           $('#event_name').html(event.title);
          $('#event_desc').html(event.description);
          $('#map_name_d').html(event.location);
          $('#event_start_date').html(moment(event.start).format('<?php echo $this->common->date_php_to_momentjs($this->settings->info->calendar_picker_format) ?>'));
          if(event.end) {
            $('#event_end_date').html(moment(event.end).format('<?php echo $this->common->date_php_to_momentjs($this->settings->info->calendar_picker_format) ?>'));
          } else {
            $('#event_end_date').html(moment(event.start).format('<?php echo $this->common->date_php_to_momentjs($this->settings->info->calendar_picker_format) ?>'));
          }
          
          $('#editEventModal').modal();
          if (event.url) {
              $('#event_url').attr("href", event.url);
              return false;
          }
          <?php endif; ?>
       },
       nextDayThreshold : '01:00:00'
    })

    $('#addEventModal').on('hidden.bs.modal', function () {
        // do something…
        date_last_clicked.css('background-color', '#ffffff');
    });

});
</script>