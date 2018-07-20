<div id="seek_students_form">
	<h2>Kurssien haku</h2>
	<form action="list_courses.php" method="post">
		<label>Kurssin tunnus:</label>
		<input type="text" name="seek_course_ID" value="<?php echo $seek_course_ID; ?>" />
		<label>Kurssin nimi:<label>
		<input type="text" name="seek_course_name" value="<?php echo $seek_course_name; ?>" />
		<input type="submit" value="Hae" />
	</form>
</div>