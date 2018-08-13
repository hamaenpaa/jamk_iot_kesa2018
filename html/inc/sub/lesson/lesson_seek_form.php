<h2>Koulutus/oppitunti Haku</h2>
<form action="index.php?screen=1" method="POST">
<div class="row">
	<div class="col-sm-3"><b>Aloitus aika</b></div>
	<div class="col-sm-3"><b>Lopetus aika</b></div>
	<div class="col-sm-2"><b>Huone</b></div>
	<div class="col-sm-2"><b>Aihe</b></div>
	<div class="col-sm-2"><b>Hae</b></div>
</div>

<?php
	$begin_time_seek = get_post_or_get($conn, "begin_time_seek");
	$end_time_seek = get_post_or_get($conn, "end_time_seek");
	$room_seek = get_post_or_get($conn, "room_seek");
	$topic_seek = get_post_or_get($conn, "topic_seek");
	if (!isset($room_seek)) {
		$room_seek = "";	
	}
	if (!isset($topic_seek)) {
		$topic_seek = "";	
	}	
	if (!isset($begin_time_seek) || $begin_time_seek == "") {
		$begin_time_seek_value_param = "";
	} else {
		$begin_time_seek_value_param = " value=\"".$begin_time_seek."\" ";
	}
	if (!isset($end_time_seek) || $end_time_seek == "") {
		$end_time_seek_value_param = "";
	} else {
		$end_time_seek_value_param = " value=\"".$end_time_seek."\" ";
	}

	echo "begin_time_seek_value_param\n";
	echo $begin_time_seek_value_param;
	echo "end_time_seek_value_param\n";
	echo $end_time_seek_value_param;
	
	$seek_params_hidden_inputs = 
		hidden_input("begin_time_seek", $begin_time_seek) .
		hidden_input("end_time_seek", $end_time_seek) .
		hidden_input("room_seek", $room_seek) .
		hidden_input("topic_seek", $topic_seek);		
?>

<div class="row">
	<div class="col-sm-3">
		<input name="begin_time_seek" class="datetime_picker" size="16"
			id="date_start" placeholder="Aloitusaika" alt="tt" 
			<?php echo $begin_time_seek_value_param; ?> />
	</div>
	<div class="col-sm-3">
		<input name="end_time_seek" class="datetime_picker" size="16" 
			id="end_time_seek" placeholder="Lopetusaika" alt="dd" 
			<?php echo $end_time_seek_value_param; ?> />
	</div>
	<div class="col-sm-2">
		<input name="room_seek" value="<?php echo $room_seek; ?>"
			placeholder="Huoneen tunnus" />
	</div>
	<div class="col-sm-2">
		<input name="topic_seek" value="<?php echo $topic_seek; ?>" 
			placeholder="Aihe"/>
	</div>
	<div class="col-sm-2">
		<input type="submit" value="Hae"/>
	</div>
</div>

</form>