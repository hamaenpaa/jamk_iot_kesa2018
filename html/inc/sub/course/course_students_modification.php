<?php
	if ($id != "") { 
		include("inc/sub/course/list_course_students.php");
		if (!isset($_POST['add_teacher']) && !isset($_GET['add_teacher']) && 
	 	   !isset($_POST['add_student']) && !isset($_GET['add_student'])) {
?>
			<form method="POST" action="list_courses.php">
				<input type="hidden" name="id" value="<?php echo $id; ?>" />
	   		 	<input type="hidden" name="add_student" value="1" />
<?php echo $seek_params_hidden_inputs; ?>	   		 	
				<input type="submit" value="Lisää oppilas"/>
			</form>
<?php
		}	
		include("inc/sub/course/add_student_to_course_ui.php");
	}
?> 