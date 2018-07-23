<?php
if (!isset($_SESSION)) {
session_start();	
}
$curpage = basename($_SERVER["SCRIPT_FILENAME"], '.php');

if (isset($_SESSION['staff_permlevel']) && $_SESSION['staff_permlevel'] == 1) {
//Admin navigation list
$pages = array("list_courses","list_nfc_tags","list_rooms","list_teachers","list_students","list_guests","list_lessons","logout");	
$pages_title = 	array("Courses","NFC Tags","Rooms","Teachers","Students","Guests","Lessons","Logout");
$pageAM = count($pages);
$pageTIAM = count($pages_title);
	if ($pageAM == $pageTIAM) {
		for ($i=0;$i<$pageAM;$i++) {
			if ($pages[$i] == "logout") {
			echo "<a class='navigationLink' id='navigationLinkLogout' href='" . $pages[$i] . ".php'>" . $pages_title[$i] . "</a>"; 
			} else if ($pages[$i] == $curpage) {
			echo "<a class='navigationLink' id='navigationLinkSelected' href='" . $pages[$i] . ".php'>" . $pages_title[$i] . "</a>"; 	
			} else {
			echo "<a class='navigationLink' href='" . $pages[$i] . ".php'>" . $pages_title[$i] . "</a>"; 	
			}
		}
	} else {
		foreach ($pages as $page) {
			if ($page == "logout") {
			echo "<a class='navigationLink' id='navigationLinkLogout' href='" . $page . ".php'>$page</a>";	
			} else if ($page == $curpage) {
			echo "<a class='navigationLink' id='navigationLinkSelected' href='" . $page . ".php'>$page</a>";	
			} else {
			echo "<a class='navigationLink' href='" . $page . ".php'>$page</a>";
			} 
		}
	}	
} else if (isset($_SESSION['staff_permlevel']) && $_SESSION['staff_permlevel'] == 0) {
	
//
//Teacher navigation list
$pages = array("list_courses","list_nfc_tags","list_rooms","list_teachers","list_students","list_guests", "list_lessons","logout");	
$pages_title = 	array("Courses","NFC Tags","Rooms","Teachers","Students","Guests","Lessons","Logout");
$pageAM = count($pages);
$pageTIAM = count ($pages_title);
	if ($pageAM == $pageTIAM) {
		for ($i=0;$i<$pageAM;$i++) {
			if ($pages[$i] == "logout") {
			echo "<a class='navigationLink' id='navigationLinkLogout' href='" . $pages[$i] . ".php'>" . $pages_title[$i] . "</a>"; 
			} else if ($pages[$i] == $curpage) {
			echo "<a class='navigationLink' id='navigationLinkSelected' href='" . $pages[$i] . ".php'>" . $pages_title[$i] . "</a>"; 	
			} else {
			echo "<a class='navigationLink' href='" . $pages[$i] . ".php'>" . $pages_title[$i] . "</a>"; 	
			}
		}
	} else {
		foreach ($pages as $page) {
			if ($page == "logout") {
			echo "<a class='navigationLink' id='navigationLinkLogout' href='" . $pages . ".php'>$page</a>";	
			} else if ($page == $curpage) {
			echo "<a class='navigationLink' id='navigationLinkSelected' href='" . $pages . ".php'>$page</a>";	
			} else {
			echo "<a class='navigationLink' href='" . $pages . ".php'>$page</a>";
			} 
		}
	}
} else if (isset($_SESSION['staff_permlevel']) || isset($_SESSION['staff_permlevel']) || isset($_SESSION['staff_permlevel'])) {
$pages = array("logout");
$pages_title = 	array("Logout");
$pageAM = count($pages);
$pageTIAM = count ($pages_title);

	if ($pageAM == $pageTIAM) {
		for ($i=0;$i<$pageAM;$i++) {
		echo "<a class='navigationLink' id='navigationLinkLogout' href='" . $pages[$i] . ".php'>" . $pages_title[$i] . "</a>"; 
		}
	} else {
		foreach ($pages as $page) {
		echo "<a class='navigationLink' id='navigationLinkLogout' href='" . $page . ".php'>$page</a>"; 
		}
	}	
} else {
$pages = array("index");	
$pages_title = 	array("Login");
$pageAM = count($pages);
$pageTIAM = count ($pages_title);
	if ($pageAM == $pageTIAM) {
		for ($i=0;$i<$pageAM;$i++) {
		echo "<a class='navigationLink' href='" . $pages[$i] . ".php'>" . $pages_title[$i] . "</a>"; 
		}
	} else {
		foreach ($pages as $page) {
		echo "<a class='navigationLink' href='" . $page . ".php'>$page</a>"; 
		}
	}	
}
?>
<style>

</style>