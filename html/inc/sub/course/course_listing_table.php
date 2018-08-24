<?php
		include("course_fetch_from_db.php");
		$courses_arr = get_courses($conn, $name_seek, $description_seek, $topic_seek, $page);
?>
		<h2>Kurssit</h2>
<?php
		if ($courses_arr['count'] > 0) {
			$seek_params_hidden_inputs .= 
				hidden_input("course_count", $courses_arr['count']) .
				hidden_input("page_size", PAGE_SIZE) .
				hidden_input("page_count", $courses_arr['page_count']) .
				hidden_input("page_page_size", PAGE_PAGE_SIZE);			
?>
			<div id="course_listing_table" class="datatable">
				<div class="row heading-row">
					<div class="col-sm-4"><h5>Nimi</h5></div>
					<div class="col-sm-6"><h5>Kuvaus</h5></div>
					<div class="col-sm-1-wrap">
						<div class="col-sm-1"></div>
						<div class="col-sm-1"></div>
					</div>
				</div>
<?php
				foreach($courses_arr['courses'] as $course) {
					$name = $course['name'];
					$description = $course['description'];
?>				
					<div class="row datarow">
						<div class="col-sm-4">
							<?php echo $name; ?>
						</div>			
						<div class="col-sm-6">
							<?php echo $description; ?>
						</div>
						<div class="col-sm-1-wrap">
							<div class="col-sm-1">
<?php
								$modify_course_params = array($course['course_id']);
								$modify_js_call = java_script_call("modifyCourse", 
									$modify_course_params);
?>	
								<button class="button" onclick="<?php echo $modify_js_call; ?>">Muokkaa</button>
							</div>
							<div class="col-sm-1">
<?php 
								$remove_course_params = array($course['course_id']);
								$remove_js_call = java_script_call("removeCourse", 
									$remove_course_params);
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
			echo generate_js_page_list("get_course_page",
				array(), 
				$courses_arr['page_count'], $page, $page_page,
				"course_pages", "",
				"curr_page", "other_page");
				
				
/*			echo generate_page_list($index_page.$seek_params_get, 
				$courses_arr['page_count'], $page, $page_page,
				"page", "page_page",
				"course_pages", "",
				"curr_page", "other_page");			*/
		} else {
?>
			<b>Haulla ei löytynyt yhtään kurssia</b>
<?php
		}
?>

<!-- These are because seek fields etc. can be changes after
     last query and other user and also to make js functions
	 work easier -->
<div id="page" style="display:none">1</div>
<div id="page_page" style="display:none">1</div>
<div id="last_query_name_seek" style="display:none"><?php echo $name_seek; ?></div>
<div id="last_query_description_seek" style="display:none"><?php echo $description_seek; ?></div>
<div id="last_query_topic_seek" style="display:none"><?php echo $topic_seek; ?></div>