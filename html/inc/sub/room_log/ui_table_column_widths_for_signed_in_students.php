<?php
	$dt_cols = "0"; $name_cols = "0"; $nfc_cols = "0"; $course_cols = "0"; $room_cols = "0";
	if (!WITH_COURSES && !WITH_ROOMS) {
		$dt_cols = "4";
		$name_cols = "6";
		$nfc_cols = "2";
	} else if (WITH_COURSES && !WITH_ROOMS) {
		$dt_cols = "2";
		$name_cols = "3";
		$course_cols = "3";
		$nfc_cols = "2";
	} else if (WITH_ROOMS && !WITH_COURSES) {
		$dt_cols = "2";
		$name_cols = "3";
		$room_cols = "3";
		$nfc_cols = "2";			
	} else {
		$dt_cols = "2";
		$name_cols = "2";
		$nfc_cols = "2";
		$room_cols = "2";
		$course_cols = "2";
	}
?>