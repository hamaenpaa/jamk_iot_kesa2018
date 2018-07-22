<?php
   	include("db_connect_inc.php");
    include("utils/request_param_utils.php");
	include("utils/sql_utils.php");

    $seek_nfc_id = get_post_or_get($conn, "seek_nfc_id");
	$seek_include_active = get_post_or_get($conn, "seek_include_active");
	$seek_include_passive = get_post_or_get($conn, "seek_include_passive");
	
	if ($seek_include_active == "" && $seek_include_passive == "") {
		$seek_include_active = "on";
	}
	
	$seek_params_hidden_inputs = hidden_input("seek_nfc_id", $seek_nfc_id).
	                             hidden_input("seek_include_active", $seek_include_active).
								 hidden_input("seek_include_passive", $seek_include_passive);

	include("sub/nfc_tags/seek_nfc_tags_form.php");
	
	include("sub/nfc_tags/nfc_tag_listing_table.php");
	include("sub/nfc_tags/modify_or_add_nfc_tag_form.php");		

    include("db_disconnect_inc.php");
?>