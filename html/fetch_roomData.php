<?php
include "inc/db_connect_inc.php";

if (isset($_GET['rid'])) {
	$rid = intval($_GET['rid']);
	
	if (isset($_GET['interval']) && $_GET['interval'] <= 20) {
		$interval = intval($_GET['interval']);
	} else {
		$interval = 10;	
	}
	
	$curDate = date("Y-m-d H:i:s");
	$curDateM = date("Y-m-d H:i:s", strtotime(date($curDate)) - $interval);
	
	if (isset($_GET['lastfetch'])) {
		$lastfetch = intval($_GET['lastfetch']);
	} else {
		$lastfetch = 0;	
	}
	
	if ($res_getRL = $conn->prepare(
		"SELECT ca_roomlog.id, ca_roomlog.NFC_ID, ca_roomlog.dt FROM ca_roomlog 
		 WHERE ca_roomlog.room_identifier = ? AND ca_roomlog.dt >= ? ORDER BY ca_roomlog.id DESC LIMIT 1")) {
		
		$res_getRL->bind_param("iss", $rid, $NFC_ID, $curDateM, $showresults);
		if ($res_getRL->execute()) {
			$res_getRL->store_result();
			$res_getRL_rows = $res_getRL->num_rows();
			if ($res_getRL_rows == 1) {
				$res_getRL->bind_result($roomlogID,$roomlogdt);
				$res_getRL->fetch();
				echo "<h2>NFC_ID: $NFC_ID</h2><p style='display:inline-block;'>Kirjautumisaika: $roomlogdt<p>";
				echo "<hr> ->" . md5($roomlogID)
			} else {
				echo "<h2>Ole hyvä ja lue korttisi lukijassa</h2>";
			}		
		} else {
			//ERROR->$res_getRL->EXECUTE
		}
	} else {
		//ERROR->$res_getRL->PREPARE	
	}
}
?>