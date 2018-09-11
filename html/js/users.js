function addUser(curr_user_permission) {
	buildAddOrModifForm("Lisää käyttäjä", 0, "", 0, curr_user_permission);
}

function modifyUser(user_id, header) {
	$.get(buildHttpGetUrl("inc/sub/user/get_user_with_id.php", ["id"], [user_id]),
		function(data) {
			if (data != "{}" && data != "") {
				jsonData = JSON.parse(data);
				buildAddOrModifForm(header, user_id, jsonData.username, 
					jsonData.permission,
					jsonData.curr_user_perm);
			}
		});
}

function buildAddOrModifForm(header, user_id, username, user_permission, curr_user_perm) {
	$("#add_or_modify_user_form").html("");
	permission_input = "";
	if (curr_user_perm == 1) {
		permission_input = 
			div_elem(undefined, "row-type-2", false,
				label_elem("Admin käyttäjä:") +
				html_checkbox("permission", "permission", undefined, user_permission == 1, 
					undefined));
	}
	$("#add_or_modify_user_form").append(
		"<h2 id=\"add_or_modify_user_header\">" + header + "</h2>" +
		input_elem("hidden", "user_id", "id", user_id, false) +
		div_elem(undefined, "row-type-2", false, 
			label_elem("Käyttäjätunnus:") + 
			input_elem("text", "username", "username", username, true, "3", "65")) +
		div_elem(undefined, "row-type-2", false, 
			label_elem("Salasana:") +
			input_elem("password", "password", "password", "", true, "5", "50")) + 
		div_elem(undefined, "row-type-2", false, 
			label_elem("Salasanan varmistus:") + 
			input_elem("password", "password_confirm", "password_confirm", "", true, "5", "50")) +		
		permission_input + 
		button_elem(js_call("saveUser", [curr_user_perm]), "Talleta"));
}

function saveUser(curr_user_perm) {
	user_id = $("#user_id").val();
	username = $("#username").val();
	password = $("#password").val();
	password_confirm = $("#password_confirm").val();
	if (curr_user_perm) {
		if ($('input#permission[type=checkbox]').prop('checked')) {
			perm = "1";
		} else {
			perm = "0";
		}
	}
	if (validateUser(username, password, password_confirm)) {
		$("#validation_msgs").html("");
		$.get(buildHttpGetUrl("inc/sub/user/save_user.php",
			["id","username","password","permission"],
			[user_id,username,password,perm]),
			function(data) {
				if (data != "{}") {
					$("#validation_msgs").html(
						"Viimeistä admin käyttäjää ei voi muuttaa tavalliseksi käyttäjäksi");
				} else {
					$("#add_or_modify_user_form").html("");
					page = $("#page").html();
					page_page = $("#page_page").html();		
		
					// load new user list with user removed and modified page & page_page
					get_user_page(page, page_page);	
					$("#validation_msgs").html("");
				}
		})
	}
}

function validateUser(username, password, password_confirm) {
	passed = true;
	if (password != password_confirm) {
		$("#validation_msgs").html("Salasanat eivät täsmää");
		passed = false;
	}
	if (passed && username.length < 3) {
		$("#validation_msgs").html("Käyttäjätunnuksen pituus on alle 3 merkkiä");
		passed = false;		
	}
	if (passed && username.length > 65) {
		$("#validation_msgs").html("Käyttäjätunnuksen pituus on yli 65 merkkiä");
		passed = false;		
	}
	if (passed && password.length < 5) {
		$("#validation_msgs").html("Salasanan pituus on alle 5 merkkiä");
		passed = false;		
	}
	if (passed && password.length > 50) {
		$("#validation_msgs").html("Salasanan pituus on yli 50 merkkiä");
		passed = false;		
	}	
	return passed;
}

function get_user_page(page, page_page) {
	username_seek = $("#last_query_username_seek").html(); 
	$.get(buildHttpGetUrl("inc/sub/user/get_user_page_ajax.php",
			["username_seek","page","page_page"],
			[username_seek,page,page_page]),
		function (data) {
			if (data != "{}" && data != "") {
				jsonData = JSON.parse(data);
				if (jsonData.users.length > 0) {
					perm = jsonData['perm'];
					if ($("#user_listing_table").length == 0) {
						buildEmptyUsersTable(perm);
					} else {
						$("#user_listing_table .datarow").remove();
					}
					curr_user_id = jsonData['curr_user_id'];
					for(iUser=0; iUser < jsonData.users.length; iUser++) {
						calls = "";
						user = jsonData.users[iUser];
						username_cols = 10;
						if (perm == 1) { 
							calls = js_action_column(
								js_call("modifyUser", [user.id, "Muokkaa käyttäjää"]),
								"Muokkaa");
							if (curr_user_id != user.id) {
								calls = calls + js_action_column(
									js_call("removeUser", [user.id]), "Poista");
							}
							username_cols = 8;
						}
						arr_cols = [username_cols,2];
						arr_data = [user.username];
						if (user.permission == 1) {
							arr_data.push("x");
						} else {
							arr_data.push("&nbsp;");
						}
						if (perm == 1) {
							arr_data.push(calls);
							arr_cols.push("1-wrap");
						}
						$("#user_listing_table").append(
							data_row(undefined, arr_cols, arr_data));
					}
					checkWidth();
					$("#user_pages").replaceWith(jsonData.page_list);
		
					// These can change also due to another user:
					$("#page").html(jsonData.page); 
					$("#page_page").html(jsonData.page_page);
				} else {
					$("#user_query_results").html(
					"<b>Haulla ei löytynyt yhtään käyttäjää</b>");					
				}
			} 
		});	
}

function buildEmptyUsersTable(perm) {
	$("#user_query_results").html("");
	heading_contents = ["<h5>Nimi</h5>","<h5>Admin</h5>"];
	if (perm == 1) {
		cols = [8];
	} else {
		cols = [10];
	}
	cols.push(2);
	if (perm == 1) {
		cols.push("1-wrap");
		heading_contents.push(
			"<div class=\"col-sm-1\"></div><div class=\"col-sm-1\"></div>");
	}
	$("#user_query_results").append( 
		div_elem("user_listing_table", "datatable", false,
			heading_row(undefined, cols, heading_contents)));
}


function removeUser(user_id) {
	$.get(buildHttpGetUrl("inc/sub/user/remove_user.php", ["id"], [user_id]), 
		function (data) {
			if (data != "") {
				page = $("#page").html();
				page_page = $("#page_page").html();		
					// load new user list with user removed and modified page & page_page
				get_user_page(page, page_page);	
			}
	});	
}