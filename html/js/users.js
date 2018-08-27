function addUser(curr_user_permission) {
	buildAddOrModifForm("Lisää käyttäjä", 0, "", 0, curr_user_permission);
}

function modifyUser(user_id, header) {
	$.get("inc/sub/user/get_user_with_id.php?id=" + user_id,
		function(data) {
			if (data != "{}") {
				jsonData = JSON.parse(data);
				buildAddOrModifForm(header, user_id, jsonData.username, 
					jsonData.permission,
					jsonData.curr_user_perm);
			}
		}
	);
}

function buildAddOrModifForm(header, user_id, username, user_permission, curr_user_perm) {
	$("#add_or_modify_user_form").html("");
	permission_input = "";
	if (curr_user_perm == 1) {
		checked = "";
		if (user_permission == 1) {
			checked = " checked=\"checked\" ";
		}
		permission_input = 
			"<div class=\"row-type-2\">" +
				"<label>Admin käyttäjä:</label>" +
				"<input type=\"checkbox\" id=\"permission\" name=\"permission\"" + checked + "/>" +
			"</div>";
	}
	$("#add_or_modify_user_form").append(
		"<h2 id=\"add_or_modify_user_header\">" + header + "</h2>" +
		"<input type=\"hidden\" id=\"user_id\" name=\"id\" value=\"" + user_id + "\" />" +
		"<div class=\"row-type-2\">" + 
			"<label>Käyttäjätunnus:</label>" +
			"<input type=\"text\" id=\"username\" name=\"username\" value=\"" + username + "\" required />" +
		"</div>" +
		"<div class=\"row-type-2\">" + 
			"<label>Salasana:</label>" +
			"<input type=\"password\" id=\"password\" name=\"password\" value=\"\" required />" +
		"</div>" +
		"<div class=\"row-type-2\">" + 
			"<label>Salasanan varmistus:</label>" +
			"<input type=\"password\" id=\"password_confirm\" name=\"password_confirm\" value=\"\" required />" +
		"</div>" +		
		permission_input + 
		"<button class=\"button\" onclick=\"saveUser(" + curr_user_perm + 
			")\"\>Talleta</button>"
	);
}

function saveUser(curr_user_perm) {
	user_id = $("#user_id").val();
	username = $("#username").val();
	password = $("#password").val();
	password_confirm = $("#password_confirm").val();
	
	if (curr_user_perm) {
		if ($('input#permission[type=checkbox]').prop('checked')) {
			input_user_perm = "&permission=1";
		} else {
			input_user_perm = "&permission=0";
		}
	}
	if (password != password_confirm) {
		alert("Salasanat eivät täsmää");
	} else {
		$.get("inc/sub/user/save_user.php?id=" + user_id + "&username=" + username +
		    "&password=" + password + input_user_perm, function(data) {
			$("#add_or_modify_user_form").html("");	
			page = $("#page").html();
			page_page = $("#page_page").html();		
		
			// load new user list with user removed and modified page & page_page
			get_user_page(page, page_page);			
		})
	}
}

function get_user_page(page, page_page) {
	username_seek = $("#last_query_username_seek").html(); 
	$.get("inc/sub/user/get_user_page_ajax.php?" +
			"username_seek="+username_seek + "&page="+page+ "&page_page="+page_page, 
			function (data) {
		jsonData = JSON.parse(data);
		$("#user_listing_table .datarow").remove();
		perm = jsonData['perm'];
		curr_user_id = jsonData['curr_user_id'];
		for(i=0; i < jsonData.users.length; i++) {
			calls = "";
			username_cols = 10;
			permission_col = "";
			if (perm == 1) { 
				calls = js_action_column(
					"modifyUser(" + jsonData.users[i].id + ",'Muokkaa käyttäjää')", "Muokkaa");
				if (curr_user_id != jsonData.users[i].id) {
					calls = calls + js_action_column(
						"removeUser(" + jsonData.users[i].id + ")","Poista");
				}
				username_cols = 9;
				permission_col = "<div class=\"col-sm-1\">";
				if (jsonData.users[i].permission == 1) {
					permission_col = permission_col + "x";
				}
				permission_col = permission_col + "</div>";
			}
			elemToBeInserted = 
				"<div class=\"row datarow\">" +
					"<div class=\"col-sm-" + username_cols + "\">" + 
						jsonData.users[i].username + 
					"</div>" +
					permission_col + 
					"<div class=\"col-sm-1-wrap\">" +
						calls +
					"</div>" + 
				"</div>";
			$("#user_listing_table").append(elemToBeInserted);
		}		
		checkWidth();
		$("#user_pages").replaceWith(jsonData.page_list);
		
		// These can change also due to another user:
		$("#page").html(jsonData.page); 
		$("#page_page").html(jsonData.page_page);
	});	
	
}


function removeUser(user_id) {
	$.get("inc/sub/user/remove_user.php?id="+user_id, function (data) {	
		page = $("#page").html();
		page_page = $("#page_page").html();		
		
		// load new user list with user removed and modified page & page_page
		get_user_page(page, page_page);	
	});	
}