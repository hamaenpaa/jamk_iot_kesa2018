<div id="seek_students">
    <h2>Etsi oppilaita</h2>
	<form action="list_students.php" method="post">
		<label>Etunimi:</label>
		<input type="text" name="seek_first_name" value="<?php echo $seek_first_name; ?>" maxlength="25" />
		<label>Sukunimi:<label>
		<input type="text" name="seek_last_name" value="<?php echo $seek_last_name; ?>" maxlength="25" />
		<label>Oppilas ID:<label>
		<input type="text" name="seek_student_id" value="<?php echo $seek_student_id; ?>" maxlength="6" />
		<input type="submit" value="Hae" />
	</form>
</div>