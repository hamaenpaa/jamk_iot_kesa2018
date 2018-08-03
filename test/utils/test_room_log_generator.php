<?php
	/* configuration of generation*/
	$student_ids = "1,2";
	$corresponding_NFC_IDS = "ASDDJ,SADAS";
	$guest_ids = "1,2";
	
	$course_ids = "1,2";
	$room_ids = "1,2";
	
	$count_student_ids = 100;
	$count_guest_ids = 20;
	
	/* generation of test student and guest logs */
	$student_ids_arr = explode(",", $student_ids);
	$guest_ids_arr = explode(",", $guest_ids);
	$course_ids_arr = explode(",", $guest_ids);
	$room_ids_arr = explode(",", $room_ids);
	$nfc_ids_arr = explode(",", $corresponding_NFC_IDS);
	
	var_dump($student_ids_arr);

	include("config.php");
	include($cfg_path ."/inc/db_connect_inc.php");
	for($i_student=0; $i_student < $count_student_ids; $i_student++) {
		$student_id_index = rand(0,count($student_ids_arr) - 1);
		$room_id_index = rand(0,count($room_ids_arr) - 1);
		$course_id_index = rand(0,count($course_ids_arr) - 1);
		$student_id_index = rand(0,count($student_ids_arr) - 1);
		
		// echo "student_id_index ". $student_id_index . "<br>\n";
		
		$room_id = $room_ids_arr[$room_id_index];
		$course_id = $course_ids_arr[$room_id_index];
		$student_id = $student_ids_arr[$student_id_index];
		$NFC_ID = $nfc_ids_arr[$student_id_index];
		
		// echo "student_id " . $student_id . "<br>\n";
		
		$sql_insert = "INSERT INTO ca_roomlog 
			(NFC_ID, guest_id, student_id, course_id, room_id, dt) 
			VALUES ('";
        $sql_insert .= $NFC_ID . "',0,";
		$sql_insert .= $student_id . ",";
		$sql_insert .= $course_id . ",";
		$sql_insert .= $room_id . ",NOW())";
		echo $sql_insert . "<br>\n";
		$conn->query($sql_insert);	
	}
	
	for($i_guest=0; $i_guest < $count_guest_ids; $i_guest++) {
		$student_id_index = rand(0,count($student_ids_arr) - 1);
		$room_id_index = rand(0,count($room_ids_arr) - 1);
		$course_id_index = rand(0,count($course_ids_arr) - 1);
		$guest_id_index = rand(0,count($guest_ids_arr) - 1);
		
		$room_id = $room_ids_arr[$room_id_index];
		$course_id = $course_ids_arr[$room_id_index];
		$guest_id = $guest_ids_arr[$guest_id_index];
		
		$sql_insert = "INSERT INTO ca_roomlog 
			(NFC_ID, guest_id, student_id, course_id, room_id, dt) 
			VALUES ('";
		$sql_insert .= $NFC_ID . "',";
		$sql_insert .= $guest_id . ",0,";
		$sql_insert .= $course_id . "," . $room_id . ",NOW())";
		echo $sql_insert . "<br>\n";
		$conn->query($sql_insert);			
	}
	
	include("config.php");
	include($cfg_path . "/inc/db_disconnect_inc.php");
?>