<!DOCTYPE html>
<html>
	<head>
		<title>IoT Project</title>
		<link rel="stylesheet" type="text/css" href="css/iot.css">
		<?php include("inc/header.php"); ?>
	</head>
	<body>
		<div class="page-wrap">
			<header>
				<div class="banner-wrap">
					<h1>Huoneet</h1>
					<?php include("inc/navigation.php"); ?>
				</div>
				<div class="nav">
				</div>
			</header>
			<div class="content-wrap">
				<?php include("inc/list_nfc_tags.php"); ?>
			</div>
	<?php include "inc/footer.php"; ?>