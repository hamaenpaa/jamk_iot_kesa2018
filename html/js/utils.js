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
				if (begin_hours > end_hours) {
					datetimeorder_correct = false;
				} else if (begin_hours == end_hours) {
					if (begin_minutes > end_minutes) {
						datetimeorder_correct = false;
					}
				}
			}
		}
	}
	return datetimeorder_correct;
}