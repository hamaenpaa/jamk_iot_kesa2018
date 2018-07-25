<?php
	define("MAX_COURSES_AT_SEARCH", 50);
	define("PAGE_SIZE", 20);
	
    $sql_seek = "SELECT * FROM ca_course WHERE removed=0 ";
    $sql_seek = add_further_seek_param($conn, $sql_seek, "course_ID", $seek_course_ID);
	$sql_seek = add_further_seek_param($conn, $sql_seek, "course_name", $seek_course_name);
	$sql_seek .= " ORDER BY course_name, course_ID";
	$sql_seek .= " LIMIT " . (($page - 1) * PAGE_SIZE) . "," . PAGE_SIZE;
	
    $sql_count_seek = "SELECT COUNT(*) AS c FROM ca_course WHERE removed=0 ";
    $sql_count_seek = add_further_seek_param($conn, $sql_count_seek, "course_ID", $seek_course_ID);
	$sql_count_seek = add_further_seek_param($conn, $sql_count_seek, "course_name", $seek_course_name);
	
	$result_count = $conn->query($sql_count_seek);
	$res_count = $result_count->fetch_assoc();
	$count = $res_count['c'];
	$courses_text = "kurssi";
	if ($count > 1) { $courses_text .= "a"; }
	
	$page_count = intdiv($count, PAGE_SIZE);
	if ($page_count * PAGE_SIZE < $count) { $page_count++; }	
	$page_links = generate_page_list(
						"list_courses.php".$seek_params_get, 
						$page_count, $page,
						"page", 
						"","","curr_page","other_page");	
	
   	if ($result = $conn->query($sql_seek)) {
   		if ($count > 0) { 
?>
<div id="count_of_results">Haussa löytyi 
	<?php echo $count." ".$courses_text ?>.
</div>

<div id="course_table">
	<div class="row">
		<div class="col-sm-2"><b>Kurssin tunnus</b></div>
		<div class="col-sm-3"><b>Kurssin nimi</b></div>
		<div class="col-sm-6"><b>Kurssin kuvaus</b></div>
		<div class="col-sm-1"></div>
	</div>

<?php   	
			while($res = $result->fetch_assoc()) {
?>
				<div class="row">
					<div class="col-sm-2"><?php echo $res['Course_ID']; ?></div>
					<div class="col-sm-2"><?php echo $res['Course_name']; ?></div>
					<div class="col-sm-6"><?php echo $res['Course_description']; ?></div>
					<div class="col-sm-1">
						<form method="post" action="list_courses.php">
							<input type="hidden" name="id" value="<?php echo $res['ID']; ?>"/>
<?php echo $seek_params_hidden_inputs; ?>
							<input type="submit" value="Muokkaa" />
						</form>
					</div>
					<div class="col-sm-1">
						<form method="post" action="inc/sub/course/remove_course.php">
							<input type="hidden" name="id" value="<?php echo $res['ID']; ?>"/>
<?php echo $seek_params_hidden_inputs; ?>
							<input type="submit" value="Poista" />
						</form>
					</div>
				</div>
<?php		
			} 
			echo $page_links;
		}
		else {
?>
			<b>Kurssien haulla ei löytynyt yhtään kurssia</b>
<?php			
		} 
	}
?>
</div>