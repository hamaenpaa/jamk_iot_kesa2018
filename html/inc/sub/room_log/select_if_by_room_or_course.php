<form action="list_room_logs.php" method="POST">
	<label>Etsitkö luokalla vai kurssilla?</label>
	
	<!-- onchange="this.form.submit()" -->
	<select name="seek_with" value="room" >
		<option value="course">Kurssilla</option>
		<option value="room">Luokalla</option>
	</select>
	<input type="submit" value="Valitse" />
</form>