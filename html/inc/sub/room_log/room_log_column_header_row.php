	<div class="row">
				<div class="col-sm-<?php echo $name_cols; ?>"><b>Nimi</b></div>
				<div class="col-sm-<?php echo $dt_cols; ?>"><b>Sisääntuloaika</b></div>
<?php 		if ($room_cols != "0") { ?>			
				<div class="col-sm-<?php echo $room_cols; ?>"><b>Luokka</b></div>
<?php 		} 
			if ($course_cols != "0") {	
?>	
				<div class="col-sm-<?php echo $course_cols; ?>"><b>Kurssi</b></div>
<?php
			}
?>
				<div class="col-sm-<?php echo $nfc_cols; ?>"><b>NFC ID</b></div>
				<div class="col-sm-1"><b>Muokkaa</b></div>
				<div class="col-sm-1"><b>Poista</b></div>
	</div>