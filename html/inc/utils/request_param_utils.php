<?php
	function get_post_or_get($conn, $param_name) {
		$param_value = "";
		if (isset($_POST[$param_name])) {
			$param_value = $_POST[$param_name];
		}
		if ($param_value == "" && isset($_GET[$param_name])) {
			$param_value = $_GET[$param_name];
		} 
		$param_value = strip_tags($conn->real_escape_string($param_value));
		return $param_value;
	}
	
	function possible_get_param($param_name,$value,$first=true) {
		$ret_val = "";
		if ($value != "") {
			if (!$first) {
			   $ret_val = "&";
			} else {
			   $ret_val = "?";
			}
			$ret_val .= $param_name."=".$value;
		}
		return $ret_val;	
	}
	
	function is_integerable( $v ){
		return is_numeric($v) && +$v === (int)(+$v);
	}	
?>