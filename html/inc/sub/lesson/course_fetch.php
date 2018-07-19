<?php
if (!isset($_SESSION)) {
session_start();	
}
if (isset($_SESSION['staff_permlevel']) && $_SESSION['staff_permlevel'] == 0) { //Validate permission levels
	echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'><select name='course_fetch'>";
	if (!isset($conn)) {	
	include "inc/db_connect_inc.php";
	}
	//SELECT ca_course.Course_ID, ca_course.Course_name FROM ca_course INNER JOIN ca_course_teacher WHERE ca_course_teacher.course_id = ca_course.id AND ca_course_teacher.staff_id = '2'
	if ($res_get_course_list = $conn->prepare("SELECT ca_course.id, ca_course.Course_name FROM ca_course INNER JOIN ca_course_teacher WHERE ca_course_teacher.course_id = ca_course.id AND ca_course_teacher.staff_id = ?")) {
	$res_get_course_list->bind_param("i",$_SESSION['staff_id']);	
		if ($res_get_course_list->execute()) {
		$res_get_course_list->bind_result($course_id,$course_name);
			while ($row_get_course_list = $res_get_course_list->fetch()) {
			echo "<option value='$course_id'>" . $course_name . "</option>";	
			}
		$res_get_course_list->free_result();
		} else {
		//ERROR->$res_get_course_list->EXECUTE	
		}
	} else {
	//ERROR->$res_get_course_list->PREPARE
	}
	echo "</select> <input type='submit' value='Hae'></form>";
}
?>