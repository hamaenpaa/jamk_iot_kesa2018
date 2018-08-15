<?php
	function get_courses($conn, $name_seek, $description_seek) {
		$sql_courses = 
			"SELECT ca_course.ID, ca_course.name, ca_course.description
				FROM ca_course WHERE 
				name LIKE '%" .$name_seek ."%'
				AND description LIKE '%" .$description_seek ."%'
				AND removed = 0";
		$q_courses = $conn->prepare($sql_courses);
//		$q_courses->bind_param();
		$q_courses->execute();		
		$q_courses->store_result();
		$q_courses->bind_result($course_id, $name, $description);
		
		$courses_arr = array();
		if ($q_courses->num_rows > 0) {
			while($q_courses->fetch()) {
				$courses_arr[] = array("course_id" => $course_id,
					"name" => $name, 
					"description" => $description);
			}
		}
		return $courses_arr;		
	}
?>