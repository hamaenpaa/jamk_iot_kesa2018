<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
header("Location: index.php");
} else {
?>
<!DOCTYPE html>
<html>
	<head>
		<title>IoT Project</title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" type="text/css" href="css/iot.css">
		<?php include("bootstrap_includes.php"); ?>
		<link rel="stylesheet" type="text/css" href="libs/dtp/jquery.datetimepicker.min.css"/ >
		<script src="libs/dtp/jquery.datetimepicker.full.min.js"></script>
	</head>
	<body>
		<div class="page-wrap">
			<header>
				<div class="banner-wrap">
					<p>Oppitunnit</p>
					<?php include "inc/navigation.php"; ?>
					<!-- datetimepicker configuration -->
					<script src="js/datepicker.js"></script>
				</div>
				<div class="nav">
				</div>
			</header>
			<div class="content-wrap">
				
			<?php
			//Kurssia ei ole haettu -> Listaa kurssit
			if (!isset($_POST['course_fetch'])) {
			include "inc/sub/lesson/course_fetch.php";
			} else {
			//Listaa takaisinpaluun jos kursseja on haettu
			echo "<a href='list_lessons.php'>Palaa</a><br>";	
			}

			//Kurssi postattu -> Hakuväliformi
			if (isset($_POST['course_fetch'])) {
			
			include "inc/sub/lesson/coursename_fetch.php";
			
			
			
			
			}
				
			?>	
			</div>
			<footer>
				<div class="footer-wrap">
					<p>&#169; IoT projekti kesä 2018</p>
				</div>
			</footer>
		</div>		
	</body>
</html>
<?php
//Staff id exist end
}
?>