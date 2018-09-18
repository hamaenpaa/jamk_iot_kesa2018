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
	$ret_val = "{}";
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
			if ($permission == 0) {
				$sql_check_last_admin = "SELECT COUNT(*) FROM ca_user WHERE permission = 1 AND id <> ?";
				$q_check = $conn->prepare($sql_check_last_admin);
				$q_check->bind_param("i", $id);
				$q_check->execute();		
				$q_check->store_result();
				$q_check->bind_result($c);
				$q_check->fetch();
				if ($c == 0) {
					$ret_val = "{'last_admin_error':1}";
				}
			}
			if ($ret_val == "{}") {
				$q = $conn->prepare("UPDATE ca_user SET username = ?, password=SHA2(?, 256),permission=? WHERE id=?");
				$q->bind_param("sssi",$username, $password, $permission, $id);
				$q->execute();
			}
		} else {
			$q = $conn->prepare("UPDATE ca_user SET username = ?, password=SHA2(?, 256) WHERE id=?");
			$q->bind_param("ssi",$username, $password, $id);
			$q->execute();
		}
	}
	include("../../db_disconnect_inc.php");
	echo $ret_val;
	
	function validateUser($username, $password) {
		$passed = true;
		if (mb_strlen($username) < 3 || mb_strlen($username) > 65) {
			$passed = false;
		}
		if (mb_strlen($password) < 5 || mb_strlen($password) > 50) {
			$passed = false;
		}		
		return $passed;
	}
?>