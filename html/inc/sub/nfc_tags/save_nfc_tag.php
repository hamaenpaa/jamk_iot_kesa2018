<?php
    include("../../db_connect_inc.php");
	include("../../utils/request_param_utils.php");
	
	$id = "";
	if (isset($_POST['id'])) {
		$id = $_POST['id'];
	}
	$NFC_ID = strip_tags($_POST['NFC_ID']);
	$active = "";
	if (isset($_POST['active']))
		$active = "1";	
	
	$seek_nfc_id = get_post_or_get($conn, "seek_nfc_id");
	$seek_include_active = get_post_or_get($conn, "seek_include_active");	
	$seek_include_passive = get_post_or_get($conn, "seek_include_passive");
	
	$seek_params_get = possible_get_param("seek_nfc_id",$seek_nfc_id);
	$seek_params_get .= possible_get_param("seek_include_active",$seek_include_active, $seek_params_get == "");
	$seek_params_get .= possible_get_param("seek_include_passive",$seek_include_passive, $seek_params_get == "");

	if ($strlen($NFC_ID) > 50) {
		header("Location: ../../../list_nfc_tags.php".$seek_params_get);
		exit;
	}	
	if ($strlen($seek_nfc_id) > 50) {
		header("Location: ../../../list_nfc_tags.php".$seek_params_get);
		exit;
	}	
	if ($strlen($seek_include_active) > 1) {
		header("Location: ../../../list_nfc_tags.php".$seek_params_get);
		exit;
	}
	if ($strlen($seek_include_passive) > 1) {
		header("Location: ../../../list_nfc_tags.php".$seek_params_get);
		exit;
	}
	
	if ($id != "") {
		$q = $conn->prepare("UPDATE ca_nfc_tag SET NFC_ID = ?,active=? WHERE ID = ?");
		if ($q) {
			$q->bind_param("ssi", $NFC_ID, $active, $id);
			$q->execute();
		}
	} else {
		$q = $conn->prepare("INSERT INTO ca_nfc_tag (NFC_ID, active) VALUES (?,?)");
		if ($q) {
			$q->bind_param("ss", $NFC_ID, $active);
			$q->execute();
		}		
	}

	include("../../db_disconnect_inc.php");
	
	header("Location: ../../../list_nfc_tags.php".$seek_params_get);
?>