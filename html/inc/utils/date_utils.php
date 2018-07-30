<?php
	function from_ui_to_db($dt) {
		if (strpos($dt,".") !== false) {
			list($date_part,$time_part) = explode(" ", $dt);
			list($dd,$mm,$yyyy) = explode(".", $date_part);
			return $yyyy."-".$mm."-".$dd." ".$time_part.":00";
		}
		return $dt;
	}
?>