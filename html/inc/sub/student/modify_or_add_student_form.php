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
		<input type="text" name="student_id" value="<?php echo $student_id; ?>" maxlength="6" required autofocus />
		<label>Etunimi:</label>
		<input type="text" name="student_firstname" value="<?php echo $student_firstname; ?>" maxlength="25" required />
		<label>Sukunimi:</label>
		<input type="text" name="student_lastname" value="<?php echo $student_lastname; ?>" maxlength="25" required />
		<label>Sähköposti:</label>
		<input type="email" name="student_email" value="<?php echo $student_email; ?>" maxlength="255" />
		<label>Puhelin:</label>
		<input type="tel" name="student_phone" value="<?php echo $student_phone; ?>" maxlength="13" />
		<label>NFC ID:</label>
		<input type="text" name="student_nfcid" value="<?php echo $student_nfcid; ?>" maxlength="50" />
<?php echo $seek_params_hidden_inputs; ?>
		<input type="submit" value="Talleta"/>
	</form>
