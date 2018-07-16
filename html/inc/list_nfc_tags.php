<?php
   include("db_connect_inc.php");

   if ($result = $conn->query("SELECT * FROM ca_nfc_tag")) {
?>
	<div id="nfc_tag_table">
		<div class="row">
			<div class="col-sm-9"><b>NFC_ID</b></div>
			<div class="col-sm-2"><b>Aktiivinen</b></div>
			<div class="col-sm-1"></div>
		</div>

<?php   	
		while($res = $result->fetch_assoc()) {
?>
			<div class="row">
				<div class="col-sm-9"><?php echo $res['NFC_ID']; ?></div>
				<div class="col-sm-2"><?php if ($res['active']) { echo "X"; }; ?></div>
				<div class="col-sm-1">
					<form method="post" action="list_nfc_tags.php">
					<input type="hidden" name="id" value="<?php echo $res['ID']; ?>"/>
					<input type="submit" value="Muokkaa" />
					</form>
				</div>
			</div>
<?php		
		}
?>
	</div>
	
<?php
	$NFC_ID = "";
	$active = 0;
	$id = "";
	if (isset($_POST['id'])) {
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
		 
?>	
	
	<form method="post" action="inc/save_nfc_tag.php">
		<input type="hidden" name="id" value="<?php echo $id; ?>" />
		<label>NFC_ID:</label>
		<input type="text" name="NFC_ID" value="<?php echo $NFC_ID; ?>" />
		<label>Aktiivinen:</label>
		<?php if ($active) { ?>
		<input type="checkbox" name="active" checked="checked" /><br/>
		<?php } else { ?>
		<input type="checkbox" name="active" /><br/>
		<?php } ?>
		<input type="submit" value="Talleta"/>
	</form>

	
<?php		
   }
   include("db_disconnect_inc.php");
?>