<?php
if (!isset($_SESSION)) {
session_start();	
}

if (!isset($conn)) {
include "../../db_connect_inc.php";	
}

/* Fetches all lessons related to logged in staff -> teacher id */
if (isset($_SESSION['staff_permlevel']) && $_SESSION['staff_permlevel'] >= 0 && $_SESSION['staff_permlevel'] <= 1) {
	if ($res_get_courses_list = $conn->prepare("SELECT ca_course_teacher.course_id, ca_course.course_name FROM ca_course 
		INNER JOIN ca_course_teacher WHERE ca_course.ID = ca_course_teacher.course_id AND ca_course_teacher.staff_id = ?")) {
		$res_get_courses_list->bind_param("s",$_SESSION['staff_id']);	
		if ($res_get_courses_list->execute()) {
		$res_get_courses_list->bind_result($course_id, $course_name);
		echo "<select name='lesson_course'>";
		echo "<option value='0'>Valitse</option>";
			while ($res_get_courses_list->fetch()) {
				if (isset($_POST['lesson_course']) && $_POST['lesson_course'] == $course_id) {
				echo "<option value='$course_id' selected>$course_name</option>";
				} else {
				echo "<option value='$course_id'>$course_name</option>";	
				}
			}
		echo "</select>";
		} else {
		echo "<p>Error in MySQL @ Lesson Fetch.
		If you contact staff refer with code: sub/les/le_ad/list_courses->exec</p>";
		//ERROR->$res_get_courses_list->EXECUTE	
		}
	} else {
	echo "<p>Error in MySQL @ Lesson Fetch.
	 If you contact staff refer with code: sub/les/le_ad/list_courses->prep</p>";
	//ERROR->$res_get_courses_list->PREPARE
	}
} else {
echo "<p>Unauthorized, please login first</p>";	
}
?>