<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php include("inc/header.php"); ?>
		<?php include("inc/datepicker.php"); ?>
		<?php echo '<script src="' . $cfg_rootdir . 'js/room_log.js" /></script>' ?>
	</head>
	<title>IoT Project</title>
	<body>
		<div class="page-wrap">
			<header>
				<div class="banner-wrap">
					<h1>Huoneen sisäänkirjautumiset</h1>
					<?php include("inc/navigation.php"); ?>
				</div>
				<div class="nav">
				</div>
			</header>
			<div class="content-wrap">
				<?php include("inc/list_room_logs.php"); ?>
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