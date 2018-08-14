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
	var begin_datetime = document.forms["add_or_modify_lesson_form"]["begin_time"].value;
	var end_datetime = document.forms["add_or_modify_lesson_form"]["end_time"].value;
	
	var correct = is_datetime_order_correct(begin_datetime, end_datetime);
	if (!correct) {
		$("#add_or_modify_lesson_form_validation_errors").html(
			"Alkuaika on loppuajan jälkeen: korjaa se!");
	} else {
		$("#add_or_modify_lesson_form_validation_errors").html("");
	}	
	return correct;
}