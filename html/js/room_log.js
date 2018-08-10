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
						$(".datarow div:first-child").each(
						   function (i,obj) {
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
							var wrappedSet = firstOfNFCIdElems[iOfNFCIdBeforeElem];
							wrappedSet.before(elemToBeInserted);
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
