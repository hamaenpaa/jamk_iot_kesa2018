<?php
	function get_users($conn, $username_seek, $page) {
		if (mb_strlen($username_seek) > 65) {
			return array();
		}
		if (!is_integerable($page) || $page == "" || $page == "0") {
			return array();
		}
		
		list($page_size, $page_page_size) =
			get_page_and_page_page_sizes($conn);
		
		$fields = "id, username, permission";
		$end_part = " FROM ca_user WHERE username LIKE '%".	$username_seek . 
					"%' AND removed=0 ";
		$sql = "SELECT " . $fields . $end_part .
			" LIMIT " . (($page - 1) * $page_size) . "," . $page_size;
		
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
		
		$page_count = intdiv($count, $page_size);
		if ($page_count * $page_size < $count) { $page_count++; }	
		if ($page_count == 0) { $page_count = 1; }
		$page_page_count = intdiv($page_count, $page_page_size);
		if ($page_page_count * $page_page_size < $page_count) { $page_page_count++; }

		$users_arr = array(
			"users" => $users,
			"count" => $count,
			"page_count" => $page_count,
			"page_page_count" => $page_page_count			
	
		);
		return $users_arr;		
	}
?>