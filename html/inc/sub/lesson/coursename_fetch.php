<?php
1if (!isset($_SESSION)) {
session_start();	
}
if (isset($SESSION['staff_permlevel']) && $_SESSION['staff_permlevel'] == 0) { //Validate permission levels
	if (isset($course_id)) { unset($course_id); } //Removes CourseID variable if it exists, not really necessary
	$course_id = intval($_POST['course_fetch']);
	echo "<form method='POST' action='" . $_SERVER['PHP_SELF'] . "'><input type='hidden' value='" . $course_id . "'>";
		if (!isset($conn)) {
		include "inc/db_connect_inc.php";	
		}
		
		if ($res_get_course_name = $conn->prepare("SELECT ca_course.Course_name, ca_course.Course_ID FROM ca_course 
			INNER JOIN ca_course_teacher WHERE 
			ca_course_teacher.staff_id = ? AND 
			ca_course.id = ? AND
			ca_course.id = ca_course_teacher.course_id")) {
			
			$res_get_course_name->bind_param("ii",$_SESSION['staff_id'],$course_id);
			if ($res_get_course_name->execute()) {
			$res_get_course_name->bind_result($course_name, $course_name_id);
			$row_get_course_name = $res_get_course_name->fetch();
			echo "<h5>Valittuna: " . $course_name .  " ($course_name_id)</h5> ";
			$res_get_course_name->free_result();
			} else {
			//ERROR->$res_get_course_name->execute();	
			}
		} else {
		//ERROR->$res_get_course_name->PREPARE	
		}
	?>
	<div class="row">
	<div class="col-sm-3"><b>Aloitus aika</b></div>
	<div class="col-sm-3"><b>Lopetus aika</b></div>
	</div>
				
	<div class="row">
	<div class="col-sm-3"><input class="datetime_picker"></div>
	<div class="col-sm-3"><input class="datetime_picker"></div>
	<div class="col-sm-1"><input type="submit" value="Etsi"></div>
	</div>
<?php
	echo "</form>";
}
?>