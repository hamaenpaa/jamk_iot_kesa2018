<?php
   	$sql_seek = "SELECT * FROM ca_room WHERE removed=0 ";
   	$sql_seek = add_further_seek_param($conn, $sql_seek, "room_name", $seek_room_name);
   	if ($result = $conn->query($sql_seek)) {
?>
		<h2>Etsityt luokat</h2>
<?php   		
   		if (mysqli_num_rows($result) > 0) { 
?>
			<div id="room_table">
				<div class="row">
					<div class="col-sm-10"><b>Huoneen tunnus</b></div>
					<div class="col-sm-1">Muokkaa</div>
					<div class="col-sm-1">Poista</div>
				</div>
<?php   	
				while($res = $result->fetch_assoc()) {
?>
					<div class="row">
						<div class="col-sm-10"><?php echo $res['room_name']; ?></div>
						<div class="col-sm-1">
							<form method="post" action="list_rooms.php">
<?php echo $seek_params_hidden_inputs; ?>
								<input type="hidden" name="id" value="<?php echo $res['ID']; ?>"/>
								<input type="submit" value="Muokkaa" />
							</form>
						</div>
						<div class="col-sm-1">
							<form method="post" action="inc/sub/room/remove_room.php">
<?php echo $seek_params_hidden_inputs; ?>
								<input type="hidden" name="id" value="<?php echo $res['ID']; ?>"/>
								<input type="submit" value="Poista" />
							</form>
						</div>
					</div>
<?php		
				}
		}
		else {
?>
			<b>Haulla ei löytynyt yhtään luokkaa</b>
<?php			
		} 
?>
	</div>
<?php
   }
?>
