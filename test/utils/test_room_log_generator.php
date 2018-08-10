<?php
	ini_set('max_execution_time', 10000); 
	set_time_limit(10000);

	/* configuration of generation*/
	$corresponding_NFC_IDS = "ASDDJ,SADAS";
	$room_ids = "ABC1,ABC2";
	
	$count_student_ids = 100;
	
	/* generation of test student and guest logs */
	$room_ids_arr = explode(",", $room_ids);
	$nfc_ids_arr = explode(",", $corresponding_NFC_IDS);
	
	include("config.php");
	include($cfg_path ."/inc/db_connect_inc.php");
	for($i_student=0; $i_student < $count_student_ids; $i_student++) {
		$room_id_index = rand(0,count($room_ids_arr) - 1);
		$student_id_index = rand(0,count($nfc_ids_arr) - 1);
		
		// echo "student_id_index ". $student_id_index . "<br>\n";
		
		$room_id = $room_ids_arr[$room_id_index];
		$NFC_ID = $nfc_ids_arr[$student_id_index];
		
		// echo "student_id " . $student_id . "<br>\n";
		
		$sql_insert = "INSERT INTO ca_roomlog 
			(NFC_ID, room_identifier, dt) 
			VALUES ('";
        $sql_insert .= $NFC_ID . "','";
		$sql_insert .= $room_id . "',NOW())";
		echo $sql_insert . "<br>\n";
		$conn->query($sql_insert);	
		
		sleep(10);
	}
	
	include($cfg_path . "inc/db_disconnect_inc.php");
?>