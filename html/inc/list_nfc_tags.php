<?php
   	include("db_connect_inc.php");
    include("utils/request_param_utils.php");
	include("utils/sql_utils.php");
	include("utils/html_utils.php");

    $seek_nfc_id = get_post_or_get($conn, "seek_nfc_id");
	$seek_include_active = get_post_or_get($conn, "seek_include_active");
	$seek_include_passive = get_post_or_get($conn, "seek_include_passive");
	$page = get_post_or_get($conn, "page");
	if (!isset($page) || $page == "") { $page = 1; }	
	
	if ($seek_include_active == "" && $seek_include_passive == "") {
		$seek_include_active = "on";
	}
	
	$seek_params_hidden_inputs = hidden_input("seek_nfc_id", $seek_nfc_id).
	                             hidden_input("seek_include_active", $seek_include_active).
								 hidden_input("seek_include_passive", $seek_include_passive).
								 hidden_input("page", $page);
	
	$seek_params_get = possible_get_param("seek_nfc_id",$seek_nfc_id);
	$first = true;
	if ($seek_nfc_id != "") { $first = false; }
	$seek_params_get .= possible_get_param("seek_include_active",$seek_include_active, $first);								 
	if ($seek_include_active != "") { $first = false; }
	$seek_params_get .= possible_get_param("seek_include_passive",$seek_include_passive, $first);	
	
	include("sub/nfc_tags/seek_nfc_tags_form.php");
	
	include("sub/nfc_tags/nfc_tag_listing_table.php");
	include("sub/nfc_tags/modify_or_add_nfc_tag_form.php");		

    include("db_disconnect_inc.php");
?>