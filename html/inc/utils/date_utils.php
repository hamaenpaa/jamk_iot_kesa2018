<?php
	function from_ui_to_db($dt) {
		if (strpos($dt,".") !== false) {
			list($date_part,$time_part) = explode(" ", $dt);
			list($dd,$mm,$yyyy) = explode(".", $date_part);
			return $yyyy."-".$mm."-".$dd." ".$time_part.":00";
		}
		return $dt;
	}
	
	function date_from_ui_to_db($dt) {
		if (strpos($dt,".") !== false) {
			list($dd,$mm,$yyyy) = explode(".", $dt);
			return $yyyy."-".$mm."-".$dd;
		}
		return $dt;		
	}
	
	function from_db_datetimes_to_same_day_date_plus_times($begin_time, $end_time) {
		list($begin_date_part,$begin_time_part) = explode(" ", $begin_time);
		list($end_date_part,$end_time_part) = explode(" ", $end_time);
		list($yyyy,$mm,$dd) = explode("-", $begin_date_part);
		$mm = ltrim($mm, "0"); $dd = ltrim($dd, "0");
		list($begin_hh,$begin_m,$begin_s) = explode(":", $begin_time_part);	
		list($end_hh,$end_m,$end_s) = explode(":", $end_time_part);	
		return 
			$dd. "." . $mm . "." .$yyyy. " ".
			$begin_hh.":".$begin_m . " - " . $end_hh .":".$end_m;
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
	
	function from_db_to_separate_date_and_time_ui($dt) {
		if (strpos($dt,"-") !== false) {
			list($date_part,$time_part) = explode(" ", $dt);
			list($yyyy,$mm,$dd) = explode("-", $date_part);	
			$mm = ltrim($mm, "0"); $dd = ltrim($dd, "0");			
			list($hh,$m,$s) = explode(":", $time_part);
			$ret_arr['date_part'] = $dd. "." . $mm . "." .$yyyy;
			$ret_arr['time_part'] = $hh. ":" . $m;
		}
		else {
			list($date_part,$time_part) = explode(" ", $dt);
			$ret_arr['date_part'] = $date_part;
			$ret_arr['time_part'] = $time_part;
		}
		return $ret_arr;
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