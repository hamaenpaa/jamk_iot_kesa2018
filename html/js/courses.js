$().ready(function() {
	seek_selection = $("#last_query_lesson_topics_seek_selection").html();
	topic_parts_seek = $("#last_query_lesson_topics_topic_seek").html();
	container_id = "lesson_topics_seek";
	selectTopicHandling(container_id, seek_selection, topic_parts_seek);
});

function checkCourseSeek() {
	if ($("#name_seek").val().length > 50) {
		$("#course_seek_validation_msgs").html(
		"Kurssin nimessä on korkeintaan 50 merkkiä." +
		"Ei ole mieltä etsiä pidemmällä merkkijonolla.");
	} else if ($("#description_seek").val().length > 500) {
		$("#course_seek_validation_msgs").html(
			"Kuvauksen maksimipituus on 500 merkkiä. " +
			"Ei ole mieltä etsiä pidemmällä merkkijonolla");
	} else if ($("#topic_seek").val().length > 150) {
		$("#course_seek_validation_msgs").html(
			"Aiheen maksimipituus on 150 merkkiä. " +
			"Ei ole mieltä etsiä pidemmällä merkkijonolla");
	}		
	return true;
}

function get_course_page(page, page_page) {
	name_seek = $("#last_query_name_seek").html(); 
	description_seek = $("#last_query_description_seek").html();
	topic_seek = $("#last_query_topic_seek").html();
	$.get("inc/sub/course/get_course_page_ajax.php?" +
			"name_seek="+name_seek + "&description_seek="+description_seek +
			"&topic_seek="+topic_seek + "&page="+page+ "&page_page="+page_page, 
			function (data) {
		jsonData = JSON.parse(data);
		$("#course_listing_table .datarow").remove();
		for(i=0; i < jsonData.courses.length; i++) {
			elemToBeInserted = 
				"<div class=\"row datarow\">" +
					"<div class=\"col-sm-4\">" + jsonData.courses[i].name + "</div>" +
					"<div class=\"col-sm-6\">" + jsonData.courses[i].description + "</div>" +
					modify_and_remove_columns(
						jsonData.courses[i].modify_call,
						jsonData.courses[i].remove_call) +
				"</div>";
			$("#course_listing_table").append(elemToBeInserted);
		}		
		checkWidth();
		$("#course_pages").replaceWith(jsonData.page_list);
		
		// These can change also due to another user:
		$("#page").html(jsonData.page); 
		$("#page_page").html(jsonData.page_page);
	});
}

function removeCourse(course_id) {
	$.get("inc/sub/course/remove_course.php?id="+course_id, function (data) {	
		page = $("#page").html();
		page_page = $("#page_page").html();		
		
		// load new course list with lesson removed and modified page & page_page
		get_course_page(page, page_page);	
	});
}

function modifyCourse(course_id) {
	$.get("inc/sub/course/fetch_course_with_id.php?id=" + course_id,
		function(data) {
			jsonData = JSON.parse(data);
				$("#add_or_modify_course_header").html(
					"Muokkaa kurssia");
				$("#id").val(course_id);
				$("#name").val(jsonData.name); 
				$("#description").val(jsonData.description);
		}
	);
	get_course_lessons(course_id);
}

function get_course_lessons(course_id) {
	course_lessons_handling_cont = $("#course_lessons_handling");
	course_lessons_handling_cont.html("");
	course_lessons_handling_cont.append("<div id=\"course_lessons_cont\"></div>");

	refresh_course_lessons(course_id);
	
	course_lessons_handling_cont.append("<div id=\"avail_new_course_lessons_cont\"></div>");
	build_new_avail_course_lessons_section(course_id);
	
	checkWidth();	
}


function refresh_course_lessons(course_id) {
	course_lessons_cont = $("#course_lessons_cont");
	course_lessons_cont.html("");
	course_lessons_cont.append("<h2>Kurssin oppitunnit</h2>");
	$.get("inc/sub/course/get_course_lessons_ajax.php?id=" + course_id,
		function(data) {
			jsonData = JSON.parse(data);
			if (jsonData.length > 0) {
				course_lessons_cont.append(
					"<div id=\"course_lesson_listing_table\" class=\"datatable\"></div>");
				course_lessons_data_table = $("#course_lesson_listing_table");
				course_lessons_data_table.append(
						"<div class=\"row heading-row\">" +
							"<div class=\"col-sm-3\"><h5>Aikaväli</h5></div>" +
							"<div class=\"col-sm-3\"><h5>Huone</h5></div>" +
							"<div class=\"col-sm-5\"><h5>Aihe</h5></div>" +
							"<div class=\"col-sm-1\"></div>" +
						"</div>");				
				for(i=0; i < jsonData.length; i++) {
					course_lessons_data_table.append(
						"<div class=\"row datarow\">" +
							"<div class=\"col-sm-3\">" + jsonData[i].lesson_period + "</div>" +
							"<div class=\"col-sm-3\">" + jsonData[i].room_identifier + "</div>" +
							"<div class=\"col-sm-5\">" + jsonData[i].topic + "</div>" +
							js_action_column(jsonData[i].remove_call, "Poista") +
						"</div>");
				}
			}
			else {
				course_lessons_cont.append("Kurssilla ei ole yhtään oppituntia");
			}
	});	
}


function build_new_avail_course_lessons_section(course_id) {
	avail_new_course_lessons_cont = $("#avail_new_course_lessons_cont");
	avail_new_course_lessons_cont.html("");
	avail_new_course_lessons_cont.append("<h2>Lisää oppitunteja kurssille</h2>");	
	avail_new_course_lessons_cont.append("<b>Hae oppitunteja kurssille</b>");
	call = "seek_available_new_course_lessons(" + course_id + ")";
	avail_new_course_lessons_cont.append(
		"<div class=\"row-type-2\">" +
			"<label>Aloitusaika:</label>" +
			"<input name=\"lesson_add_begin_time_seek\" class=\"datetime_picker\" " + 
				"id=\"lesson_add_begin_time_seek\" autocomplete=\"off\" placeholder=\"Aloitusaika\" " +
				"alt=\"Päivämäärä suomalaisessa muodossa esim: 31.07.2018 07:00\" " +
				" required />" +
		"</div>" +
		"<div class=\"row-type-2\">" +
			"<label>Lopetusaika:</label>" + 
			"<input name=\"lesson_add_end_time_seek\" class=\"datetime_picker\" " +
				"id=\"lesson_add_end_time_seek\" autocomplete=\"off\" placeholder=\"Lopetusaika\" " +
				"alt=\"Päivämäärä suomalaisessa muodossa esim: 31.07.2018 07:00\" " +
				" required />" +
		"</div>" + 
		"<div class=\"row-type-2\">" + 
			"<label>Etsittävä luokan tunnisteen osa:</label>" + 
			"<input id=\"lesson_add_room_seek\" name=\"lesson_add_room_seek\" " +
				"placeholder=\"Huoneen tunnus\" maxlength=\"50\"/>"+
		"</div>" +
		"<div class=\"row-type-2\">" +
			"<label>Etsittävä aiheen osa:</label>" +
			"<input id=\"lesson_add_topic_seek\" name=\"lesson_add_topic_seek\" " + 
				"placeholder=\"Aihe\" maxlength=\"150\"/>" + 
		"</div>" + 
		"<div class=\"row-type-5\">" + 
			"<button class=\"button\" onclick=\"" + call + "\" >Hae</button>" +
		"</div>" +
		"<div id=\"new_avail_lessons_validation_msgs\"></div>" +
		"<div id=\"add_avail_lesson_seek_params\" style=\"display:none\"></div>");
		setUpDatetimepickers();
}

function seek_available_new_course_lessons(course_id) {
	avail_new_course_lessons_seek_params = $("#add_avail_lesson_seek_params");
	avail_new_course_lessons_seek_params.html("");
	avail_new_course_lessons_seek_params.append(
		"<div id=\"last_query_add_lesson_begin_time_seek\" >" + 
			$("#lesson_add_begin_time_seek").val() + 
		"</div>");
	avail_new_course_lessons_seek_params.append(
		"<div id=\"last_query_add_lesson_end_time_seek\" >" + 
			$("#lesson_add_end_time_seek").val() + 
		"</div>");		
	avail_new_course_lessons_seek_params.append(
		"<div id=\"last_query_add_lesson_room_seek\" >" + 
			$("#lesson_add_room_seek").val() + 
		"</div>");
	avail_new_course_lessons_seek_params.append(
		"<div id=\"last_query_add_lesson_topic_seek\" >" + 
			$("#lesson_add_topic_seek").val() + 
		"</div>");
	avail_new_course_lessons_seek_params.append(	
		"<div id=\"lessons_without_course_page\" >1</div>");
	avail_new_course_lessons_seek_params.append(	
		"<div id=\"lessons_without_course_page_page\" >1</div>");
		
	avail_new_course_lessons_cont = $("#avail_new_course_lessons_cont");
	avail_new_course_lessons_cont.append(
		"<div id=\"lessons_without_course_listing_table\" " +
			"class=\"datatable\"></div>");
	avail_new_course_lessons_cont.append(
		"<div id=\"lessons_without_course_pages\"></div>");			

	fetch_available_new_course_lessons(1,1,course_id);
}


function fetch_available_new_course_lessons(page,page_page,course_id) {
	seek_begin_time = $("#last_query_add_lesson_begin_time_seek").html();
	seek_end_time = $("#last_query_add_lesson_end_time_seek").html();
	seek_room = $("#last_query_add_lesson_room_seek").html();
	seek_topic = $("#last_query_add_lesson_topic_seek").html();
	if (validate_available_new_lessons_fetch_params(
		seek_begin_time, seek_end_time, seek_room, seek_topic)) {
		$.get("inc/sub/course/get_new_avail_lessons.php?id=" + course_id +
			"&begin_time_seek="+seek_begin_time+"&end_time_seek="+seek_end_time+
			"&room_seek="+seek_room+"&topic_seek="+seek_topic+
			"&page="+page+"&page_page="+page_page,
			function(data) {
				if (data != "") {
					jsonData = JSON.parse(data);
					if (jsonData.lessons.length > 0) {	
						avail_new_course_lessons_cont_table =
							$("#lessons_without_course_listing_table");
						avail_new_course_lessons_cont_table.html("");
						avail_new_course_lessons_cont_table.append(
							"<div class=\"row heading-row\">" +
								"<div class=\"col-sm-3\"><h5>Aikaväli</h5></div>" +
								"<div class=\"col-sm-3\"><h5>Huone</h5></div>" +
								"<div class=\"col-sm-5\"><h5>Aihe</h5></div>" +
								"<div class=\"col-sm-1\"></div>" +
							"</div>");
						for(i=0; i < jsonData.lessons.length; i++) {
							avail_new_course_lessons_cont_table.append(
								"<div class=\"row datarow\">" +
									"<div class=\"col-sm-3\">" + jsonData.lessons[i].lesson_period + "</div>" +
								"<div class=\"col-sm-3\">" + jsonData.lessons[i].room_identifier + "</div>" +
								"<div class=\"col-sm-5\">" + jsonData.lessons[i].topic + "</div>" +
									js_action_column(jsonData.lessons[i].add_call, "Lisää") +
								"</div>"
							);
						}
						$("#lessons_without_course_page").html(jsonData.page);
						$("#lessons_without_course_page_page").html(jsonData.page_page);
						$("#lessons_without_course_pages").replaceWith(
							jsonData.page_list);
					} else {
						$("#lessons_without_course_listing_table").html(
						"Yhtään oppituntia ei ole vapaana lisättäväksi");
					}
					checkWidth();
				}
			}
		);
	}
}

function validate_available_new_lessons_fetch_params(
	seek_begin_time, seek_end_time, seek_room, seek_topic) {
	if (!is_datetime_order_correct(seek_begin_time, seek_begin_time)) {
		$("#new_avail_lessons_validation_msgs").html(
		   "Etsinnässä olevan aikavälin ajat eivät ole oikeita aikoja tai ne ovat väärässä järjestyksessä.");
		return false;
	} else if (seek_room.length > 50) {
		$("#new_avail_lessons_validation_msgs").html(
			"Huoneen tunnisteen maksimipituus on 50 merkkiä. " +
			"Ei ole mieltä etsiä pidemmällä merkkijonolla");
		return false;
	} else if (seek_topic.length > 150) {
		$("#new_avail_lessons_validation_msgs").html(
			"Aiheen maksimipituus on 150 merkkiä. " +
			"Ei ole mieltä etsiä pidemmällä merkkijonolla");
		return false;
	}	
	return true;
}

function addLessonCourse(course_id, lesson_id) {
	$.get("inc/sub/course/add_lesson_to_course.php?course_id=" + course_id + 
		"&lesson_id=" + lesson_id,
		function(data) {	
			refresh_course_lessons(course_id);	
			page = $("#lessons_without_course_page").html();
			page_page =	$("#lessons_without_course_page_page").html();			
			fetch_available_new_course_lessons(page, page_page,course_id);
		}
	);
}

function removeCourseLesson(course_id, lesson_id) {
	$.get("inc/sub/course/remove_lesson_from_course.php?lesson_id=" + lesson_id,
		function(data) {
			refresh_course_lessons(course_id);	
			page = $("#lessons_without_course_page").html();
			page_page =	$("#lessons_without_course_page_page").html();	
			// refetch new course lessons IF THEY HAVE BEEN SEEKED SO FAR
			if (page != "" && page != "0" && page != undefined) {
				fetch_available_new_course_lessons(page, page_page, course_id);
			}
		}
	);
}

function saveCourse() {
	id = $("#id").val();
	name = $("#name").val();
	description = $("#description").val();
	if (!validateCourse(name, description)) {
		return;
	}
	$.get("inc/sub/course/save_course.php?id=" + id +
		"&name="+name+"&description="+description,
		function(data) {
			page = $("#page").html();
			page_page = $("#page_page").html();				
			get_course_page(page, page_page);					
			$("#id").val("");
			$("#name").val("");
			$("#description").val("");
			$("#add_or_modify_course_header").html("Lisää kurssi");
			$("#course_lessons_handling").html("");
			$("#course_validation_msgs").html("");
		}
	);		
}


function validateCourse(name, description) {
	passed = true;
	msg = "";
	if (name == "") {
		msg = "Kurssin nimi on pakollinen";
		passed = false;
	}
	if (passed && name.length > 50) {
		msg = "Kurssin nimen maksimimipituus on 50 merkkiä";
		passed = false;		
	}
	if (passed && description.length > 500) {
		msg = "Kurssin kuvauksen maksimimipituus on 500 merkkiä";
		passed = false;		
	}
	if (!passed) {
		$("#course_validation_msgs").html(msg);
	}
	return passed;
}