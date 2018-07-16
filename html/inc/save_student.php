<?php
    echo "Heredsdasdas";

    include("db_connect_inc.php");
	$id = "";
	if (isset($_POST['id'])) {
		$id = $_POST['id'];
	}
	$student_id = $_POST['student_id'];
	$student_firstname = $_POST['student_firstname'];
	$student_lastname = $_POST['student_lastname'];
	$student_email = $_POST['student_email'];
	$student_phone = $_POST['student_phone'];
	$student_nfcid = $_POST['student_nfcid'];
	
	
	if ($id != "") {
		$q = $conn->prepare("UPDATE ca_student SET student_ID = ?, FirstName = ?, LastName = ?, Email=?, PhoneNumber=?, NFC_ID=? WHERE ID = ?");
		if ($q) {
			$q->bind_param("ssssssi", $student_id, $student_firstname, $student_lastname, $student_email, $student_phone, $student_nfcid, $id);
			$q->execute();
		}
	} else {
		$q = $conn->prepare("INSERT INTO ca_student (student_ID,FirstName,LastName,Email,PhoneNumber,NFC_ID) VALUES (?,?,?,?,?,?)");
		if ($q) {
			$q->bind_param("ssssss", $student_id, $student_firstname, $student_lastname, $student_email, $student_phone, $student_nfcid);
			$q->execute();
		}		
	}

	include("db_disconnect_inc.php");
	
	header("Location: ../list_students.php");
?>