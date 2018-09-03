$().ready(function() {
	seek_selection = $("#last_query_lesson_topics_seek_selection").html();
	topic_parts_seek = $("#last_query_lesson_topics_topic_seek").html();
	container_id = "lesson_topics_seek";
	selectTopicHandling(container_id, seek_selection, topic_parts_seek);
});


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
	if (!checkDateStr($("#lesson_date").val())) {
		$("#add_or_modify_lesson_form_validation_errors").html(
			"Päiväys ei ole oikea päiväys: korjaa se!");
		return false;
	}
	if ($("#room_identifier").val().length > 50) {
		$("#add_or_modify_lesson_form_validation_errors").html(
			"Huoneen tunnus ei voi olla pidempi kuin 50 merkkiä. Korjaa se!");
		return false;		
	}
	
	var begin_time = $("#begin_time").val();
	var end_time = $("#end_time").val();
	
	var correct = is_time_order_correct(begin_time, end_time);
	if (!correct) {
		$("#add_or_modify_lesson_form_validation_errors").html(
			"Ajat eivät ole oikeita aikoja tai alkuaika on loppuajan jälkeen: korjaa se!");
	} else {
		$("#add_or_modify_lesson_form_validation_errors").html("");
	}	
	return correct;
}

function get_lessons_page(page, page_page) {
	begin_time_seek = $("#last_query_begin_time_seek").html(); 
	// pages are not refreshed if lessons are not even fetched yet
	if (begin_time_seek != undefined && begin_time_seek != "") { 
		end_time_seek = $("#last_query_end_time_seek").html();
		room_seek = $("#last_query_room_seek").html();
		topic_seek_selection_ids = $("#last_query_lesson_topics_seek_selection").html();
		topic_seek_name_parts = $("#last_query_lesson_topics_topic_seek").html();
		$.get("inc/sub/lesson/get_lesson_page_ajax.php?" +
			"begin_time_seek="+begin_time_seek + "&end_time_seek="+end_time_seek +
			"&room_seek="+room_seek+
			"&topic_seek_selection_ids="+topic_seek_selection_ids + 
			"&topic_seek_name_parts="+topic_seek_name_parts + 
			"&page="+page+
			"&page_page="+page_page, function (data) {
			if (data != "") {
				jsonData = JSON.parse(data);
				$("#lesson_listing_table .datarow").remove();
				for(i=0; i < jsonData.lessons.length; i++) {
					elemToBeInserted = 
					"<div class=\"row datarow\">" +
						"<div class=\"col-sm-5\">" + jsonData.lessons[i].time_interval + "</div>" +
						"<div class=\"col-sm-6\">" + jsonData.lessons[i].room_identifier + "</div>" +
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
			buildLessonTopicHandling(lesson_id);
		}
	);
}

function buildLessonTopicHandling(lesson_id) {
	$("#lesson_topics_handling").html(
		"<h2>Oppitunnin/koulutuksen aiheiden käsittely</h2>" +
		"<div id=\"lesson_topics\"></div>" +
		"<div id=\"new_avail_lesson_topics\"></div>"	
	);
	fetchLessonTopics(lesson_id);
	fetchNewAvailLessonTopics(lesson_id);
}

function fetchLessonTopics(lesson_id) {
	$.get("inc/sub/lesson/get_lesson_topics.php?id=" + lesson_id,
		function(data) {
			jsonData = JSON.parse(data);
			$("#lesson_topics").html("<b>Oppitunnin aiheet</b>");
			if (jsonData.length > 0) {
				$("#lesson_topics").append(
					"<div id=\"lesson_topics_listing_table\" class=\"datatable\"></div>");
				table = $("#lesson_topics_listing_table");
				table.append(
					"<div class=\"row heading-row\">" +
						"<div class=\"col-sm-11\"><h5>Aihe</h5></div>" +
						"<div class=\"col-sm-1\"></div>" +
					"</div>");
				for(i=0; i < jsonData.length; i++) {
					table.append(
						"<div class=\"row data-row\">" +	
							"<div class=\"col-sm-11\">" +jsonData[i].name + "</div>" +
							js_action_column(jsonData[i].remove_call, "Poista") +
						"</div>");
				}
				checkWidth();
			} else {
				$("#lesson_topics").html("<b>Oppitunnilla/koulutuksella ei ole yhtään aihetta");
			}			
		}
	);
}

function removeLessonTopic(lesson_id, topic_id) {
	$.get("inc/sub/lesson/remove_lesson_topic.php?topic_id=" + topic_id + 
		"&lesson_id=" + lesson_id,
		function (data) {
			fetchLessonTopics(lesson_id);
			fetchNewAvailLessonTopics(lesson_id);			
		}
	);
}

function fetchNewAvailLessonTopics(lesson_id) {
	$.get("inc/sub/lesson/fetch_new_avail_lesson_topics.php?id=" + lesson_id,
		function(data) {	
			jsonData = JSON.parse(data);
			$("#new_avail_lesson_topics").html(
				"<b>Uusia aiheita saatavilla oppitunnin aiheiksi</b>");	
			if (jsonData.length > 0) {
				$("#new_avail_lesson_topics").append(
					"<div id=\"lesson_new_avail_topics_listing_table\" class=\"datatable\"></div>");
				table = $("#lesson_new_avail_topics_listing_table");
				table.append(
					"<div class=\"row heading-row\">" +
						"<div class=\"col-sm-11\"><h5>Aihe</h5></div>" +
						"<div class=\"col-sm-1\"></div>" +
					"</div>");	
				for(i=0; i < jsonData.length; i++) {
					table.append(
						"<div class=\"row data-row\">" +	
							"<div class=\"col-sm-11\">" +jsonData[i].name + "</div>" +
							js_action_column(jsonData[i].add_call, "Lisää") +
						"</div>");
				}	
				checkWidth();
			} else {
				$("#new_avail_lesson_topics").html(
					"<b>Oppitunnille ei ole olemassa yhtään uutta aihetta</b>");
			}
		}
	);
}


function addTopicToLesson(topic_id, lesson_id) {
	$.get("inc/sub/lesson/add_topic_to_lesson.php?topic_id=" + topic_id + 
		"&lesson_id=" + lesson_id,
		function (data) {
			fetchLessonTopics(lesson_id);
			fetchNewAvailLessonTopics(lesson_id);			
		}
	)
}

function saveLesson() {
	if (validateAddOrModifyForm()) {
		id = $("#id").val();
		lesson_date = $("#lesson_date").val();
		begin_time = $("#begin_time").val();
		end_time = $("#end_time").val();
		room_identifier = $("#room_identifier").val();
		$.get("inc/sub/lesson/save_lesson.php?id=" + id +
			"&lesson_date="+lesson_date+"&begin_time="+begin_time+
			"&end_time="+end_time+"&room_identifier="+room_identifier,
			function(data) {
				page = $("#page").html();
				page_page = $("#page_page").html();				
				get_lessons_page(page, page_page);					
				$("#id").val("");
				$("#lesson_date").val("");
				$("#begin_time").val("");
				$("#end_time").val("");
				$("#room_identifier").val("");
				$("#add_or_modify_lesson_header").html(
					"Lisää koulutus tai oppitunti");
				$("#lesson_topics_handling").html("");
			}
		);		
	}
}