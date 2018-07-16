<?php
   include("db_connect_inc.php");

   if ($result = $conn->query("SELECT * FROM ca_staff WHERE Permission = 0 AND Active = 1")) {
?>
	<div id="active_staff_table">
		<div class="row">
			<div class="col-sm-3"><b>Etunimi</b></div>
			<div class="col-sm-3"><b>Sukunimi</b></div>
			<div class="col-sm-3"><b>Sähköposti</b></div>
			<div class="col-sm-2"><b>Puhelin</b></div>
			<div class="col-sm-1"><b>Muokkaa</b></div>
		</div>

<?php   	
		while($res = $result->fetch_assoc()) {
?>
			<div class="row">
				<div class="col-sm-3"><?php echo $res['FirstName']; ?></div>
				<div class="col-sm-3"><?php echo $res['LastName']; ?></div>
				<div class="col-sm-3"><?php echo $res['Email']; ?></div>
				<div class="col-sm-2"><?php echo $res['PhoneNumber']; ?></div>
				<div class="col-sm-1">
					<form method="post" action="list_teachers.php">
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
	$teacher_firstname = "";
	$teacher_lastname = "";
	$teacher_email = "";
	$teacher_phone = "";
	$teacher_password = "";
	$id = "";
	if (isset($_POST['id'])) {
		$id = $_POST['id'];
		$q = $conn->prepare("SELECT FirstName,LastName,Email,PhoneNumber FROM ca_staff WHERE ID=?");
		if ($q) {
			$q->bind_param("s", $_POST['id']);
			$q->execute();
			$q->bind_result($teacher_firstname,$teacher_lastname,$teacher_email,$teacher_phone);
			$q->fetch();
			$q->close();
		}
	}
		 
?>	

	
	<form method="post" action="inc/save_teacher.php">
		<input type="hidden" name="id" value="<?php echo $id; ?>" />
		<label>Etunimi:</label>
		<input type="text" name="teacher_firstname" value="<?php echo $teacher_firstname; ?>" />
		<label>Sukunimi:</label>
		<input type="text" name="teacher_lastname" value="<?php echo $teacher_lastname; ?>" />
		<label>Sähköposti:</label>
		<input type="text" name="teacher_email" value="<?php echo $teacher_email; ?>" />
		<label>Puhelin:</label>
		<input type="text" name="teacher_phone" value="<?php echo $teacher_phone; ?>" />
		<?php if ($id != "") { ?>
			<label>Aseta salasana:</label>
			<input type="checkbox" name="set_password" value=""/><br>
		<?php 
			} else {
        ?>
        	<input type="hidden" name="set_password" value="1"/>
        <?php
			}	
		?>
		<label>Salasana:</label>
		<input type="password" name="teacher_password" value="" />
		<label>Salasanan vahvistus:</label>
		<input type="password" name="teacher_password_confirm" value="" />
		<input type="submit" value="Talleta"/>
	</form>	
<?php		
   }
   include("db_disconnect_inc.php");
?>