<?php
	$username_seek = get_post_or_get($conn, "username_seek");
	$page = get_post_or_get($conn, "page");
	if (!isset($page) || $page == "") {
		$page = "1";
	}
	$page_page = get_post_or_get($conn, "page_page");
	if (!isset($page_page) || $page_page == "") {
		$page_page = "1";
	}
?>
<form name="users_seek" action="<?php echo $index_page; ?>" method="POST" >	
	<div class="row-type-2">
		<label>Käyttäjänimi:</label>
		<input name="username_seek" 
			id="username_seek" placeholder="Käyttäjänimi" 
			value="<?php echo $username_seek; ?>" />
	</div>
	<div class="row-type-5">
		<input class="button" type="submit" value="Hae"/>
	</div>
</form>