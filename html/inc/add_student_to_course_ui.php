<?php if (isset($_POST['add_student']) || isset($_GET['add_student'])) { ?>
	<div id="teacher_adding">
<?php 
     	$q2 = $conn->prepare(
     		"SELECT ca_student.ID, ca_student.FirstName, ca_student.LastName FROM ca_student WHERE 
     	 	NOT EXISTS (SELECT ID FROM ca_course_student WHERE 
     	 	ca_course_student.student_id = ca_student.ID AND ca_course_student.course_id = ?)");
     	if ($q2) {
        	$firstName = "-"; $lastName = "-"; $student_id = 0;
			$q2->bind_param("i", $id);
			$q2->execute();
			$q2->store_result();
			if ($q2->num_rows > 0) {
?>
        		<h2>Lisää oppilas</h2>
				<div id="table_students_to_add">
					<div class="row">
						<div class="col-sm-6"><b>Etunimi</b></div>
						<div class="col-sm-5"><b>Sukunimi</b></div>
						<div class="col-sm-1"></div>
					</div>			
<?php				
					$firstName = "-"; $lastName = "-"; $student_id = 0;
					$q2->bind_result($student_id, $firstName, $lastName);
					while ($q2->fetch()) {
?>  				
						<div class="row">
							<div class="col-sm-6"><?php echo $firstName ?></div>
							<div class="col-sm-5"><?php echo $lastName ?></div>
							<div class="col-sm-1">
								<form action="inc/add_course_student.php" method="POST">
									<input type="hidden" name="course_id" value="<?php echo $id; ?>" />
									<input type="hidden" name="student_id" value="<?php echo $student_id; ?>" />				
									<input type="submit" value="Lisää"/>
								</form>
							</div>
						</div>	
<?php				
					}
?>
				</div>
<?php					
			}
			else {
?>
				<b>Kaikki oppilaat on jo kurssilla</b>
<?php 
			}			
		}
?>
	<form action="list_courses.php?id=<?php echo $id; ?>" method="post">
		<input type="submit" value="Lopeta lisääminen"/>
	</form>
<?php
	}
?>	
