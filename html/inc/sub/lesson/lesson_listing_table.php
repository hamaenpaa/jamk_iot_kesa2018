<?php
	if ($begin_time_seek_value_param != "" && $end_time_seek_value_param != "") {
		include("lessons_fetch_from_db.php");
		$begin_time_seek = from_ui_to_db($begin_time_seek);
		$end_time_seek = from_ui_to_db($end_time_seek);	
		$lessons_arr = get_lessons($conn, 
			$begin_time_seek, $end_time_seek, $room_seek, $topic_seek, $page);
?>
		<h2>Haetut koulutukset tai oppitunnit</h2>
<?php
		if ($lessons_arr['count'] > 0) {
?>
			<div id="lesson_listing_table" class="datatable">
				<div class="row heading-row">
					<div class="col-sm-4"><h5>Aikaväli</h5></div>
					<div class="col-sm-3"><h5>Huone</h5></div>
					<div class="col-sm-3"><h5>Aihe</h5></div>
					<div class="col-sm-1-wrap">
						<div class="col-sm-1"></div>
						<div class="col-sm-1"></div>
					</div>
				</div>
<?php
				foreach($lessons_arr['lessons'] as $lesson) {
					$time_interval = $lesson['time_interval'];
					$room_identifier = $lesson['room_identifier'];
					$topic = $lesson['topic'];
?>				
					<div class="row datarow">
						<div class="col-sm-4">
							<?php echo $time_interval; ?>
						</div>			
						<div class="col-sm-3">
							<?php echo $room_identifier; ?>
						</div>
						<div class="col-sm-3">
							<?php echo $topic;  ?>
						</div>
						<div class="col-sm-1-wrap">
							<div class="col-sm-1">
<?php
								$modify_lesson_params = array($lesson['lesson_id']);
								$modify_js_call = java_script_call("modifyLesson", 
									$modify_lesson_params);
?>	
								<button class="button" onclick="<?php echo $modify_js_call; ?>">Muokkaa</button>
							</div>						
							<div class="col-sm-1">
<?php 
								$remove_lesson_params = array($lesson['lesson_id']);
								$remove_js_call = java_script_call("removeLesson", 
									$remove_lesson_params);
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
			echo generate_js_page_list("get_lessons_page",
				array(), 
				$lessons_arr['page_count'], $page, $page_page,
				"lesson_pages", "",
				"curr_page", "other_page");
		} else {
?>
			<b>Haulla ei löytynyt yhtään koulutusta/oppituntia</b>
<?php
		}
?>
		<!-- These are because seek fields etc. can be changes after
		     last query and other user and also to make js functions
			 work easier -->
		<div id="page" style="display:none"><?php echo $page; ?></div>
		<div id="page_page" style="display:none"><?php echo $page_page; ?></div>
		<div id="last_query_begin_time_seek" style="display:none"><?php echo $begin_time_seek; ?></div>
		<div id="last_query_end_time_seek" style="display:none"><?php echo $end_time_seek; ?></div>
		<div id="last_query_topic_seek" style="display:none"><?php echo $topic_seek; ?></div>
		<div id="last_query_room_seek" style="display:none"><?php echo $room_seek; ?></div>
<?php
	}
?>