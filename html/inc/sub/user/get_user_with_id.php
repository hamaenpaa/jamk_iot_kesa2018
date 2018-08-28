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
	if (!is_integerable($id) || $id == "" || $id == "0") {
		include("../../db_disconnect_inc.php");
		return;
	}
	
	$user = array();
	if ($id == $_SESSION['user_id'] || $_SESSION['user_permlevel'] == 1) {
		$sql = "SELECT ca_user.username,permission FROM ca_user WHERE id=?";
		$q_user = $conn->prepare($sql);
		$q_user->bind_param("i", $id);
		$q_user->execute();		
		$q_user->store_result();	
		$q_user->bind_result($username,$permission);
		if ($q_user->fetch()) {	
			$user["username"] = $username;
			$user["permission"] = $permission;
		}		
	}
	$user['curr_user_perm'] = $_SESSION['user_permlevel'];

	include("../../db_disconnect_inc.php");
	echo json_encode($user);
?>