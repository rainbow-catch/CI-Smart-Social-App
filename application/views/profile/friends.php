 <div class="row">
 <div class="col-md-12">


 <div class="profile-header" style="background: url(<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative . "/" . $user->profile_header ?>) center center; background-size: cover;">
 <div class="profile-header-avatar">
	<img src="<?php echo base_url() ?>/<?php echo $this->settings->info->upload_path_relative ?>/<?php echo $user->avatar ?>">
 </div>
 <div class="profile-header-name">
<?php echo $user->first_name ?> <?php echo $user->last_name ?>
 </div>
 </div>
 <div class="profile-header-bar clearfix">
 <ul>
 	<li><a href="<?php echo site_url("profile/" . $user->username) ?>"><?php echo lang("ctn_200") ?></a></li>
 	<li class="active"><a href="<?php echo site_url("profile/friends/" . $user->ID) ?>"><?php echo lang("ctn_493") ?></a></li>
  <li><a href="<?php echo site_url("profile/albums/" . $user->ID) ?>"><?php echo lang("ctn_483") ?></a></li>
 </ul>

 <div class="pull-right profile-friend-box">
  <?php if($user->ID != $this->user->info->ID) : ?>
<?php if($friend_flag) : ?>
<button type="button" class="btn btn-success btn-sm" id="friend_button_<?php echo $user->ID ?>"><span class="glyphicon glyphicon-ok"></span> <?php echo lang("ctn_493") ?></button>
<?php else : ?>
<?php if($request_flag) : ?>
<button type="button" class="btn btn-success btn-sm disabled" id="friend_button_<?php echo $user->ID ?>"><?php echo lang("ctn_601") ?></button>
<?php else : ?>
  <?php if(!$user->allow_friends) : ?>
  <button type="button" class="btn btn-success btn-sm" onclick="add_friend(<?php echo $user->ID ?>)" id="friend_button_<?php echo $user->ID ?>"><?php echo lang("ctn_602") ?></button>
  <?php endif; ?>
<?php endif; ?>
<?php endif; ?>
<?php endif; ?>
 </div>
 </div>

<div class="white-area-content separator">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo $user->first_name ?>'s <?php echo lang("ctn_493") ?></div>
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
          <li><a href="#" onclick="change_search(2)"><span class="glyphicon glyphicon-ok nodisplay" id="username-exact"></span> <?php echo lang("ctn_25") ?></a></li>
          <li><a href="#" onclick="change_search(3)"><span class="glyphicon glyphicon-ok nodisplay" id="firstname-exact"></span> <?php echo lang("ctn_29") ?></a></li>
          <li><a href="#" onclick="change_search(4)"><span class="glyphicon glyphicon-ok nodisplay" id="lastname-exact"></span> <?php echo lang("ctn_30") ?></a></li>
        </ul>
      </div><!-- /btn-group -->
</div>
</div>

</div>
</div>

<div class="table-responsive">
<table id="friends-table" class="table table-striped table-hover table-bordered">
<thead>
<tr class="table-header"><td><?php echo lang("ctn_347") ?></td><td><?php echo lang("ctn_25") ?></td><td><?php echo lang("ctn_29") ?></td><td><?php echo lang("ctn_30") ?></td><td><?php echo lang("ctn_604") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
</thead>
<tbody>
</tbody>
</table>
</div>


</div>

 </div>
 </div>
 <script type="text/javascript">
$(document).ready(function() {

   var st = $('#search_type').val();
    var table = $('#friends-table').DataTable({
        "dom" : "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
      "processing": false,
        "pagingType" : "full_numbers",
        "pageLength" : 15,
        "serverSide": true,
        "orderMulti": false,
        "order": [
        ],
        "columns": [
        { "orderable" : false },
        null,
        null,
        null,
        null,
        { "orderable" : false }
    ],
        "ajax": {
            url : "<?php echo site_url("profile/friends_page/" . $user->ID) ?>",
            type : 'GET',
            data : function ( d ) {
                d.search_type = $('#search_type').val();
            }
        },
        "drawCallback": function(settings, json) {
        $('[data-toggle="tooltip"]').tooltip();
      }
    });
    $('#form-search-input').on('keyup change', function () {
    table.search(this.value).draw();
});

} );
function change_search(search) 
    {
      var options = [
      "search-like", 
      "search-exact",
      "username-exact",
      "firstname-exact",
      "lastname-exact",

      ];
      set_search_icon(options[search], options);
        $('#search_type').val(search);
        $( "#form-search-input" ).trigger( "change" );
    }

function set_search_icon(icon, options) 
    {
      for(var i = 0; i<options.length;i++) {
        if(options[i] == icon) {
          $('#' + icon).fadeIn(10);
        } else {
          $('#' + options[i]).fadeOut(10);
        }
      }
    }
</script>