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
  <li class="active"><a href="<?php echo site_url("pages/albums/" . $slug) ?>"><?php echo lang("ctn_483") ?></a></li>
  <li><a href="<?php echo site_url("pages/events/" . $slug) ?>"><?php echo lang("ctn_553") ?></a></li>
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
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo $page->name ?>'s <?php echo lang("ctn_483") ?></div>
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
          <li><a href="#" onclick="change_search(2)"><span class="glyphicon glyphicon-ok nodisplay" id="name-exact"></span> <?php echo lang("ctn_81") ?></a></li>
        </ul>
      </div><!-- /btn-group -->
</div>
</div>

<?php if( (isset($member) && $member != null && $member->roleid == 1) || ($this->common->has_permissions(array("admin", "page_admin"), $this->user)) ) : ?> 
<input type="button" class="btn btn-primary btn-sm" value="<?php echo lang("ctn_555") ?>" data-toggle="modal" data-target="#addModal">
<?php endif; ?>

</div>
</div>



<div class="table-responsive">
<table id="album-table" class="table table-striped table-hover table-bordered">
<thead>
<tr class="table-header"><td><?php echo lang("ctn_556") ?></td><td><?php echo lang("ctn_81") ?></td><td><?php echo lang("ctn_557") ?></td><td><?php echo lang("ctn_558") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
</thead>
<tbody>
</tbody>
</table>
</div>


</div>

 </div>
</div>
</div>
</div>
<?php if( (isset($member) && $member != null && $member->roleid == 1) || ($this->common->has_permissions(array("admin", "page_admin"), $this->user)) ) : ?> 
  <?php echo form_open(site_url("pages/add_album/" . $page->ID)) ?>
 <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-picture"></span> <?php echo lang("ctn_555") ?></h4>
      </div>
      <div class="modal-body ui-front form-horizontal">
          <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_81") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="name">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_271") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="description">
                    </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_559") ?>">
      </div>
    </div>
  </div>
</div>
<?php echo form_close() ?>

 <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" id="edit-album">
     
    </div>
  </div>
</div>
<?php endif; ?>
 <script type="text/javascript">
$(document).ready(function() {

   var st = $('#search_type').val();
    var table = $('#album-table').DataTable({
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
        { "orderable" : false }
    ],
        "ajax": {
            url : "<?php echo site_url("pages/albums_page/" . $page->ID) ?>",
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
      "name-exact",
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

function edit_album(id) 
{
	$.ajax({
		url: global_base_url + 'pages/edit_album/' + id,
		type: 'GET',
		data: {
		},
		success: function(msg) {
			$('#editModal').modal('show');
			$('#edit-album').html(msg);
		}
	})
}
</script>