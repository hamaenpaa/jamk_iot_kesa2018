<?php
    $sql_seek = "SELECT * FROM ca_course WHERE removed=0 ";
    $sql_seek = add_further_seek_param($conn, $sql_seek, "course_ID", $seek_course_ID);
	$sql_seek = add_further_seek_param($conn, $sql_seek, "course_name", $seek_course_name);
   	if ($result = $conn->query($sql_seek)) {
   		if (mysqli_num_rows($result) > 0) {
?>
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
		}
		else {
?>
	<b>Kurssien haulla ei löytynyt yhtään kurssia</b>
<?php			
		} 
	}
?>
</div>