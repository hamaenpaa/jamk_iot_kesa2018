<?php
	function from_ui_to_db($dt) {
		if (strpos($dt,".") !== false) {
			list($date_part,$time_part) = explode(" ", $dt);
			list($dd,$mm,$yyyy) = explode(".", $date_part);
			return $yyyy."-".$mm."-".$dd." ".$time_part.":00";
		}
		return $dt;
	}
	
	function from_db_to_ui($dt) {
		if (strpos($dt,"-") !== false) {
			list($date_part,$time_part) = explode(" ", $dt);
			list($yyyy,$mm,$dd) = explode("-", $date_part);
			$mm = ltrim($mm, "0"); $dd = ltrim($dd, "0");
			list($hh,$m,$s) = explode(":", $time_part);		
			return $dd. "." . $mm . "." .$yyyy. " ".$hh.":".$m.":".$s;
		}
		return $dt;
	}
	
	
	function drop_seconds_from_ui($dt) {
		if (strpos($dt,".") !== false) {
			list($date_part,$time_part) = explode(" ", $dt);
			list($dd,$mm,$yyyy) = explode(".", $date_part);
			$mm = ltrim($mm, "0"); $dd = ltrim($dd, "0");
			list($hh,$m,$s) = explode(":", $time_part);
			return $dd. "." . $mm. "." . $yyyy." ".$hh.":".$m;
		}
		return $dt;		
	}
	
	function drop_date_part_from_ui($dt) {
		if (strpos($dt,".") !== false) {
			list($date_part,$time_part) = explode(" ", $dt);
			return $time_part;
		}
		return $dt;
	}
	
	function from_db_to_unix_milliseconds($db_dt) {
		list($date_part,$time_part) = explode(" ", $db_dt);
		list($dd,$mm,$yyyy) = explode("-", $date_part);
		list($hours,$minutes,$seconds) = explode(":", $time_part);
		return mktime($hours,$minutes,$seconds,$mm,$dd,$yyyy);
	}
	
	function from_unix_time_to_ui($unix_time, $seconds_included) {
		$second_part = "";
		if ($seconds_included) {
			$second_part = ":s";
		}
		return date("j.n.Y G:i".$second_part, $unix_time);
	}
	
	function from_unix_time_to_db($unix_time) {
		return date("Y-m-d H:i:s", $unix_time);
	}	
	
	function get_db_time_of_school_day_begin() {
		return date("Y-m-d 06:00:00");
	}
?>