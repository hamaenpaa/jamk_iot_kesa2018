<?php
    include("db_connect_inc.php");
	$id = "";
	if (isset($_POST['id'])) {
		$id = $_POST['id'];
	}
	$teacher_firstname = $_POST['teacher_firstname'];
	$teacher_lastname = $_POST['teacher_lastname'];
	$teacher_email = $_POST['teacher_email'];
	$teacher_phone = $_POST['teacher_phone'];
	$teacher_password = $_POST['teacher_password'];
	$teacher_confirm_password = $_POST['teacher_password_confirm'];	
	$set_password = "";
	if (isset($_POST['set_password']))
		$set_password = "1";
		
	if ($set_password == "1" && $teacher_password != $teacher_confirm_password) {
		include("db_disconnect_inc.php");
		header("Location: ../list_teachers.php");	
	   	exit;	
	}
	
	if ($id != "") {
		if ($set_password == "1") {
			echo "set password";
			$q = $conn->prepare("UPDATE ca_staff SET FirstName = ?, LastName = ?, Email=?, PhoneNumber=?, Password=SHA2(?, 256) WHERE ID = ?");
			if ($q) {
				$q->bind_param("sssssi", $teacher_firstname, $teacher_lastname, $teacher_email, $teacher_phone, $teacher_password, $id);
				$q->execute();
			}			
		}
		else {
			echo "not set password";
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

	include("db_disconnect_inc.php");
	
	header("Location: ../list_teachers.php");
?>