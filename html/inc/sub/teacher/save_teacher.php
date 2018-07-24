<?php
	include("../../utils/request_param_utils.php");
    include("../../db_connect_inc.php");
	$id = "";
	if (isset($_POST['id'])) {
		$id = $_POST['id'];
	}
	
    $seek_first_name = get_post_or_get($conn, "seek_first_name");
	$seek_last_name = get_post_or_get($conn, "seek_last_name");
	if (isset($_POST['page'])) {
		$page = strip_tags($_POST['page']);
	} else {
		$page = 1;
	}		
	
	$seek_params_get = possible_get_param("seek_first_name",$seek_first_name);
	$seek_params_get .= possible_get_param("seek_last_name",$seek_last_name, $seek_params_get == "");	
	$seek_params_get .= possible_get_param("page", $page, $seek_params_get == "");
	
	$teacher_firstname = strip_tags($_POST['teacher_firstname']);
	$teacher_lastname = strip_tags($_POST['teacher_lastname']);
	$teacher_email = strip_tags($_POST['teacher_email']);
	$teacher_phone = strip_tags($_POST['teacher_phone']);
	$teacher_password = $_POST['teacher_password'];
	$teacher_confirm_password = $_POST['teacher_password_confirm'];	
	
	if (strlen($teacher_firstname) > 25 || strlen($teacher_lastname) > 25 || 
		strlen($teacher_email) > 255 || strlen($teacher_phone) > 13 || 
		strlen($seek_first_name) > 25 || strlen($seek_last_name) > 25 ||
		strlen($teacher_password) > 65) {
		header("Location: ../../../list_teachers.php".$seek_params_get);
		exit;
	}	
	
	$set_password = "";
	if (isset($_POST['set_password']))
		$set_password = "1";
		
	if ($set_password == "1" && $teacher_password != $teacher_confirm_password) {
		include("db_disconnect_inc.php");
		header("Location: ../list_teachers.php".$seek_params_get);	
	   	exit;	
	}
	
	if ($id != "") {
		if ($set_password == "1") {
			$q = $conn->prepare("UPDATE ca_staff SET FirstName = ?, LastName = ?, Email=?, PhoneNumber=?, Password=SHA2(?, 256) WHERE ID = ?");
			if ($q) {
				$q->bind_param("sssssi", $teacher_firstname, $teacher_lastname, $teacher_email, $teacher_phone, $teacher_password, $id);
				$q->execute();
			}			
		}
		else {
			$q = $conn->prepare("UPDATE ca_staff SET FirstName = ?, LastName = ?, Email=?, PhoneNumber=? WHERE ID = ?");
			if ($q) {
				$q->bind_param("ssssi", $teacher_firstname, $teacher_lastname, $teacher_email, $teacher_phone, $id);
				$q->execute();
			}
		}
	} else {
		$q = $conn->prepare("INSERT INTO ca_staff (FirstName,LastName,Email,PhoneNumber,Password,Active) VALUES (?,?,?,?,SHA2(?, 256),0)");
		if ($q) {
			$q->bind_param("sssss", $teacher_firstname, $teacher_lastname, $teacher_email, $teacher_phone, $teacher_password);
			$q->execute();
		}		
	}

	include("../../db_disconnect_inc.php");
	
	header("Location: ../../../list_teachers.php" . $seek_params_get);
?>