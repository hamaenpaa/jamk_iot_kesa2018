function get_topics_page(page, page_page) {
	name_seek = $("#last_query_name_seek").html();
	$.get(buildHttpGetUrl("inc/sub/topic/get_topic_page_ajax.php", 
			["name_seek","page","page_page"], [name_seek,page,page_page]), 
		function (data) {
			if (data != "") {
				jsonData = JSON.parse(data);
				if (jsonData.topics.length > 0) {	
					if ($("#topics_listing_table").length == 0) {
						buildEmptyTopicsTable();
					} else {
						$("#topics_listing_table .datarow").remove();
					}				
					for(i=0; i < jsonData.topics.length; i++) {
						$("#topics_listing_table").append(
							data_row(undefined, [10, "1-wrap"],
								[jsonData.topics[i].name, 
								modify_and_remove_columns(
									jsonData.topics[i].modify_call,
									jsonData.topics[i].remove_call)]));
					}
			
					checkWidth();
					$("#topic_pages").replaceWith(jsonData.page_list);
		
					// These can change also due to another user:
					$("#page").html(jsonData.page); 
					$("#page_page").html(jsonData.page_page);
				} else {
					$("#topics_query_results").html(
						"<b>Haulla ei löytynyt yhtään aihetta</b>");				
				}
			}
		});
}

function buildEmptyTopicsTable() {
	$("#topics_query_results").html("");
	$("#topics_query_results").append( 
		div_elem("topics_listing_table", "datatable", false,
			heading_row(undefined, [10,"1-wrap"], 
				["<h5>Nimi</h5>",
					"<div class=\"col-sm-1\"></div><div class=\"col-sm-1\"></div>"])));
}

function removeTopic(topic_id) {
	$.get(buildHttpGetUrl("inc/sub/topic/remove_topic.php", ["id"], [topic_id]), 
		function (data) {	
			page = $("#page").html();
			page_page = $("#page_page").html();		
		
			// load new lesson list with lesson removed and modified page & page_page
			get_topics_page(page, page_page);	
		});
}

function modifyTopic(topic_id) {
	$.get(buildHttpGetUrl("inc/sub/topic/fetch_topic_with_id.php", 
		["id"], [topic_id]), 
		function(data) {
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
		$.get(buildHttpGetUrl("inc/sub/topic/save_topic.php", 
			["id","name"], [id,name]), 
			function(data) {
				if (data == "{}") {
					page = $("#page").html();
					page_page = $("#page_page").html();				
					get_topics_page(page, page_page);					
					$("#id").val("");
					$("#name").val("");
					$("#add_or_modify_topic_header").html("Lisää aihe");
					$("#add_or_modify_topic_form_validation_errors").html("");
				} else {
					$("#add_or_modify_topic_form_validation_errors").html(
						"Toisella aiheella on jo sama nimi. Korjaa se!");
				}
			}
		);		
	}
}

function validateAddOrModifyForm() {
	if ($("#name").val().length > 150) {
		$("#add_or_modify_topic_form_validation_errors").html(
			"Aiheen nimi ei voi olla pidempi kuin 150 merkkiä. Korjaa se!");
		return false;		
	}	
	if ($("#name").val().indexOf(",") > -1) {
		$("#add_or_modify_topic_form_validation_errors").html(
			"Aiheen nimessä ei saa olla pilkkua. Korjaa se!");
		return false;		
	}
	$("#add_or_modify_topic_form_validation_errors").html("");
	return true;
}
