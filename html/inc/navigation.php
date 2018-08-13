<?php
$screen = get_post_or_get($conn, "screen");
if ($screen != "") { 
	$curpage = "index.php?screen=1";
	$inc_module = "list_lessons";
} else {
	$curpage = "index.php";
	$inc_module = "list_room_logs";
}
$pages = array("index.php","index.php?screen=1");	
$pages_title = 	array("Huoneloki","Koulutukset/oppitunnit");
$pageAM = count($pages);
for ($i=0;$i<$pageAM;$i++) {
	if ($pages[$i] == $curpage) {
		echo "<a class='navigationLink' id='navigationLinkSelected' href='" . $pages[$i] . "'>" . $pages_title[$i] . "</a>"; 	
	} else {
		echo "<a class='navigationLink' href='" . $pages[$i] . "'>" . $pages_title[$i] . "</a>"; 	
	}
}
?>