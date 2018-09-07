<?php
	define("PAGE_SIZE", 2);

    include("../db_connect_inc.php");	
	include("../utils/request_param_utils.php");
	include("../utils/sql_utils.php");

	list($page_size, $page_page_size) =
		get_page_and_page_page_sizes($conn);
	
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
			"page" => intdiv($i, $page_size)
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