<?php
	function get_page_and_page_page_sizes($conn) {
		$sql = "SELECT page_size, page_page_size FROM ca_setting WHERE id=1";
		$q = $conn->prepare($sql);
		$q->execute();	
		$q->store_result();
		$q->bind_result($page_size, $page_page_size);
		$q->fetch();
		return array($page_size, $page_page_size);
	}

    function add_first_seek_param($conn,$orig_sql,$seek_field_name,$seek_value) {
   		if ($seek_value != "") {
   			$orig_sql .= " WHERE " . $seek_field_name . " LIKE '%" . 
   			$conn->real_escape_string($seek_value) . "%'";
   		}        
		return $orig_sql;
	}
	
    function add_further_seek_param($conn,$orig_sql,$seek_field_name,$seek_value) {
   		if ($seek_value != "") {
   			$orig_sql .= " AND " . $seek_field_name . " LIKE '%" . 
   			$conn->real_escape_string($seek_value) . "%'";
   		}        
		return $orig_sql;
	}
	
	function add_in_condition($orig_sql, $seek_field_name, $value_list) {
		if ($value_list != "") {
			$orig_sql .= " AND " . $seek_field_name . " IN (" . $value_list . ")";
		}
		return $orig_sql;	
	}
?>
    	