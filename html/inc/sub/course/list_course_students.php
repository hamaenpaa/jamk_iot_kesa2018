<div id="list_of_course_students">
<h2>Kurssin oppilaat</h2>
<?php
	define("COURSE_STUDENT_PAGE","20");
	
	$course_student_page = get_post_or_get($conn, "course_student_page");
	if (!isset($course_student_page) || $course_student_page == "") { $course_student_page = 1; }	
	$seek_params_hidden_inputs .= hidden_input("course_student_page", $course_student_page);	
	
	$sql_course_student_count = 
   		"SELECT COUNT(*) AS c FROM 
     	 	ca_course_student, ca_student WHERE 
     	 	ca_student.ID = ca_course_student.student_id AND ca_course_student.course_id = ?";
	$sql_course_student_seek = 
   		"SELECT ca_course_student.student_id, ca_student.FirstName, ca_student.LastName FROM 
     	 	ca_course_student, ca_student WHERE 
     	 	ca_student.ID = ca_course_student.student_id AND ca_course_student.course_id = ?";
	$sql_course_student_seek .= " ORDER BY ca_student.LastName, ca_student.FirstName";
	$sql_course_student_seek .= " LIMIT " . (($course_student_page - 1) * COURSE_STUDENT_PAGE) . 
		"," . COURSE_STUDENT_PAGE;
	$seek_params_get .= possible_get_param("id", $id, $first);
	$seek_params_get .= possible_get_param("page",$page,false);		
	
   	$q = $conn->prepare($sql_course_student_seek);
	$qc = $conn->prepare($sql_course_student_count);
    if ($q) {
		$q->bind_param("i", $id);
		$q->execute();
		$q->store_result(); 
		
		$qc->bind_param("i", $id);
		$qc->execute();  
		$qc->store_result();
		$count_course_students = 0;
		$qc->bind_result($count_course_students);
		$qc->fetch();	
		
		if ($count_course_students > 0) {  	
			$page_count_course_students = intdiv($count_course_students, COURSE_STUDENT_PAGE);
			if ($page_count * COURSE_STUDENT_PAGE < $count_course_students) { 
				$page_count_course_students++; 
			}
			$course_student_page_links = generate_page_list(
				"list_courses.php".$seek_params_get, 
				$page_count_course_students, $course_student_page,
				"course_student_page",
				"","","curr_page","other_page");	
?>
		<div id="table_course_students">
			<div class="row">
				<div class="col-sm-6"><b>Etunimi</b></div>
				<div class="col-sm-5"><b>Sukunimi</b></div>
				<div class="col-sm-1"></div>
			</div>	
<?php     
        	$firstName = "-"; $lastName = "-"; $student_id = 0;

			$q->bind_result($student_id, $firstName, $lastName);
			while ($q->fetch()) {
?>
				<div class="row">
					<div class="col-sm-6"><?php echo $firstName ?></div>
					<div class="col-sm-5"><?php echo $lastName ?></div>
					<div class="col-sm-1">
						<form action="inc/sub/course/remove_course_student.php" action="post">
							<?php 
								echo hidden_input("course_id", $id);
								echo hidden_input("student_id", $student_id);
								echo $seek_params_hidden_inputs; 
							?>			
							<input type="submit" value="Poista"/>				
						</form>					
					
					</div>
				</div>
<?php
			} 
			
			
?>
		</div> 
<?php	
			echo $course_student_page_links;
	 	} else {
?>
			<b>Kurssilla ei ole oppilaita.</b>
<?php	 	
	 	}				
     } 
?>
</div> 
