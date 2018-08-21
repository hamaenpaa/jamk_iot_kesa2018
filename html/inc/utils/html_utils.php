<?php
	define("PAGE_PAGE_SIZE", 20);

	function hidden_input($field_name, $value) {
		if ($value == "")
		   return "";
		return '<input type="hidden" name="'.$field_name.'" value="'.$value.'"/>';	
	}
	
	function link_with_params($root_url,$params,$classes,$content) {
		$html = "<a href=\"" . $root_url;
		$i_param = 1;
		foreach($params as $param) {
			if (strpos($root_url, "?") > 0 || $i_param > 1) {
				$html .= "&";
			} else {
				$html .= "?";
			}
			$i_param++;
			$html .= $param['name'] . "=" . $param['value'];
		}
		$html .= "\" ";
		if ($classes != "") 
			$html .= " class=\"" . $classes . "\" ";
		$html .= ">".$content."</a>";
		return $html;
	}
	
	function generate_page_list($root_url, $page_count, $curr_page, $page_page,
		$html_page_parameter, $html_page_page_parameter,
		$container_id, $container_more_classes,
		$curr_page_class, $other_page_class) {

		$id_attribute = "";
		if ($page_count == 1) { return ""; } // No pages for one page
		
		if ($container_id != "") { $id_attribute = " id=\"" . $container_id . "\" "; }
		$html =  "<div " . $id_attribute . 
					" class=\"page_list " . $container_more_classes . "\" >";
	    $i_begin = ($page_page - 1) * PAGE_PAGE_SIZE;
		$i_end   = $page_page * PAGE_PAGE_SIZE;
		$page_page_count = intdiv($page_count, PAGE_PAGE_SIZE);
		if ($page_page_count * PAGE_PAGE_SIZE < $page_count) { $page_page_count++; }
		if ($page_count < $i_end) {
			$i_end = $page_count;
		}
		$i_end--;

		if ($page_page != 1) {
			$params = array(array("name" => $html_page_parameter, "value" => "1"),
							array("name" => $html_page_page_parameter, "value" => "1"));
			$html .= link_with_params($root_url, $params, "other_page", "<<") . "&nbsp;";
			$params = array(array("name" => $html_page_parameter, 
								  "value" => ($page_page - 1) * PAGE_PAGE_SIZE),
							array("name" => $html_page_page_parameter, 
							      "value" => ($page_page - 1) ));
			$html .= link_with_params($root_url, $params, "other_page", "<") . "&nbsp;";
		}
		
		for($i= $i_begin; $i <= $i_end; $i++) {
			$params = array(array("name" => $html_page_parameter, "value" => ($i+1)),
							array("name" => $html_page_page_parameter, 
							      "value" => $page_page));
			$class = $other_page_class;
			if ($i == $curr_page - 1) {
				$class = $curr_page_class;
			}
			$html .= link_with_params($root_url, $params, $class, ($i+1)) . "&nbsp;";
		}
		
		if ($page_page < $page_page_count) {
			$params = array(array("name" => $html_page_parameter, 
			                      "value" => $page_page * PAGE_PAGE_SIZE + 1),
							array("name" => $html_page_page_parameter, 
							      "value" => ($page_page + 1) ));
			$html .= "&nbsp;" . link_with_params($root_url, $params, "other_page", ">") . "&nbsp;";
			$params = array(array("name" => $html_page_parameter, 
			                      "value" => $page_count),
							array("name" => $html_page_page_parameter, 
							      "value" => $page_page_count));
			$html .= "&nbsp;" . link_with_params($root_url, $params, "other_page", ">>");			
		}
		
		$html .= "</div>";
		return $html;
	}	
?>