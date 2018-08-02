<div class="heading-row">
	<div class="row">
		<div class="col-sm-<?php echo $name_cols; ?>"><h5>Nimi</h5></div>
	<?php if ($dt_cols != "0") { ?>
			<div class="col-sm-<?php echo $dt_cols; ?>"><b>Sisääntuloaika</b></div>
	<?php }
		  if ($room_cols != "0") { ?>			
			<div class="col-sm-<?php echo $room_cols; ?>"><h5>Luokka</h5></div>
	<?php } 
		  if ($course_cols != "0") {	
	?>	
			<div class="col-sm-<?php echo $course_cols; ?>"><b>Kurssi</b></div>
	<?php
		  }
	?>
		<div class="col-sm-<?php echo $nfc_cols; ?>"><h5>NFC ID</h5></div>
		<div class="col-sm-1-wrap">
			<div class="col-sm-1"><b>Muokkaa</b></div>
			<div class="col-sm-1"><b>Poista</b></div>
		</div>
	</div>
</div>