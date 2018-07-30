<div class="row">
<?php
	if ($student_first_name != "" || $student_last_name != "") {
?>
		<div class="col-sm-<?php echo $name_cols; ?>">
<?php
			echo $student_last_name . " " . $student_first_name;
?>	
		</div>
<?php
	} else {
		if (!isset($guest_last_name)) { $guest_last_name = ""; }
		if (!isset($guest_first_name)) { $guest_first_name = ""; }
?>						
		<div class="col-sm-<?php echo $name_cols; ?>">
<?php
			echo $guest_last_name . " " . $guest_first_name;
?>	
		</div>
<?php
	}
	if ($dt_cols != "0") { 
?>						
		<div class="col-sm-<?php echo $dt_cols; ?>"><?php echo $dt; ?></div>
<?php 
    }
	if ($room_cols != "0") { 
?>			
		<div class="col-sm-<?php echo $room_cols; ?>"><?php echo $room_name;  ?></div>
<?php 
	} 
	if ($course_cols != "0") {	
?>	
		<div class="col-sm-<?php echo $course_cols; ?>">
			<?php echo $ui_course_ID . " ". $course_name;  ?>
		</div>
<?php } ?>
	<div class="col-sm-<?php echo $nfc_cols; ?>"><?php echo $nfc_id; ?></div>
	<div class="col-sm-1">
		<form method="post" action="list_room_logs.php">
			<input type="hidden" name="id" value="<?php echo $res['ID']; ?>"/>
			<input class="button" type="submit" value="Muokkaa" />
		</form>
	</div>
	<div class="col-sm-1">
		<form method="post" action="inc/sub/room/remove_room_log.php">
			<input type="hidden" name="id" value="<?php echo $res['ID']; ?>"/>
			<input class="button" type="submit" value="Poista" />
		</form>
	</div>
</div>
