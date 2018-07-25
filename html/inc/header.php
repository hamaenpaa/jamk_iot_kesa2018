<?php
if (substr(getcwd(),-6) == 'layout') {
$cfg_rootdir = "../";	
}

//echo getcwd();

if (!isset($cfg_rootdir)) {
$cfg_rootdir = "";
}
?>



<?php
	echo '<link rel="stylesheet" href="' . $cfg_rootdir . 'css/iot_reset.css" />';

   echo '<link rel="stylesheet" href="' . $cfg_rootdir . 'libs/bootstrap/3.3.7/css/bootstrap.min.css" />';
   echo '<link rel="stylesheet" href="' . $cfg_rootdir . 'libs/bootstrap/3.3.7/css/bootstrap-theme.min.css" />';
   
   echo '<script src="' . $cfg_rootdir . 'libs/jquery/jquery-3.3.1.min.js" /></script>';
   echo '<script src="' . $cfg_rootdir . 'libs/bootstrap/3.3.7/js/bootstrap.min.js" /></script>';
   
   echo '<link rel="stylesheet" href="' . $cfg_rootdir . 'css/custom_iot.css" />';
?>


<!-- 
<link rel="stylesheet" type="text/css" href="css/iot.css">
-->
<!--
<script>
$(function() {
	navitext = "<a href='index.php'>Login</a> | "; //Login
	navitext += "<a href='list_courses.php'>List Courses</a> | "; //List Courses
	
	navitext += "<a href='list_nfc_tags.php'>List Tags</a> | "; //List nfc tags
	navitext += "<a href='list_nfc_tags.php'>List Rooms</a> | "; //List rooms
	
	navitext += "<a href='list_teachers.php'>List Teachers</a> | "; //List Guests
	navitext += "<a href='list_students.php'>List Students</a> | "; //List Guests
	navitext += "<a href='list_guests.php'>List Guests</a>"; //List Guests
	$(".banner-wrap").append(navitext);
});
</script>
-->