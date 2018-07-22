<?php
    include("../../db_connect_inc.php");
	include("../../utils/request_param_utils.php");
	
	$id = get_post_or_get($conn, "id");
 	
	$seek_nfc_id = get_post_or_get($conn, "seek_nfc_id");
	$seek_include_active = get_post_or_get($conn, "seek_include_active");	
	$seek_include_passive = get_post_or_get($conn, "seek_include_passive");
	$seek_params_get = possible_get_param("seek_nfc_id",$seek_nfc_id);
	$seek_params_get .= possible_get_param("seek_include_active",$seek_include_active, $seek_params_get == "");
	$seek_params_get .= possible_get_param("seek_include_passive",$seek_include_passive, $seek_params_get == "");

	$q = $conn->prepare("UPDATE ca_nfc_tag SET removed = 1 WHERE ID = ?");
	if ($q) {
		$q->bind_param("i", $id);
		$q->execute();
	}

	include("../../db_disconnect_inc.php");
	header("Location: ../../../list_nfc_tags.php".$seek_params_get);
?>