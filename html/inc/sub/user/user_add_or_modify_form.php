<div id="add_or_modify_user_btns">
	<?php
		$add_js_call = java_script_call("addUser", array($_SESSION['user_permlevel']));	
		$modify_own_user_js_call = java_script_call("modifyUser", 
									array($_SESSION['user_id'],
										"Muokkaa omia käyttäjätietoja"));	
	?>
	<button class="button" onclick="<?php echo $modify_own_user_js_call; ?>">Muokkaa omaa käyttäjää</button>
	<button class="button" onclick="<?php echo $add_js_call; ?>">Lisää käyttäjä</button>
</div>
<div id="add_or_modify_user_form"></div>