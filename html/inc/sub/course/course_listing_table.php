<?php
		include("course_fetch_from_db.php");
		$courses_arr = get_courses($conn, $name_seek, $description_seek, $topic_seek, $page);
?>
		<h2>Kurssit</h2>
<?php
		if ($courses_arr['count'] > 0) {
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
								<form method="post" action="<?php echo $index_page; ?>">
									<input type="hidden" name="id" 
										value="<?php echo $course['course_id']; ?>"/>
<?php echo $seek_params_hidden_inputs; ?>
									<input class="button" type="submit" value="Muokkaa" />
								</form>	
							</div>
							<div class="col-sm-1">
								<form method="post" action="inc/sub/course/remove_course.php">
									<input type="hidden" name="id" 
										value="<?php echo $course['course_id']; ?>"/>
<?php echo $seek_params_hidden_inputs; ?>
									<input class="button" type="submit" value="Poista" />
								</form>				
							</div>
						</div>
					</div>
<?php
				}
?>
			</div>
<?php
			echo generate_page_list($index_page.$seek_params_get, 
				$courses_arr['page_count'], $page, $page_page,
				"page", "page_page",
				"course_pages", "",
				"curr_page", "other_page");			
		} else {
?>
			<b>Haulla ei löytynyt yhtään kurssia</b>
<?php
		}
?>