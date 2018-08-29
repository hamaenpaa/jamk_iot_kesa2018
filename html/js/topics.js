function get_topics_page(page, page_page) {
	name_seek = $("#last_query_name_seek").html();
	$.get("inc/sub/topic/get_topic_page_ajax.php?" +
			"&name_seek="+name_seek + "&page="+page+ "&page_page="+page_page, 
			function (data) {
		if (data != "") {
			jsonData = JSON.parse(data);
			$("#topics_listing_table .datarow").remove();
			for(i=0; i < jsonData.topics.length; i++) {
				elemToBeInserted = 
					"<div class=\"row datarow\">" +
						"<div class=\"col-sm-10\">" + jsonData.topics[i].name + "</div>" +
						modify_and_remove_columns(
							jsonData.topics[i].modify_call,
							jsonData.topics[i].remove_call) +
					"</div>";
				$("#topics_listing_table").append(elemToBeInserted);
			}
			if (jsonData.topics.length == 0) {
				$("#no_topic_findings").html("<b>Haulla ei löytynyt yhtään aihetta</b>");
			} else {
				$("#no_topic_findings").html("");
			}
			
			checkWidth();
			$("#topic_pages").replaceWith(jsonData.page_list);
		
			// These can change also due to another user:
			$("#page").html(jsonData.page); 
			$("#page_page").html(jsonData.page_page);
		}
	});
}

function removeTopic(topic_id) {
	$.get("inc/sub/topic/remove_topic.php?id="+topic_id, function (data) {	
		page = $("#page").html();
		page_page = $("#page_page").html();		
		
		// load new lesson list with lesson removed and modified page & page_page
		get_topics_page(page, page_page);	
	});
}

function modifyTopic(topic_id) {
	$.get("inc/sub/topic/fetch_topic_with_id.php?id=" + topic_id,
		function(data) {
			console.log(data);
			jsonData = JSON.parse(data);
			$("#add_or_modify_topic_header").html("Muokkaa aihetta");
			$("#id").val(topic_id);
			$("#name").val(jsonData.name); 
		}
	);
}

function saveTopic() {
	if (validateAddOrModifyForm()) {
		id = $("#id").val();
		name = $("#name").val();
		$.get("inc/sub/topic/save_topic.php?id=" + id +
			"&name="+name,
			function(data) {
				console.log(data);
				page = $("#page").html();
				page_page = $("#page_page").html();				
				get_topics_page(page, page_page);					
				$("#id").val("");
				$("#name").val("");
				$("#add_or_modify_topic_header").html("Lisää aihe");
			}
		);		
	}
}

function validateAddOrModifyForm() {
	if ($("#name").val().length > 150) {
		$("#add_or_modify_name_form_validation_errors").html(
			"Aiheen nimi ei voi olla pidempi kuin 150 merkkiä. Korjaa se!");
		return false;		
	}	
	return true;
}
