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
		$.get(buildHttpGetUrl("inc/sub/lesson/get_lesson_page_ajax.php", 
			["begin_time_seek","end_time_seek", "room_seek", 				 
			 "topic_seek_selection_ids", "topic_seek_name_parts",
			 "page","page_page"],
			[begin_time_seek,end_time_seek, room_seek,
			 topic_seek_selection_ids, topic_seek_name_parts,
			 page,page_page]), function (data) {
			if (data != "") {
				jsonData = JSON.parse(data);
				$("#lesson_listing_table .datarow").remove();
				for(i=0; i < jsonData.lessons.length; i++) {
					$("#lesson_listing_table").append(
						data_row(undefined, [5,5,"1-wrap"],
							[jsonData.lessons[i].time_interval,
							 jsonData.lessons[i].room_identifier,
							 modify_and_remove_columns(
								jsonData.lessons[i].modify_call,
								jsonData.lessons[i].remove_call)]));
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
	$.get(buildHttpGetUrl("inc/sub/lesson/remove_lesson.php", ["id"], [lesson_id]), 
		function (data) {	
			page = $("#page").html();
			page_page = $("#page_page").html();		
		
			// load new lesson list with lesson removed and modified page & page_page
			get_lessons_page(page, page_page);	
	});
}

function modifyLesson(lesson_id) {
	$.get(buildHttpGetUrl("inc/sub/lesson/fetch_lesson_with_id.php", ["id"], [lesson_id]), 
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
		div_elem("lesson_topics", undefined, false, "") +
		div_elem("new_avail_lesson_topics", undefined, false, ""));
	fetchLessonTopics(lesson_id);
	fetchNewAvailLessonTopics(lesson_id);
}

function fetchLessonTopics(lesson_id) {
	$.get(buildHttpGetUrl("inc/sub/lesson/get_lesson_topics.php", ["id"], [lesson_id]),
		function(data) {
			jsonData = JSON.parse(data);
			$("#lesson_topics").html("<b>Oppitunnin aiheet</b>");
			if (jsonData.length > 0) {
				$("#lesson_topics").append(
					div_elem("lesson_topics_listing_table", "datatable", false, ""));
				table = $("#lesson_topics_listing_table");
				table.append(heading_row(undefined, [11,1], ["<h5>Aihe</h5>",""]));
				for(iTopic=0; iTopic < jsonData.length; iTopic++) {
					table.append(data_row(undefined, [11,1], 
						[jsonData[iTopic].name, 
						 button_elem(jsonData[iTopic].remove_call, "Poista")]));
				}
				checkWidth();
			} else {
				$("#lesson_topics").html("<b>Oppitunnilla/koulutuksella ei ole yhtään aihetta");
			}			
		}
	);
}

function removeLessonTopic(lesson_id, topic_id) {
	$.get(buildHttpGetUrl("inc/sub/lesson/get_lesson_topics.php", 
		["topic_id","lesson_id"], [topic_id,lesson_id]),
		function (data) {
			fetchLessonTopics(lesson_id);
			fetchNewAvailLessonTopics(lesson_id);			
		}
	);
}

function fetchNewAvailLessonTopics(lesson_id) {
	$.get(buildHttpGetUrl("inc/sub/lesson/fetch_new_avail_lesson_topics.php", 
		["id"], [lesson_id]),
		function(data) {	
			jsonData = JSON.parse(data);
			$("#new_avail_lesson_topics").html(
				"<b>Uusia aiheita saatavilla oppitunnin aiheiksi</b>");	
			if (jsonData.length > 0) {
				$("#new_avail_lesson_topics").append(
					div_elem("lesson_new_avail_topics_listing_table", "datatable", false, ""));
				table = $("#lesson_new_avail_topics_listing_table");
				table.append(heading_row(undefined, [11,1], ["<h5>Aihe</h5>",""]));	
				for(iTopic=0; iTopic < jsonData.length; iTopic++) {
					table.append(data_row(undefined, [11,1], 
						[jsonData[iTopic].name, 
						 button_elem(jsonData[iTopic].add_call, "Lisää")]));
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
	$.get(buildHttpGetUrl("inc/sub/lesson/fetch_new_avail_lesson_topics.php", 
		["topic_id","lesson_id"], [topic_id,lesson_id]),
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
		$.get(buildHttpGetUrl("inc/sub/lesson/save_lesson.php", 
			["id","lesson_date","begin_time","end_time","room_identifier"], 
			[id,lesson_date,begin_time,end_time,room_identifier]),
			function(data) {
				jsonData = JSON.parse(data);
				if (jsonData.overlapping == "1") {
					$("#add_or_modify_lesson_form_validation_errors").html(
						"Toinen oppitunti on jo olemassa samassa huoneessa ja samana päivänä. Vaihda jompakumpaa!");				
					return;
				}
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
				$("#add_or_modify_lesson_form_validation_errors").html("");
			}
		);		
	}
}