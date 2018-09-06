<h2>Aiheet Haku</h2>
<?php
	$name_seek = get_post_or_get($conn, "name_seek");
	$page = get_post_or_get($conn, "page");
	$page_page = get_post_or_get($conn, "page_page");	
	
	if (!isset($name_seek)) {
		$name_seek = "";	
	}
	$page = get_post_or_get($conn, "page");
	if (!isset($page) || $page == "") {
		$page = "1";
	}
	$page_page = get_post_or_get($conn, "page_page");
	if (!isset($page_page) || $page_page == "") {
		$page_page = "1";
	}	
?>
<form name="topics_seek" action="<?php echo $index_page; ?>" method="POST"
	onsubmit="return validateSeekForm()" >	
	<div class="row-type-2">
		<label>Etsittävä nimen osa:</label>
		<input id="name_seek" name="name_seek" value="<?php echo $name_seek; ?>"
			placeholder="nimen osa" maxlength="50"/>
	</div>
	<div class="row-type-5">
		<input class="button" type="submit" value="Hae"/>
	</div>
</form>
<div id="seekform_validation_errors"></div>