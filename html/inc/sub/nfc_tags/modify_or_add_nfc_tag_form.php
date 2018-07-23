<?php
	$NFC_ID = "";
	$active = 0;
	$id = "";
	if (isset($_POST['id'])) {
?>
		<h2>Muokkaa NFC tagia</h2>
<?php		
		$id = $_POST['id'];
		$q = $conn->prepare("SELECT NFC_ID,active FROM ca_nfc_tag WHERE ID=?");
		if ($q) {
			$q->bind_param("s", $_POST['id']);
			$q->execute();
			$q->bind_result($NFC_ID,$active);
			$q->fetch();
			$q->close();
		}
	}
	else {
?>
		<h2>Lisää NFC tagi</h2>
<?php		
	}
?>	
<form method="post" action="inc/sub/nfc_tags/save_nfc_tag.php">
	<input type="hidden" name="id" value="<?php echo $id; ?>" maxlength="50" required autofocus />
	<label>NFC ID:</label>
	<input type="text" name="NFC_ID" value="<?php echo $NFC_ID; ?>" />
	<label>Aktiivinen:</label>
	<?php 
		echo checkbox_input("active", $active); 
		echo $seek_params_hidden_inputs;
	?>
	<input type="submit" value="Talleta"/>
</form>