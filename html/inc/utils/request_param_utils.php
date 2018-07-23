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
	
	function hidden_input($field_name, $value) {
		if ($value == "")
		   return "";
		return '<input type="hidden" name="'.$field_name.'" value="'.$value.'"/>';	
	}
	
	function checkbox_input($field_name, $value) {
		$checked_part = "";
		if ($value != "0" && $value != "") {
			$checked_part = " checked=\"checked\" ";	
		}
		return "<input type=\"checkbox\" name=\"" . $field_name . "\" " . $checked_part . " />"; 
	}
?>