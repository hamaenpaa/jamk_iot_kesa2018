<?php
	$guest_firstname = "";
	$guest_lastname = "";
	$id = "";
	if (isset($_POST['id'])) {
?>
		<h2>Muokkaa vierailijaa</h2>
<?php
		$id = $_POST['id'];
		$q = $conn->prepare("SELECT FirstName,LastName FROM ca_guest WHERE ID=?");
		if ($q) {
			$q->bind_param("s", $_POST['id']);
			$q->execute();
			$q->bind_result($guest_firstname,$guest_lastname);
			$q->fetch();
			$q->close();
		}
	}
	else {
?>
		<h2>Lisää vierailija</h2>
<?php		
	}
		 
?>	
<form method="post" action="inc/sub/guest/save_guest.php">
	<input type="hidden" name="id" value="<?php echo $id; ?>" />
	<label>Etunimi:</label>
	<input type="text" name="guest_firstname" value="<?php echo $guest_firstname; ?>" />
	<label>Sukunimi:</label>
	<input type="text" name="guest_lastname" value="<?php echo $guest_lastname; ?>" />
<?php echo $seek_params_hidden_inputs; ?>
	<input type="submit" value="Talleta"/>
</form>
