<?php
	$room_name = "";
	$id = "";
	if (isset($_POST['id'])) {
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
		 
?>	
	
<form method="post" action="inc/sub/room/save_room.php">
	<input type="hidden" name="id" value="<?php echo $id; ?>" />
	<label>Huoneen tunnus:</label>
	<input type="text" name="room_name" value="<?php echo $room_name; ?>" />
	<input type="submit" value="Talleta"/>
</form>
