<?php
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
    	