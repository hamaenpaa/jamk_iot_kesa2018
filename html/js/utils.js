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
