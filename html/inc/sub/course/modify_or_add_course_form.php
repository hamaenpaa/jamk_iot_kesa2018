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
	<div class="row-type-2">
		<label>Kurssin tunnus:</label>
		<input type="text" name="course_id" value="<?php echo $course_id; ?>" maxlength="20" required autofocus/>
	</div>
	<div class="row-type-2">
		<label>Kurssin nimi:</label> 
		<input type="text" name="course_name" value="<?php echo $course_name; ?>" maxlength="50" required />
	</div>
	<div class="row-type-2">
		<label>Kurssin kuvaus:</label>
	</div>
	<div class="row-type-4">
		<textarea class="text-box" name="course_description" rows="7" cols="50" maxlength="500"><?php echo $course_description; ?></textarea>
		<?php echo $seek_params_hidden_inputs; ?>
	</div>
	<div class="row-type-5">
		<input class="button" type="submit" value="Talleta"/>
	</div>
</form>
	