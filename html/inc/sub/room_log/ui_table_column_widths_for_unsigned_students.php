<?php
	$dt_cols = "0"; $course_cols = "0"; $room_cols = "0";
	
	$dt_extra_css_classes = "";
	$name_extra_css_classes = "";
	$room_extra_css_classes = "";
	$course_extra_css_classes = "";
	$btn_wrap_extra_css_classes = "";
	$btn_extra_css_classes = "";	
	
	if (!WITH_COURSES && !WITH_ROOMS) {
		$name_cols = "10";
		$nfc_cols = "2";
	} else if (WITH_COURSES && !WITH_ROOMS) {
		$name_cols = "4";
		$course_cols = "4";
		$nfc_cols = "2";
	} else if (WITH_ROOMS && !WITH_COURSES) {
		$name_cols = "4";
		$room_cols = "4";
		$nfc_cols = "2";			
	} else {
		$name_cols = "3";
		$nfc_cols = "2";
		$room_cols = "3";
		$course_cols = "2";
	}
?>