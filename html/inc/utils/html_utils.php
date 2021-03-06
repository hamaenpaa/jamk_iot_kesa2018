<?php
	$sql = "SELECT page_size, page_page_size FROM ca_setting WHERE id=1";

	$q = $conn->prepare($sql);
	$q->execute();	
	$q->store_result();
	$q->bind_result($page_size, $page_page_size);
	$q->fetch();

	function html_attr($name, $value) {
		if ($value == null) {
			return "";
		}
		return $name . "=\"" . $value . "\" ";
	}

	function div_elem($id, $classes, $no_display, $content) {
		$no_display_part = "";
		if ($no_display) {
			$no_display_part = html_attr("style", "display:none");
		}
		return "<div " . html_attr("id", $id) .
			html_attr("class", $classes) .
			$no_display_part . ">" . $content . "</div>";
	}
	
	function div_nodisplay_elem_group($ids_to_values) {
		$html = "";
		foreach($ids_to_values as $id => $value) {
			$html .= div_elem($id, null, true, $value);
		}
		return $html;
	}

	function table_cell($length, $content) {
		return div_elem("", "col-sm-" . $length, false, $content);
	}

	function data_row($id, $col_lengths, $contents) {
		if (count($col_lengths) != count($contents)) {
			return "datarow error";
		}
		$col_contents = "";
		for($i=0; $i < count($col_lengths); $i++) {
			$col_contents = $col_contents.
				table_cell($col_lengths[$i], $contents[$i]);
		}
		return div_elem($id, "row datarow", false, $col_contents); 
	}

	function heading_row($id, $col_lengths, $contents) {
		if (count($col_lengths) != count($contents)) {
			return "headingrow error";
		}
		$col_contents = "";
		for($i=0; $i < count($col_lengths); $i++) {
			$col_contents = $col_contents.
				table_cell($col_lengths[$i], $contents[$i]);
		}
		return div_elem($id, "row heading-row", false, $col_contents); 
	}

	function button_elem($call, $button_text) {
		$call_part = "";
		if ($call != "") {
			$call_part = "onclick=\"" . $call . "\" ";
		}
		return "<button class=\"button\" " . $call_part . ">" . 
			$button_text . "</button>";
	}
	
	function modify_and_remove_btn_block($modify_js_call, $remove_js_call) {
		return "<div class=\"col-sm-1\">".
					button_elem($modify_js_call, "Muokkaa").
				"</div><div class=\"col-sm-1\">".
					button_elem($remove_js_call, "Poista").
				"</div>";
	}
	
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

	function java_script_call($js_function, $params) {
		$js_call = $js_function . "(";
		$i_param = 1;
		foreach($params as $param) {
			$js_call .= "'". $param. "'";
			if ($i_param < count($params)) {
				$js_call .= ",";
			}
			$i_param++;
		}
		$js_call .= ")";
		return $js_call;
	}
	
	function link_with_javascript_call($js_function, $params, $classes, $content) {
		$html = "<a href=\"javascript:void(0);\" onclick=\"".
				java_script_call($js_function, $params) . "\"";
		if ($classes != "") 
			$html .= " class=\"" . $classes . "\" ";		
		$html .= ">" . $content . "</a>";
		return $html;
	}
	
	function join_arrs($arr1, $arr2) {
		$arr_total = array();
		foreach($arr1 as $arr_item) {
			$arr_total[] = $arr_item;
		}
		foreach($arr2 as $arr_item) {
			$arr_total[] = $arr_item;
		}		
		return $arr_total;
	}
	
	function generate_js_page_list($js_function, 
		$seek_params,
		$page_size, $page_page_size,
		$page_count, $curr_page, $page_page,
		$container_id, $container_more_classes,
		$curr_page_class, $other_page_class) {
			
		
		if ($container_id != "") { $id_attribute = " id=\"" . $container_id . "\" "; }
		$html =  "<div " . $id_attribute . 
					" class=\"page_list " . $container_more_classes . "\" >";
		if ($page_count == 1) { return $html . "</div>"; } // Only container for one page
	    $i_begin = ($page_page - 1) * $page_page_size;
		$i_end   = $page_page * $page_page_size;
		$page_page_count = intdiv($page_count, $page_page_size);
		if ($page_page_count * $page_page_size < $page_count) { $page_page_count++; }
		if ($page_count < $i_end) {
			$i_end = $page_count;
		}
		$i_end--;	

		if ($page_page != 1) {	
			$html .= link_with_javascript_call($js_function, 
						join_arrs(
							array(1,1),
							$seek_params),
						"other_page", "<<") . "&nbsp;";
			$html .= link_with_javascript_call(
						$js_function, 
						join_arrs(
							array( 
								($page_page - 1) * $page_page_size,
								$page_page - 1),
							$seek_params), 
						"other_page", "<") . "&nbsp;";			
		}
		for($i = $i_begin; $i <= $i_end; $i++) {
			$class = $other_page_class;
			if ($i == $curr_page - 1) {
				$class = $curr_page_class;
			}
			$html .= link_with_javascript_call(
						$js_function, 
						join_arrs(
							array(
								$i+1, $page_page),
							$seek_params), 
						$class, ($i+1)) . "&nbsp;";			
		}
		if ($page_page < $page_page_count) {
			$html .= link_with_javascript_call(
						$js_function, 
						join_arrs(
							array(
								$page_page * $page_page_size + 1, 
								($page_page + 1)),
							$seek_params), 
						"other_page", ">") . "&nbsp;";	
			$html .= link_with_javascript_call($js_function, 
						join_arrs(
							array(
								$page_count, $page_page_count),
							$seek_params), 
						"other_page", ">>");							
		}
		$html .= "</div>";
		return $html;					
	}

	function getTopicsHandlingContainer($conn, $container_id) {
		$curpage = get_post_or_get($conn, $container_id . "_curpage");
		if ($curpage == "") { $curpage = "1"; }
		return 
			"<div id=\"" . $container_id . "\">" . 
				"<input type=\"hidden\" name=\"" .$container_id . "_curpage\" " .
					"id=\"" .$container_id . "_curpage\" ".
					"value=\"" . $curpage ."\" ".
				"/>".
				"<input type=\"hidden\" name=\"" .$container_id . "_seek_topics_name\" " .
					"id=\"" .$container_id . "_seek_topics_name\" ".
					"value=\"" . get_post_or_get($conn, $container_id . "_seek_topics_name")."\" ".
				"/>".
			"</div>";		
	}
?>