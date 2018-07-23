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
<form method="post" action="inc/sub/teacher/save_teacher.php">
	<input type="hidden" name="id" value="<?php echo $id; ?>" />
	<label>Etunimi:</label>
	<input type="text" name="teacher_firstname" value="<?php echo $teacher_firstname; ?>" 
		maxlength="25" required autofocus />
	<label>Sukunimi:</label>
	<input type="text" name="teacher_lastname" value="<?php echo $teacher_lastname; ?>" 
		maxlength="25" required />
	<label>Sähköposti:</label>
	<input type="email" name="teacher_email" value="<?php echo $teacher_email; ?>" 
		maxlength="255" />
	<label>Puhelin:</label>
	<input type="tel" name="teacher_phone" value="<?php echo $teacher_phone; ?>" 
		maxlength="13" />
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
	<input type="password" name="teacher_password" value="" maxlength="65" />
	<label>Salasanan vahvistus:</label>
	<input type="password" name="teacher_password_confirm" value="" maxlength="65" />
<?php echo $seek_params_hidden_inputs; ?>		
	<input type="submit" value="Talleta"/>
</form>	
