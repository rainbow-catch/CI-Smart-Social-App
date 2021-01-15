<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_1") ?></div>
    <div class="db-header-extra"> <input type="button" class="btn btn-primary btn-sm" value="<?php echo lang("ctn_707") ?>" data-toggle="modal" data-target="#myModal">
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li class="active"><?php echo lang("ctn_706") ?></li>
</ol>


<div class="table-responsive">
<table id="member-table" class="table table-striped table-hover table-bordered">
<thead>
<tr class="table-header"><td><?php echo lang("ctn_81") ?></td><td><?php echo lang("ctn_606") ?></td><td><?php echo lang("ctn_561") ?></td><td><?php echo lang("ctn_339") ?></td><td><?php echo lang("ctn_453") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
</thead>
<tbody>
</tbody>
</table>
</div>


</div>


 <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo lang("ctn_707") ?></h4>
      </div>
      <div class="modal-body">
      <?php echo form_open(site_url("admin/add_rotation_ad"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_81") ?></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="email-in" name="name">
                    </div>
            </div>
            <div class="form-group">
                    <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_606") ?></label>
                    <div class="col-md-9">
                        <select name="status" class="form-control">
                        <option value="0"><?php echo lang("ctn_701") ?></option>
                        <option value="1"><?php echo lang("ctn_702") ?></option>
                        <option value="2"><?php echo lang("ctn_703") ?></option>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_708") ?></label>
                    <div class="col-md-9">
                        <textarea name="advert" id="advert"></textarea>
                    </div>
            </div>
            <div class="form-group">
                        <label for="password-in" class="col-md-3 label-heading"><?php echo lang("ctn_561") ?></label>
                        <div class="col-md-9">
                            <input type="text" name="pageviews" class="form-control" value="0">
                            <span class="help-block"><?php echo lang("ctn_709") ?></span>
                        </div>
                </div>
               
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_707") ?>" />
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
CKEDITOR.replace('advert', { height: '150'});
});
</script>
<script type="text/javascript">
$(document).ready(function() {

   var st = $('#search_type').val();
    var table = $('#member-table').DataTable({
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
        null,
        null,
        null,
        null,
        null,
        { "orderable" : false }
    ],
        "ajax": {
            url : "<?php echo site_url("admin/rotation_ad_page") ?>",
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
        "reason-exact",
        "user-exact",
        "page-exact",
        "from-exact",
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