<?php	
	if ($id != "") { 
		include("list_course_teachers.php");
		if (!isset($_POST['add_teacher']) && !isset($_GET['add_teacher']) && 
	    	!isset($_POST['add_student']) && !isset($_GET['add_student'])) {
?>
			<form method="POST" action="list_courses.php">
				<input type="hidden" name="id" value="<?php echo $id; ?>" />
	    		<input type="hidden" name="add_teacher" value="1" />
<?php echo $seek_params_hidden_inputs; ?>
				<div class="row-type-5">    		
					<input class="button" type="submit" value="Lisää opettaja"/>
				</div>
			</form>
<?php
		}
		include("add_teacher_to_course_ui.php");
	}	
?> 