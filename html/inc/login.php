<div id="login-input-form">
	<form action="inc/login_confirm.php" method="post">
		<div id="username-cell">
			<p>Käyttäjätunnus</p>
			<input type="text" name="username" id="username">
		</div>
		<div id="password-cell">
			<p>Salasana</p>
			<input type="password" name="password" id="password">
		</div>
		<div id="button-wrap">
			<input class="button" type="submit" id="sign-in" value="Kirjaudu sisään">
		</div>
	</form>
</div>
<div id="login_msg">
<?php 
	$msg = get_post_or_get($conn, "msg");
	if (isset($msg) && $msg != "") {
		echo $msg;
	}
?>
</div>
