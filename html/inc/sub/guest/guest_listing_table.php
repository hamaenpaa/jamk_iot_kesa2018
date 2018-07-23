<?php
	define("MAX_GUESTS_AT_SEARCH", 50);

    $sql_count_seek = "SELECT COUNT(*) AS c FROM ca_guest WHERE removed=0 ";
   	$sql_count_seek = add_further_seek_param($conn, $sql_count_seek, "firstName", $seek_first_name);
	$sql_count_seek = add_further_seek_param($conn, $sql_count_seek, "lastName", $seek_last_name);

	
   	$sql_seek = "SELECT * FROM ca_guest WHERE removed=0 ";
   	$sql_seek = add_further_seek_param($conn, $sql_seek, "firstName", $seek_first_name);
	$sql_seek = add_further_seek_param($conn, $sql_seek, "lastName", $seek_last_name);
	$sql_seek .= " LIMIT " . MAX_GUESTS_AT_SEARCH;

	$result_count = $conn->query($sql_count_seek);
	$res_count = $result_count->fetch_assoc();
	$count = $res_count['c'];
	$guests_text = "vieras";
	if ($count > 1) { $guests_text .= "ta"; }
	
 	if ($result = $conn->query($sql_seek)) {
		$count_rows = mysqli_num_rows($result);
		if ($count_rows > 0) {  		
?>
		<div id="count_of_results">Haussa löytyi 
			<?php echo $count." ".$guests_text ?>.
		</div>
			<div id="guest_table">
				<div class="row">
					<div class="col-sm-5"><b>Etunimi</b></div>
					<div class="col-sm-5"><b>Sukunimi</b></div>
					<div class="col-sm-1"><b>Muokkaa</b></div>
					<div class="col-sm-1"><b>Poista</b></div>
				</div>
<?php   	
				while($res = $result->fetch_assoc()) {
?>
					<div class="row">
						<div class="col-sm-5"><?php echo $res['FirstName']; ?></div>
						<div class="col-sm-5"><?php echo $res['LastName']; ?></div>
						<div class="col-sm-1">
							<form method="post" action="list_guests.php">
								<input type="hidden" name="id" value="<?php echo $res['ID']; ?>"/>
<?php echo $seek_params_hidden_inputs; ?>					
								<input type="submit" value="Muokkaa" />
							</form>
						</div>		
						<div class="col-sm-1">
							<form method="post" action="inc/sub/guest/remove_guest.php">
								<input type="hidden" name="id" value="<?php echo $res['ID']; ?>"/>
<?php echo $seek_params_hidden_inputs; ?>					
								<input type="submit" value="Poista" />
							</form>
						</div>							
					</div>
				</div>					
<?php		
			}
			
			if ($count_rows < $count) {
?>
				<div class="row">
					<div id="guest_query_count_exceeded" class="col-sm-12">
						<b>
						Haussa tuli yli <?php echo MAX_GUESTS_AT_SEARCH; ?> kurssia. 
						Vain ensimmäiset <?php echo MAX_GUESTS_AT_SEARCH; ?> näytetään. Tarkenna hakua.</b>
					</div>
				</div>
<?php				
			}			
			
		}
		else {
?>
			<b>Haulla ei löytynyt yhtään vierailijaa</b>
<?php			
		}
	}
?>
	