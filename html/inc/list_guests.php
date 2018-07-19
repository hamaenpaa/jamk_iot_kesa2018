<?php
   	include("db_connect_inc.php");
    include("utils/request_param_utils.php");
	include("utils/sql_utils.php");

    $seek_first_name = get_post_or_get($conn, "seek_first_name");
	$seek_last_name = get_post_or_get($conn, "seek_last_name");
	$seek_params_hidden_inputs = hidden_input("seek_first_name", $seek_first_name).
	                             hidden_input("seek_last_name", $seek_last_name);

	include("sub/guest/seek_guests_form.php");
	
	include("sub/guest/guest_listing_table.php");
	include("sub/guest/modify_or_add_guest_form.php");	

   	include("db_disconnect_inc.php");
?>