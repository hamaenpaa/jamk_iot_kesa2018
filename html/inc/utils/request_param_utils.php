<?php
	function get_post_or_get($param_name) {
		$param_value = "";
		if (isset($_POST[$param_name])) {
			$param_value = $_POST[$param_name];
		}
		if ($param_value == "" && isset($_GET[$param_name])) {
			$param_value = $_GET[$param_name];
		}
		return $param_value;
	}
	
	function possible_get_param($param_name,$value,$first=false) {
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
	
	function hidden_input($field_name, $value) {
		if ($value == "")
		   return "";
		return '<input type="hidden" name="'.$field_name.'" value="'.$value.'"/>';	
	}
?>