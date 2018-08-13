<?php
	if ($begin_time_seek_value_param != "" && $end_time_seek_value_param != "") {
		include("lessons_fetch_from_db.php");
		$lessons = get_lessons($conn, 
			$begin_time_seek, $end_time_seek, $room_seek, $topic_seek);
?>
<h2>Haetut koulutukset tai oppitunnit</h2>
<div class="lesson_listing_table">
	<div class="row">
		<div class="col-sm-3"><h5>Alkuaika</h5></div>
		<div class="col-sm-3"><h5>Loppuaika</h5></div>
		<div class="col-sm-3"><h5>Huone</h5></div>
		<div class="col-sm-3"><h5>Aihe</h5></div>
	</div>
<?php
		foreach($lessons as $lesson) {
			$begin_time = $lesson['begin_time'];
			$end_time = $lesson['end_time'];
			$room_identifier = $lesson['room_identifier'];
			$topic = $lesson['topic'];
?>				
			<div class="row datarow">
				<div class="col-sm-3">
					<?php echo from_db_to_ui($begin_time); ?>
				</div>			
				<div class="col-sm-3">
					<?php echo from_db_to_ui($end_time); ?>
				</div>							
				<div class="col-sm-3">
					<?php echo $room_identifier; ?>
				</div>
				<div class="col-sm-3">
					<?php echo $topic;  ?>
				</div>
			</div>
<?php
		}
	}
?>
</div>