<?php
    include("db_connect_inc.php");
	$id = "";
	if (isset($_POST['id'])) {
		$id = $_POST['id'];
	}
	$course_id = $_POST['course_id'];
	$course_name = $_POST['course_name'];
	$course_description= $_POST['course_description'];
	
	if ($id != "") {
		$q = $conn->prepare("UPDATE ca_course SET course_id = ?, course_name = ?, course_description = ? WHERE ID = ?");
		if ($q) {
			$q->bind_param("sssi", $course_id, $course_name, $course_description, $id);
			$q->execute();
		}
	} else {
		$q = $conn->prepare("INSERT INTO ca_course (course_id,course_name,course_description) VALUES (?,?,?)");
		if ($q) {
			$q->bind_param("sss", $course_id, $course_name, $course_description);
			$q->execute();
		}		
	}

	include("db_disconnect_inc.php");
	
	header("Location: ../list_courses.php");
?>