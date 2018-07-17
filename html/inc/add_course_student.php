<?php
    include("db_connect_inc.php");
	
	$student_id = $_POST['student_id'];
	$course_id = $_POST['course_id'];	
	
	$q = $conn->prepare("INSERT INTO ca_course_student (student_id,course_id) VALUES (?,?)");
	if ($q) {
		$q->bind_param("ii", $student_id, $course_id);
		$q->execute();
	}	

    include("db_disconnect_inc.php");
	
	header("Location: ../list_courses.php?id=".$course_id."&add_student=1");
?>