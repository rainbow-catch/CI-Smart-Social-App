var lang = new Array()
lang.push("Send message ...");
lang.push("NEW");

$(document).ready(function() {

	check_chat_noti();

	// Clicking the Chat Circle
	$('#chat_start_button').click(function() {
		// Adjust margin of active chats
		$('#active_chats').css("margin-right", "300px");
		$.ajax({
			url: global_base_url + "chat/load_active_users",
			type: "get",
			success: function(msg) {
				$('#chat-main-body').html(msg);
			}
		});
		$('#chat_history_window').fadeIn(100);
	});

	$('#chat_online_users_button').click(function() {
		// Adjust margin of active chats
		$('#active_chats').css("margin-right", "300px");
		$.ajax({
			url: global_base_url + "chat/load_active_users",
			type: "get",
			success: function(msg) {
				$('#chat-main-body').html(msg);
			}
		});
		$('#chat_history_window').fadeIn(100);
	});

	$('#chat_multi_user_button').click(function() {
		$.ajax({
			url: global_base_url + "chat/load_multi_chat",
			type: "get",
			success: function(msg) {
				$('#chat-main-body').html(msg);
			}
		});
	});

	$('#chat_project_button').click(function() {
		$.ajax({
			url: global_base_url + "chat/load_project_chat",
			type: "get",
			success: function(msg) {
				$('#chat-main-body').html(msg);
			}
		});
	});

	// Click the X 
	$('#chat_close_button').click(function() {
		// Adjust margin of active chats
		$('#active_chats').css("margin-right", "50px");
		$('#chat_history_window').fadeOut(100);
	});

	// Click the +
	$('#chat_new_button').click(function() {
		// Ajax
		$.ajax({
			url: global_base_url + "chat/load_new_chat",
			type: "get",
			success: function(msg) {
				$('#chat-main-body').html(msg);
				$('#start_chat_username').autocomplete({
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
			}
		});
	});



	// Creating a new chat
	$('#chat-main-body').on("click", "#start_chat_button", function() {
		var username = $('#start_chat_username').val();
		var message = $('#start_chat_message').val();
		var title = $('#start_chat_title').val();
		var projectid = $('#start_chat_projectid').val();
		$.ajax({
			url: global_base_url + "chat/start_new_chat",
			type: "get",
			data: {
				username : username,
				message : message,
				title : title,
				projectid : projectid
			},
			dataType: 'json',
			success: function(msg) {
				if(msg.error) {
					handle_error("#chat-body-errors", msg.error_msg);
					return;
				} else {
					// Success
					get_all_chat_messages(1, function() {
						$('#chat_close_button').click();
						load_active_chat(msg.chatid);

					});
				}
			}
		});
	});

	// Get active chats once page has loaded
	get_active_chats();

	setInterval(function() { get_all_chat_messages(); }, time_to_update);

	// ping chat messages of active chats every 5 seconds
	//setInterval(function() {show_active_chats();}, 5000);
});

var time_to_update = 5000;

var active_chats = new Array();

function show_active_chats() {
	for(var i=0; i<active_chats.length; i++) {
		get_chat_messages(active_chats[i]);
	}
}

function load_empty_chat(userid) 
{
	$.ajax({
			url: global_base_url + "chat/load_empty_chat/" + userid,
			type: "get",
			data : {
			},
			success: function(msg) {
				$('#active_chats').html(msg);
			}
		});
}

function new_chat_username(username) 
{
	$.ajax({
			url: global_base_url + "chat/load_new_chat",
			type: "get",
			data : {
				username : username
			},
			success: function(msg) {
				$('#chat-main-body').html(msg);
				$('#start_chat_username').autocomplete({
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
			}
		});
}



/* When user clicks on an active chat bubble */
function load_active_chat(id) 
{
	$.ajax({
			url: global_base_url + "chat/get_active_chat/" + id,
			type: "get",
			dataType: 'json',
			success: function(msg) {
				if(msg.error) {
					handle_error("#active_chat_bubble_" + id, msg.error_msg);
					return;
				} else {
					// Success
					build_chat_area(msg);
				}
			}
		});
}

// Builds a chat window from a bubble
function build_chat_area(data) 
{
	$('#active_chat_bubble_' + data.chatid).attr('onclick',null); 
	$('#active_chat_bubble_' + data.chatid).addClass("active_chat_window");
	$('#active_chat_bubble_' + data.chatid).html("");
	$('#active_chat_bubble_' + data.chatid).append('<div class="chat-top-bar">'+data.title+' <div class="pull-right"> <span class="glyphicon glyphicon-minus click chat-icon" onclick="close_active_chat_window('+data.chatid+')"></span> <span class="glyphicon glyphicon-remove click chat-icon" onclick="hide_chat_window('+data.chatid+')"></span></div></div>');
	$('#active_chat_bubble_' + data.chatid).append('<div class="chat-chat-body" id="active_chat_window_'+data.chatid+'"></div>');
	$('#active_chat_bubble_' + data.chatid).append('<div class="chat-main-reply"><input type="text" name="reply" class="form-control" id="chat_input_message_'+data.chatid+'" placeholder="'+lang[0]+'" onkeypress="return wait_for_enter(event, '+data.chatid+');"></div>');

	get_chat_messages(data.chatid, 0);
}

// Build Active Chat

function get_all_chat_messages(nosound=0, myCallBack=null) 
{
	$.ajax({
		url: global_base_url + "chat/get_all_chat_messages/",
		type: "get",
		dataType: 'json',
		success: function(msg) {
			if(msg.error) {
				handle_error("#active_chat_bubble_" + id, msg.error_msg);
				return;
			} else {
				update_chat_noti(msg.noti_count);
				// Loop through all chat windows
				var new_window_flag = false;
				for(var i =0;i< msg.chats.length;i++) {

					var chat = msg.chats[i];


					if(!$('#active_chat_bubble_' + chat.chatid).length) {
						// Create Chat Bubble
						$('#active_chats').append(chat.chat_bubble_template);

						new_window_flag = true;
						
					} else {

						var last_reply_id = $('#last_reply_chatid_' + chat.chatid).val();
						if($('#active_chat_window_' + chat.chatid).length) {
							$('#active_chat_window_' + chat.chatid).html(chat.messages_template);
							$('#active_chat_window_' + chat.chatid).scrollTop($('#active_chat_window_' + chat.chatid)[0].scrollHeight);

							// Now check
							if(!nosound) {
								if(last_reply_id != $('#last_reply_chatid_' + chat.chatid).val()) {
									$("#bleep").trigger('play');
								}
							}
						} else {
							// Update active chat bubbles
							if(chat.unread == 1) {
								// Look for unread sign
								if(!$('#active_chat_bubble_' + chat.chatid +' span').length) {
									$("#bleep").trigger('play');
								}
								$('#active_chat_bubble_' + chat.chatid).html('<span class="badge-chat small-text">'+lang[1]+'</span>' + chat.title);
							} else {
								$('#active_chat_bubble_' + chat.chatid).html(chat.title);
							}
						}
					}

				}// end for loop

				if(new_window_flag) {
					$("#bleep").trigger('play');
				}

				if(typeof myCallBack === "function") {
					myCallBack();
				}
			}
		}
	});
}

// get the chat log to the chat window
function get_chat_messages(id, nosound=0) 
{
	// Get last reply id
	var last_reply_id = $('#last_reply_chatid_' + id).val();
	$.ajax({
			url: global_base_url + "chat/get_chat_messages/" + id,
			type: "get",
			dataType: 'JSON',
			success: function(data) {
				if($('#active_chat_window_' + id).length) {
					$('#active_chat_window_' + id).html(data.messages_template);
					$('#active_chat_window_' + id).scrollTop($('#active_chat_window_' + id)[0].scrollHeight);

					// Now check
					if(nosound) {
						if(last_reply_id != $('#last_reply_chatid_' + id).val()) {
							$("#bleep").trigger('play');
						}
					}
				} else {
					// Update active chat bubbles
					if(data.unread == 1) {
						$('#active_chat_bubble_' + data.chatid).html('<span class="badge-chat small-text">'+lang[1]+'</span>' + data.title);
					} else {
						$('#active_chat_bubble_' + data.chatid).html(data.title);
					}
				}
			}
		});
}

// Close active chat window
// We make ajax call to flag the chat as closed (for refreshes)
function hide_chat_window(id) 
{
	$.ajax({
			url: global_base_url + "chat/hide_chat/" + id,
			type: "get",
			dataType: 'json',
			success: function(msg) {
				if(msg.error) {
					handle_error("#active_chat_bubble_" + id, msg.error_msg);
					return;
				} else {
					// Success
					delete_chat_area(msg.chatid);
				}
			}
		});
}


// Close active chat window
// We make ajax call to flag the chat as closed (for refreshes)
function close_active_chat_window(id) 
{
	$.ajax({
			url: global_base_url + "chat/close_active_chat/" + id,
			type: "get",
			dataType: 'json',
			success: function(msg) {
				if(msg.error) {
					handle_error("#active_chat_bubble_" + id, msg.error_msg);
					return;
				} else {
					// Success
					close_chat_area(msg);
				}
			}
		});
}

// Deletes the chat for the user.
function delete_chat_window(id) 
{
	$.ajax({
			url: global_base_url + "chat/delete_chat/" + id,
			type: "get",
			dataType: 'json',
			success: function(msg) {
				if(msg.error) {
					handle_error("#active_chat_bubble_" + id, msg.error_msg);
					return;
				} else {
					// Success
					delete_chat_area(msg.chatid);
				}
			}
		});
}

function close_empty_chat() 
{
	console.log("CALLED");
	$('#active_chat_bubble_0').remove();
}

// Wait For Enter
// Waits for the enter key to be pressed before sending the message.
function wait_for_enter(event, chatid) 
{
	 // look for window.event in case event isn't passed in
    var e = event || window.event;
    if (e.keyCode == 13) {
        // Send Chat Message

        var message = $('#chat_input_message_' + chatid).val();
        var userid = $('#chat_hidden_userid').val();
        $.ajax({
			url: global_base_url + "chat/send_chat_message/" + chatid,
			type: "get",
			data : {
				hash : global_hash,
				message : message,
				userid : userid
			},
			dataType : 'json',
			success: function(msg) {
				if(msg.error) {
					handle_error("#active_chat_window_" + chatid, msg.error_msg);
					return false;;
				} else {
					if(chatid == 0) {
						close_empty_chat();
						get_all_chat_messages(1, function() {
							$('#chat_close_button').click();
							load_active_chat(msg.chatid);

						});
						return false;
					} else {
						// Success
						$('#chat_input_message_' + chatid).val("");
						// Load new chat messages
						get_all_chat_messages(true);
						return false;
					}
				}
			}
		});
        return false;
    }
    return true;
}

// Function used to open chat chat when bubble doesn't exist
function load_closed_window(id) 
{
	$.ajax({
			url: global_base_url + "chat/get_active_chat/" + id,
			type: "get",
			dataType: 'json',
			success: function(msg) {
				if(msg.error) {
					handle_error("#active_chat_bubble_" + id, msg.error_msg);
					return;
				} else {
					// Success
					get_all_chat_messages(1, function() {
							$('#chat_close_button').click();
							load_active_chat(id);

						});
					return false;
				}
			}
		});
}

function delete_chat_area(chatid) 
{
	$('#active_chat_bubble_' + chatid).remove();
}

function close_chat_area(data) 
{
	$('#active_chat_bubble_' + data.chatid).removeClass("active_chat_window");
	$('#active_chat_bubble_' + data.chatid).attr('onclick','load_active_chat('+data.chatid+')'); 
	if(data.unread == 1) {
		$('#active_chat_bubble_' + data.chatid).html('<span class="badge-chat small-text">'+lang[1]+'</span>' + data.title);
	} else {
		$('#active_chat_bubble_' + data.chatid).html(data.title);
	}
}

function chat_with(friendid) 
{
	$.ajax({
			url: global_base_url + "chat/chat_with/" + friendid,
			type: "get",
			dataType: 'json',
			success: function(msg) {
				if(msg.error) {
					handle_error("#active_chat_bubble_" + id, msg.error_msg);
					return;
				} else {
					if(msg.chatid > 0) {
						// Check if there is a bubble active
						if(!$('#active_chat_bubble_' + msg.chatid).length) {
							// Create Chat Bubble
							$('#active_chats').append('<div class="active_chat_bubble" onclick="load_active_chat('+msg.chatid+')" id="active_chat_bubble_'+msg.chatid+'"></div>');
						}
						load_active_chat(msg.chatid);
					} else {
						// new window
						load_empty_chat(friendid) 
					}
				}
			}
		});
}

/* Get a list of the user's active chat windows */
function get_active_chats() 
{
	$.ajax({
			url: global_base_url + "chat/get_active_chats",
			type: "get",
			data: {
			},
			dataType: 'JSON',
			success: function(msg) {
				$('#active_chats').html(msg.view);

				// Get active chat list
				for(var i=0; i<msg.active_chats.length; i++) {
					active_chats.push(msg.active_chats[i]);
				}

				// Unique it
				active_chats = jQuery.unique(active_chats);
				
			}
		});
}

function handle_error(element, error_msg) {
	$(element).html(error_msg);
}

function remove_chat_from_active_chats(id) 
{
	for(var i =0; i< active_chats.length; i++) {
		if(active_chats[i] == id) {
			// Pop it
			
			active_chats.splice(i, 1);
			
		}
	}
}

function check_chat_noti() 
{
	$.ajax({
		url: global_base_url + "chat/check_notifications",
		beforeSend: function () { 
		$('#loading_spinner_notification').fadeIn(10);
		$("#ajspinner_notification").addClass("spin");
	 	},
	 	complete: function () { 
		$('#loading_spinner_notification').fadeOut(10);
		$("#ajspinner_notification").removeClass("spin");
	 	},
		data: {
		},
		dataType: 'json',
		success: function(msg) {
			update_chat_noti(msg.noti_count);
		}
	});
}

function update_chat_noti(count) 
{
	if (typeof count != "undefined" && count >0) {
		$('#chat-noti').html(count);
		$('#chat-noti').fadeIn();
	} else {
		$('#chat-noti').html("");
		$('#chat-noti').fadeOut(10);
	}
}