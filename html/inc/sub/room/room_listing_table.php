<?php
	define("MAX_ROOMS_AT_SEARCH", 50);

    $sql_count_seek = "SELECT COUNT(*) AS c FROM ca_room WHERE removed=0 ";
   	$sql_count_seek = add_further_seek_param($conn, $sql_count_seek, "room_name", $seek_room_name);

   	$sql_seek = "SELECT * FROM ca_room WHERE removed=0 ";
   	$sql_seek = add_further_seek_param($conn, $sql_seek, "room_name", $seek_room_name);
	$sql_seek .= " LIMIT " . MAX_ROOMS_AT_SEARCH;
	
	$result_count = $conn->query($sql_count_seek);
	$res_count = $result_count->fetch_assoc();
	$count = $res_count['c'];
	$rooms_text = "luokka";	
	if ($count > 1) { $rooms_text .= "a"; }
	
   	if ($result = $conn->query($sql_seek)) {
?>
		<h2>Etsityt luokat</h2>
<?php   		
		$count_rows = mysqli_num_rows($result);
		if ($count_rows > 0) {  
?>
			<div id="count_of_results">Haussa löytyi 
				<?php echo $count." ".$rooms_text ?>.
			</div>
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

				if ($count_rows < $count) {
?>
					<div class="row">
						<div id="room_query_count_exceeded" class="col-sm-12">
						<b>
						Haussa tuli yli <?php echo MAX_ROOMS_AT_SEARCH; ?> kurssia. 
						Vain ensimmäiset <?php echo MAX_ROOMS_AT_SEARCH; ?> näytetään. Tarkenna hakua.</b>
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
