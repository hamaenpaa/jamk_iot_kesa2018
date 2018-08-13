<?php
	$id = "";
	$begin_time_value_param = "";
	$end_time_value_param = "";
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
			if (isset($begin_time)) {
				$begin_time_value_param = " value=\"".$begin_time."\" ";
			}
			if (isset($end_time)) {
				$end_time_value_param = " value=\"".$end_time."\" ";
			}				
		}
	}
	else {
?>
		<h2>Lisää koulutus tai oppitunti</h2>
<?php		
	}
?>	
	
<form method="post" action="inc/sub/lesson/save_lesson.php">
	<input type="hidden" name="id" value="<?php echo $id; ?>" />
	<label>Alkuaika:</label>
	<input id="begin_time" name="begin_time" class="datetime_picker" 
		<?php echo $begin_time_value_param; ?> />	
	<label>Loppuaika:</label>
	<input id="end_time" name="end_time" class="datetime_picker" 
		<?php echo $end_time_value_param; ?> />		
	<label>Huoneen tunnus:</label>
	<input type="text" name="room_identifier" value="<?php echo $room_identifier; ?>" 
		maxlength="40" required />
	<label>Aihe:</label>
	<input type="text" name="topic" value="<?php echo $topic; ?>" 
		maxlength="40" required />		
<?php echo $seek_params_hidden_inputs; ?>	
	<input class="button" type="submit" value="Talleta"/>
</form>