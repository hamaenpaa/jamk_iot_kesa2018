<?php
session_start(); //Starts sessions and check if user already logged in.
if (!isset($_SESSION['staff_id']) && !isset($_SESSION['staff_permlevel'])) {
	if (isset($_POST['username']) && isset($_POST['password'])) { //Checks submitted form data
		$postun = $_POST['username']; //Form input field -> username.
		$postpw = $_POST['password']; // Current timestamp Y-m-d H:i:s
		if (strlen($postun) >= 3 && strlen($postun <= 255) && strlen($postpw) >= 5 && strlen($postpw) <= 200) { //Username length validation
			if (filter_var($postun, FILTER_VALIDATE_EMAIL)) {
				include "inc/db_connect_inc.php";
					
				/* Gather all data for anti-bruteforce from same IP -> Security measure */
				if ($_SERVER['REMOTE_ADDR'] == "::1") {
				$remIP = "localhost"; //Converts ip to localhost instead of ::1, if used at localhost
				} else {
				$remIP = $_SERVER['REMOTE_ADDR']; //Client // Visitor Public IP-address.
				}
				
				$curdate = date("Y-m-d H:i:s");
				$allowed_date = date("Y-m-d H:i:s", strtotime("-15 minutes"));
					
				if ($res_get_false_logins = $conn->prepare("SELECT id FROM ca_staff_failed_login_log WHERE user_ip = ? AND dt > ?")) {
					$res_get_false_logins->bind_param("ss",$remIP,$allowed_date);
					$res_get_false_logins->execute();
					$res_get_false_logins->store_result();
					$rows_false_logins = $res_get_false_logins->num_rows();
					$res_get_false_logins->free_result();
					
					if ($rows_false_logins < 5) {
						$post_vpa = hash("sha256", $postpw);
						$activate = 1;
						
						if ($res_gd = $conn->prepare("SELECT id, Permission, FirstName, LastName FROM ca_staff WHERE email = ? AND password = ? AND Active = ?")) {
						$res_gd->bind_param("ssi",$postun, $post_vpa, $activate);
							if ($res_gd->execute()) {							
							$res_gd->store_result();
							$rows_gd = $res_gd->num_rows();
								if ($rows_gd == 1) {
								$res_gd->bind_result($id,$perms,$firstname,$lastname);
								$res_gd->fetch();
								$_SESSION['staff_id'] = $id;
								$_SESSION['staff_permlevel'] = $perms;
								$_SESSION['staff_fullname'] = $firstname . " " . $lastname;
								header("Location: main.php");
								//Login success, redirect -> ?
								} else {
								//Login failed, wrong information	
									if ($res_insert_falselogin = $conn->prepare("INSERT INTO ca_staff_failed_login_log (user_ip, dt) VALUES (?,?)")) {
										$res_insert_falselogin->bind_param("ss", $remIP, $curdate);
										$res_insert_falselogin->execute();
										header("Location: list_room_logs.php");
									} else {
									//ERROR IN PREPARE (INSERT) -> SQL MISTAKE (failed to insert false login data)
									echo "t";
									}
								}
							$res_gd->free_result();
							} else {
							//ERROR	-> MISSING DATA OR SIMILAR
							echo "l";
							}
						} else {
						//ERROR IN PREPARE (SELECT) -> SQL MISTAKE (failed to check account database)
						echo "y";
						}				
					} else {
					echo "Too many logins, please try again in 15 minutes after your first failed login attempt.";	
					}
				} else {
				//ERROR IN PREPARE (SELECT) -> SQL MISTAKE (failed logins)	
				echo "s";
				}
			include "inc/db_disconnect_inc.php";
			} else {
			echo "Invalid email address.";	
			}
		} else {
		echo "Username or password length were invalid. Username 3-255, Password 5-200.";	
		}		
	} else {
	echo "Invalid form data, either username or password input were empty.";	
	}
} else {
header("Location: index.php");
echo "You have already logged in.";
}
?>