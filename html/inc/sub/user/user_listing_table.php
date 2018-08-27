<?php
	include("user_fetch_from_db.php");
	$users_arr = get_users($conn, $username_seek, $page);
?>
	<h2>Käyttäjät</h2>
<?php
	if ($users_arr['count'] > 0) {
		$user_name_cols = 10;
		$permission_heading = "";
		if ($_SESSION['user_permlevel'] == 1) {
			$user_name_cols = 9;
			$permission_heading = "<div class=\"col-sm-1\"><h5>Admin</h5></div>";
		}
?>
		<div id="user_listing_table" class="datatable">
			<div class="row heading-row">
				<div class="col-sm-<?php echo $user_name_cols; ?>">
					<h5>Käyttäjänimi</h5>
				</div>
				<?php echo $permission_heading; ?>
				<div class="col-sm-1-wrap">
					<div class="col-sm-1"></div>
					<div class="col-sm-1"></div>
				</div>
			</div>
<?php
			foreach($users_arr['users'] as $user) {
?>				
				<div class="row datarow">
					<div class="col-sm-<?php echo $user_name_cols; ?>">
						<?php echo $user['username']; ?>
					</div>	
<?php				if ($_SESSION['user_permlevel'] == 1) { ?>
					<div class="col-sm-1">
						<?php if ($user['permission'] == 1) { echo "x"; } ?>
					</div>							
<?php	  		    } ?>
					<div class="col-sm-1-wrap">
<?php 					if ($_SESSION['user_permlevel'] == 1) { ?>
						<div class="col-sm-1">
<?php
							$modify_user_params = array($user['id'], "Muokkaa käyttäjää");
							$modify_js_call = java_script_call("modifyUser", $modify_user_params);
?>		
							<button class="button" onclick="<?php echo $modify_js_call; ?>">Muokkaa</button>
						</div>
						<div class="col-sm-1">
<?php 
							if ($user['id'] != $_SESSION['user_id']) {
								$remove_user_params = array($user['id']);
								$remove_js_call = java_script_call("removeUser", 
									$remove_user_params);
?>	
								<button class="button" onclick="<?php echo $remove_js_call; ?>">Poista</button>
<?php
							}
?>
						</div>
<?php
						}
?>						
					</div>
				</div>
<?php
			}
?>
		</div>
<?php 
		echo generate_js_page_list("get_user_page",
			array(), 
			$users_arr['page_count'], $page, $page_page,
				"user_pages", "",
				"curr_page", "other_page");
	} else {
?>
		<b>Haulla ei löytynyt yhtään käyttäjää</b>
<?php
	}
?>

<!-- These are because seek fields etc. can be changes after
     last query and other user and also to make js functions
	 work easier -->
<div id="page" style="display:none">1</div>
<div id="page_page" style="display:none">1</div>
<div id="last_query_username_seek" style="display:none"><?php echo $username_seek; ?></div>
