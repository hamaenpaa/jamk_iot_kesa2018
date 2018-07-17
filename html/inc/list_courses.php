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
		} // while($res = $result->fetch_assoc()
?>
	</div>
	
<?php
    $course_id = "";
	$course_name = "";
	$course_description = "";
	$id = "";
	if (isset($_POST['id'])) {
		$id = $_POST['id'];
	}
	else if (isset($_GET['id'])) {
		$id = $_GET['id'];
	}
	if ($id != "") {
		$q = $conn->prepare("SELECT course_id,course_name,course_description FROM ca_course WHERE ID=?");
		if ($q) {
			$q->bind_param("s", $_POST['id']);
			$q->execute();
			$q->bind_result($course_id,$course_name,$course_description);
			$q->fetch();
			$q->close();
		}
	} // if (isset($_POST['id'])) {
		 
?>	
	
	<form method="post" action="inc/save_course.php">
		<input type="hidden" name="id" value="<?php echo $id; ?>" />
		<label>Kurssin tunnus:</label>
		<input type="text" name="course_id" value="<?php echo $course_id; ?>" />
		<label>Kurssin nimi:</label>
		<input type="text" name="course_name" value="<?php echo $course_name; ?>" />
		<label>Kurssin kuvaus:</label>
		<textarea name="course_description" rows="7" cols="50"><?php echo $course_description; ?></textarea>
		<input type="submit" value="Talleta"/>	
	</form>
	
<?php if ($id != "") { 
	include("inc/list_course_teachers.php");
	if (!isset($_POST['add_teacher']) && !isset($_GET['add_teacher']) && 
	    !isset($_POST['add_student']) && !isset($_GET['add_student'])) {
?>
	<form method="POST" action="list_courses.php">
		<input type="hidden" name="id" value="<?php echo $id; ?>" />
	    <input type="hidden" name="add_teacher" value="1" />
		<input type="submit" value="Lisää opettaja"/>
	</form>
<?php
	}	
	include("inc/add_teacher_to_course_ui.php"); 
	
	include("inc/list_course_students.php");
	if (!isset($_POST['add_teacher']) && !isset($_GET['add_teacher']) && 
	    !isset($_POST['add_student']) && !isset($_GET['add_student'])) {
?>
	<form method="POST" action="list_courses.php">
		<input type="hidden" name="id" value="<?php echo $id; ?>" />
	    <input type="hidden" name="add_student" value="1" />
		<input type="submit" value="Lisää oppilas"/>
	</form>
<?php
	}	
	include("inc/add_student_to_course_ui.php"); 
	
   }
   }
   include("db_disconnect_inc.php");
?>