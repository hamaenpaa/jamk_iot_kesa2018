<?php
   	$sql_seek = "SELECT * FROM ca_guest";
   	$sql_seek = add_first_seek_param($conn, $sql_seek, "firstName", $seek_first_name);
	if ($seek_first_name != "")
   		$sql_seek = add_further_seek_param($conn, $sql_seek, "lastName", $seek_last_name);
	else 
		$sql_seek = add_first_seek_param($conn, $sql_seek, "lastName", $seek_last_name);
   	if ($result = $conn->query($sql_seek)) {
?>
	<div id="student_table">
		<div class="row">
			<div class="col-sm-6"><b>Etunimi</b></div>
			<div class="col-sm-5"><b>Sukunimi</b></div>
			<div class="col-sm-1"><b>Muokkaa</b></div>
		</div>

<?php   	
		while($res = $result->fetch_assoc()) {
?>
			<div class="row">
				<div class="col-sm-6"><?php echo $res['FirstName']; ?></div>
				<div class="col-sm-5"><?php echo $res['LastName']; ?></div>
				<div class="col-sm-1">
					<form method="post" action="list_guests.php">
					<input type="hidden" name="id" value="<?php echo $res['ID']; ?>"/>
<?php echo $seek_params_hidden_inputs; ?>					
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
	