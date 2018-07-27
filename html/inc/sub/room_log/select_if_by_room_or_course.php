<form id="seek_with_form" action="list_room_logs.php" method="POST">
	<label>Etsitkö luokalla vai kurssilla?</label>
<?php
	$seek_with = get_post_or_get($conn, "seek_with");
	if (!isset($seek_with) || $seek_with == "") {
		$seek_with = "room";
	}
?>	
	<select id="seek_with" name="seek_with" value="<?php echo $seek_with; ?>" >
		<option value="course" 
			<?php if ($seek_with == "course") { echo "selected=\"selected\""; } ?>
		>Kurssilla</option>
		<option value="room"
			<?php if ($seek_with == "room") { echo "selected=\"selected\""; } ?>
		>Luokalla</option>
	</select>
</form>