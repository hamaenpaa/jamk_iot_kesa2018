var page_size = 50;
var page_page_size = 20;

arr_monthnames = ["Tammikuu", "Helmikuu", "Maaliskuu", "Huhtikuu", "Toukokuu",
   "Kesäkuu", "Heinäkuu", "Elokuu", "Syyskuu", "Lokakuu", "Marraskuu", "Joulukuu"];

$.get("inc/utils/get_page_and_page_page_sizes.php",
	function(data) {
		jsonData = JSON.parse(data);
		page_size = jsonData.page_size;
		page_page_size = jsonData.page_page_size;
	});

function strOnlyDigits(str) {
	for(iStr=0; iStr < str.length; iStr++) {
		if (str[iStr] < '0' || str[iStr] > '9') {
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

function html_attr(attr_name, attr_value) {
	if (attr_value == undefined) {
		return "";
	}
	return attr_name + "=\"" + attr_value + "\" ";
}

function div_elem(div_elem_id, div_classes, div_no_display, div_content) {
	div_no_display_part = "";
	if (div_no_display) {
		div_no_display_part = html_attr("style", "display:none;");
	}
	return "<div " + html_attr("id", div_elem_id) + html_attr("class", div_classes) +
			div_no_display_part + ">" + div_content + "</div>";
}

function label_elem(label_text) {
	return "<label>" + label_text + "</label>";
}

function table_cell(col_length, cell_content) {
	return div_elem(undefined, "col-sm-" + col_length, false, cell_content);
}

function data_row(data_row_id, data_col_lengths, data_contents) {
	if (data_col_lengths.length != data_contents.length) {
		return "datarow error";
	}
	data_cols_contents = "";
	for(i_data_col=0; i_data_col < data_col_lengths.length; i_data_col++) {
		data_cols_contents += table_cell(data_col_lengths[i_data_col], 
			data_contents[i_data_col]);
	}
	return div_elem(data_row_id, "row datarow", false, data_cols_contents); 
}

function heading_row(heading_id, heading_col_lengths, heading_contents) {
	if (heading_col_lengths.length != heading_contents.length) {
		return "headingrow error";
	}
	heading_cols_contents = "";
	for(i_heading_col=0; i_heading_col < heading_col_lengths.length; i_heading_col++) {
		heading_cols_contents += table_cell(
			heading_col_lengths[i_heading_col], heading_contents[i_heading_col]);
	}
	return div_elem(heading_id, "row heading-row", false, heading_cols_contents); 
}

function button_elem(button_call, button_text) {
	button_call_part = "";
	if (button_call != "") {
		button_call_part = "onclick=\"" + button_call + "\" ";
	}
	return "<button type=\"button\" class=\"button\" " + 
			button_call_part + ">" + button_text + "</button>";
}

function input_elem(input_type, input_id, input_name, input_value, 
	input_required, input_min_length, input_max_length, input_place_holder,
	input_alt, input_autocomplete, css_classes) {
	input_required_part = "";
	if (input_required) {
		input_required_part = "required ";
	}
	return "<input " + 
		html_attr("type", input_type) + 
		html_attr("id", input_id) +
		html_attr("name", input_name) +
		html_attr("class", css_classes) +
		html_attr("value", input_value) + 
		html_attr("min_length", input_min_length) +
		html_attr("max_length", input_max_length) +
		html_attr("placeholder", input_place_holder) +
		html_attr("alt", input_alt) +
		html_attr("autocomplete", input_autocomplete) +
		input_required_part + " />";
}

function html_checkbox(checkbox_id, checkbox_name, checkbox_value, checkbox_checked, 
	checkbox_on_click) {
	checkbox_checked_part = "";
	if (checkbox_checked) {
		checkbox_checked_part = html_attr("checked", "checked");
	}
	return "<input " + 
		html_attr("type", "checkbox") + 
		html_attr("id", checkbox_id) +
		html_attr("name", checkbox_name) +
		html_attr("value", checkbox_value) + 
		checkbox_checked_part + 
		html_attr("onclick", checkbox_on_click) + " />";
}

function js_call(func_name, js_call_params) {
	js_call_params_contents = "";
	for(i_js_call_param=0; i_js_call_param < js_call_params.length; 
		i_js_call_param++) {
		if (js_call_params_contents != "") {
			js_call_params_contents += ",";
		}
		js_call_params_contents += "'" + js_call_params[i_js_call_param] + "'";
	}
	return func_name + "(" + js_call_params_contents + ")";
}

function js_action_column(js_action_call, js_action_button_text) {
	return table_cell(1, button_elem(js_action_call, js_action_button_text));
}

function modify_and_remove_columns(modify_call, remove_call) {
	modify_call_action = js_action_column(modify_call, "Muokkaa");
	remove_call_action = js_action_column(remove_call, "Poista");
	return modify_call_action + remove_call_action;
}

function purifyHttpParamValue(value) {
	return encodeURIComponent(value);
}

function buildHttpGetUrl(root_url, param_names, param_values) {
	if (param_names.length != param_values.length) {
		return "";
	}
	url = root_url + "?";
	for(iUrlParam=0; iUrlParam < param_names.length; iUrlParam++) {
		if (iUrlParam > 0) {
			url += "&";
		}
		url += param_names[iUrlParam] + "=" + 
			purifyHttpParamValue(param_values[iUrlParam]);
	}
	return url;
}

function selectTopicHandling(container_id, selected_ids, topic_parts_seek) {
	selected_ids = selected_ids + "";
	selected_ids_arr = [];
	if (selected_ids != "") {
		selected_ids_arr = selected_ids.split(",");
	}
	topics_seek_input = $("#" + container_id + "_seek_topics_name");
	topics_seek = topics_seek_input.val();
	$.get(buildHttpGetUrl(
		"inc/utils/list_topics_ajax.php", ["topics_seek"],[topics_seek]),
		function(data) {
			if (data != "") {
				topics_seek_input.remove();	
				jsonData = JSON.parse(data);
				build_initial_topic_section_elems(
					container_id, selected_ids, topic_parts_seek, topics_seek);
				allTopics = jsonData.allTopics;
				topics = [];
				for(iTopic=0; iTopic < allTopics.length; iTopic++) {
					if (selected_ids_arr.indexOf(allTopics[iTopic].id + "") >= 0) {
						topics_elem = [];
						topics_elem.push(allTopics[iTopic].name);
						topics_elem.push(allTopics[iTopic].id);
						topics.push(topics_elem);
					}
				}
				curpage = $("#" + container_id + "_curpage").val();
				if (curpage == null || curpage == undefined || curpage == "") {
					$("#" + container_id + "_curpage").val("1");
					curpage = 1;
				}
				buildTopicSelectionPage(container_id, 
					allTopics, jsonData.seekedTopics, selected_ids, curpage); 
				refreshTopicSelections(container_id, topics);
			}
		}
	);
}

function build_initial_topic_section_elems(container_id, selected_ids, 
	topic_parts_seek, topics_seek) {
	id_and_name = container_id + "_topic_parts_seek";
	$("#" + container_id).append(
		div_elem(container_id + "_topic_seek_cont", "", false,		
			"Valitut aiheen nimen osat pilkulla eroteltuina:<br>" + 
			input_elem("text", id_and_name, id_and_name, topic_parts_seek, false, "")));
	$("#" + container_id).append(
		div_elem(container_id + "_seek_topics_section", "", false, ""));
	seek_topics_section = $("#" + container_id + "_seek_topics_section");
	seek_topics_section.append("Etsi aiheita valittavaksi:");	
	id_and_name = container_id + "_seek_topics_name";
	seek_topics_section.append(
		input_elem("text", id_and_name, id_and_name, topics_seek, false));
	seek_topics_section.append(
		button_elem("seek_topics('" + container_id + "'); return false; ", "Etsi aiheita"));
	seek_topics_section.append(
		div_elem(container_id + "_table", "datatable", false, ""));
	seek_topics_section.append(buildTopicsHandlingContainer(container_id, 1, ""));
	seek_topics_section.append(
		div_elem(container_id + "_topic_pages", "", false, ""));
	topic_pages_section = $("#" + container_id + "_topic_pages");
	$("#" + container_id).append(
		div_elem(container_id + "_selections", "", false, ""));
	selections = $("#" + container_id + "_selections");
	selections.append("<b>Lisäksi valitut aiheet</b>");
	id_and_name = container_id + "_selected_topic_ids";
	selections.append(input_elem("hidden", id_and_name, id_and_name, selected_ids, false));
	selections.append(div_elem(container_id + "_selections_table", "datatable", false, ""));
}

function seek_topics(container_id) {
	page = $("#" + container_id + "_curpage").val();
	selectedIds = $("#" + container_id + "_selected_topic_ids").val() + "";
	refreshTopicSelectionPage(container_id, selectedIds, page);
}

function refreshTopicSelectionPage(container_id, selectedIds, page) {
	topics_seek = $("#" + container_id + "_seek_topics_name").val();
	$.get(buildHttpGetUrl(
		"inc/utils/list_topics_ajax.php", ["topics_seek"],[topics_seek]),
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
	for(iSelectedId=0; iSelectedId < selected_ids_arr.length; iSelectedId++) {
		found_selected_id = false;
		for(jTopic=0; jTopic < allTopics.length; jTopic++) {
			if (selected_ids_arr[iSelectedId] == allTopics[jTopic].id + "") {
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
		for(iSelectedId=0; iSelectedId < selected_ids_arr.length; iSelectedId++) {
			name = "";
			for(jTopic=0; jTopic < allTopics.length; jTopic++) {
				if (selected_ids_arr[iSelectedId] == allTopics[jTopic].id + "") {
					name = allTopics[jTopic].name;
					break;
				}
			}
			topic_elem = [];
			topic_elem.push(name);
			topic_elem.push(selected_ids_arr[iSelectedId]);
			topics.push(topic_elem);			
		}
		refreshTopicSelections(container_id, topics);
	}
	page_count = Math.floor((seekedTopics.length - 1)/ page_size) + 1;
	if (page_count == 0) {
		page_count = 1;
	}
	if (page > page_count) {
		page = page_count;
	}
	for(iSeekedTopic=0; iSeekedTopic < seekedTopics.length; iSeekedTopic++) {
		if (Math.floor(iSeekedTopic / page_size) + 1 == page) { 		
			if ((iSeekedTopic - (page - 1) * page_size) % 4 == 0) {
				i_row = (iSeekedTopic - (page - 1) * page_size) / 4 + 1;
				row_id = container_id + "_table_" + i_row;
				table.append(div_elem(row_id, "row datarow", false, ""));
				row = $("#" + row_id);
			}
			call = js_call("setOrUnsetTopic", 
				[container_id, seekedTopics[iSeekedTopic].id, 
					seekedTopics[iSeekedTopic].name]);
			checked = selected_ids_arr.indexOf(
				seekedTopics[iSeekedTopic].id + "") >= 0;
			check_box = html_checkbox(
				undefined, container_id + "_topics", 
				seekedTopics[iSeekedTopic].id, checked, call);
			row.append(table_cell(3, 
				check_box + seekedTopics[iSeekedTopic].name));
		}
	}
	buildTopicPages(container_id, selectedIds, page_count, page);
}

function get_topic_page_link(container_id, selectedIds, page, link_content, css_classes) {
	return "<a href=\"javascript:void(0);\" class=\"" + css_classes + "\" " +
	       "onclick=\"" + 
			js_call("refreshTopicSelectionPage", [container_id,	selectedIds, page]) + "\" >" + 
			link_content + "</a>";
}

function buildTopicPages(container_id, selectedIds, page_count, page) {
	html_cont = "";
	$("#" + container_id + "_curpage").val(page);
	if (page_count <= 1) {
		$("#" + container_id + "_topic_pages").html("");
		return;
	}
	page_page = Math.floor((page - 1) / page_page_size) + 1;
	if (page > page_size) {
		html_cont += get_topic_page_link(container_id, selectedIds, 1, "<<", "other_page");
		html_cont += "&nbsp;";
		html_cont += get_topic_page_link(
			container_id, selectedIds, (page_page - 1) * page_size, "<", "other_page");
	}
	p_begin = (page_page - 1) * page_page_size + 1;
	p_end = page_page * page_page_size;
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
	if (page_page * page_size < page_count) {
		if (html_cont != "") {
			html_cont += "&nbsp;";
		}
		html_cont += get_topic_page_link(container_id, 
			selectedIds, page_page * page_size + 1, ">", "other_page");
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
	for(iTopic=0; iTopic < topics.length; iTopic++) {
		row_id_attr = container_id + "_topics_selection_row_" + row_id;
		if (iTopic % 4 == 0) {
			row_id++;
			row_id_attr = container_id + "_topics_selection_row_" + row_id;
			selections_table.append(div_elem(row_id_attr, "row datarow", false, ""));
		}
		row = $("#" + row_id_attr);
		row.append(
			div_elem(container_id + "_selections_" + topics[iTopic][1], 
				"topic_selection col-sm-3", false, topics[iTopic][0]));
		if (selection_vals != "") {
			selection_vals += ",";
		}
		selection_vals += topics[iTopic][1];
	}		
	$("#" + container_id + "_selected_topic_ids").val(selection_vals);
}

function buildTopicsHandlingContainer(container_id, curpage, seek_topics_name) {
	id_name_1 = container_id + "_curpage";
	id_name_2 = container_id + "_seek_topics_name";
	if (curpage == "" || curpage == undefined) { curpage = "1"; }
	elem = 
		div_elem(container_id, undefined, false, 
			input_elem("hidden", id_name_1, id_name_1, curpage, 
				false, undefined, undefined, undefined,
				undefined, undefined, undefined) + 
			input_elem("hidden", id_name_2, id_name_2, seek_topics_name, 
				false, undefined, undefined, undefined,
				undefined, undefined, undefined) + 
			div_elem(
				"last_query_seek_topics_for_new_lessons_of_course_seek_selection",
				undefined, false, "") +
			div_elem(
				"last_query_seek_topics_for_new_lessons_of_course_topic_seek",
				undefined, false, ""));
	return elem;
}