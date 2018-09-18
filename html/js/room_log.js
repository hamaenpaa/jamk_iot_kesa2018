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
							getSummaries(jsonData);
							checkWidth();
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
				getSummaries(jsonData);
				checkWidth();
			}
	});	
}

function getSummaries(jsonData) {
	getDynamitSummary(jsonData);
	getExpaSummary(jsonData);
}

function getDynamitSummary(jsonData) {
	if (jsonData.NFC_ID_topics_and_lessons !== undefined) {
		topics_and_lessons = jsonData.NFC_ID_topics_and_lessons;
		dynamit_summary_html = "";
		for(iTopicsAndLessonItem=0; iTopicsAndLessonItem < topics_and_lessons.length; 
			iTopicsAndLessonItem++) {
			dynamit_summary_html += "<h3><b>NFC ID: " + 
				topics_and_lessons[iTopicsAndLessonItem].nfc_id +
				"</b></h3><h4><b>Aiheet:</b></h4>";
			topics_list = "";
			topics_arr = topics_and_lessons[iTopicsAndLessonItem].topics;
			for(iTopicsItem=0; iTopicsItem < topics_arr.length; iTopicsItem++) {
				if (topics_list != "") { topics_list += ","; }
				topics_list += topics_arr[iTopicsItem].topic_name;
			}
			dynamit_summary_html += topics_list + "<br>";
			dynamit_summary_html += "<h4><b>Aiheet oppitunneittain</b></h4>";
			for(iTopicsItem=0; iTopicsItem < topics_arr.length; iTopicsItem++) {
				dynamit_summary_html += "<b>Aihe: " + topics_arr[iTopicsItem].topic_name + "</b><br>" +
					"<b>oppitunnit</b>:<br> ";
				for(iTopicLesson=0; iTopicLesson < topics_arr[iTopicsItem].lessons.length; iTopicLesson++) {
					lesson = topics_arr[iTopicsItem].lessons[iTopicLesson];
					if (lesson.course != undefined && lesson.course != "" && lesson.course != null &&
						lesson.course != "&nbsp;") {
						dynamit_summary_html += "<b>Kurssi: </b>" + lesson.course + " ";
					}
					dynamit_summary_html += "<b>Huone:</b> " + lesson.room_identifier +
						" <b>Aika:</b> " + lesson.time_interval + "<br>";
				}
				dynamit_summary_html += "<br>";			
			}
		}
		$("#dynamit_summary").html(dynamit_summary_html);
	}
}

function getExpaSummary(jsonData) {
	if (jsonData.NFC_ID_monthly_counts !== undefined) {
		counts = jsonData.NFC_ID_monthly_counts;
		expa_summary_html = "";
		for(iYearItem=0; iYearItem < counts.year_counts.length; iYearItem++) {
			expa_summary_html += "<h2>Kävijät vuonna " + counts.year_counts[iYearItem].year + "</h2>";
			expa_summary_html += "Koko vuonna oli valitulla ajalla yhteensä " + 
				counts.year_counts[iYearItem].count + " kävijää.<br><br>";
			expa_summary_html += "Vuonna oli kuukausittain valitulla ajalla kävijöitä seuraavasti: <br><br>";
			arr_months = [];
			for(iMonthItem=0; iMonthItem < counts.month_counts.length; iMonthItem++) {
				if (counts.month_counts[iMonthItem].year == counts.year_counts[iYearItem].year) {
					arr_months = counts.month_counts[iMonthItem].months;
				}
			}
			for(var monthKey in arr_months) {
				expa_summary_html += 
					arr_monthnames[monthKey - 1] + ": " + arr_months[monthKey] + " kävijää.";
			}
			expa_summary_html += "<br>";
			
		}
		$("#expa_summary").html(expa_summary_html);
	}
}