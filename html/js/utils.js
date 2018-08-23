function is_time_order_correct(begin_time, end_time) {
	var begin_time_parts = begin_time.split(":");
	var end_time_parts = end_time.split(":");	
	
	var begin_hours = parseInt(begin_time_parts[0]);
	var end_hours = parseInt(end_time_parts[0]);

	var begin_minutes = parseInt(begin_time_parts[1]);
	var end_minutes = parseInt(end_time_parts[1]);	

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


function is_datetime_order_correct(begin_datetime, end_datetime) {
	var begin_datetime_parts = begin_datetime.split(" ");
	var end_datetime_parts = end_datetime.split(" ");
	
	var begin_date_parts = begin_datetime_parts[0].split(".");
	var end_date_parts = end_datetime_parts[0].split(".");
	
	var begin_time_parts = begin_datetime_parts[1].split(":");
	var end_time_parts = end_datetime_parts[1].split(":");	
	
	var begin_day_of_month = parseInt(begin_date_parts[0]);
	var end_day_of_month = parseInt(end_date_parts[0]);
	
	var begin_month = parseInt(begin_date_parts[1]);
	var end_month = parseInt(end_date_parts[1]);
	
	var begin_year = parseInt(begin_date_parts[2]);
	var end_year = parseInt(end_date_parts[2]);
	
	var begin_hours = parseInt(begin_time_parts[0]);
	var end_hours = parseInt(end_time_parts[0]);

	var begin_minutes = parseInt(begin_time_parts[1]);
	var end_minutes = parseInt(end_time_parts[1]);
	
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

