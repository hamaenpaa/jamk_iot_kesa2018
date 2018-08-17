<?php
		$seek_params_hidden_inputs = 
			hidden_input("id", $id) .
			hidden_input("name_seek", $name_seek) .
			hidden_input("description_seek", $description_seek).
			hidden_input("topic_seek", $topic_seek);		
?>
		<h2>Lisää kurssille oppitunti</h2>
		<h3>Lisättävien oppituntien etsintä</h3>
			<form name="lessons_seek" action="<?php $index_page;?>" method="POST"
				onsubmit="return validateAddLessonSeekForm()" >	
				<div class="row-type-2">
					<label>Aloitusaika:</label>
					<input name="lesson_add_begin_time_seek" class="datetime_picker" 
						id="lesson_add_begin_time_seek" placeholder="Aloitusaika" 
						alt="Päivämäärä suomalaisessa muodossa esim: 31.07.2018 07:00"
						<?php echo $lesson_add_begin_time_seek_value_param; ?> required />
				</div>
				<div class="row-type-2">
					<label>Lopetusaika:</label>
					<input name="lesson_add_end_time_seek" class="datetime_picker" 
						id="lesson_add_end_time_seek" placeholder="Lopetusaika" 
						alt="Päivämäärä suomalaisessa muodossa esim: 31.07.2018 07:00"
						<?php echo $lesson_add_end_time_seek_value_param; ?> required />
				</div>
				<div class="row-type-2">
					<label>Etsittävä luokan tunnisteen osa:</label>
					<input name="lesson_add_room_seek" value="<?php echo $lesson_add_room_seek; ?>"
						placeholder="Huoneen tunnus" maxlength="50"/>
				</div>
				<div class="row-type-2">
					<label>Etsittävä aiheen osa:</label>
					<input name="lesson_add_topic_seek" value="<?php echo $lesson_add_topic_seek; ?>" 
						placeholder="Aihe" maxlength="150"/>
				</div>
<?php echo $seek_params_hidden_inputs; ?>
				<div class="row-type-5">
					<input class="button" type="submit" value="Hae"/>
				</div>
			</form>
			<div id="add_lesson_seek_form_validation_errors"></div>
<?php
		if ($lesson_add_begin_time_seek != "" && $lesson_add_end_time_seek != "") {
			$lessons_without_course = get_lessons_without_course(
			$conn, $lesson_add_begin_time_seek, $lesson_add_end_time_seek,
			$lesson_add_room_seek, $lesson_add_topic_seek);
	
			$seek_params_hidden_inputs = 
				hidden_input("name_seek", $name_seek) .
				hidden_input("description_seek", $description_seek).
				hidden_input("topic_seek", $topic_seek).
				hidden_input("lesson_add_begin_time_seek", $lesson_add_begin_time_seek) .
				hidden_input("lesson_add_end_time_seek", $lesson_add_end_time_seek) .
				hidden_input("lesson_add_room_seek", $lesson_add_room_seek) .
				hidden_input("lesson_add_topic_seek", $lesson_add_topic_seek);		
	
			if (count($lessons_without_course) > 0) {
?>
				<div id="lessons_without_course_listing_table" class="datatable">
					<div class="row heading-row">
						<div class="col-sm-3"><h5>Aikaväli</h5></div>
						<div class="col-sm-3"><h5>Huone</h5></div>
						<div class="col-sm-5"><h5>Aihe</h5></div>
						<div class="col-sm-1"></div>
					</div>				
<?php
					foreach($lessons_without_course as $lesson_without_course) {
?>
						<div class="row datarow">
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
				<b>Kaikki haetut oppitunnit ovat jo kursseilla</b>
<?php			
			}
		}
?>