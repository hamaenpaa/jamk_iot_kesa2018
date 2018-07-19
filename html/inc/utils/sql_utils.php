<?php
    function add_first_seek_param($orig_sql,$seek_field_name,$seek_value) {
   		if ($seek_value != "") {
   			$orig_sql .= " WHERE " . $seek_field_name . " LIKE '%" . $seek_value . "%'";
   		}        
		return $orig_sql;
	}
	
    function add_further_seek_param($orig_sql,$seek_field_name,$seek_value) {
   		if ($seek_value != "") {
   			$orig_sql .= " AND " . $seek_field_name . " LIKE '%" . $seek_value . "%'";
   		}        
		return $orig_sql;
	}
?>
    	