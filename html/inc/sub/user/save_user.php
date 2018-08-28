<?php
	if (!isset($_SESSION)) {
		session_start();	
	} 
	if (!isset($_SESSION['user_id'])) {
		return;	
	} 

    include("../../db_connect_inc.php");
    include("../../utils/request_param_utils.php");

	$id = get_post_or_get($conn, "id");
	$username = get_post_or_get($conn, "username");
	$password = get_post_or_get($conn, "password");
	
	if (!is_integerable($id)) {
		include("../../db_disconnect_inc.php");
		return;
	}
	
	if ($_SESSION['user_permlevel'] == 1) {
		$permission = get_post_or_get($conn, "permission");
	}

	if (!validateUser($username, $password)) {
		include("../../db_disconnect_inc.php");
		return;
	}
	
	if ($id == "0") {
		if ($_SESSION['user_permlevel'] == 1) {
			$q = $conn->prepare("INSERT INTO ca_user (username,password,permission) VALUES (?,SHA2(?, 256),?)");
			$q->bind_param("sss",$username, $password, $permission);
			
		} else {
			$q = $conn->prepare("INSERT INTO ca_user (username,password) VALUES (?,SHA2(?, 256))");
			$q->bind_param("ss",$username, $password);
		}
		$q->execute();
	} else if ($id == $_SESSION['user_id'] || $_SESSION['user_permlevel'] == 1) {
		if ($_SESSION['user_permlevel'] == 1) {
			$q = $conn->prepare("UPDATE ca_user SET username = ?, password=SHA2(?, 256),permission=? WHERE id=?");
			$q->bind_param("sssi",$username, $password, $permission, $id);
		} else {
			$q = $conn->prepare("UPDATE ca_user SET username = ?, password=SHA2(?, 256) WHERE id=?");
			$q->bind_param("ssi",$username, $password, $id);
		}
		$q->execute();
	}
	include("../../db_disconnect_inc.php");
	
	
	function validateUser($username, $password) {
		$passed = true;
		if (strlen($username) < 3 || strlen($username) > 65) {
			$passed = false;
		}
		if (strlen($password) < 5 || strlen($password) > 50) {
			$passed = false;
		}		
		return $passed;
	}
?>