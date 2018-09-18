<?php
	include("user_fetch_from_db.php");
	$users_arr = get_users($conn, $username_seek, $page);
?>
	<h2>Käyttäjät</h2>
	<div id="user_query_results">
<?php
	if ($users_arr['count'] > 0) {
		$user_name_cols = 10;
		if ($_SESSION['user_permlevel'] == 1) {	$user_name_cols = 8; }
?>
		<div id="user_listing_table" class="datatable">
<?php
			$cols = array($user_name_cols, 2);
			if ($_SESSION['user_permlevel'] == 1) {	
				$cols[] = "1-wrap";
			}
			$heading_contents = array("<h5>Nimi</h5>","<h5>Admin</h5>");
			if ($_SESSION['user_permlevel'] == 1) {				
				$heading_contents[] = 
					"<div class=\"col-sm-1\"></div><div class=\"col-sm-1\"></div>";
			}
			echo heading_row(null, $cols, $heading_contents);
			foreach($users_arr['users'] as $user) {
				$data_contents = array($user['username']);
				$is_admin_mark = "";
				if ($user['permission'] == 1) { 
					$is_admin_mark = "x"; 
				} 
				$data_contents[] = $is_admin_mark;
				if ($_SESSION['user_permlevel'] == 1) {
					$modify_user_params = array($user['id'], "Muokkaa käyttäjää");
					$modify_js_call = java_script_call("modifyUser", $modify_user_params);
					$modify_btn = button_elem($modify_js_call, "Muokkaa");
					$remove_btn = "";
					if ($user['id'] != $_SESSION['user_id']) {
						$remove_user_params = array($user['id']);
						$remove_js_call = java_script_call("removeUser", $remove_user_params);
						$remove_btn = button_elem($remove_js_call, "Poista");
					}
					$data_contents[] = 
						"<div class=\"col-sm-1\">" . $modify_btn . "</div>".
						"<div class=\"col-sm-1\">" . $remove_btn . "</div>";
				} 
				echo data_row(null, $cols, $data_contents);
			}
?>
		</div>
<?php 
		echo generate_js_page_list("get_user_page",
			array(), 
			$page_size, $page_page_size,
			$users_arr['page_count'], $page, $page_page,
				"user_pages", "",
				"curr_page", "other_page");
	} else {
?>
		<b>Haulla ei löytynyt yhtään käyttäjää</b>
<?php
	}
?>
	</div>
<?php
/*
	These are because seek fields etc. can be changes after
     last query and other user and also to make js functions
	 work easier
*/
	echo div_nodisplay_elem_group(array(
			"page" => 1, "page_page" => 1, 
			"last_query_username_seek" => $username_seek));
?>