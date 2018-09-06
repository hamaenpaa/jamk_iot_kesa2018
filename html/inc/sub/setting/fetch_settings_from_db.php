<?php
	function getSettings($conn) {
		$sql_get_setting = 
			"SELECT default_roomidentifier, usage_type, page_size, page_page_size FROM ca_setting WHERE id=1";
		$q_setting = $conn->prepare($sql_get_setting);
		$q_setting->execute();		
		$q_setting->store_result();
		$q_setting->bind_result($default_roomidentifier,$usage_type,$page_size,$page_page_size);		
		$q_setting->fetch();
		return array(
			"default_roomidentifier" => $default_roomidentifier,
			"usage_type" => $usage_type,
			"page_size" => $page_size,
			"page_page_size" => $page_page_size);
	}
?>