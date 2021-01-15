var prev = 0;

function load_mail(id , page) 
{
	if(prev > 0) {
		// White
		$('#mail-box-msg-' + prev).css('background', '#FFF');
	}

	$('#mail-box-msg-' +id).css('background', '#EEE');
	prev = id;
	
	$.ajax({
		url: global_base_url + "chat/view_mail/" + id + "/" + page,
		beforeSend: function () { 
		$('#loading_spinner_mail').fadeIn(10);
		$("#ajspinner_mail").addClass("spin");
	 	},
	 	complete: function () { 
		$('#loading_spinner_mail').fadeOut(10);
		$("#ajspinner_mail").removeClass("spin");
	 	},
		data: {
		},
		success: function(msg) {
			if($('#mail-box-msg-' +id).hasClass('mail-unread-alert')) {
				$('#mail-box-msg-' +id).delay(10000).queue(function(next){
				    $(this).removeClass('mail-unread-alert');
				    next();
				});
			}
			$('#mail-view').html(msg);
			CKEDITOR.replace('mail-reply-textarea', { height: '100'});
		}
	});
}

function compose() 
{
	if(prev > 0) {
		// White
		$('#mail-box-msg-' + prev).css('background', '#FFF');
	}
	prev = 0;
	
	$.ajax({
		url: global_base_url + "chat/compose/",
		beforeSend: function () { 
		$('#loading_spinner_mail').fadeIn(10);
		$("#ajspinner_mail").addClass("spin");
	 	},
	 	complete: function () { 
		$('#loading_spinner_mail').fadeOut(10);
		$("#ajspinner_mail").removeClass("spin");
	 	},
		data: {
		},
		success: function(msg) {
			$('#mail-view').html(msg);
			CKEDITOR.replace('mail-reply-textarea', { height: '100'});
		}
	});
}