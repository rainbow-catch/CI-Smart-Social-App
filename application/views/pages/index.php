<div class="row">
        <div class="col-md-12">
        	<div class="white-area-content">
        		
        		<div class="db-header clearfix">
				    <div class="page-header-title"> <span class="glyphicon glyphicon-file"></span> <?php echo lang("ctn_484") ?></div>
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
                                      <li><a href="#" onclick="change_search(3)"><span class="glyphicon glyphicon-ok nodisplay" id="cat-exact"></span> <?php echo lang("ctn_560") ?></a></li>
                                    </ul>
                                  </div><!-- /btn-group -->
                            </div>
                            </div>

                            <a href="<?php echo site_url("pages/your") ?>" class="btn btn-default btn-sm"><?php echo lang("ctn_577") ?></a> 

                            <?php if( ($this->common->has_permissions(array("admin", "page_admin", "page_creator"), $this->user)) ) : ?> 

                        <a href="<?php echo site_url("pages/add") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_531") ?></a>
                      <?php endif; ?>
				</div>
				</div>


                <div class="table-responsive">
                <table id="page-table" class="table table-striped table-hover table-bordered">
                <thead>
                <tr class="table-header"><td><?php echo lang("ctn_81") ?></td><td><?php echo lang("ctn_561") ?></td><td><?php echo lang("ctn_21") ?></td><td><?php echo lang("ctn_562") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
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
    var table = $('#page-table').DataTable({
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
        { "orderable" : false }
    ],
        "ajax": {
            url : "<?php echo site_url("pages/your_page/2") ?>",
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
      "cat-exact"
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