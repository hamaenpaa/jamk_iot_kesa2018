<?php
   	include("db_connect_inc.php");
	
    include("utils/request_param_utils.php");
	include("utils/sql_utils.php");

    $seek_room_name = get_post_or_get($conn, "seek_room_name");
	$seek_params_hidden_inputs = hidden_input("room_name", $seek_room_name);

	include("sub/room/seek_rooms_form.php");
	include("sub/room/room_listing_table.php");
	include("sub/room/modify_or_add_room_form.php");

   	include("db_disconnect_inc.php");
?>