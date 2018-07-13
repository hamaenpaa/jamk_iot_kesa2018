<?php
   include("db_connect_inc.php");

   if ($result = $conn->query("SELECT * FROM ca_staff WHERE Permission = 0 AND Active = 1")) {
?>
	<div id="active_staff_table">
		<div class="row">
			<div class="col-sm-3"><b>Etunimi</b></div>
			<div class="col-sm-3"><b>Sukunimi</b></div>
			<div class="col-sm-3"><b>Sähköposti</b></div>
			<div class="col-sm-3"><b>Puhelin</b></div>
		</div>

<?php   	
		while($res = $result->fetch_assoc()) {
?>
			<div class="row">
				<div class="col-sm-3"><?php echo $res['FirstName']; ?></div>
				<div class="col-sm-3"><?php echo $res['LastName']; ?></div>
				<div class="col-sm-3"><?php echo $res['Email']; ?></div>
				<div class="col-sm-3"><?php echo $res['PhoneNumber']; ?></div>
			</div>
<?php		
		}
?>
	</div>
<?php		
   }
   include("db_disconnect_inc.php");
?>