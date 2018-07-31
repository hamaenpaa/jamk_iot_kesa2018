<?php
if (!isset($_SESSION)) {
session_start();	
}
if (!isset($conn)) {
include '../../db_connect_inc.php';	
}

/* Fetches all available rooms */
if (isset($_SESSION['staff_permlevel']) && $_SESSION['staff_permlevel'] >= 0 && $_SESSION['staff_permlevel'] <= 1) {
	if ($res_get_roomnames = $conn->prepare("SELECT ID, room_name FROM ca_room ORDER BY room_name ASC")) {
		if ($res_get_roomnames->execute()) {
		$res_get_roomnames->bind_result($room_id, $room_name);
		echo "<select name='lesson_room'>";
		echo "<option value='0'>Valitse</option>";
			while ($res_get_roomnames->fetch()) {
				
				if (isset($_POST['lesson_room']) && $_POST['lesson_room'] == $room_id && isset($room_prefill) && $room_prefill == true) {
				echo "<option value='$room_id' selected>$room_name</option>";
				} else {
				echo "<option value='$room_id'>$room_name</option>";	
				}
				
			}
		echo "</select>";
		} else {
		//ERROR->$res_get_roomnames->EXECUTE	
		}
	} else {
		echo "sx";
	//ERROR->$res_get_roomnames->PREPARE
	}
} else {
echo "<p>Unauthorized, please login first</p>";	
}
?>