<?php 
	if ($id != "") { 
?>
<h2>Kurssin oppitunnit</h2>	
<?php
		$course_lessons = get_course_lessons($conn, $id);
		if (count($course_lessons) > 0) {
?>			
			<div id="course_lesson_listing_table">
				<div class="row">
					<div class="col-sm-3"><h5>Aikaväli</h5></div>
					<div class="col-sm-3"><h5>Huone</h5></div>
					<div class="col-sm-5"><h5>Aihe</h5></div>
					<div class="col-sm-1"></div>
				</div>	
<?php
				foreach($course_lessons as $course_lesson) {
?>
					<div class="row">
						<div class="col-sm-3">
							<?php echo 
								from_db_datetimes_to_same_day_date_plus_times(
									$course_lesson["begin_time"], 
									$course_lesson["end_time"]); ?>
						</div>			
						<div class="col-sm-3">
							<?php echo $course_lesson["room_identifier"]; ?>
						</div>
						<div class="col-sm-5">
							<?php echo $course_lesson["topic"];  ?>
						</div>
						<div class="col-sm-1">
								<form method="post" action="inc/sub/course/remove_lesson_from_course.php">
									<input type="hidden" name="course_id" 
										value="<?php echo $id; ?>"/>									
									<input type="hidden" name="lesson_id" 
										value="<?php echo $course_lesson['lesson_id']; ?>"/>
<?php echo $seek_params_hidden_inputs; ?>
									<input class="button" type="submit" value="Poista" />
								</form>	
						</div>
					</div>					
<?php
				}
?>
			</div>	
<?php 
		} else {
?>
			<b>Kurssilla ei ole yhtään oppituntia</b>
<?php			
		}
?>
		<h2>Lisää kurssille oppitunti</h2>

<?php
		$lessons_without_course = get_lessons_without_course($conn);
		if (count($lessons_without_course) > 0) {
?>
			<div id="lessons_without_course_listing_table">
<?php
			foreach($lessons_without_course as $lesson_without_course) {
?>
					<div class="row">
						<div class="col-sm-3">
							<?php echo 
								from_db_datetimes_to_same_day_date_plus_times(
									$lesson_without_course["begin_time"], 
									$lesson_without_course["end_time"]); ?>
						</div>			
						<div class="col-sm-3">
							<?php echo $lesson_without_course["room_identifier"]; ?>
						</div>
						<div class="col-sm-5">
							<?php echo $lesson_without_course["topic"];  ?>
						</div>
						<div class="col-sm-1">
								<form method="post" action="inc/sub/course/add_lesson_to_course.php">
									<input type="hidden" name="course_id" 
										value="<?php echo $id; ?>"/>
									<input type="hidden" name="lesson_id" 
										value="<?php echo $lesson_without_course['lesson_id']; ?>"/>
<?php echo $seek_params_hidden_inputs; ?>
									<input class="button" type="submit" value="Lisää" />
								</form>	
						</div>
					</div>					

<?php			
			}
?>
			</div>
<?php			
			
		} else {
?>
			<b>Kaikki oppitunnit ovat jo kursseilla</b>
<?php			
		}
	} 
?>