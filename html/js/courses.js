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
	topic_seek_selection_ids = $("#last_query_lesson_topics_seek_selection").html();
	topic_seek_name_parts = $("#last_query_lesson_topics_topic_seek").html();
	$.get(buildHttpGetUrl(
			"inc/sub/course/get_course_page_ajax.php", 
			["name_seek","description_seek",
			 "topic_seek_selection_ids", "topic_seek_name_parts",					 
			 "page","page_page"],
			[name_seek,description_seek,
			 topic_seek_selection_ids, topic_seek_name_parts,
			 page,page_page]), 
		function (data) {
			jsonData = JSON.parse(data);
			$("#course_listing_table .datarow").remove();
			for(i=0; i < jsonData.courses.length; i++) {
				courseData = jsonData.courses[i];
				$("#course_listing_table").append(
					data_row(id, 
						[4,6,"1-wrap"], 
						[courseData.name, courseData.description,
						modify_and_remove_columns(
							jsonData.courses[i].modify_call,
							jsonData.courses[i].remove_call)]));
			}		
			checkWidth();
			$("#course_pages").replaceWith(jsonData.page_list);
		
			// These can change also due to another user:
			$("#page").html(jsonData.page); 
			$("#page_page").html(jsonData.page_page);
	});
}

function removeCourse(course_id) {
	$.get(buildHttpGetUrl("inc/sub/course/remove_course.php", ["id"],[course_id]), 
		function (data) {	
			page = $("#page").html();
			page_page = $("#page_page").html();		
		
			// load new course list with lesson removed and modified page & page_page
			get_course_page(page, page_page);	
	});
}

function modifyCourse(course_id) {
	$.get(buildHttpGetUrl("inc/sub/course/fetch_course_with_id.php", ["id"],[course_id]), 
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
	course_lessons_handling_cont.append(
		div_elem("course_lessons_cont", undefined, false, ""));

	refresh_course_lessons(course_id);
	
	course_lessons_handling_cont.append(
		div_elem("avail_new_course_lessons_cont", undefined, false, ""));

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
					div_elem("course_lesson_listing_table", "datatable", false, ""));
				course_lessons_data_table = $("#course_lesson_listing_table");
				course_lessons_data_table.append(
					heading_row(undefined, [3,3,5,1], 
						["<h5>Aikaväli</h5>","<h5>Huone</h5>","<h5>Aihe</h5>",""]));
				for(iLesson=0; iLesson < jsonData.length; iLesson++) {
					lesson = jsonData[iLesson];
					course_lessons_data_table.append(
						data_row(undefined, [3,3,5,1],
						[lesson.lesson_period, lesson.room_identifier, lesson.topic,
						 button_elem(lesson.remove_call, "Poista")]));
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
		div_elem(undefined, "row-type-2", false, 
			label_elem("Aloitusaika:") + 
			input_elem(undefined, "lesson_add_begin_time_seek", "lesson_add_begin_time_seek", 
				undefined, true, undefined, undefined, "Aloitusaika",
				"Päivämäärä suomalaisessa muodossa esim: 31.07.2018 07:00", 
				"off", "datetime_picker")) +
		div_elem(undefined, "row-type-2", false, 
			label_elem("Lopetusaika:") + 
			input_elem(undefined, "lesson_add_end_time_seek", "lesson_add_end_time_seek", 
				undefined, true, undefined, undefined, "Lopetusaika",
				"Päivämäärä suomalaisessa muodossa esim: 31.07.2018 07:00", 
				"off", "datetime_picker")) +
		div_elem(undefined, "row-type-2", false,
			label_elem("Etsittävä luokan tunnisteen osa:") + 
			input_elem("text", "lesson_add_room_seek", "lesson_add_room_seek", 
				undefined, false, undefined, "50", "Huoneen tunnus")) +		
		div_elem(undefined, "row-type-2", false,
			label_elem("Etsittävä aiheen osa:") + 
			input_elem("text", "lesson_add_topic_seek", "lesson_add_topic_seek", 
				undefined, false, undefined, "50", "Aihe")) +	
		div_elem(undefined, "row-type-5", false, button_elem(call, "Hae")) +
		div_elem("new_avail_lessons_validation_msgs", undefined, false, "") +
		div_elem("add_avail_lesson_seek_params", undefined, true, ""));
	setUpDatetimepickers();
}

function seek_available_new_course_lessons(course_id) {
	avail_new_course_lessons_seek_params = $("#add_avail_lesson_seek_params");
	avail_new_course_lessons_seek_params.html("");
	avail_new_course_lessons_seek_params.append(
		div_elem("last_query_add_lesson_begin_time_seek", undefined, false, 
			$("#lesson_add_begin_time_seek").val()));
	avail_new_course_lessons_seek_params.append(
		div_elem("last_query_add_lesson_end_time_seek", undefined, false,
			$("#lesson_add_end_time_seek").val()));
	avail_new_course_lessons_seek_params.append(
		div_elem("last_query_add_lesson_room_seek", undefined, false,
			$("#lesson_add_room_seek").val()));
	avail_new_course_lessons_seek_params.append(
		div_elem("last_query_add_lesson_topic_seek", undefined, false,
			$("#lesson_add_topic_seek").val()));
	avail_new_course_lessons_seek_params.append(
		div_elem("lessons_without_course_page", undefined, false, "1"));
	avail_new_course_lessons_seek_params.append(
		div_elem("lessons_without_course_page_page", undefined, false, "1"));
	avail_new_course_lessons_cont = $("#avail_new_course_lessons_cont");
	avail_new_course_lessons_cont.append(
		div_elem("lessons_without_course_listing_table", "datatable", false, ""));
	avail_new_course_lessons_cont.append(
		div_elem("lessons_without_course_pages", undefined, false, ""));

	fetch_available_new_course_lessons(1,1,course_id);
}


function fetch_available_new_course_lessons(page,page_page,course_id) {
	seek_begin_time = $("#last_query_add_lesson_begin_time_seek").html();
	seek_end_time = $("#last_query_add_lesson_end_time_seek").html();
	seek_room = $("#last_query_add_lesson_room_seek").html();
	seek_topic = $("#last_query_add_lesson_topic_seek").html();
	if (validate_available_new_lessons_fetch_params(
		seek_begin_time, seek_end_time, seek_room, seek_topic)) {
		$.get(buildHttpGetUrl(
			"inc/sub/course/get_new_avail_lessons.php", 
			["id","begin_time_seek","end_time_seek",
			 "room_seek","topic_seek","page","page_page"],
			[course_id,seek_begin_time,seek_end_time,
			 seek_room,seek_topic,page,page_page]),
			function(data) {
				if (data != "") {
					console.log("fetch_available_new_course_lessons, data");
					console.log(data);
					jsonData = JSON.parse(data);
					if (jsonData.lessons.length > 0) {	
						avail_new_course_lessons_cont_table =
							$("#lessons_without_course_listing_table");
						avail_new_course_lessons_cont_table.html("");
						avail_new_course_lessons_cont_table.append(
							heading_row(undefined, [3,3,5,1], 
								["<h5>Aikaväli</h5>", "<h5>Huone</h5>", "<h5>Aihe</h5>",""]));
						for(iLesson=0; iLesson < jsonData.lessons.length; iLesson++) {
							lesson = jsonData.lessons[iLesson];
							avail_new_course_lessons_cont_table.append(
								data_row(undefined, [3,3,5,"1-wrap"],
									[lesson.lesson_period, lesson.room_identifier,
									 lesson.topic,
									 js_action_column(lesson.add_call, "Lisää")]));
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
	$.get(buildHttpGetUrl("inc/sub/course/add_lesson_to_course.php", 
			["course_id"],[course_id]),
		function(data) {	
			refresh_course_lessons(course_id);	
			page = $("#lessons_without_course_page").html();
			page_page =	$("#lessons_without_course_page_page").html();			
			fetch_available_new_course_lessons(page, page_page,course_id);
		}
	);
}

function removeCourseLesson(course_id, lesson_id) {
	$.get(buildHttpGetUrl("inc/sub/course/remove_lesson_from_course.php", 
			["lesson_id"],[lesson_id]),
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
	$.get(buildHttpGetUrl("inc/sub/course/save_course.php", 
			["id","name","description"],[id,name,description]),
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