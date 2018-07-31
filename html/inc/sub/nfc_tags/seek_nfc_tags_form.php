<div id="seek_nfc_tags">
    <h2>Etsi NFC tageja</h2>
	<form action="list_nfc_tags.php" method="post">
		<label>NFC ID:<label>
		<input type="text" name="seek_nfc_id" value="<?php echo $seek_nfc_id; ?>" maxlength="50" />
		<label>Aktiiviset mukaan:<label>
<?php echo checkbox_input("seek_include_active", $seek_include_active); ?>
		<label>Passiiviset mukaan:<label>
<?php echo checkbox_input("seek_include_passive", $seek_include_passive); ?>		
		<input class="button" type="submit" value="Hae" />
	</form>
</div>