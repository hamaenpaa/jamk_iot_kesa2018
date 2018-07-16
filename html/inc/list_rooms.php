<?php
   include("db_connect_inc.php");

   if ($result = $conn->query("SELECT * FROM ca_room")) {
?>
	<div id="room_table">
		<div class="row">
			<div class="col-sm-11"><b>Huoneen tunnus</b></div>
			<div class="col-sm-1"></div>
		</div>

<?php   	
		while($res = $result->fetch_assoc()) {
?>
			<div class="row">
				<div class="col-sm-3"><?php echo $res['room_name']; ?></div>
				<div class="col-sm-1">
					<form method="post" action="list_rooms.php">
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
	
	<form method="post" action="inc/save_room.php">
		<input type="hidden" name="id" value="<?php echo $id; ?>" />
		<label>Huoneen tunnus:</label>
		<input type="text" name="room_name" value="<?php echo $room_name; ?>" />
		<input type="submit" value="Talleta"/>
	</form>

	
<?php		
   }
   include("db_disconnect_inc.php");
?>