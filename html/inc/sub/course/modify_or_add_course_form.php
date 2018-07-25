<?php
    $course_id = "";
	$course_name = "";
	$course_description = "";

	if ($id != "") {
?>
	<h2>Muokkaa kurssia</h2>
<?php		
		
		$q = $conn->prepare("SELECT course_id,course_name,course_description FROM ca_course WHERE ID=?");
		if ($q) {
			$q->bind_param("s", $id);
			$q->execute();
			$q->bind_result($course_id,$course_name,$course_description);
			$q->fetch();
			$q->close();
		}
	} 
	else {
?>
	<h2>Lisää kurssi</h2>
<?php
	}
?>	
	
<form method="post" action="inc/sub/course/save_course.php">
	<input type="hidden" name="id" value="<?php echo $id; ?>" />
	<label>Kurssin tunnus:</label>
	<input type="text" name="course_id" value="<?php echo $course_id; ?>" maxlength="20" required autofocus/>
	<label>Kurssin nimi:</label> 
	<input type="text" name="course_name" value="<?php echo $course_name; ?>" maxlength="50" required />
	<label>Kurssin kuvaus:</label>
	<textarea name="course_description" rows="7" cols="50" maxlength="500"><?php echo $course_description; ?></textarea>
<?php echo $seek_params_hidden_inputs; ?>		
	<input type="submit" value="Talleta"/>	
</form>
	