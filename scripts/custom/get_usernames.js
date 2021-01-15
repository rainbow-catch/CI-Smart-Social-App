$(document).ready(function() { 
  /* Get list of usernames */
  $('#username-search').autocomplete({
  	delay : 300,
  	minLength: 2,
  	source: function (request, response) {
         $.ajax({
             type: "GET",
             url: global_base_url + "home/get_usernames",
             data: {
             		query : request.term
             },
             dataType: 'JSON',
             success: function (msg) {
                 response(msg);
             }
         });
      }
  });
  $('#username-search2').autocomplete({
    delay : 300,
    minLength: 2,
    source: function (request, response) {
         $.ajax({
             type: "GET",
             url: global_base_url + "home/get_usernames",
             data: {
                query : request.term
             },
             dataType: 'JSON',
             success: function (msg) {
                 response(msg);
             }
         });
      }
  });  
    $('#name-search').autocomplete({
    delay : 300,
    minLength: 2,
    source: function (request, response) {
         $.ajax({
             type: "GET",
             url: global_base_url + "home/get_names",
             data: {
                query : request.term
             },
             dataType: 'JSON',
             success: function (msg) {
                 response(msg);
             },
         });
      },
      select: function (event, ui) {
              var v = ui.item.value;
              var l = ui.item.label;
              $('#name-search').html(l);
              $('#userid-search').val(v);
              // update what is displayed in the textbox
              this.value = l; 
              return false;
              },
  });
});