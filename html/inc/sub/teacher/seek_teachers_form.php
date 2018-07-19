<div id="seek_students">
    <h2>Etsi opettajia</h2>
	<form action="list_teachers.php" method="post">
		<label>Etunimi:</label>
		<input type="text" name="seek_first_name" value="<?php echo $seek_first_name; ?>" />
		<label>Sukunimi:<label>
		<input type="text" name="seek_last_name" value="<?php echo $seek_last_name; ?>" />
		<input type="submit" value="Hae" />
	</form>
</div>