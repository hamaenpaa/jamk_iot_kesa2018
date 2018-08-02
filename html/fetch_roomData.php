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
	
	//Fetches only last one
	$showresults = 1;
	
	if ($res_getRL = $conn->prepare("SELECT ca_roomlog.id,ca_roomlog.dt, FirstName, LastName, Course_name, Course_description FROM ca_roomlog 
		INNER JOIN ca_student ON ca_roomlog.student_id = ca_student.ID 
		INNER JOIN ca_course ON ca_roomlog.course_id = ca_course.ID 
		WHERE ca_roomlog.room_id = ? AND ca_roomlog.dt >= ? ORDER BY ca_roomlog.id DESC LIMIT ?")) {
		
		$res_getRL->bind_param("isi", $rid, $curDateM, $showresults);
		
		/* Kuvitteelinen kuvasuodatus jos kuva olisi uploadattu palvelimelle ((Esimerkki)) */
		$rngpic = rand(0,2);
		$pictures = array("http://images4.fanpop.com/image/photos/17900000/fantasy-animals-random-17904028-500-375.jpg", "https://404store.com/2017/12/08/Random-Pictures-of-Conceptual-and-Creative-Ideas-02.jpg", "https://pbs.twimg.com/profile_images/653700295395016708/WjGTnKGQ_400x400.png");
		
		
		if ($res_getRL->execute()) {
			$res_getRL->store_result();
			$res_getRL_rows = $res_getRL->num_rows();
			if ($res_getRL_rows == 1) {
			$res_getRL->bind_result($roomlogID,$roomlogdt,$firstname,$lastname,$coursename,$coursedesc);
			$res_getRL->fetch();
			
			echo "<h2>Henkilö: $firstname $lastname</h2><p style='display:inline-block;'>Kirjautumisaika: $roomlogdt<p><img src='" . $pictures[$rngpic] . "' style='display:inline-block;max-width:200px; max-height:200px;'>";
			echo "<hr>";
			echo "<h2>Kurssi: $coursename</h2>";
			echo "<pre>$coursedesc</pre>";			
			/*
			echo "<pre>SELECT ca_roomlog.id, FirstName, LastName, Course_name, Course_description FROM ca_roomlog 
		INNER JOIN ca_student ON ca_roomlog.student_id = ca_student.ID 
		INNER JOIN ca_course ON ca_roomlog.course_id = ca_course.ID 
		WHERE ca_roomlog.room_id = $rid AND ca_roomlog.dt >= $curDateM ORDER BY ca_roomlog.id DESC LIMIT 1</pre>";
		*/
		echo "->" . md5($roomlogID);
		
		/*
			echo "<hr>";
			echo date("Y-m-d H:i:s");
		*/
			} else {
			echo "<h2>Ole hyvä ja lue korttisi lukijassa</h2>";
			/*
			echo date("Y-m-d H:i:s");
			echo "<hr>";
			echo $curDateM;
			*/
			}		
		} else {
		//ERROR->$res_getRL->EXECUTE
		}
		
		
		
	} else {
	//ERROR->$res_getRL->PREPARE	
	}
	
	
	
}



?>