<?php
if (!isset($_SESSION)) {
	session_start();	
} 
$screen = get_post_or_get($conn, "screen");
$inc_module = "";
$skip_screens = array();
if (!isset($_SESSION['user_id'])) {
	$login_logout_text = "Sisäänkirjautuminen";
	$skip_screens = array(1,2,3,4);
} else {
	$login_logout_text = "Uloskirjautuminen";
}
if ($screen != "") { 
	$curpage = "index.php?screen=" . $screen;
	if ($screen == "1") {
		if (isset($_SESSION['user_id'])) {
			$inc_module = "list_topics";
		}
	} else if ($screen == "2") {
		if (isset($_SESSION['user_id'])) {
			$inc_module = "list_lessons";
		}
	} else if ($screen == "3") {
		if (isset($_SESSION['user_id'])) {
			$inc_module = "list_courses";
		}
	} else if ($screen == "4") {
		if (isset($_SESSION['user_id'])) {
			$inc_module = "list_users";
		}
	} else if ($screen == "5") {
		if (!isset($_SESSION['user_id'])) {
			$inc_module = "login";
		} else {
			$inc_module = "logout";
		}
	}
} else {
	$curpage = "index.php";
	$inc_module = "list_room_logs";
}
?>
<nav id="navigation">
<img class="mobile-nav-img" src="../html/img/navbutton.png" alt="Navigation Button">
<ul id="mobile_links">
<?php
$pages = array("index.php","index.php?screen=1","index.php?screen=2","index.php?screen=3","index.php?screen=4","index.php?screen=5");	
$pages_title = 	array("Huoneloki","Aiheet","Koulutukset/oppitunnit","Kurssit","Käyttäjät",$login_logout_text);
$pageAM = count($pages);
for ($i=0;$i<$pageAM;$i++) {
	if (!in_array($i,$skip_screens)) {
		if ($pages[$i] == $curpage) {
			echo "<li><a class='navigationLink' id='navigationLinkSelected' href='" . $pages[$i] . "'>" . $pages_title[$i] . "</a></li>"; 	
		} else {
			echo "<li><a class='navigationLink' href='" . $pages[$i] . "'>" . $pages_title[$i] . "</a></li>"; 	
		}
	}
}
?>
</ul>
</nav>