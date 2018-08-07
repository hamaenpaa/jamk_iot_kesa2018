<!DOCTYPE html>
<html>
    <?php include("inc/db_connect_inc.php"); ?>
	<head>
		<title>IoT Project</title>
		<?php include "inc/header.php"; ?>
		<script>
		<?php 
		if (isset($_GET['rid'])) {
		?>
		$(function() {
			/* Configuration */
			interval = 2; //Aika sekunneissa per refresh
			time = 10; //Shows userdata last 10 seconds
			
			/* Do not touch */
			refreshRate = interval * 1000; 
			<?php echo "rid = " . intval($_GET['rid']) . ";"; ?>
			lque = 0;
			function getRoomData(room,time) {
				$.get("fetch_roomData.php?rid="+rid+"&interval="+time, function(data) {
				ndata = data.split("->");
					/*
					console.log("Data: " + ndata[0]);
					console.log("Datx: " + "<h2>Ole hyvä ja lue korttisi lukijassa</h2>");
					console.log("Daty: " + $(".content-wrap").html());
					console.log("Datr:" + ndata[1]);
					*/
				
					if(data[1] != '<h2>Ole hyvä ja lue korttisi lukijassa</h2>' && $(".content-wrap").html() != ndata[1] && lque != ndata[1]) {
					/*
					console.log("lque:"+ lque);
					console.log("ndat:"+ ndata[1]);
					*/
					if (ndata[1] != '') {
					lque = ndata[1];
					}
					$(".content-wrap").html(ndata[0]);	
					}
				});
			}
				
			
		setInterval(function() {
		getRoomData(rid, time);
		}, refreshRate);
			
		getRoomData(rid,time);	
		});
		<?php
		}
		?>
		</script>
	</head>
	<body>
		<div class="page-wrap">
			<header>
				<div class="banner-wrap">
					<h1>Viimeisimmät Kirjautumiset</h1>
				</div>
				<div class="nav">
				</div>
			</header>
				<div class="content-wrap">
					<h2>JavaScript vaaditaan jotta sivusto näkyy oikein.</h2>
				</div>
	<?php include "inc/footer.php"; ?>