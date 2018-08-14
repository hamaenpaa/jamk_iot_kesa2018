<h2>Koulutus/oppitunti Haku</h2>
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
	$seek_params_hidden_inputs = 
		hidden_input("begin_time_seek", $begin_time_seek) .
		hidden_input("end_time_seek", $end_time_seek) .
		hidden_input("room_seek", $room_seek) .
		hidden_input("topic_seek", $topic_seek);		
?>

<form action="index.php?screen=1" method="POST">	    
	<input name="begin_time_seek" class="datetime_picker" size="16"
		id="begin_time_seek" placeholder="Aloitusaika" 
		alt="Päivämäärä suomalaisessa muodossa esim: 31.07.2018 07:00"
		<?php echo $begin_time_seek_value_param; ?> />
	<input name="end_time_seek" class="datetime_picker" size="16" 
		id="end_time_seek" placeholder="Lopetusaika" 
		alt="Päivämäärä suomalaisessa muodossa esim: 31.07.2018 07:00"
		<?php echo $end_time_seek_value_param; ?> />
	<input name="room_seek" value="<?php echo $room_seek; ?>"
		placeholder="Huoneen tunnus" />
	<input name="topic_seek" value="<?php echo $topic_seek; ?>" 
		placeholder="Aihe"/>
	<input type="submit" value="Hae"/>
</form>
<div id="validation_errors"></div>