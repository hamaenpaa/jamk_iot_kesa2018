<?php
   include("db_connect_inc.php");

   if ($result = $conn->query("SELECT * FROM ca_student")) {
?>
	<div id="student_table">
		<div class="row">
			<div class="col-sm-2"><b>Etunimi</b></div>
			<div class="col-sm-2"><b>Sukunimi</b></div>
			<div class="col-sm-2"><b>Sähköposti</b></div>
			<div class="col-sm-2"><b>Puhelin</b></div>
			<div class="col-sm-2"><b>Oppilas ID</b></div>
			<div class="col-sm-1"><b>NFC ID</b></div>
			<div class="col-sm-1"><b>Muokkaa</b></div>
		</div>

<?php   	
		while($res = $result->fetch_assoc()) {
?>
			<div class="row">
				<div class="col-sm-2"><?php echo $res['FirstName']; ?></div>
				<div class="col-sm-2"><?php echo $res['LastName']; ?></div>
				<div class="col-sm-2"><?php echo $res['Email']; ?></div>
				<div class="col-sm-2"><?php echo $res['PhoneNumber']; ?></div>
				<div class="col-sm-2"><?php echo $res['Student_ID']; ?></div>
				<div class="col-sm-1"><?php echo $res['NFC_ID']; ?></div>
				<div class="col-sm-1">
					<form method="post" action="list_students.php">
					<input type="hidden" name="id" value="<?php echo $res['ID']; ?>"/>
					<input type="submit" value="Muokkaa" />
					</form>
				</div>				
			</div>
<?php		
		}
?>
	</div>
	
<?php
    $student_id = "";
	$student_firstname = "";
	$student_lastname = "";
	$student_email = "";
	$student_phone = "";
	$student_nfcid = "";
	$id = "";
	if (isset($_POST['id'])) {
		$id = $_POST['id'];
		$q = $conn->prepare("SELECT student_ID,FirstName,LastName,Email,PhoneNumber,NFC_ID FROM ca_student WHERE ID=?");
		if ($q) {
			$q->bind_param("s", $_POST['id']);
			$q->execute();
			$q->bind_result($student_id,$student_firstname,$student_lastname,$student_email,$student_phone,$student_nfcid);
			$q->fetch();
			$q->close();
		}
	}
		 
?>	
	
	<form method="post" action="inc/save_student.php">
		<input type="hidden" name="id" value="<?php echo $id; ?>" />
		<label>Tunnus:</label>
		<input type="text" name="student_id" value="<?php echo $student_id; ?>" />
		<label>Etunimi:</label>
		<input type="text" name="student_firstname" value="<?php echo $student_firstname; ?>" />
		<label>Sukunimi:</label>
		<input type="text" name="student_lastname" value="<?php echo $student_lastname; ?>" />
		<label>Sähköposti:</label>
		<input type="text" name="student_email" value="<?php echo $student_email; ?>" />
		<label>Puhelin:</label>
		<input type="text" name="student_phone" value="<?php echo $student_phone; ?>" />
		<label>NFC ID:</label>
		<input type="text" name="student_nfcid" value="<?php echo $student_nfcid; ?>" />
		<input type="submit" value="Talleta"/>
	</form>
	
	
<?php		
   }
   include("db_disconnect_inc.php");
?>