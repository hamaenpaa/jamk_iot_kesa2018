<?php
	define("PAGE_SIZE", 50);

	function get_topics($conn, $name_seek, $page) {
		if (!is_integerable($page) || $page == "" || $page == "0") {
			return array();	
		}
		$name_seek = purifyParam($conn, $name_seek);
		if (strlen($name_seek) > 150) {
			return array();	
		}
		
		$total_fields = "ca_topic.ID, ca_topic.name ";
		$sql_end_without_page_def = "FROM ca_topic WHERE 
			  ca_topic.name LIKE '%" .$name_seek ."%'
			  AND ca_topic.removed = 0 
			  ORDER BY name ASC";
		$sql_topics = 
			"SELECT " .  $total_fields . $sql_end_without_page_def .
			 " LIMIT " . (($page - 1) * PAGE_SIZE) . "," . PAGE_SIZE;

		$q_topics = $conn->prepare($sql_topics);
		$q_topics->execute();		
		$q_topics->store_result();
		$q_topics->bind_result($topic_id, $name);		

		$sql_topics_count = "SELECT COUNT(*) " . $sql_end_without_page_def;
		
		$q_topics_count = $conn->prepare($sql_topics_count);
		$q_topics_count->execute();		
		$q_topics_count->store_result();
		$q_topics_count->bind_result($count);
		$q_topics_count->fetch();
		
		$topics = array();
		if ($q_topics->num_rows > 0) {
			while($q_topics->fetch()) {
				$topics[] = array(
					"topic_id" => $topic_id,
					"name" => str_replace(" ", "&nbsp;", $name)
				);
			}
		}
		
		$page_count = intdiv($count, PAGE_SIZE);
		if ($page_count * PAGE_SIZE < $count) { $page_count++; }	
		$page_page_count = intdiv($page_count, PAGE_PAGE_SIZE);
		if ($page_page_count * PAGE_PAGE_SIZE < $page_count) { $page_page_count++; }
		
		if ($page_count == 0) { $page_count = 1; }
		if ($page_page_count == 0) { $page_page_count = 1; }
		
		$topics_arr = array(
			"topics" => $topics, 
			"count" => $count,
			"page_count" => $page_count,
			"page_page_count" => $page_page_count);
		
		return $topics_arr;		
	}
?>