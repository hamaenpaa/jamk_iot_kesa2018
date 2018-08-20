<?php 
	if ($id != "") { 
?>
<h2>Kurssin oppitunnit</h2>	
<?php
		$course_lessons = get_course_lessons($conn, $id);
		if (count($course_lessons) > 0) {
?>			
			<div id="course_lesson_listing_table" class="datatable">
				<div class="row heading-row">
					<div class="col-sm-3"><h5>Aikaväli</h5></div>
					<div class="col-sm-3"><h5>Huone</h5></div>
					<div class="col-sm-5"><h5>Aihe</h5></div>
					<div class="col-sm-1"></div>
				</div>	
<?php
				foreach($course_lessons as $course_lesson) {
?>
					<div class="row datarow">
						<div class="col-sm-3">
							<?php echo 
									str_replace(" ", "&nbsp;", 
									from_db_datetimes_to_same_day_date_plus_times(
									$course_lesson["begin_time"], 
									$course_lesson["end_time"])); ?>
						</div>			
						<div class="col-sm-3">
							<?php echo str_replace(" ", "&nbsp;",
								$course_lesson["room_identifier"]); ?>
						</div>
						<div class="col-sm-5">
							<?php echo str_replace(" ", "&nbsp;",
								$course_lesson["topic"]);  ?>
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
		include("add_course_lessons_ui.php");
	} 
?>