<?php
	define("PAGE_SIZE", 20);

	function get_users($conn, $username_seek, $page) {
		if (strlen($username_seek) > 65) {
			return array();
		}
		if (!is_integerable($page) || $page == "" || $page == "0") {
			return array();
		}
		
		$fields = "id, username, permission";
		$end_part = " FROM ca_user WHERE username LIKE '%".	$username_seek . 
					"%' AND removed=0 ";
		$sql = "SELECT " . $fields . $end_part .
			" LIMIT " . (($page - 1) * PAGE_SIZE) . "," . PAGE_SIZE;
		
		$sql_user_count = "SELECT COUNT(*) " . $end_part;
		
		$q_user = $conn->prepare($sql);
		$q_user->execute();		
		$q_user->store_result();
		$q_user->bind_result($id, $username,$permission);
		$users = array();
		while($q_user->fetch()) {
			$users[] = array(
				"id" => $id,
				"username" => $username,
				"permission" => $permission
			);
		}
		
		$q_user_count = $conn->prepare($sql_user_count);
		$q_user_count->execute();		
		$q_user_count->store_result();
		$q_user_count->bind_result($count);
		$q_user_count->fetch();		
		
		$page_count = intdiv($count, PAGE_SIZE);
		if ($page_count * PAGE_SIZE < $count) { $page_count++; }		
		$page_page_count = intdiv($page_count, PAGE_PAGE_SIZE);
		if ($page_page_count * PAGE_PAGE_SIZE < $page_count) { $page_page_count++; }

		$users_arr = array(
			"users" => $users,
			"count" => $count,
			"page_count" => $page_count,
			"page_page_count" => $page_page_count			
	
		);
		return $users_arr;		
	}
?>