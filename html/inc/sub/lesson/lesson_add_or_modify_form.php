<?php
	$id = "";
	$lesson_date_value_param = "";
	$begin_time_value_param = "";
	$end_time_value_param = "";
	$room_identifier = "";
	$topic = "";
	if (isset($_POST['id'])) {
?>
		<h2>Muokkaa koulutusta tai oppituntia</h2>
<?php
		$id = $_POST['id'];
		$q = $conn->prepare(
			"SELECT begin_time, end_time, room_identifier, topic FROM ca_lesson WHERE ID=?");
		if ($q) {
			$q->bind_param("s", $_POST['id']);
			$q->execute();
			$q->bind_result($begin_time, $end_time, $room_identifier, $topic);
			$q->fetch();
			$q->close();
			if (isset($begin_time) && isset($end_time)) {
				$begin_time_parts = from_db_to_separate_date_and_time_ui($begin_time);
				$end_time_parts = from_db_to_separate_date_and_time_ui($end_time);
				$begin_time = from_db_to_ui($begin_time);
				$end_time = from_db_to_ui($end_time);
				$lesson_date_value_param = " value=\"".
					$begin_time_parts['date_part'] . "\" ";
				$begin_time_value_param = " value=\"".
					$begin_time_parts['time_part'] ."\" ";
				$end_time_value_param = " value=\"".
					$end_time_parts['time_part'] ."\" ";
			}
		}
	}
	else {
?>
		<h2>Lisää koulutus tai oppitunti</h2>
<?php		
	}
?>	
	
<form name="add_or_modify_lesson_form" method="post" 
	action="inc/sub/lesson/save_lesson.php"
	onsubmit="return validateAddOrModifyForm()" >
	<input type="hidden" name="id" value="<?php echo $id; ?>" />
	<div class="row-type-2">
		<label>Päiväys:</label>
		<input id="lesson_date" name="lesson_date" class="date_picker" 
			<?php echo $lesson_date_value_param; ?> />		
	</div>
	<div class="row-type-2">
		<label>Alkuaika:</label>
		<input id="begin_time" name="begin_time" class="time_picker" 
			<?php echo $begin_time_value_param; ?> />
	</div>
	<div class="row-type-2">
		<label>Loppuaika:</label>
		<input id="end_time" name="end_time" class="time_picker" 
			<?php echo $end_time_value_param; ?> />	
    </div>		
	<div class="row-type-2">
		<label>Huoneen tunnus:</label>
		<input type="text" name="room_identifier" value="<?php echo $room_identifier; ?>" 
			maxlength="50" required />
	</div>
	<div class="row-type-2">
		<label>Aihe:</label>
		<input type="text" name="topic" value="<?php echo $topic; ?>" 
			maxlength="150" required />		
	</div>
<?php echo $seek_params_hidden_inputs; ?>
    <div class="row-type-5">
		<input class="button" type="submit" value="Talleta"/>
	</div>
</form>
<div id="add_or_modify_lesson_form_validation_errors"></div>