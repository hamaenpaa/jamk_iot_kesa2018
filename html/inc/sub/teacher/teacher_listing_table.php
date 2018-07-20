<?php
	$seek_params_hidden_inputs = hidden_input("seek_first_name", $seek_first_name).
	                             hidden_input("seek_last_name", $seek_last_name);
?>
    <h2>Etsityt opettajat</h2>
<?php
   	$sql_seek = "SELECT * FROM ca_staff WHERE Permission = 0 AND Active = 1 AND removed = 0 ";
   	$sql_seek = add_further_seek_param($conn, $sql_seek, "firstName", $seek_first_name);
   	$sql_seek = add_further_seek_param($conn, $sql_seek, "lastName", $seek_last_name);
		
   	if ($result = $conn->query($sql_seek)) {
   		if (mysqli_num_rows($result) > 0) {   				
		
?>
			<div id="active_staff_table">
				<div class="row">
					<div class="col-sm-3"><b>Etunimi</b></div>
					<div class="col-sm-3"><b>Sukunimi</b></div>
					<div class="col-sm-2"><b>Sähköposti</b></div>
					<div class="col-sm-2"><b>Puhelin</b></div>
					<div class="col-sm-1"><b>Muokkaa</b></div>
					<div class="col-sm-1"><b>Poista</b></div>
			</div>

<?php   	
			while($res = $result->fetch_assoc()) {
?>
				<div class="row">
					<div class="col-sm-3"><?php echo $res['FirstName']; ?></div>
					<div class="col-sm-3"><?php echo $res['LastName']; ?></div>
					<div class="col-sm-2"><?php echo $res['Email']; ?></div>
					<div class="col-sm-2"><?php echo $res['PhoneNumber']; ?></div>
					<div class="col-sm-1">
						<form method="post" action="list_teachers.php">
							<input type="hidden" name="id" value="<?php echo $res['ID']; ?>"/>
<?php echo $seek_params_hidden_inputs; ?>					
							<input type="submit" value="Muokkaa" />
						</form>
					</div>	
					<div class="col-sm-1">
						<form method="post" action="inc/sub/teacher/remove_teacher.php">
							<input type="hidden" name="id" value="<?php echo $res['ID']; ?>"/>
<?php echo $seek_params_hidden_inputs; ?>					
							<input type="submit" value="Poista" />
						</form>
					</div>							
				</div>
<?php		
			}
	} else {
?>
		<b>Haulla ei löytynyt opettajia</b>
<?php		
	}
?>
	</div>
<?php
}
?>