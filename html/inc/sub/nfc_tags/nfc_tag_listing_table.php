<h2>Haetut NFC tagit</h2>
<?php
    $active_values = "";
	if ($seek_include_active == "on") {
		$active_values = "1";	
	} 
	if ($seek_include_passive == "on") {
		if ($active_values != "") {
			$active_values .= ",";		
		}
		$active_values .= "0";
	}
   	$sql_seek = "SELECT * FROM ca_nfc_tag WHERE removed=0 ";
   	$sql_seek = add_further_seek_param($conn, $sql_seek, "NFC_ID", $seek_nfc_id);
	$sql_seek = add_in_condition($sql_seek, "active", $active_values);
   	if ($result = $conn->query($sql_seek)) {
   	  	if (mysqli_num_rows($result) > 0) {  	
?>
			<div id="nfc_tag_table">
				<div class="row">
					<div class="col-sm-8"><b>NFC ID</b></div>
					<div class="col-sm-2"><b>Aktiivinen</b></div>
					<div class="col-sm-1">Muokkaa</div>
					<div class="col-sm-1">Poista</div>
				</div>
<?php   	
				while($res = $result->fetch_assoc()) {
?>
					<div class="row">
						<div class="col-sm-8"><?php echo $res['NFC_ID']; ?></div>
						<div class="col-sm-2"><?php if ($res['active']) { echo "X"; }; ?></div>
						<div class="col-sm-1">
							<form method="post" action="list_nfc_tags.php">
								<input type="hidden" name="id" value="<?php echo $res['ID']; ?>"/>
<?php echo $seek_params_hidden_inputs; ?>					
								<input type="submit" value="Muokkaa" />
							</form>
						</div>
						<div class="col-sm-1">
							<form method="post" action="inc/sub/nfc_tags/remove_nfc_tag.php">
								<input type="hidden" name="id" value="<?php echo $res['ID']; ?>"/>
<?php echo $seek_params_hidden_inputs; ?>					
								<input type="submit" value="Poista" />
							</form>
						</div>				
					</div>
<?php		
				}
?>
			</div>
<?php
		}
		else {
?>
			<b>Haussa ei löytynyt yhtään NFC tagia</b>
<?php			
		} 
	}
?>
