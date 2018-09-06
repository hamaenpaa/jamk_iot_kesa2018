<?php
    include("../db_connect_inc.php");	

	$sql = "SELECT page_size, page_page_size FROM ca_setting WHERE id=1";

	$q = $conn->prepare($sql);
	$q->execute();	
	$q->store_result();
	$q->bind_result($page_size, $page_page_size);
	$q->fetch();

	include("../db_disconnect_inc.php");
	echo json_encode(array("page_size" => $page_size, 
		"page_page_size" => $page_page_size));
?>