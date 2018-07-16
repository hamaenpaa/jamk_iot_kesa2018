<?php
   include("db_connect_inc.php");

   if ($result = $conn->query("SELECT * FROM ca_guest")) {
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
					<input type="submit" value="Muokkaa" />
					</form>
				</div>				
			</div>
<?php		
		}
?>
	</div>
	
<?php
	$guest_firstname = "";
	$guest_lastname = "";
	$id = "";
	if (isset($_POST['id'])) {
		$id = $_POST['id'];
		$q = $conn->prepare("SELECT FirstName,LastName FROM ca_guest WHERE ID=?");
		if ($q) {
			$q->bind_param("s", $_POST['id']);
			$q->execute();
			$q->bind_result($guest_firstname,$guest_lastname);
			$q->fetch();
			$q->close();
		}
	}
		 
?>	
	
	<form method="post" action="inc/save_guest.php">
		<input type="hidden" name="id" value="<?php echo $id; ?>" />
		<label>Etunimi:</label>
		<input type="text" name="guest_firstname" value="<?php echo $guest_firstname; ?>" />
		<label>Sukunimi:</label>
		<input type="text" name="guest_lastname" value="<?php echo $guest_lastname; ?>" />
		<input type="submit" value="Talleta"/>
	</form>
	
	
<?php		
   }
   include("db_disconnect_inc.php");
?>