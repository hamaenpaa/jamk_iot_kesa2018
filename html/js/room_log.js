$().ready(function() {
	time = $('#last_fetch_time').html();
	if (time !== undefined) {
		refreshRate = 2000;
		function getRoomData() {
			seek_room = $("#seek_room").val();
			seek_nfc = $("#seek_nfc_id").val();
			time = $('#last_fetch_time').html();
			if (seek_room === undefined)
				seek_room = "";
			if (seek_nfc === undefined)
				seek_nfc = "";
			$.get("inc/sub/room_log/get_new_room_log.php?last_fetch_time=" + time +
				"&seek_room="+seek_room + "&seek_nfc_id="+seek_nfc, 
				function(data) {
					if (data != "{}") {
						jsonData = JSON.parse(data);
						$('#last_fetch_time').html(jsonData.last_fetch_time);
						var nfc_ids_at_new = [];
						for(i=0; i < jsonData.count; i++) {
						   if (!nfc_ids_at_new.includes(jsonData[i].NFC_ID)) {
							   nfc_ids_at_new.push(jsonData[i].NFC_ID);
						   }
						}
						var firstNFCIdElemFound = [];
						var firstOfNFCIdElems = [];
						var allEmpty = true;
						$(".datarow div:first-child").each(
						   function (i,obj) {
							   allEmpty = false;
							   var nfc_id = obj.innerHTML.trim();
							   if (!firstNFCIdElemFound.includes(nfc_id)) {
								   if (nfc_ids_at_new.includes(nfc_id)) {
									   firstOfNFCIdElems.push($(obj.parentElement));  
									   firstNFCIdElemFound.push(nfc_id);
								   }
							   }
						   }
						);
						var wrappedSets = [];
						for(j=0; j < firstNFCIdElemFound.length; j++) {
							wrappedSets.push($());
							wrappedSets[j].add(firstOfNFCIdElems[j]);
						}
						for(i=0; i < jsonData.count; i++) {
							iOfNFCIdBeforeElem = firstNFCIdElemFound.indexOf(jsonData[i].NFC_ID);
							elemToBeInserted = "<div class=\"row datarow\">" +
								"<div class=\"col-sm-4\">" + jsonData[i].NFC_ID + "</div>" +
								"<div class=\"col-sm-4\">" + jsonData[i].roomlog_dt + "</div>" +
								"<div class=\"col-sm-4\">" + jsonData[i].room_identifier + "</div>" +
								"</div>";
							if (iOfNFCIdBeforeElem == -1) {
								if (allEmpty) {
									$(".room_log_listing_table").append(elemToBeInserted);
									firstOfNFCIdElems.push($($(".datarow")[0]));	
									allEmpty = false;
								} else {
									var lastBefore = -1;
									for(j=0; j < firstNFCIdElemFound.length; j++) {
										if (firstNFCIdElemFound[j].localeCompare(jsonData[i].NFC_ID) < 0) {
											lastBefore = j;
										} else {
											break;
										}
									}
									if (lastBefore == -1) {
										$(".room_log_listing_table").append(elemToBeInserted);
										firstOfNFCIdElems.push($($(".datarow")[0]));	
									} else {
										var wrappedSet = firstOfNFCIdElems[lastBefore];
										wrappedSet.before(elemToBeInserted);	
										firstOfNFCIdElems.push(wrappedSet.prev());
									}
								}
								firstNFCIdElemFound.push(jsonData[i].NFC_ID);
															
							} else {
								var wrappedSet = firstOfNFCIdElems[iOfNFCIdBeforeElem];
								wrappedSet.before(elemToBeInserted);
							}
							if (i == 0)
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
	
	var begin_datetime_parts = begin_datetime.split(" ");
	var end_datetime_parts = end_datetime.split(" ");
	
	var begin_date_parts = begin_datetime_parts[0].split(".");
	var end_date_parts = end_datetime_parts[0].split(".");
	
	var begin_time_parts = begin_datetime_parts[1].split(":");
	var end_time_parts = end_datetime_parts[1].split(":");	
	
	var begin_day_of_month = parseInt(begin_date_parts[0]);
	var end_day_of_month = parseInt(end_date_parts[0]);
	
	var begin_month = parseInt(begin_date_parts[1]);
	var end_month = parseInt(end_date_parts[1]);
	
	var begin_year = parseInt(begin_date_parts[2]);
	var end_year = parseInt(end_date_parts[2]);
	
	var begin_hours = parseInt(begin_time_parts[0]);
	var end_hours = parseInt(end_time_parts[0]);

	var begin_minutes = parseInt(begin_time_parts[1]);
	var end_minutes = parseInt(end_time_parts[1]);
	
	var datetimeorder_correct = true;
	if (begin_year > end_year) {
		datetimeorder_correct = false;
	} else if (begin_year == end_year) {
		if (begin_month > end_month) {
			datetimeorder_correct = false;
		} else if (begin_month == end_month) {
			if (begin_day_of_month > end_day_of_month) {
				datetimeorder_correct = false;
			} else if (begin_day_of_month == end_day_of_month) {
				if (begin_hours > end_hours) {
					datetimeorder_correct = false;
				} else if (begin_hours == end_hours) {
					if (begin_minutes > end_minutes) {
						datetimeorder_correct = false;
					}
				}
			}
		}
	}
		
	if (!datetimeorder_correct) {
		$("#validation_errors").html("Alkuaika on loppuajan jälkeen: korjaa se!");
		return false;
	} else {
		$("#validation_errors").html("");
	}
}
