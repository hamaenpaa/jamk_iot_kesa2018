<?php
    include("../../db_connect_inc.php");
	include("../../utils/request_param_utils.php");
    $seek_first_name = get_post_or_get($conn, "seek_first_name");
	$seek_last_name = get_post_or_get($conn, "seek_last_name");
	$seek_student_id = get_post_or_get($conn, "seek_student_id");
	
	$seek_params_get = possible_get_param("seek_student_id",$seek_student_id);
	$seek_params_get .= possible_get_param("seek_first_name",$seek_first_name, $seek_params_get == "");
	$seek_params_get .= possible_get_param("seek_last_name",$seek_last_name, $seek_params_get == "");
	
	$id = "";
	if (isset($_POST['id'])) {
		$id = strip_tags($_POST['id']);
	}
	$student_id = strip_tags($_POST['student_id']);
	$student_firstname = strip_tags($_POST['student_firstname']);
	$student_lastname = strip_tags($_POST['student_lastname']);
	$student_email = strip_tags($_POST['student_email']);
	$student_phone = strip_tags($_POST['student_phone']);
	$student_nfcid = strip_tags($_POST['student_nfcid']);

	if (strlen($student_id) > 6 || strlen($student_firstname) > 25 ||
		strlen($student_lastname) > 25 || strlen($student_email) > 255 ||
		strlen($student_phone) > 13 || strlen($student_nfcid) > 50 || 
		strlen($seek_first_name) > 25 || strlen($seek_last_name) > 25 ||
		strlen($seek_student_id) > 6) {
		header("Location: ../../../list_students.php".$seek_params_get);
		exit;
	}
	
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