<?php
	define("DB_HOST", "localhost");
	define("DB_USER", "root");
	define("DB_PASS", "");
	define("DB_DATABASE", "ca");
   
	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
	mysqli_select_db($conn, DB_DATABASE);
   
	$conn->query('set character_set_client=utf8');
	$conn->query('set character_set_connection=utf8');
	$conn->query('set character_set_results=utf8');
	$conn->query('set character_set_server=utf8');
?>