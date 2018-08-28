<?php
	$id = "";
	$lesson_date_value_param = "";
	$begin_time_value_param = "";
	$end_time_value_param = "";
	$room_identifier = "";
	$topic = "";
?>	
<h2 id="add_or_modify_lesson_header">Lisää koulutus tai oppitunti</h2>
	
	<input type="hidden" id="id" name="id"  />
	<div class="row-type-2">
		<label>Päiväys:</label>
		<input id="lesson_date" name="lesson_date" class="date_picker" 
			autocomplete="off"
			<?php echo $lesson_date_value_param; ?> />		
	</div>
	<div class="row-type-2">
		<label>Alkuaika:</label>
		<input id="begin_time" name="begin_time" class="time_picker"
			autocomplete="off"
			<?php echo $begin_time_value_param; ?> />
	</div>
	<div class="row-type-2">
		<label>Loppuaika:</label>
		<input id="end_time" name="end_time" class="time_picker" 
			autocomplete="off"
			<?php echo $end_time_value_param; ?> />	
    </div>		
	<div class="row-type-2">
		<label>Huoneen tunnus:</label>
		<input type="text" id="room_identifier" name="room_identifier" value="<?php echo $room_identifier; ?>" 
			maxlength="50" required />
	</div>
	<div class="row-type-2">
		<label>Aihe:</label>
		<input type="text" id="topic" name="topic" value="<?php echo $topic; ?>" 
			maxlength="150" required />		
	</div>
    <div class="row-type-5">
		<button class="button" onclick="saveLesson()">Talleta</button>
	</div>

<div id="add_or_modify_lesson_form_validation_errors"></div>