<?php
	function getSettings($conn) {
		$sql_get_setting = 
			"SELECT default_roomidentifier, usage_type FROM ca_setting WHERE id=1";
		$q_setting = $conn->prepare($sql_get_setting);
		$q_setting->execute();		
		$q_setting->store_result();
		$q_setting->bind_result($default_roomidentifier,$usage_type);		
		$q_setting->fetch();
		return array("default_roomidentifier" => $default_roomidentifier,
					"usage_type" => $usage_type);
	}
?>