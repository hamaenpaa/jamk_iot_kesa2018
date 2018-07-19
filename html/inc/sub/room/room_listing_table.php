<?php
   $sql_seek = "SELECT * FROM ca_room";
   $sql_seek = add_first_seek_param($conn, $sql_seek, "room_name", $seek_room_name);
   if ($result = $conn->query($sql_seek)) {
?>
	<h2>Etsityt luokat</h2>
	<div id="room_table">
		<div class="row">
			<div class="col-sm-11"><b>Huoneen tunnus</b></div>
			<div class="col-sm-1"></div>
		</div>

<?php   	
		while($res = $result->fetch_assoc()) {
?>
			<div class="row">
				<div class="col-sm-11"><?php echo $res['room_name']; ?></div>
				<div class="col-sm-1">
					<form method="post" action="list_rooms.php">
<?php echo $seek_params_hidden_inputs; ?>
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
   }
?>
