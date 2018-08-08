$(function() {
  $("#seek_with").on("change", function() {
   $("#seek_with_form").submit();
  });
});

$(function() {
  $("#seek_specific_room_or_course").on("change", function() {
   $("#seek_specific_room_or_course_form").submit();
  });
});

$().ready(function() {
	/* Configuration */
	time = $('#last_fetch_time').html();
	console.log("time");
	console.log(time);
	if (time !== undefined) {
		seek_room = $("#seek_room").value;
		seek_nfc = $("#seek_nfc_id").value;
		refreshRate = 2000;
		if (seek_room === undefined)
			seek_room = "";
		if (seek_nfc === undefined)
			seek_nfc = "";
			
		console.log("seek_room " + seek_room);
		console.log("seek_nfc " + seek_nfc);
		
		function getRoomData(seek_room,seek_nfc,time) {
			time = $('#last_fetch_time').html();
			$.get("inc/sub/room_log/get_new_room_log.php?last_fetch_time=" + time +
				"&seek_room="+seek_room + "&seek_nfc_id="+seek_nfc, 
				function(data) {
					if (data != "{}") {
						jsonData = JSON.parse(data);
						console.log(jsonData.last_fetch_time);
						$('#last_fetch_time').html(jsonData.last_fetch_time);
					}
			});
		};
	
		setInterval(
			function() {
				getRoomData(seek_room, seek_nfc, time);
			}, 
			refreshRate
		);
	}
});
