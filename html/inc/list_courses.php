<?php
   include("db_connect_inc.php");

   if ($result = $conn->query("SELECT * FROM ca_course")) {
?>
	<div id="course_table">
		<div class="row">
			<div class="col-sm-3"><b>Kurssin tunnus</b></div>
			<div class="col-sm-3"><b>Kurssin nimi</b></div>
			<div class="col-sm-6"><b>Kurssin kuvaus</b></div>
		</div>

<?php   	
		while($res = $result->fetch_assoc()) {
?>
			<div class="row">
				<div class="col-sm-3"><?php echo $res['Course_ID']; ?></div>
				<div class="col-sm-3"><?php echo $res['Course_name']; ?></div>
				<div class="col-sm-6"><?php echo $res['Course_description']; ?></div>
			</div>
<?php		
		}
?>
	</div>
<?php		
   }
   include("db_disconnect_inc.php");
?>