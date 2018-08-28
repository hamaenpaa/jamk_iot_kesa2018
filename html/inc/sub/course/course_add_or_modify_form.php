<h2 id="add_or_modify_course_header">Lisää kurssi</h2>

<input type="hidden" id="id" name="id" value="" />
<div class="row-type-2">
	<label>Nimi:</label>
	<input id="name" name="name" value="" maxlength="50" required />		
</div>
<div class="row-type-2">
	<label>Kurssin kuvaus:</label>
</div>
<div class="row-type-4">
	<textarea id="description" class="text-box" name="description" 
		rows="7" cols="50" maxlength="500"></textarea>
</div>	
<div class="row-type-5">
	<button class="button" onclick="saveCourse()">Talleta</button>
</div>
<div id="course_validation_msgs"></div>
<div id="course_lessons_handling"></div>