<?php
	$room_name = "";
	$id = "";
	if (isset($_POST['id'])) {
?>
		<h2>Muokkaa luokkaa</h2>
<?php
		$id = $_POST['id'];
		$q = $conn->prepare("SELECT room_name FROM ca_room WHERE ID=?");
		if ($q) {
			$q->bind_param("s", $_POST['id']);
			$q->execute();
			$q->bind_result($room_name);
			$q->fetch();
			$q->close();
		}
	}
	else {
?>
		<h2>Lisää luokka</h2>
<?php		
	}
?>	
	
<form method="post" action="inc/sub/room/save_room.php">
	<input type="hidden" name="id" value="<?php echo $id; ?>" />
	<label>Huoneen tunnus:</label>
	<input type="text" name="room_name" value="<?php echo $room_name; ?>" 
		maxlength="40" required autofocus />
<?php echo $seek_params_hidden_inputs; ?>	
	<input type="submit" value="Talleta"/>
</form>
