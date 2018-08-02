<div class="row">
<?php
	if (!isset($dt_extra_css_classes) || $dt_extra_css_classes == null) {
		$dt_extra_css_classes = "";
	}
	if (!isset($name_extra_css_classes) || $name_extra_css_classes == null) {
		$name_extra_css_classes = "";
	}
	if (!isset($room_extra_css_classes) || $room_extra_css_classes == null) {
		$room_extra_css_classes = "";
	}
	if (!isset($course_extra_css_classes) || $course_extra_css_classes == null) {
		$course_extra_css_classes = "";
	}
	if (!isset($btn_wrap_extra_css_classes) || $btn_wrap_extra_css_classes == null) {
		$btn_wrap_extra_css_classes = "";
	}
	if (!isset($btn_extra_css_classes) || $btn_extra_css_classes == null) {
		$btn_extra_css_classes = "";
	}	

	if ($student_first_name != "" || $student_last_name != "") {
?>
		<div class="col-sm-<?php echo $name_cols ." ". $name_extra_css_classes; ?>">
<?php
			echo $student_last_name . " " . $student_first_name;
?>	
		</div>
<?php
	} else {
		if (!isset($guest_last_name)) { $guest_last_name = ""; }
		if (!isset($guest_first_name)) { $guest_first_name = ""; }
?>						
		<div class="col-sm-<?php echo $name_cols ." ". $name_extra_css_classes; ?>">
<?php
			echo $guest_last_name . " " . $guest_first_name;
?>	
		</div>
<?php
	}
	if ($dt_cols != "0") { 
?>						
		<div class="col-sm-<?php echo $dt_cols . " ". $dt_extra_css_classes; ?>"><?php echo $dt; ?></div>
<?php 
    }
	if ($room_cols != "0") { 
?>			
		<div class="col-sm-<?php echo $room_cols . " " . $room_extra_css_classes; ?>"><?php echo $room_name;  ?></div>
<?php 
	} 
	if ($course_cols != "0") {	
?>	
		<div class="col-sm-<?php echo $course_cols . " " . $course_extra_css_classes; ?>">
			<?php echo $ui_course_ID . " ". $course_name;  ?>
		</div>
<?php } ?>
	<div class="col-sm-<?php echo $nfc_cols; ?>"><?php echo $nfc_id; ?></div>
	<div class="col-sm-1-wrap <?php echo $btn_wrap_extra_css_classes; ?>">
		<div class="col-sm-1">
			<form method="post" action="list_room_logs.php">
				<input type="hidden" name="id" value=""/>
				<input class="button" type="submit" value="Muokkaa" />
			</form>
		</div>
		<div class="col-sm-1 <?php echo $btn_extra_css_classes; ?>">
			<form method="post" action="inc/sub/room/remove_room_log.php">
				<input type="hidden" name="id" value=""/>
				<input class="button" type="submit" value="Poista" />
			</form>
		</div>
	</div>
</div>
