<div id="list_of_course_teachers">
<?php
   	$q = $conn->prepare(
   		"SELECT ca_course_teacher.staff_id, ca_staff.FirstName, ca_staff.LastName FROM 
     	 	ca_course_teacher, ca_staff WHERE 
     	 	ca_staff.ID = ca_course_teacher.staff_id AND ca_course_teacher.course_id = ?");
    if ($q) { 
?>
    	<h2>Kurssin opettajat</h2>
		<div id="table_course_teachers">
			<div class="row">
				<div class="col-sm-6"><b>Etunimi</b></div>
				<div class="col-sm-5"><b>Sukunimi</b></div>
				<div class="col-sm-1"></div>
			</div>	
	
<?php     
        	$firstName = "-"; $lastName = "-"; $staff_id = 0;
			$q->bind_param("i", $id);
			$q->execute();
			$q->store_result();
			$q->bind_result($staff_id, $firstName, $lastName);
			while ($q->fetch()) {
?>
				<div class="row">
					<div class="col-sm-6"><?php echo $firstName ?></div>
					<div class="col-sm-5"><?php echo $lastName ?></div>
					<div class="col-sm-1"></div>
				</div>
<?php
			} // while ($q->fetch()) {
?>
		</div> <!-- <div id="table_course_teachers"> -->

<?php					
     } // if ($q) 
?>
</div> <!-- id="list_of_course_teachers"> -->
