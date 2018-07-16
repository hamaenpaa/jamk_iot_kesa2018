<?php
   include("db_connect_inc.php");

   if ($result = $conn->query("SELECT * FROM ca_course")) {
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
				<div class="col-sm-3"><?php echo $res['Course_name']; ?></div>
				<div class="col-sm-6"><?php echo $res['Course_description']; ?></div>
				<div class="col-sm-1">
					<form method="post" action="list_courses.php">
					<input type="hidden" name="id" value="<?php echo $res['ID']; ?>"/>
					<input type="submit" value="Muokkaa" />
					</form>
				</div>
			</div>
<?php		
		}
?>
	</div>
	
<?php
    $course_id = "";
	$course_name = "";
	$course_description = "";
	$id = "";
	if (isset($_POST['id'])) {
		$id = $_POST['id'];
		$q = $conn->prepare("SELECT course_id,course_name,course_description FROM ca_course WHERE ID=?");
		if ($q) {
			$q->bind_param("s", $_POST['id']);
			$q->execute();
			$q->bind_result($course_id,$course_name,$course_description);
			$q->fetch();
			$q->close();
		}
	}
		 
?>	
	
	<form method="post" action="inc/save_course.php">
		<input type="hidden" name="id" value="<?php echo $id; ?>" />
		<label>Kurssin tunnus:</label>
		<input type="text" name="course_id" value="<?php echo $course_id; ?>" />
		<label>Kurssin nimi:</label>
		<input type="text" name="course_name" value="<?php echo $course_name; ?>" />
		<label>Kurssin kuvaus:</label>
		<input type="text" name="course_description" value="<?php echo $course_description; ?>" />
		<input type="submit" value="Talleta"/>
	</form>

	
<?php		
   }
   include("db_disconnect_inc.php");
?>