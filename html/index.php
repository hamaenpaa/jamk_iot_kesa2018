<!DOCTYPE html>
<html>
    <?php include("inc/db_connect_inc.php"); ?>
	<head>
		<link rel="stylesheet" type="text/css" href="css/iot.css">
		<title>IoT Project</title>
	</head>
	<body>
		<div class="page-wrap">
			<header>
				<div class="banner-wrap">
					<p>Hienon hieno sisäänkirjautuminen</p>
				</div>
				<div class="nav">
				</div>
			</header>
				<div class="content-wrap">
					<div id="input-form">
						<form>
							<div id="username-cell">
								<p>Käyttäjätunnus</p>
								<input type="text" name="username" id="username">
							</div>
							<div id="password-cell">
								<p>Salasana</p>
								<input type="password" name="password" id="password">
							</div>
							<div id="button-wrap">
								<button id="sign-in">Sign In</button>
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