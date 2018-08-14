<?php 
	include("inc/db_connect_inc.php"); 
	include("inc/utils/request_param_utils.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php include("inc/header.php"); ?>
		<?php include("inc/datepicker.php"); ?>
		<?php echo '<script src="' . $cfg_rootdir . 'js/utils.js" /></script>' ?>
		<?php 
			$screen = get_post_or_get($conn, "screen");
			if (!isset($screen) || $screen == "" || $screen == "0") {
				echo '<script src="' . $cfg_rootdir . 'js/room_log.js" /></script>'.
					'<title>Sisäänkirjaukset</title>';
			}  else {
				echo '<script src="' . $cfg_rootdir . 'js/lessons.js" /></script>'.
					'<title>Oppitunnit/koulutukset</title>';
					
			}
		?>
	</head>
	<
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
				<?php include("inc/". $inc_module . ".php"); ?>
			</div>
			<footer>
				<div class="footer-wrap">
					<div class="footer-links">
					</div>
					<div class="footer-contacts">
					</div>
				</div>
			</footer>
		</div>
	</body>
</html>
<?php include("inc/db_disconnect_inc.php"); ?>