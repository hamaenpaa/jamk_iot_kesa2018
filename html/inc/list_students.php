<?php
   include("db_connect_inc.php");

   if ($result = $conn->query("SELECT * FROM ca_student")) {
?>
	<div id="student_table">
		<div class="row">
			<div class="col-sm-2"><b>Etunimi</b></div>
			<div class="col-sm-2"><b>Sukunimi</b></div>
			<div class="col-sm-3"><b>Sähköposti</b></div>
			<div class="col-sm-2"><b>Puhelin</b></div>
			<div class="col-sm-1"><b>Oppilas ID</b></div>
			<div class="col-sm-2"><b>NFC ID</b></div>
			
		</div>

<?php   	
		while($res = $result->fetch_assoc()) {
?>
			<div class="row">
				<div class="col-sm-2"><?php echo $res['FirstName']; ?></div>
				<div class="col-sm-2"><?php echo $res['LastName']; ?></div>
				<div class="col-sm-3"><?php echo $res['Email']; ?></div>
				<div class="col-sm-2"><?php echo $res['PhoneNumber']; ?></div>
				<div class="col-sm-1"><?php echo $res['Student_ID']; ?></div>
				<div class="col-sm-2"><?php echo $res['NFC_ID']; ?></div>
			</div>
<?php		
		}
?>
	</div>
<?php		
   }
   include("db_disconnect_inc.php");
?>