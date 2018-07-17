<?php
    include("db_connect_inc.php");
	
	$staff_id = $_POST['staff_id'];
	$course_id = $_POST['course_id'];	
	
	$q = $conn->prepare("INSERT INTO ca_course_teacher (staff_id,course_id) VALUES (?,?)");
	if ($q) {
		$q->bind_param("ii", $staff_id, $course_id);
		$q->execute();
	}	

    include("db_disconnect_inc.php");
	
	header("Location: ../list_courses.php?id=".$course_id."&add_teacher=1");
?>