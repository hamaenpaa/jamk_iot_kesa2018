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
							<?php echo $course_lesson["lesson_period"]; ?>
						</div>			
						<div class="col-sm-3">
							<?php echo $course_lesson["room_identifier"]); ?>
						</div>
						<div class="col-sm-5">
							<?php echo $course_lesson["topic"]);  ?>
						</div>
						<div class="col-sm-1">
							<div class="col-sm-1">
<?php 
								$remove_course_lesson_params = array($course['course_id'], $course['lessom_id');
								$remove_js_call = java_script_call("removeCourseLesson", 
									$remove_course_lesson_params);
?>			
								<button class="button" onclick="<?php echo $remove_js_call; ?>">Poista</button>
							</div>
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