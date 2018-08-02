<div id="list_of_course_teachers">
    <h2>Kurssin opettajat</h2>
<?php
	define("COURSE_TEACHER_PAGE","20");
	
	$course_teacher_page = get_post_or_get($conn, "course_teacher_page");
	if (!isset($course_teacher_page) || $course_teacher_page == "") { $course_teacher_page = 1; }	
	$seek_params_hidden_inputs .= hidden_input("course_teacher_page", $course_teacher_page);	

	$sql_course_teacher_count = 
   		"SELECT COUNT(*) AS c FROM 
     	 	ca_course_teacher, ca_staff WHERE 
     	 	ca_staff.ID = ca_course_teacher.staff_id AND ca_course_teacher.course_id = ?";	
	$sql_course_teacher_seek = 
   		"SELECT ca_course_teacher.staff_id, ca_staff.FirstName, ca_staff.LastName FROM 
     	 	ca_course_teacher, ca_staff WHERE 
     	 	ca_staff.ID = ca_course_teacher.staff_id AND ca_course_teacher.course_id = ?";
	$sql_course_teacher_seek .= " ORDER BY ca_staff.LastName, ca_staff.FirstName";
	$sql_course_teacher_seek .= " LIMIT " . (($course_teacher_page - 1) * COURSE_TEACHER_PAGE) . 
		"," . COURSE_TEACHER_PAGE;	
		
	// echo $sql_course_teacher_seek;
		
	$seek_params_get .= possible_get_param("id", $id, $first);
	$seek_params_get .= possible_get_param("page",$page,false);			
	
   	$q = $conn->prepare($sql_course_teacher_seek);
	$qc = $conn->prepare($sql_course_teacher_count);	

    if ($q) {
		$q->bind_param("i", $id);
		$q->execute();
		$q->store_result(); 
		
		$qc->bind_param("i", $id);
		$qc->execute();  
		$qc->store_result();
		$count_course_teachers = 0;
		$qc->bind_result($count_course_teachers);
		$qc->fetch();  
		if ($count_course_teachers > 0) {  	
			$page_count_course_teachers = intdiv($count_course_teachers, COURSE_TEACHER_PAGE);
			if ($page_count_course_teachers * COURSE_TEACHER_PAGE < $count_course_teachers) { 
				$page_count_course_teachers++; 
			}
			$course_teacher_page_links = generate_page_list(
				"list_courses.php".$seek_params_get, 
				$page_count_course_teachers, $course_teacher_page,
				"course_teacher_page",
				"","","curr_page","other_page");		
?>

		<div id="table_course_teachers">
			<div class="row">
				<div class="col-sm-6"><h5>Etunimi</h5></div>
				<div class="col-sm-5"><h5>Sukunimi</h5></div>
				<div class="col-sm-1"></div>
			</div>	
	
<?php     
        	$firstName = "-"; $lastName = "-"; $staff_id = 0;

			$q->bind_result($staff_id, $firstName, $lastName);
			while ($q->fetch()) {
?>
				<div class="row">
					<div class="col-sm-6"><?php echo $firstName ?></div>
					<div class="col-sm-5"><?php echo $lastName ?></div>
					<div class="col-sm-1">
						<form action="inc/sub/course/remove_course_teacher.php" action="post">
							<?php 
								echo hidden_input("course_id", $id);
								echo hidden_input("staff_id", $staff_id);
								echo $seek_params_hidden_inputs; 
							?>			
							<input class="button" type="submit" value="Poista"/>				
						</form>
					</div>
				</div>
<?php
			} 
?>
		</div> 

<?php				
			echo $course_teacher_page_links;
		} else {
?>
			<b>Kurssilla ei ole opettajia</b>
<?php
		}	
     } // if ($q) 
?>
</div> <!-- id="list_of_course_teachers"> -->
