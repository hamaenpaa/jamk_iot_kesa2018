function validateSeekForm() {
	var begin_datetime = document.forms["lessons_seek"]["begin_time_seek"].value;
	var end_datetime = document.forms["lessons_seek"]["end_time_seek"].value;
	
	var correct = is_datetime_order_correct(begin_datetime, end_datetime);
	if (!correct) {
		$("#seekform_validation_errors").html(
			"Alkuaika on loppuajan jälkeen: korjaa se!");
	} else {
		$("#seekform_validation_errors").html("");
	}
	return correct;
}

function validateAddOrModifyForm() {
	var begin_time = $("#begin_time").val();
	var end_time = $("#end_time").val();
	
	var correct = is_time_order_correct(begin_time, end_time);
	if (!correct) {
		$("#add_or_modify_lesson_form_validation_errors").html(
			"Alkuaika on loppuajan jälkeen: korjaa se!");
	} else {
		$("#add_or_modify_lesson_form_validation_errors").html("");
	}	
	return correct;
}

function get_lessons_page(page, page_page) {
	begin_time_seek = $("#last_query_begin_time_seek").html(); 
	end_time_seek = $("#last_query_end_time_seek").html();
	room_seek = $("#last_query_room_seek").html();
	topic_seek = $("#last_query_topic_seek").html();
	$.get("inc/sub/lesson/get_lesson_page_ajax.php?" +
			"begin_time_seek="+begin_time_seek + "&end_time_seek="+end_time_seek +
			"&room_seek="+room_seek+"&topic_seek="+topic_seek+
			"&page="+page+
			"&page_page="+page_page, function (data) {
		if (data != "") {
			jsonData = JSON.parse(data);
			$("#lesson_listing_table .datarow").remove();
			for(i=0; i < jsonData.lessons.length; i++) {
				elemToBeInserted = 
					"<div class=\"row datarow\">" +
						"<div class=\"col-sm-4\">" + jsonData.lessons[i].time_interval + "</div>" +
						"<div class=\"col-sm-3\">" + jsonData.lessons[i].room_identifier + "</div>" +
						"<div class=\"col-sm-3\">" + jsonData.lessons[i].topic + "</div>" +
						modify_and_remove_columns(
							jsonData.lessons[i].modify_call,
							jsonData.lessons[i].remove_call) +
					"</div>";
				$("#lesson_listing_table").append(elemToBeInserted);
			}		
			checkWidth();
			$("#lesson_pages").replaceWith(jsonData.page_list);
		
			// These can change also due to another user:
			$("#page").html(jsonData.page); 
			$("#page_page").html(jsonData.page_page);
		}
	});
}

function removeLesson(lesson_id) {
	$.get("inc/sub/lesson/remove_lesson.php?id="+lesson_id, function (data) {	
		page = $("#page").html();
		page_page = $("#page_page").html();		
		
		// load new lesson list with lesson removed and modified page & page_page
		get_lessons_page(page, page_page);	
	});
}

function modifyLesson(lesson_id) {
	$.get("inc/sub/lesson/fetch_lesson_with_id.php?id=" + lesson_id,
		function(data) {
			jsonData = JSON.parse(data);
			if (jsonData.date !== undefined && jsonData.date != null && jsonData.date != "" ) {
				$("#add_or_modify_lesson_header").html(
					"Muokkaa koulutusta tai oppituntia");
				$("#id").val(lesson_id);
				$("#lesson_date").val(jsonData.date); 
				$("#begin_time").val(jsonData.begin_time);
				$("#end_time").val(jsonData.end_time);
				$("#room_identifier").val(jsonData.room_identifier);
				$("#topic").val(jsonData.topic);
			}
		}
	);
}

function saveLesson() {
	if (validateAddOrModifyForm()) {
		id = $("#id").val();
		lesson_date = $("#lesson_date").val();
		begin_time = $("#begin_time").val();
		end_time = $("#end_time").val();
		room_identifier = $("#room_identifier").val();
		topic = $("#topic").val();
		$.get("inc/sub/lesson/save_lesson.php?id=" + id +
			"&lesson_date="+lesson_date+"&begin_time="+begin_time+
			"&end_time="+end_time+"&room_identifier="+room_identifier+
			"&topic="+topic,
			function(data) {
				page = $("#page").html();
				page_page = $("#page_page").html();				
				get_lessons_page(page, page_page);					
				$("#id").val("");
				$("#lesson_date").val("");
				$("#begin_time").val("");
				$("#end_time").val("");
				$("#room_identifier").val("");
				$("#topic").val("");					
				$("#add_or_modify_lesson_header").html(
					"Lisää koulutus tai oppitunti");
			}
		);		
	}
}