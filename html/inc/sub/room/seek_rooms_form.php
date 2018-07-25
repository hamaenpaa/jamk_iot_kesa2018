<div id="seek_rooms">
    <h2>Etsi luokkia</h2>
	<form action="list_rooms.php" method="post">
		<label>Tunnus:<label>
		<input type="text" name="seek_room_name" value="<?php echo $seek_room_name; ?>" 
			   maxlength="40" />
		<input class="button" type="submit" value="Hae" /> 
	</form>
</div>