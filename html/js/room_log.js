function getParameterByName(name) {
    url = window.location.href;
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

$().ready(function() {
	time = $('#last_fetch_time').html();
	if (time !== undefined) {
		refreshRate = 2000;
		function getRoomData() {
			seek_room = $("#seek_room").val();
			seek_nfc = $("#seek_nfc_id").val();
			seek_topic = $("#seek_topic").val();
			seek_course_name = $("#seek_course_name").val();
			begin_time = $("#begin_time").val();
			end_time = $("#end_time").val();
			page = getParameterByName("page");
			page_page = getParameterByName("page_page");
			time = $('#last_fetch_time').html();
			if (begin_time === undefined || begin_time == "") {
				return;
			}
			if (end_time === undefined || end_time == "") {
				return;
			}			
			if (seek_room === undefined)
				seek_room = "";
			if (seek_nfc === undefined)
				seek_nfc = "";
			if (seek_course_name === undefined)
				seek_course_name = "";	
			if (seek_topic === undefined)
				seek_topic = "";
			if (page == null)
				page = 1;
			if (page_page == null)
				page_page = 1;
			$.get("inc/sub/room_log/get_new_room_log.php?last_fetch_time=" + time +
				"&seek_room="+seek_room + "&seek_nfc_id="+seek_nfc +
				"&seek_course_name="+seek_course_name+"&seek_topic="+seek_topic+
				"&begin_time="+begin_time + "&end_time="+end_time+"&page="+page+
				"&page_page="+page_page, 
				function(data) {
					if (data != "{}") {
						jsonData = JSON.parse(data);
						if (jsonData.count_new > 0) {
							$('#last_fetch_time').html(jsonData.last_fetch_time);
							$("#room_log_listing_table .datarow").remove();
							for(i=0; i < jsonData.room_logs.length; i++) {
								if (jsonData.room_logs[i].topic == null) { 
									jsonData.room_logs[i].topic = "&nbsp;"; 
								}
								if (jsonData.room_logs[i].course_name == null) { 
									jsonData.room_logs[i].course_name = "&nbsp;"; 
								}								
								
								elemToBeInserted = "<div class=\"row datarow\">" +
								"<div class=\"col-sm-2\">" + jsonData.room_logs[i].nfc_id + "</div>" +
								"<div class=\"col-sm-4\">" + jsonData.room_logs[i].dt + "</div>" +
								"<div class=\"col-sm-2\">" + jsonData.room_logs[i].room_identifier + "</div>" +
								"<div class=\"col-sm-2\">" + jsonData.room_logs[i].topic + "</div>" +
								"<div class=\"col-sm-2\">" + jsonData.room_logs[i].course_name + "</div>" +
								"</div>";
								$("#room_log_listing_table").append(elemToBeInserted);
							}
							$("#roomlog_pages").replaceWith(jsonData.page_list);
							$("#new_room_log_notifications").html(
								"<b>Sinulla on uusia merkintöjä lokissa</b>");
						}
					}
			});
		};
	
		setInterval(
			function() {
				getRoomData();
			}, 
			refreshRate
		);
	}
});



function validateForm() {
    var begin_datetime = document.forms["seek_room_log_form"]["begin_time"].value;
	var end_datetime = document.forms["seek_room_log_form"]["end_time"].value;
	
	var correct = is_datetime_order_correct(begin_datetime, end_datetime);
	if (!correct) {
		$("#validation_errors").html("Alkuaika on loppuajan jälkeen: korjaa se!");
	} else {
		$("#validation_errors").html("");
	}
	return correct;
}
