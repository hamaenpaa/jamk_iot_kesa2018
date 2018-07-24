<?php
   	include("db_connect_inc.php");
	
    include("utils/request_param_utils.php");
	include("utils/sql_utils.php");
	include("utils/html_utils.php");

    $seek_room_name = get_post_or_get($conn, "seek_room_name");
	$page = get_post_or_get($conn, "page");
	$seek_params_hidden_inputs = 
		hidden_input("seek_room_name", $seek_room_name) .
		hidden_input("page", $page);
	$seek_params_get = possible_get_param("seek_room_name",$seek_room_name);
	
	if (!isset($page) || $page == "") { $page = 1; }
	
	include("sub/room/seek_rooms_form.php");
	include("sub/room/room_listing_table.php");
	include("sub/room/modify_or_add_room_form.php");

   	include("db_disconnect_inc.php");
?>