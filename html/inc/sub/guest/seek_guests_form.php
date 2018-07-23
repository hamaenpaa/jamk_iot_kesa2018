<div id="seek_guests">
    <h2>Etsi vierailijoita</h2>
	<form action="list_guests.php" method="post">
		<label>Etunimi:</label>
		<input type="text" name="seek_first_name" value="<?php echo $seek_first_name; ?>" maxlength="25" autofocus/>
		<label>Sukunimi:<label>
		<input type="text" name="seek_last_name" value="<?php echo $seek_last_name; ?>" maxlength="25" />
		<input type="submit" value="Hae" />
	</form>
</div>