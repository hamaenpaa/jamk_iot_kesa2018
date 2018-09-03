topics_page_page_size = 2;
topics_page_size = 2;

function strOnlyDigits(str) {
	for(i=0; i < str.length; i++) {
		if (str[i] < '0' || str[i] > '9') {
			return false;
		}
	}
	return true;
}

function is_time_order_correct(begin_time, end_time) {
	if (begin_time === undefined || end_time === undefined) {
		return false;
	}
	
	var begin_time_parts = begin_time.split(":");
	var end_time_parts = end_time.split(":");	

	if (begin_time_parts.length != 2 || end_time_parts.length != 2) {
		return false;
	}	
	
	var begin_hours_str = begin_time_parts[0];
	if (!strOnlyDigits(begin_hours_str)) {
		return false;
	}
	var begin_hours = parseInt(begin_hours_str);
	
	var end_time_str = end_time_parts[0];
	if (!strOnlyDigits(end_time_str)) {
		return false;
	}	
	var end_hours = parseInt(end_time_str);

	var begin_minutes_str = begin_time_parts[1];
	if (!strOnlyDigits(begin_minutes_str)) {
		return false;
	}		
	var begin_minutes = parseInt(begin_minutes_str);
	
	var end_minutes_str = end_time_parts[1];
	if (!strOnlyDigits(end_minutes_str)) {
		return false;
	}
	var end_minutes = parseInt(end_minutes_str);	

	if (begin_hours < 0 || begin_hours > 23 ||
		end_hours < 0 || end_hours > 23) {
		return false;
	}
	if (begin_minutes < 0 || begin_minutes > 59 ||
		end_minutes < 0 || end_minutes > 59) {
		return false;
	}	
	
	timeorder_correct = true;
	if (begin_hours > end_hours) {
		timeorder_correct = false;
	} else if (begin_hours == end_hours) {
		if (begin_minutes > end_minutes) {
			timeorder_correct = false;
		}
	}	
	return timeorder_correct;
}

function checkDate(year, month, day_of_month) {
	if (!strOnlyDigits(year)) {
		return false;
	}
	if (!strOnlyDigits(month)) {
		return false;
	}
	if (!strOnlyDigits(day_of_month)) {
		return false;
	}	
	if (month < 1 || month > 12) {
		return false;
	}
	if (day_of_month < 1 || day_of_month > 31) {
		return false;
	}
	monthsWith31Days = [1,3,5,7,8,10,12];
	monthsWith30Days = [2,4,6,9,11];
	passed = true;
	if (monthsWith30Days.indexOf(month) >= 0) {
		if (day_of_month == 31) {
			passed = false;
		}
	} else if (month == 2) {
		leap_year = false;
		if (year % 4 == 0) {
			if (year % 100 != 0) {
				leap_year = true;
			} else if (year % 400 == 0) {
				leap_year = true;
			}
		}
		if (!leap_year && day_of_month > 28) {
			passed = false;
		}
		if (leap_year && day_of_month > 29) {
			passed = false;
		}
	}
	return passed;
}

function checkDateStr(dateStr) {
	var date_parts = dateStr.split(".");
	if (date_parts.length != 3) {
		return false;
	}	
	return checkDate(date_parts[2], date_parts[1], date_parts[0]);
}

function is_datetime_order_correct(begin_datetime, end_datetime) {
	var begin_datetime_parts = begin_datetime.split(" ");
	var end_datetime_parts = end_datetime.split(" ");
	if (begin_datetime_parts.length != 2 || end_datetime_parts.length != 2) {
		return false;
	}
	
	var begin_date_parts = begin_datetime_parts[0].split(".");
	var end_date_parts = end_datetime_parts[0].split(".");
	if (begin_date_parts.length != 3 || end_date_parts.length != 3) {
		return false;
	}	
	
	var begin_time_parts = begin_datetime_parts[1].split(":");
	var end_time_parts = end_datetime_parts[1].split(":");
	if (begin_time_parts.length != 2 || end_time_parts.length != 2) {
		return false;
	}	

	if (!checkDate(begin_date_parts[2], begin_date_parts[1], begin_date_parts[0]) || 
		!checkDate(end_date_parts[2], end_date_parts[1], end_date_parts[0])) {
		return false;	
	}
	
	var begin_day_of_month = parseInt(begin_date_parts[0]);
	var end_day_of_month = parseInt(end_date_parts[0]);
	
	var begin_month = parseInt(begin_date_parts[1]);
	var end_month = parseInt(end_date_parts[1]);
	
	var begin_year = parseInt(begin_date_parts[1]);
	var end_year = parseInt(end_date_parts[2]);
	
	var datetimeorder_correct = true;
	if (begin_year > end_year) {
		datetimeorder_correct = false;
	} else if (begin_year == end_year) {
		if (begin_month > end_month) {
			datetimeorder_correct = false;
		} else if (begin_month == end_month) {
			if (begin_day_of_month > end_day_of_month) {
				datetimeorder_correct = false;
			} else if (begin_day_of_month == end_day_of_month) {
				datetimeorder_correct = 
					is_time_order_correct(begin_datetime_parts[1],
										  end_datetime_parts[1]);
			}
		}
	}
	return datetimeorder_correct;
}


function checkWidth() {
	var $window = $(window);
    windowsize = $window.width();
	
	// remove duplicates
	$(".datatable").each(function(i, obj) {
	   headingrows = $(obj).find(".heading-row");
	   for(i=1; i < headingrows.length; i++) {
		   $(headingrows[i]).remove();
	   }
	});			
		
	/* duplicate heading row for each datarow if narrow screen;
	   done again for each resize */
    if (windowsize <= 767) {
	   $(".datatable").each(function(i, obj) {
		   headingrows = $(obj).find(".heading-row");
		   if (headingrows.length == 1) {
				$(obj).find(".datarow").before(headingrows[0]);
		   }
	   });
    }
}


$(document).ready(function(){
	checkWidth();
	var $window = $(window);
	$window.resize(checkWidth).resize();
});	



function js_action_column(call, button_text) {
	return "<div class=\"col-sm-1\">" +
			   "<button class=\"button\" onclick=\"" + call + "\" >" +
				button_text + 
				"</button>" + 
		   "</div>";
}

function modify_and_remove_columns(modify_call, remove_call) {
	ret =
		"<div class=\"col-sm-1-wrap\">" +
			js_action_column(modify_call, "Muokkaa") +
			js_action_column(remove_call, "Poista") +
		"</div>";
	return ret;
}

function selectTopicHandling(container_id, selected_ids, topic_parts_seek) {
	selected_ids = selected_ids + "";
	selected_ids_arr = [];
	if (selected_ids != "") {
		selected_ids_arr = selected_ids.split(",");
	}
	topics_seek_input = $("#" + container_id + "_seek_topics_name");
	topics_seek = topics_seek_input.val();
	$.get("inc/utils/list_topics_ajax.php?topics_seek=" + topics_seek,
		function(data) {
			if (data != "") {
				topics_seek_input.remove();	
				jsonData = JSON.parse(data);	
				$("#" + container_id).append(
					"<div id=\"" + container_id + "_topic_seek_cont\" >" + 
					"Valitut aiheen nimen osat pilkulla eroteltuina:<br>" + 
					"<input type=\"text\" name=\"" + container_id + "_topic_parts_seek\"" + 
						" id=\"topic_parts_seek\" value=\"" + topic_parts_seek + "\" />" +
					"</div>");
				$("#" + container_id).append(
					"<div id=\"" + container_id + "_seek_topics_section\"></div>");
				seek_topics_section = $("#" + container_id + "_seek_topics_section");
				seek_topics_section.append("Etsi aiheita valittavaksi:");
				seek_topics_section.append("<input type=\"text\" " +
					"name=\"" + container_id + "_seek_topics_name\" " +
					"id=\"" + container_id + "_seek_topics_name\" " +
					"value=\"" + topics_seek + "\" />");
				seek_topics_section.append(
					"<button type=\"button\" class=\"button\" onclick=\"seek_topics('" + 
						container_id + "'); return false;\">Etsi aiheita</button>");
				seek_topics_section.append(
					"<div id=\"" + container_id + "_table\" class=\"datatable\"></div>");
				seek_topics_section.append("<div id=\"" + container_id + "_topic_pages\"></div>");	
				topic_pages_section = $("#" + container_id + "_topic_pages");
				$("#" + container_id).append(
					"<div id=\"" + container_id + "_selections\"></div>");	
				selections = $("#" + container_id + "_selections");
				selections.append("<b>Lisäksi valitut aiheet</b>");
				selections.append("<input type=\"hidden\" " +
					"name=\"" + container_id + "_selected_topic_ids\" " + 
					"id=\"" + container_id + "_selected_topic_ids\" value=\"" + selected_ids + "\"/>");
				selections.append(
					"<div id=\"" + container_id + "_selections_table\" class=\"datatable\"></div>");
				table = $("#" + container_id + "_table");
				allTopics = jsonData.allTopics;
				topics = [];
				for(i=0; i < allTopics.length; i++) {
					if (selected_ids_arr.indexOf(allTopics[i].id + "") >= 0) {
						topics_elem = [];
						topics_elem.push(allTopics[i].name);
						topics_elem.push(allTopics[i].id);
						topics.push(topics_elem);
					}
				}
				curpage = $("#" + container_id + "_curpage").val();
				buildTopicSelectionPage(container_id, 
					allTopics, jsonData.seekedTopics, selected_ids, curpage); 
				refreshTopicSelections(container_id, topics);
			}
		}
	);
}

function seek_topics(container_id) {
	page = $("#" + container_id + "_curpage").val();
	selectedIds = $("#" + container_id + "_selected_topic_ids").val() + "";
	refreshTopicSelectionPage(container_id, selectedIds, page);
}

function refreshTopicSelectionPage(container_id, selectedIds, page) {
	topics_seek = $("#" + container_id + "_seek_topics_name").val();
	$.get("inc/utils/list_topics_ajax.php?topics_seek=" + topics_seek,
		function(data) {
			if (data != "") {
				jsonData = JSON.parse(data);
				buildTopicSelectionPage(container_id, jsonData.allTopics,
					jsonData.seekedTopics, selectedIds, page)
			}
		}
	);
}

function buildTopicSelectionPage(
	container_id, allTopics, seekedTopics, selectedIds, page) {
	selected_ids_arr = [];
	if (selectedIds != "") {
		selected_ids_arr = selectedIds.split(",");
	}
	table = $("#" + container_id + "_table");
	table.html("");
	found_removed_selected_id = false;
	for(i=0; i < selected_ids_arr.length; i++) {
		found_selected_id = false;
		for(j=0; j < allTopics.length; j++) {
			if (selected_ids_arr[j] == allTopics[j].id + "") {
				found_selected_id = true;
				break;
			}
		}
		if (!found_selected_id) {
			found_removed_selected_id = true;
			break;
		}
	}
	if (found_removed_selected_id) {
		topics = [];
		for(i=0; i < selected_ids_arr.length; i++) {
			name = "";
			for(j=0; j < allTopics.length; j++) {
				if (selected_ids_arr[j] == allTopics[j].id + "") {
					name = allTopics[j].name;
					break;
				}
			}
			topic_elem = [];
			topics_elem.push(name);
			topics_elem.push(selected_ids_arr[i]);
			topics.push(topics_elem);			
		}
		refreshTopicSelections(container_id, topics);
	}
	page_count = Math.floor((seekedTopics.length - 1)/ topics_page_size) + 1;
	if (page_count == 0) {
		page_count = 1;
	}
	if (page > page_count) {
		page = page_count;
	}
	for(i=0; i < seekedTopics.length; i++) {
		checkedValue = "";
		if (selected_ids_arr.indexOf(seekedTopics[i].id + "") >= 0) {
			checkedValue = " checked=\"checked\" ";
		}
		if (Math.floor(i / topics_page_size) + 1 == page) { 		
			if ((i - (page - 1) * topics_page_size) % 4 == 0) {
				i_row = (i - (page - 1) * topics_page_size) / 4 + 1;
				row_id = container_id + "_table_" + i_row;
				table.append(
					"<div id=\"" + row_id + 
						"\" class=\"row datarow\" ></div>");
				row = $("#" + row_id);
			}
			row.append(
				"<div class=\"col-sm-3\">" +
					"<input type=\"checkbox\" " + 
							"name=\""  + container_id + "_topics\" " +
							"value=\"" + seekedTopics[i].id + "\" " + 
							checkedValue + 
							"onclick=\"setOrUnsetTopic('" + 
							container_id + "','" + 
							seekedTopics[i].id + "','" + 
							seekedTopics[i].name +
							"')\" />" + seekedTopics[i].name +
				"</div>");
		}
	}
	buildTopicPages(container_id, selectedIds, page_count, page);
}


function get_topic_page_link(container_id, selectedIds, page, link_content, css_classes) {
	return "<a href=\"javascript:void(0);\" class=\"" + css_classes + "\" " +
	       "onclick=\"refreshTopicSelectionPage('" +container_id +"','" + 
			selectedIds + "','" + page + "')\" >" + 
			link_content + "</a>";
}

function buildTopicPages(container_id, selectedIds, page_count, page) {
	html_cont = "";
	$("#" + container_id + "_curpage").val(page);
	if (page_count <= 1) {
		$("#" + container_id + "_topic_pages").html("");
		return;
	}
	page_page = Math.floor((page - 1) / topics_page_page_size) + 1;
	if (page > topics_page_size) {
		html_cont += get_topic_page_link(container_id, selectedIds, 1, "<<", "other_page");
		html_cont += "&nbsp;";
		html_cont += get_topic_page_link(
			container_id, selectedIds, (page_page - 1) * topics_page_size, "<", "other_page");
	}
	p_begin = (page_page - 1) * topics_page_page_size + 1;
	p_end = page_page * topics_page_page_size;
	if (p_end > page_count) {
		p_end = page_count;
	}
	for(i_page=p_begin; i_page <= p_end; i_page++) {
		css_classes = "other_page";
		if (i_page == page) {
			css_classes = "curr_page";
		}
		if (html_cont != "") {
			html_cont += "&nbsp;";
		}
		html_cont += get_topic_page_link(container_id, selectedIds, i_page, i_page, css_classes);
	}
	if (page_page * topics_page_size < page_count) {
		if (html_cont != "") {
			html_cont += "&nbsp;";
		}
		html_cont += get_topic_page_link(container_id, 
			selectedIds, page_page * topics_page_size + 1, ">", "other_page");
		html_cont += "&nbsp;";
		html_cont += get_topic_page_link(container_id, 
			selectedIds, page_count, ">>", "other_page");		
	}
	$("#" + container_id + "_topic_pages").html(html_cont);	
}

function setOrUnsetTopic(container_id, topic_id, topic_name) {
	selections = $("#" + container_id + "_selections_table div.topic_selection");
	existing = $("#" + container_id + "_selections_" + topic_id);
	new_topic = true;
	if (existing.length > 0) {
		existing.remove();
		new_topic = false;
	} 
	topics = [];
	if (new_topic) {
		topic_arr_elem = [];
		topic_arr_elem.push(topic_name);
		topic_arr_elem.push(topic_id);
		topics.push(topic_arr_elem);
	}
	addCurrentSelectionsToTopicsAndSort(container_id, topics);
	refreshTopicSelections(container_id, topics);
}

function addCurrentSelectionsToTopicsAndSort(container_id, topics) {
	selections = $("#" + container_id + "_selections_table div.topic_selection");
	for(i=0; i < selections.length; i++) {
		topic_arr_elem = [];
		topic_arr_elem.push($(selections[i]).html());
		id_attr = $(selections[i]).prop("id");
		topic_arr_elem.push(id_attr.substring(container_id.length + 12, id_attr.length));
		topics.push(topic_arr_elem);
	}	
	topics.sort(function (a,b) {
		return a[1].localeCompare(b[1]);
	});
}

function refreshTopicSelections(container_id, topics) {
	elem_seek_id = "#" + container_id + "_selections_table";
	selections_table = $("" + elem_seek_id);
	selections_table.html("");
	row_id = 0;
	selection_vals = "";
	var row;
	for(i=0; i < topics.length; i++) {
		row_id_attr = container_id + "_topics_selection_row_" + row_id;
		if (i % 4 == 0) {
			row_id++;
			row_id_attr = container_id + "_topics_selection_row_" + row_id;
			selections_table.append(
				"<div id=\"" + row_id_attr + "\" class=\"row datarow\"></div>");
		}
		row = $("#" + row_id_attr);
		row.append("<div class=\"topic_selection col-sm-3\" " + 
			" id=\"" + container_id + "_selections_" + topics[i][1] + "\" >" +
			topics[i][0] + "</div>");
		if (selection_vals != "") {
			selection_vals += ",";
		}
		selection_vals += topics[i][1];
	}		
	$("#" + container_id + "_selected_topic_ids").val(selection_vals);
}