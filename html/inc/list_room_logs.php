<?php
	include("utils/sql_utils.php");
	include("utils/html_utils.php");
	include("utils/date_utils.php");
	
	include("utils/topic_selection.php");
	include("inc/sub/setting/fetch_settings_from_db.php");
	$settings = getSettings($conn);
	include("inc/sub/room_log/room_log_seek_form.php");
	include("inc/sub/room_log/room_log_listing_table.php"); 
?>