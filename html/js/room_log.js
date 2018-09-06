$().ready(function() {
	seek_selection = $("#last_query_lesson_topics_seek_selection").html();
	topic_parts_seek = $("#last_query_lesson_topics_topic_seek").html();
	container_id = "lesson_topics_seek";
	selectTopicHandling(container_id, seek_selection, topic_parts_seek);
	time = $('#last_fetch_time').html();
	if (time !== undefined) {
		refreshRate = 2000;
		function getRoomData() {
			begin_time = $("#last_query_begin_time").html();
			end_time = $("#last_query_end_time").html();
			seek_room = $("#last_query_room").html();
			seek_nfc_id = $("#last_query_nfc_id").html();
			seek_course_name = $("#last_query_course_name").html();
			seek_topic_selection = $("#last_query_lesson_topics_seek_selection").html();
			topic_parts_seek = $("#last_query_lesson_topics_topic_seek").html();
			page = $('#page').html();
			page_page = $('#page_page').html();
			time = $('#last_fetch_time').html();
			if (begin_time === undefined || begin_time == "") {
				return;
			}
			if (end_time === undefined || end_time == "") {
				return;
			}			
			if (seek_room === undefined) {
				seek_room = "";
			}
			if (seek_nfc_id === undefined) {
				seek_nfc_id = "";
			}
			if (seek_course_name === undefined) {
				seek_course_name = "";	
			}
			if (page == null) {
				page = 1;
			}
			if (page_page == null) {
				page_page = 1;
			}
			$.get(buildHttpGetUrl("inc/sub/room_log/get_new_room_log.php", 
					["last_fetch_time","seek_room","seek_nfc_id",
					 "seek_course_name","seek_topic_selection",
					 "topic_parts_seek","begin_time","end_time",
					 "page","page_page"],
					[time,seek_room,seek_nfc_id,seek_course_name,
					seek_topic_selection,topic_parts_seek,begin_time,
					end_time,page,page_page]), 
				function(data) {
					if (data != "{}" && data != "") {
						jsonData = JSON.parse(data);
						if (jsonData.count_new > 0) {
							$('#last_fetch_time').html(jsonData.last_fetch_time);
							$("#room_log_listing_table .datarow").remove();
							for(iRoomLog=0; iRoomLog < jsonData.room_logs.length; iRoomLog++) {
								if (jsonData.room_logs[iRoomLog].topic == null) { 
									jsonData.room_logs[iRoomLog].topic = "&nbsp;"; 
								}
								if (jsonData.room_logs[iRoomLog].course_name == null) { 
									jsonData.room_logs[iRoomLog].course_name = "&nbsp;"; 
								}								
								row_data = jsonData.room_logs[iRoomLog];
								$("#room_log_listing_table").append(
									data_row(undefined, [2,4,2,2,2], 
											 [row_data.nfc_id, row_data.dt, row_data.room_identifier,
											  row_data.topics, row_data.course_name]));
							}
							$("#roomlog_pages").replaceWith(jsonData.page_list);
							$("#new_room_log_notifications").html(
								"<b>Sinulla on uusia merkintöjä lokissa</b>");
						}
					}
			});
		};
	
		setInterval(
			function() {
				getRoomData();
			}, 
			refreshRate
		);
	}
});

function validateForm() {
    var begin_datetime = document.forms["seek_room_log_form"]["begin_time"].value;
	var end_datetime = document.forms["seek_room_log_form"]["end_time"].value;
	var correct = is_datetime_order_correct(begin_datetime, end_datetime);
	if (!correct) {
		$("#validation_errors").html(
			"Ajat ei ole aikoja ollenkaan tai alkuaika on loppuajan jälkeen: korjaa se!");
	} else {
		$("#validation_errors").html("");
	}
	return correct;
}

function get_js_room_log_page(page, page_page) {
	begin_time = $("#last_query_begin_time").html();
	end_time = $("#last_query_end_time").html();
	seek_room = $("#last_query_room").html();
	seek_nfc_id = $("#last_query_nfc_id").html();
	seek_topic = $("#last_query_topic").html();
	seek_course_name = $("#last_query_course_name").html();
	$.get(buildHttpGetUrl(
			"inc/sub/room_log/get_room_log_page_ajax.php", 
			["seek_room","seek_nfc_id",
			 "seek_course_name","seek_topic",
			 "begin_time","end_time",
			 "page","page_page"],
			[seek_room,seek_nfc_id,seek_course_name,
			seek_topic,begin_time,
			end_time,page,page_page]), 
		function (data) {
			if (data != "") {
				jsonData = JSON.parse(data);	
				$("#room_log_listing_table .datarow").remove();
				for(iRoomLog=0; iRoomLog < jsonData.room_logs.length; iRoomLog++) {
					if (jsonData.room_logs[iRoomLog].topic == null) { 
						jsonData.room_logs[iRoomLog].topic = "&nbsp;"; 
					}
					if (jsonData.room_logs[iRoomLog].course_name == null) { 
						jsonData.room_logs[iRoomLog].course_name = "&nbsp;"; 
					}		
					row_data = jsonData.room_logs[iRoomLog];
					$("#room_log_listing_table").append(
						data_row(undefined, [2,4,2,2,2], 
							 [row_data.nfc_id, row_data.dt, row_data.room_identifier,
							  row_data.topics, row_data.course_name]));
				}		
				$("#roomlog_pages").replaceWith(jsonData.page_list);
				$('#page').html(page);
				$('#page_page').html(page_page);
			}
	});	
}