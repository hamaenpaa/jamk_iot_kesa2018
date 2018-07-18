<?php
   echo '<link rel="stylesheet" href="bootstrap-3.3.7/dist/css/bootstrap.min.css" />';
   echo '<link rel="stylesheet" href="bootstrap-3.3.7/dist/css/bootstrap-theme.min.css" />';
   
   echo '<script src="jquery-3.3.1.min.js" /></script>';
   echo '<script src="bootstrap-3.3.7/dist/js/bootstrap.min.js" /></script>';
?>
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