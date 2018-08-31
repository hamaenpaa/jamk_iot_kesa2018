<?php
	define("PAGE_SIZE", 2);

	if (!isset($_SESSION)) {
		session_start();	
	} 
	if (!isset($_SESSION['user_id'])) {
		return;	
	}
	
    include("../db_connect_inc.php");	
	include("../utils/request_param_utils.php");
	
	$topics_seek = get_post_or_get($conn, "topics_seek");
	
	$sql_seeked = 
		"SELECT ca_topic.id, ca_topic.name FROM ca_topic
	        WHERE ca_topic.name LIKE '%". $topics_seek . "%' 
			AND ca_topic.removed = 0 ORDER BY name ASC";
	$sql_all =
		"SELECT ca_topic.id, ca_topic.name FROM ca_topic
	        WHERE ca_topic.removed = 0 ORDER BY name ASC";	

	$q = $conn->prepare($sql_seeked);
	$q->execute();	
	$q->store_result();
	$q->bind_result($topic_id, $name);
	$seeked_topics = array();
	$i = 1;
	while($q->fetch()) {
		$seeked_topics[] = array(
			"id" => $topic_id,
			"name" => $name,
			"page" => intdiv($i, PAGE_SIZE)
		);
		$i++;
	}
	
	$q_all = $conn->prepare($sql_all);
	$q_all->execute();	
	$q_all->store_result();
	$q_all->bind_result($topic_id, $name);
	$allTopics = array();
	while($q_all->fetch()) {
		$allTopics[] = array(
			"id" => $topic_id,
			"name" => $name
		);
	}	

	$topics_arr = array(
		"allTopics" => $allTopics, 
		"seekedTopics" => $seeked_topics);
	echo json_encode($topics_arr);
	include("../db_disconnect_inc.php");
?>