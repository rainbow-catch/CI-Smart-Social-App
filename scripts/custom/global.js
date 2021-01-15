var glang = new Array();
glang.push("Remove Saved Post");
glang.push("Save Post");
glang.push("Turn Off Notifications");
glang.push("Turn On Notifications");

function load_notifications() 
{
	
	$.ajax({
		url: global_base_url + "home/load_notifications",
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
		success: function(msg) {
			$('#notifications-scroll').html(msg);
		}

	});
	console.log("Done");
}


var chat_page = 0;
function load_chats() 
{
	
	$.ajax({
		url: global_base_url + "chat/load_chats",
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
		success: function(msg) {
			$('#chat-scroll').html(msg);
		}

	});
	console.log("Done");
}

function load_chat_page() 
{
	chat_page += 10;
	$.ajax({
		url: global_base_url + "chat/load_chats/" + chat_page,
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
		success: function(msg) {
			$('#chat-scroll').append(msg);
		}

	});
	console.log("Done");
}



function load_notifications_unread() 
{
	$.ajax({
		url: global_base_url + "home/load_notifications_unread",
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
		success: function(msg) {
			$('#notifications-scroll').html(msg);
			return false;
		}

	});
	console.log("Done");
}


function load_notification_url(id) 
{
	window.location.href= global_base_url + "home/load_notification/" + id;
	return;
}

$(document).ready(function() {
	$("#editor-textarea").mentionsInput({trigger: "#@", source: global_base_url + 'home/get_user_friends'});
	
	$('.dropdown-menu #noti-click-unread').click(function(e) {
	    e.stopPropagation();
	});
	$('#chat-click-more').click(function(e) {
	    e.stopPropagation();
	});
	if(navigator.appVersion.indexOf("Win")!=-1) {
		$('#notifications-scroll').niceScroll({touchbehavior: false, zindex: 9999999999});
	}

	$('#search-complete').autocomplete({
	  	delay : 300,
	  	minLength: 2,
	  	source: function (request, response) {
	         $.ajax({
	             type: "GET",
	             url: global_base_url + "home/get_search_results",
	             data: {
	             		query : request.term
	             },
	             dataType: 'JSON',
	             success: function (msg) {
	                 response(msg);
	             }
	         });
	      },
        	focus: function( event, ui ) {
		        $(this).val( ui.item.label );
		        return false;
		    },
		    create: function () {
	            $(this).data('ui-autocomplete')._renderItem = function (ul, item) {
	            	if(item.type == "user") {
		                return $('<li class="clearfix search-option-user">')
		                    .append('<div class="search-user-avatar"><img src="'+item.avatar+'"></div><div class="search-user-info"><a href="'+item.url+'">' + item.label + '</a></div>')
		                    .appendTo(ul);
	                } else if(item.type == "page") {
	                	return $('<li class="clearfix search-option-page">')
		                    .append('<div class="search-user-avatar"><img src="'+item.avatar+'"></div><div class="search-user-info"><a href="'+item.url+'">' + item.label + '</a></div>')
		                    .appendTo(ul);
	                }
	            };
	        }
	  });


	$('#with_users').select2({
		placeholder: "Select users",
  		allowClear: true,
  		ajax: {
		    url: global_base_url + "home/get_user_friends_v2",
		    dataType: 'json',
		    delay: 250,
		    data: function (params) {
		      return {
		        term: params.term // search term
		      };
		    }
		},
		minimumInputLength: 1
	});
 
    $('#social-form').submit(function() { 
        
        $(this).ajaxSubmit({
        	success: addPost,
        	dataType: 'json',
        	clearForm: false
        }); 
 
        return false; 
    }); 

    $('#social-form-edit').submit(function() { 
        
        $(this).ajaxSubmit({
        	success: editPostComplete,
        	dataType: 'json',
        	clearForm: true
        }); 
 
        return false; 
    }); 

	$('.map_name').geocomplete();

	$('#home_posts').on("focus", ".feed-comment-input", function() {
		var id = $(this).attr("data-id");

		// check to see which events this comment already has
        var events = $._data( this, 'events' ).keypress;

        // Try to find if keypress has already been registered
        // registering it twice causes duplicate comments

        var hasEvents = false;
        for(var i=0;i<events.length;i++) {
        	if(events[i].namespace == "") {
        		hasEvents = true;
        	}
        }

        if(!hasEvents) {
			$(this).keypress(function (e) {
			    if (e.keyCode == 13) {
			        var comment = $(this).mentionsInput("getValue");
			        $(this).val("");
			        $(this).mentionsInput("clear");
			        $.ajax({
						url: global_base_url + 'feed/post_comment/' + id,
						type: 'POST',
						data: {
							comment : comment,
							csrf_test_name : global_hash,
							page : global_page,
							hide_prev : hide_prev
						},
						dataType: 'json',
						success: function(msg) {

							if(msg.error) {
								alert(msg.error_msg);
								return;
							}
							$('#feed-comments-spot-'+id).html(msg.content);
							$('#feed-comments-'+id).html(msg.comments);
						}
					});
			    }
			});
		}
	});

	$('#home_posts').on("focus", ".feed-comment-input-reply", function() {
		var id = $(this).attr("data-id");

		// check to see which events this comment already has
		var events = $._data( this, 'events' ).keypress;

        // Try to find if keypress has already been registered
        // registering it twice causes duplicate comments

        console.log(events);

        var hasEvents = false;
        for(var i=0;i<events.length;i++) {
        	if(events[i].namespace == "") {
        		hasEvents = true;
        	}
        }
        console.log(hasEvents);
        if(!hasEvents) {
			$(this).keypress(function (e) {
			    if (e.keyCode == 13) {
			        var comment = $(this).mentionsInput("getValue");
			        $(this).val("");
			        $(this).mentionsInput("clear");
			        $.ajax({
						url: global_base_url + 'feed/post_comment_reply/' + id,
						type: 'POST',
						data: {
							comment : comment,
							csrf_test_name : global_hash,
							page : global_page,
							hide_prev : hide_prev
						},
						dataType: 'json',
						success: function(msg) {

							if(msg.error) {
								alert(msg.error_msg);
								return;
							}
							$('#feed-comments-spot-reply-'+id).html(msg.content);
							$('#feed-reply-comments-'+id).html("(" + msg.comments + ")");
							$('#feed-comments-'+msg.feeditemid).html(msg.comments_count);
						}
					});
			    }
			});
		}
	});


});

function editPostComplete(data) 
{
	$('#editPostModal').modal('hide');
	$('#feed-post-'+data.id).replaceWith(data.post);
}

function reloadPost(postid) 
{
	$.ajax({
		url: global_base_url + 'feed/reload_post/' + postid,
		type: 'GET',
		data: {
		},
		dataType : 'json',
		success: function(data) {
			$('#feed-post-'+data.id).replaceWith(data.post);
		}
	});
}

function addPost(msg) 
{
	$('#poll-button').removeClass("highlight-button");
	$('#user-button').removeClass("highlight-button");
	$('#map-button').removeClass("highlight-button");
	$('#video-button').removeClass("highlight-button");
	$('#image-button').removeClass("highlight-button");
	if(msg.error) {
		alert(msg.error_msg);
		return;
	}
	$('#social-form').clearForm();
	$('#editor-textarea').mentionsInput("clear");
	// reload feed
	load_posts_wrapper();
}

var global_page = 0;
var hide_prev = 0;

function load_previous_comments(id, page, obj) 
{
	$(obj).remove();
	$.ajax({
		url: global_base_url + 'feed/get_previous_comments/' + id,
		type: 'GET',
		data: {
			page : page
		},
		success: function(msg) {
			global_page = page;
			hide_prev = 1;
			$('#feed-comment-'+id).prepend(msg);
		}
	});
}

function get_post_likes(id)
{
	$.ajax({
		url: global_base_url + 'feed/get_post_likes/' + id,
		type: 'GET',
		data: {

		},
		success: function(msg) {
			$('#likeModal').modal('show');
			$('#post-likes').html(msg);
		}
	})
}

function load_comments(id)
{
	if($('#feed-comment-'+id).is(':visible')) {
		$('#feed-comment-'+id).slideUp(400);
	} else {
		$(".feed-comment-input").mentionsInput("destroy");
		$.ajax({
			url: global_base_url + 'feed/get_feed_comments/' + id,
			type: 'GET',
			data: {

			},
			success: function(msg) {
				$('#feed-comment-'+id).html(msg);
				$('#feed-comment-'+id).slideDown(400);
				$(".feed-comment-input").mentionsInput({source: global_base_url + 'home/get_user_friends'});
			}
		});
	}
}

function load_single_comment(id, commentid, replyid) 
{
	if($('#feed-comment-'+id).is(':visible')) {
		$('#feed-comment-'+id).slideUp(400);
	} else {
		$(".feed-comment-input").mentionsInput("destroy");
		$.ajax({
			url: global_base_url + 'feed/get_single_comment/' + id,
			type: 'GET',
			data: {
				commentid : commentid
			},
			success: function(msg) {
				$('#feed-comment-'+id).html(msg);
				$('#feed-comment-'+id).slideDown(400);
				$(".feed-comment-input").mentionsInput({source: global_base_url + 'home/get_user_friends'});
				if(replyid >0) {
					load_comment_replies(commentid, replyid);
				}
			}
		});
	}
}


function delete_comment(id) 
{
	$.ajax({
		url: global_base_url + 'feed/delete_feed_comment/' + id + '/' + global_hash,
		type: 'GET',
		data: {

		},
		dataType: 'json',
		success: function(msg) {
			if(msg.error) {
				alert(msg.error_msg);
				return;
			}
			if(msg.success) {
				$('#feed-comment-area-'+id).fadeOut(500);
			}
		}
	})
}

function delete_comment_reply(id) 
{
	$.ajax({
		url: global_base_url + 'feed/delete_feed_comment_reply/' + id + '/' + global_hash,
		type: 'GET',
		data: {

		},
		dataType: 'json',
		success: function(msg) {
			if(msg.error) {
				alert(msg.error_msg);
				return;
			}
			if(msg.success) {
				$('#comment-reply-'+id).fadeOut(500);
			}
		}
	})
}

function load_comment_replies(id, replyid=0) 
{
	$(".feed-comment-input-reply").mentionsInput("destroy");
	$.ajax({
		url: global_base_url + 'feed/get_feed_comments_replies/' + id,
		type: 'GET',
		data: {

		},
		success: function(msg) {
			$('#feed-comment-reply-'+id).html(msg);
			$('#feed-comment-reply-'+id).slideDown(400);
			$(".feed-comment-input-reply").mentionsInput({source: global_base_url + 'home/get_user_friends'});
			if(replyid > 0) {
				window.location.hash = '#comment-reply-' + replyid;
			}
		}
	})
}
function like_feed_post(id, type) 
{
	$.ajax({
		url: global_base_url + 'feed/like_post/' + id,
		type: 'GET',
		data: {
			hash : global_hash,
			type : type
		},
		dataType: 'JSON',
		success: function(msg) {
			if(msg.error) {
				alert(msg.error_msg);
				return;
			}
			
				if(msg.like_status) {
					$('#likes-click-' + id).fadeIn(10);
					$('#like-button-' +id).addClass("active-like");
				} else {
					$('#like-button-' +id).removeClass("active-like");
				}
				
				$('#feed-likes-' +id).html(msg.likes);
				
		
				if(msg.dislike_status) {
					$('#dislikes-click-' + id).fadeIn(10);
					$('#dislike-button-' +id).addClass("active-like");
				} else {
					$('#dislike-button-' +id).removeClass("active-like");
				}
				$('#feed-dislikes-' +id).html(msg.dislikes);

		}
	})
}

function like_comment(id) 
{
	$.ajax({
		url: global_base_url + 'feed/like_comment/' + id,
		type: 'GET',
		data: {
			hash : global_hash
		},
		dataType: 'JSON',
		success: function(msg) {
			if(msg.error) {
				alert(msg.error_msg);
				return;
			}
			if(msg.like_status) {
				$('#comment-like-link-' +id).addClass("active-comment-like");
			} else {
				$('#comment-like-link-' +id).removeClass("active-comment-like");
			}
			var like_icon = '';
			if(msg.likes > 0) {
				like_icon = '- <span class="glyphicon glyphicon-thumbs-up" id=""></span> ' + msg.likes;
			}
			$('#comment-like-' +id).html(like_icon);
		}
	})
}

function promote_post(id)
{
	$.ajax({
		url: global_base_url + 'feed/promote_post/' + id,
		type: 'GET',
		data: {
		},
		success: function(msg) {
			// Load modal
			$('#promotePost').html(msg);

			$('#promotePostModal').modal('show');
		}
	});
}

function vote_poll(postid, type) 
{
	// Answers
	if(type == 0) {
		var answers = $('#poll_answers_' + postid + ' input[type=radio]:checked').val();
	} else {
		var searchIDs = $('#poll_answers_' + postid + ' input[type=checkbox]:checked').map(function(){

	      return $(this).val();

	    });
		var answers =searchIDs.get();
	}

	$.ajax({
		url: global_base_url + 'feed/vote_poll/' + postid,
		type: 'GET',
		data: {
			answers : answers
		},
		dataType: 'JSON',
		success: function(msg) {
			if(msg.error) {
				alert(msg.error_msg);
				return;
			}
			if(msg.success) {
				// Reload post
				reloadPost(postid);
			}
		}
	});
}

function edit_post(id)
{
	$.ajax({
		url: global_base_url + 'feed/edit_post/' + id,
		type: 'GET',
		data: {
		},
		success: function(msg) {
			// Load modal
			$('#editPost').html(msg);
			//$("#editor-textarea").mentionsInput("destroy");
			$(".edit-editor-textarea").mentionsInput({trigger: "#@", source: global_base_url + 'home/get_user_friends'});
			$('#editPostModal').modal('show');

			$('#edit-image').click(function() {
				$('#edit-image-area').toggle();
			});

			$('#edit-video').click(function() {
				$('#edit-video-area').toggle();
			});

			$('#edit-location').click(function() {
				$('#edit-location-area').toggle();
			});

			$('#edit-users').click(function() {
				$('#edit-users-area').toggle();
			});

			$('#edit-poll').click(function() {
				$('#edit-poll-area').toggle();
			});

			$('.map_name').geocomplete();

			$('#social-form-edit').submit(function() { 
        
		        $(this).ajaxSubmit({
		        	success: editPostComplete,
		        	dataType: 'json',
		        	clearForm: true
		        }); 
		 
		        return false; 
		    }); 

		    $('.with_users').select2({
				placeholder: "Select users",
		  		allowClear: true,
		  		ajax: {
				    url: global_base_url + "home/get_user_friends_v2",
				    dataType: 'json',
				    delay: 250,
				    data: function (params) {
				      return {
				        term: params.term // search term
				      };
				    }
				},
				minimumInputLength: 1
			});
		}
	})
}

function delete_post(id) 
{
	$.ajax({
		url: global_base_url + 'feed/delete_post/' + id + '/' + global_hash,
		type: 'GET',
		data: {
		},
		dataType: 'JSON',
		success: function(msg) {
			if(msg.error) {
				alert(msg.error_msg);
				return;
			}
			if(msg.success) {
				$('#feed-post-' + id).fadeOut(500);
			}
		}
	})
}

function share_post(id) 
{
	$.ajax({
		url: global_base_url + 'feed/share_post/' + id + '/' + global_hash,
		type: 'GET',
		data: {
		},
		dataType: 'JSON',
		success: function(msg) {
			if(msg.error) {
				alert(msg.error_msg);
				return;
			}
			if(msg.success) {
				// reload feed
				load_posts_wrapper();
			}
		}
	})
}

function save_post(id) 
{
	$.ajax({
		url: global_base_url + 'feed/save_post/' + id + '/' + global_hash,
		type: 'GET',
		data: {
		},
		dataType: 'JSON',
		success: function(msg) {
			if(msg.error) {
				alert(msg.error_msg);
				return;
			}
			if(msg.success) {
				if(msg.status == 1) {
					$('#save_post_' + id).html(lang[0]);
				} else {
					$('#save_post_' + id).html(lang[1]);
				}
			}
		}
	})
}

function subscribe_post(id) 
{
	$.ajax({
		url: global_base_url + 'feed/subscribe_post/' + id + '/' + global_hash,
		type: 'GET',
		data: {
		},
		dataType: 'JSON',
		success: function(msg) {
			if(msg.error) {
				alert(msg.error_msg);
				return;
			}
			if(msg.success) {
				if(msg.status == 1) {
					$('#subscribe_post_' + id).html(lang[2]);
				} else {
					$('#subscribe_post_' + id).html(lang[3]);
				}
			}
		}
	})
}

function set_post_as(id, img) 
{
	$('#post_as').val(id);
	$('#editor-poster-icon').attr("src", img);
	$('.postastoggle').toggle();
	$('.postastoggle').removeClass("postastoggle");
	$('#'+id+'-postas').addClass("postastoggle");
	$('#' + id+'-postas').fadeOut();
}

function add_smile($text) {
	$('#editor-textarea').val($('#editor-textarea').val() + " " +$text);
	$('input[name="content"]').val($('input[name="content"]').val() + " " +$text);
}

function edit_smile($text) {
	$('.edit-editor-textarea').val($('.edit-editor-textarea').val() + " " +$text);
	$('.edit-editor-textarea').trigger('change');
}