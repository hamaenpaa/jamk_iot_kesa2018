<?php 
	if ($id != "") { 
?>
<h2>Kurssin oppitunnit</h2>	
<?php
		$course_lessons = get_course_lessons($conn, $id);
		if (count($course_lessons) > 0) {
?>			
			<div id="course_lesson_listing_table" class="datatable">
<?php
				echo heading_row(null, array(3,3,5,1), 
					array("<h5>Aikaväli</h5>","<h5>Huone</h5>","<h5>Aihe</h5>",""));
				foreach($course_lessons as $course_lesson) {
					$remove_course_lesson_params = array($course['course_id'], $course['lessom_id');
					$remove_js_call = java_script_call("removeCourseLesson", 
						$remove_course_lesson_params);
					echo data_row(null, array(3,3,5,1), 
						array($course_lesson["lesson_period"],
					          $course_lesson["room_identifier"],
							  $course_lesson["topic"],
							  "<div class=\"col-sm-1\">".
								button_elem($remove_js_call, "Poista").
							  "</div>"));
				}
?>
			</div>	
<?php 
		} else {
?>
			<b>Kurssilla ei ole yhtään oppituntia</b>
<?php			
		}
	} 
?>