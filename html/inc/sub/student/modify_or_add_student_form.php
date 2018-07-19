<?php
    $student_id = "";
	$student_firstname = "";
	$student_lastname = "";
	$student_email = "";
	$student_phone = "";
	$student_nfcid = "";
	$id = "";
	if (isset($_POST['id'])) {
?>
		<h2>Muokkaa oppilasta</h2>		
<?php	
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
	else {
?>
	<h2>Lisää oppilas</h2>
<?php
	}
?>

	<form method="post" action="inc/sub/student/save_student.php">
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
<?php echo $seek_params_hidden_inputs; ?>
		<input type="submit" value="Talleta"/>
	</form>
