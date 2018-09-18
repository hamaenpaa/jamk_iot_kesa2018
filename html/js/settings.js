function checkSetting() {
	valid_page_size = true;
	page_size_val = $("#page_size").val();
	if (strOnlyDigits(page_size_val)) {
		if (parseInt(page_size_val) == 0) {
			valid_page_size = false;
		}
	} else {
		valid_page_size = false;
	}
	if (!valid_page_size) {
		$("#modify_setting_validation_info").
			html("Sivun koko täytyy olla positiivinen kokonaisluku!");	
		return false;
	}
	valid_page_page_size = true;
	page_page_size_val = $("#page_page_size").val();
	if (strOnlyDigits(page_page_size_val)) {
		if (parseInt(page_page_size_val) == 0) {
			valid_page_page_size = false;
		}
	} else {
		valid_page_page_size = false;
	}
	if (!valid_page_page_size) {
		$("#modify_setting_validation_info").
			html("Sivukokoelman koko täytyy olla positiivinen kokonaisluku!");
		return false;			
	}	
	if ($("#default_roomidentifier").val().length > 50) {
		$("#modify_setting_validation_info").
			html("Huoneen oletustunnus voi olla maksimissaan 50 merkkiä pitkä!");
		return false;		
	}
	$("#modify_setting_validation_info").html("");
	return true;
}