<?php
	include("../../utils/request_param_utils.php");
    $seek_first_name = get_post_or_get("seek_first_name");
	$seek_last_name = get_post_or_get("seek_last_name");
	$seek_student_id = get_post_or_get("seek_student_id");
	
	$seek_params_get = possible_get_param("seek_student_id",$seek_student_id);
	$seek_params_get .= possible_get_param("seek_first_name",$seek_first_name, $seek_params_get == "");
	$seek_params_get .= possible_get_param("seek_last_name",$seek_last_name, $seek_params_get == "");

    include("../../db_connect_inc.php");
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

	include("../../db_disconnect_inc.php");
	header("Location: ../../../list_students.php".$seek_params_get);
?>