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
		<?php include("inc/header.php"); ?>
		<link rel="stylesheet" type="text/css" href="libs/dtp/jquery.datetimepicker.min.css"/ >
		<script src="libs/dtp/jquery.datetimepicker.full.min.js"></script>
	</head>
	<body>
		<div class="page-wrap">
			<header>
				<div class="banner-wrap">
					<h1>Oppitunnit</h1>
					<?php include "inc/navigation.php"; ?>
					<!-- datetimepicker configuration -->
					<script src="js/datepicker.js"></script>
				</div>
				<div class="nav">
				</div>
			</header>
			<div class="content-wrap">
			<style>
			form { width:auto; }
			
			</style>
			
			<?php
			//Kurssia ei ole haettu -> Listaa kurssit 

			//Kurssi postattu -> Hakuväliformi
	
			
			include "inc/sub/lesson/coursename_fetch.php";
			
			
			include "inc/sub/lesson/lesson_add_form.html";
			
			
			include "inc/db_disconnect_inc.php";
				
			?>	
			</div>
			<footer>
				<div class="footer-wrap">
					<div class="footer-links">
						<p>Linkkejä</p>
					</div>
					<div class="footer-contacts">
						<p>&#169; IoT projekti kesä 2018</p>
						<p>JAMKin logo</p>
						<p>Yhteystietoja yms.</p>
					</div>
				</div>
			</footer>
		</div>
	</body>
</html>
<?php
//Staff id exist end
}
?>