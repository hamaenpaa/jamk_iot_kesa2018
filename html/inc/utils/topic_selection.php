<?php
	function get_total_topic_ids($conn, $name_parts, $add_ids) {
		$name_parts_sql_condition = "";
		$name_parts_arr = explode(",", $name_parts);
		foreach($name_parts_arr as $name_part) {
			if (trim($name_part) != "") {
				if ($name_parts_sql_condition != "") {
					$name_parts_sql_condition .= " OR ";
				}
				$name_parts_sql_condition .= " ca_topic.name LIKE '%" . 
					trim($name_part) . "%'";
			}
		}
		$name_parts_topic_ids = array();
		$gather_name_parts = false;
		if ($name_parts_sql_condition != "") {
			$name_parts_sql_condition = "(" . $name_parts_sql_condition . ") AND ";
			$sql_name_parts = "SELECT ca_topic.id FROM ca_topic WHERE " .
			$name_parts_sql_condition . " ca_topic.removed = 0";
			$q_topic_name_parts = $conn->prepare($sql_name_parts);
			$q_topic_name_parts->execute();		
			$q_topic_name_parts->store_result();
			$q_topic_name_parts->bind_result($topic_id);	
			while($q_topic_name_parts->fetch()) {
				$name_parts_topic_ids[] = $topic_id;
			}
		}
		
		$add_id_arr = explode(",", $add_ids);
		$total_id_arr = array();
		foreach($name_parts_topic_ids as $add_id) {
			if (!in_array($add_id, $total_id_arr)) {
				$total_id_arr[] = $add_id;
			}
		}
		if ($add_ids != "") {
			foreach($add_id_arr as $add_id) {
				if (!in_array($add_id, $total_id_arr)) {
					$total_id_arr[] = $add_id;
				}
			}
		}
		return implode(",", $total_id_arr);
	}
?>