<div id="list_of_course_students">
<?php
   	$q = $conn->prepare(
   		"SELECT ca_course_student.student_id, ca_student.FirstName, ca_student.LastName FROM 
     	 	ca_course_student, ca_student WHERE 
     	 	ca_student.ID = ca_course_student.student_id AND ca_course_student.course_id = ?");
    if ($q) { 
?>
    	<h2>Kurssin oppilaat</h2>
		<div id="table_course_students">
			<div class="row">
				<div class="col-sm-6"><b>Etunimi</b></div>
				<div class="col-sm-5"><b>Sukunimi</b></div>
				<div class="col-sm-1"></div>
			</div>	
	
<?php     
        	$firstName = "-"; $lastName = "-"; $student_id = 0;
			$q->bind_param("i", $id);
			$q->execute();
			$q->store_result();
			$q->bind_result($student_id, $firstName, $lastName);
			while ($q->fetch()) {
?>
				<div class="row">
					<div class="col-sm-6"><?php echo $firstName ?></div>
					<div class="col-sm-5"><?php echo $lastName ?></div>
					<div class="col-sm-1"></div>
				</div>
<?php
			} 
?>
		</div> 
<?php					
     } 
?>
</div> 
