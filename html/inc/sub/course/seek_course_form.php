<h2>Kurssien haku</h2>
<div id="seek_students_form">
	<form action="list_courses.php" method="post">
		<div class="row">
			<div class="row-type-2">
				<label>Kurssin tunnus:</label>
				<input type="text" name="seek_course_ID" value="<?php echo $seek_course_ID; ?>" maxlength="20" />
			</div>
			<div class="row-type-2">
				<label>Kurssin nimi:</label>
				<input type="text" name="seek_course_name" value="<?php echo $seek_course_name; ?>" maxlength="50" />
			</div>
			<div class="row-type-3">
				<input class="button" type="submit" value="Hae" />
			</div>
		</div>
	</form>
</div>