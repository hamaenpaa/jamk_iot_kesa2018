<!DOCTYPE html>
<html>
    <?php include("inc/db_connect_inc.php"); ?>
	<head>
	<title>IoT Project</title>
	<?php include "inc/header.php"; ?>
	
	<script src="js/login_validate.js"></script>
	</head>
	<body>
		<div class="page-wrap">
			<header>
				<div class="banner-wrap">
					<p>Hienon hieno sisäänkirjautuminen</p><?php include "inc/navigation.php"; ?>
				</div>
				<div class="nav">
				</div>
			</header>
				<div class="content-wrap">
					<div id="input-form">
						<form action="login_confirm.php" method="post">
							<div id="username-cell">
								<p>Käyttäjätunnus</p>
								<input type="text" name="username" id="username">
							</div>
							<div id="password-cell">
								<p>Salasana</p>
								<input type="password" name="password" id="password">
							</div>
							<div id="button-wrap">
								<input type="submit" id="sign-in" value="Sign In">
							</div>
						</form>
					</div>
				</div>
			<footer>
				<div class="footer-wrap">
					<p>&#169; IoT projekti kesä 2018</p>
				</div>
			</footer>
		</div>
	</body>
</html>