<?php
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
	
	function generate_page_list($root_url, $page_count, $curr_page, 
		$container_id, $container_more_classes,
		$curr_page_class, $other_page_class) {
		$id_attribute = "";
		if ($page_count == 1) { return ""; } // No pages for one page
		
		if ($container_id != "") { $id_attribute = " id=\"" . $container_id . "\" "; }
		$html =  "<div " . $id_attribute . 
					" class=\"page_list " . $container_more_classes . "\" >";
		for($i=0; $i < $page_count; $i++) {
			$html .= "<a href=\"" . $root_url; 
			if (strpos($root_url, "?") > 0) {
				$html .= "&";
			} else {
				$html .= "?";
			}
			$html .= "page=" . ($i + 1). "\" ";
			if ($i == $curr_page - 1) {
				if ($curr_page_class != "") {
					$html .= " class=\"" . $curr_page_class . "\" />";
				} else {
					$html .= " />";
				}
			} else {
				if ($other_page_class != "") {
					$html .= " class=\"" . $other_page_class . "\" >";
				} else {
					$html .= " >";
				}
			}
			$html .= ($i + 1);
			$html .= "</a>";
		}
		$html .= "</div>";
		return $html;
	}
?>