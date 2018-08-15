<?php
	$id = "";
	$name = "";
	$description = "";
	if (isset($_POST['id'])) {
?>
		<h2>Muokkaa kurssia</h2>
<?php
		$id = $_POST['id'];
		$q = $conn->prepare(
			"SELECT name, description FROM ca_course WHERE ID=?");
		if ($q) {
			$q->bind_param("s", $_POST['id']);
			$q->execute();
			$q->bind_result($name, $description);
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
	
<form name="add_or_modify_course_form" method="post" 
	action="inc/sub/course/save_course.php" >
	<input type="hidden" name="id" value="<?php echo $id; ?>" />
	<div class="row-type-2">
		<label>Nimi:</label>
		<input id="name" name="name" value="<?php echo $name; ?>" required />		
	</div>
	<div class="row-type-2">
		<label>Kurssin kuvaus:</label>
	</div>
	<div class="row-type-4">
		<textarea class="text-box" name="description" 
			rows="7" cols="50" maxlength="500"><?php echo $description; ?></textarea>
	</div>	
<?php echo $seek_params_hidden_inputs; ?>
    <div class="row-type-5">
		<input class="button" type="submit" value="Talleta"/>
	</div>
</form>