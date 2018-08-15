<h2>Kurssi haku</h2>
<?php
	$name_seek = get_post_or_get($conn, "name_seek");
	$description_seek = get_post_or_get($conn, "description_seek");
	$seek_params_hidden_inputs = 
		hidden_input("name_seek", $name_seek) .
		hidden_input("description_seek", $description_seek);	
?>
<form name="courses_seek" action="<?php echo $index_page; ?>" method="POST" >	
	<div class="row-type-2">
		<label>Nimi:</label>
		<input name="name_seek" 
			id="name_seek" placeholder="Nimi" 
			value="<?php echo $name_seek; ?>" />
	</div>
	<div class="row-type-2">
		<label>Kuvaus:</label>
		<input name="description_seek" placeholder="Kuvaus"
			id="description_seek" 
			value="<?php echo $description_seek; ?>"  />
	</div>
	<div class="row-type-5">
		<input class="button" type="submit" value="Hae"/>
	</div>
</form>