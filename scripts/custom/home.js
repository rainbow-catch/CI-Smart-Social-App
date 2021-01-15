var global_page = 0;
var hide_prev = 0;


$(document).ready(function() {
	load_posts();
});

function load_posts_wrapper() 
{
  load_posts();
}


function load_posts() 
{
	$.ajax({
		url: global_base_url + 'feed/load_home_posts',
		type: 'GET',
		data: {

		},
		success: function(msg) {
			$('#home_posts').html(msg);
		}
	})
}