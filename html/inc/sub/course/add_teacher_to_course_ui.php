<?php if (isset($_POST['add_teacher']) || isset($_GET['add_teacher'])) { ?>
	<div id="teacher_adding">
<?php 
     	$q2 = $conn->prepare(
     		"SELECT ca_staff.ID, ca_staff.FirstName, ca_staff.LastName FROM ca_staff WHERE 
     	 	ca_staff.permission=0 AND ca_staff.active=1 AND
     	 	NOT EXISTS (SELECT ID FROM ca_course_teacher WHERE 
     	 	ca_course_teacher.staff_id = ca_staff.ID AND ca_course_teacher.course_id = ?)");
     	if ($q2) {
        	$firstName = "-"; $lastName = "-"; $staff_id = 0;
			$q2->bind_param("i", $id);
			$q2->execute();
			$q2->store_result();
			if ($q2->num_rows > 0) {
?>
        		<h2>Lisää opettaja</h2>
				<div id="table_teachers_to_add">
					<div class="row">
						<div class="col-sm-6"><b>Etunimi</b></div>
						<div class="col-sm-5"><b>Sukunimi</b></div>
						<div class="col-sm-1"></div>
					</div>			
<?php				
					$firstName = "-"; $lastName = "-"; $staff_id = 0;
					$q2->bind_result($staff_id, $firstName, $lastName);
					while ($q2->fetch()) {
?>  				
						<div class="row">
							<div class="col-sm-6"><?php echo $firstName ?></div>
							<div class="col-sm-5"><?php echo $lastName ?></div>
							<div class="col-sm-1">
								<form action="inc/sub/course/add_course_teacher.php" method="POST">
									<input type="hidden" name="course_id" value="<?php echo $id; ?>" />
									<input type="hidden" name="staff_id" value="<?php echo $staff_id; ?>" />
<?php echo $seek_params_hidden_inputs; ?>													
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
				<b>Kaikki opettajat on jo kurssilla</b>
<?php 
			}			
		}
?>
	<form action="list_courses.php method="post">
		<input type="hidden" name="id" value="<?php echo $id; ?>"/>
		<?php echo $seek_params_hidden_inputs; ?>	
		<input type="submit" value="Lopeta lisääminen"/>
	</form>
<?php
	}
?>	
