$(document).ready(function() {

});

function add_friend(userid) 
{
	$.ajax({
		url: global_base_url + 'profile/add_friend/' + userid,
		type: 'POST',
		data: {
			csrf_test_name : global_hash
		},
		dataType : 'json',
		success: function(msg) {
			if(msg.error) {
				alert(msg.error_msg);
				return;
			}
			if(msg.success) {
				$('#friend_button_' + userid).html(msg.message);
				$('#friend_button_' + userid).addClass("disabled");
			}
		}
	})
}

function edit_album(id) 
{
	$.ajax({
		url: global_base_url + 'profile/edit_album/' + id,
		type: 'GET',
		data: {
		},
		success: function(msg) {
			$('#editModal').modal('show');
			$('#edit-album').html(msg);
		}
	})
}

function edit_image(id) 
{
	$.ajax({
		url: global_base_url + 'profile/edit_image/' + id,
		type: 'GET',
		data: {
		},
		success: function(msg) {
			$('#editModal').modal('show');
			$('#edit-album').html(msg);
		}
	})
}