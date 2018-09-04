<?php
	if (!isset($_SESSION)) {
		session_start();	
	} 
	if (!isset($_SESSION['user_id'])) {
		return;	
	}

    include("../../db_connect_inc.php");
	include("../../utils/request_param_utils.php");
	
	$default_roomidentifier = get_post_or_get($conn, 'default_roomidentifier');
	$usage_type = get_post_or_get($conn, 'usage_type');
	
	echo "default_roomidentifier " . $default_roomidentifier . 
	" usage_type " . $usage_type . "<br>";
	
	if (!is_integerable($usage_type) || $usage_type == "" || $usage_type == "0") {
		include("../../db_disconnect_inc.php");
		header("Location: ../../../index.php?screen=5");
		return;
	}	

	$sql_update_setting = 
		"UPDATE ca_setting SET default_roomidentifier=?, usage_type=? WHERE id=1";
	$q_setting = $conn->prepare($sql_update_setting);
	$q_setting->bind_param("si", $default_roomidentifier, $usage_type);
	$q_setting->execute();	
	
	include("../../db_disconnect_inc.php");
	header("Location: ../../../index.php?screen=5");
?>