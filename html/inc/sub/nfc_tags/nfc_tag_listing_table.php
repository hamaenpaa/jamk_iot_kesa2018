<h2>Haetut NFC tagit</h2>
<?php
	define("MAX_NFC_TAGS_AT_SEARCH", 50);
	define("PAGE_SIZE", 2);

    $active_values = "";
	if ($seek_include_active == "on") {
		$active_values = "1";	
	} 
	if ($seek_include_passive == "on") {
		if ($active_values != "") {
			$active_values .= ",";		
		}
		$active_values .= "0";
	}
	
    $sql_count_seek = "SELECT COUNT(*) AS c FROM ca_nfc_tag WHERE removed=0 ";
   	$sql_count_seek = add_further_seek_param($conn, $sql_count_seek, "NFC_ID", $seek_nfc_id);
	$sql_count_seek = add_in_condition($sql_count_seek, "active", $active_values);
	
   	$sql_seek = "SELECT * FROM ca_nfc_tag WHERE removed=0 ";
   	$sql_seek = add_further_seek_param($conn, $sql_seek, "NFC_ID", $seek_nfc_id);
	$sql_seek = add_in_condition($sql_seek, "active", $active_values);
	$sql_seek .= " ORDER BY NFC_ID";
	$sql_seek .= " LIMIT " . (($page - 1) * PAGE_SIZE) . "," . PAGE_SIZE;
	
	$result_count = $conn->query($sql_count_seek);
	$res_count = $result_count->fetch_assoc();
	$count = $res_count['c'];
	$page_count = intdiv($count, PAGE_SIZE);
	if ($page_count * PAGE_SIZE < $count) { $page_count++; }	
	$page_links = generate_page_list(
					"list_nfc_tags.php".$seek_params_get, 
					$page_count, $page,
					"page", 
					"","","curr_page","other_page");	
	
	$nfc_tags_text = "NFC tagi";
	if ($count > 1) { $nfc_tags_text .= "a"; }	
	
   	if ($result = $conn->query($sql_seek)) {
		$count_rows = mysqli_num_rows($result);
		if ($count_rows > 0) {  			
?>
		<div id="count_of_results">Haussa löytyi 
			<?php echo $count." ".$nfc_tags_text ?>.
		</div>
			<div id="nfc_tag_table">
				<div class="row">
					<div class="col-sm-8"><b>NFC ID</b></div>
					<div class="col-sm-2"><b>Aktiivinen</b></div>
					<div class="col-sm-1">Muokkaa</div>
					<div class="col-sm-1">Poista</div>
				</div>
<?php   	
				while($res = $result->fetch_assoc()) {
?>
					<div class="row">
						<div class="col-sm-8"><?php echo $res['NFC_ID']; ?></div>
						<div class="col-sm-2"><?php if ($res['active']) { echo "X"; }; ?></div>
						<div class="col-sm-1">
							<form method="post" action="list_nfc_tags.php">
								<input type="hidden" name="id" value="<?php echo $res['ID']; ?>"/>
<?php echo $seek_params_hidden_inputs; ?>					
								<input type="submit" value="Muokkaa" />
							</form>
						</div>
						<div class="col-sm-1">
							<form method="post" action="inc/sub/nfc_tags/remove_nfc_tag.php">
								<input type="hidden" name="id" value="<?php echo $res['ID']; ?>"/>
<?php echo $seek_params_hidden_inputs; ?>					
								<input type="submit" value="Poista" />
							</form>
						</div>				
					</div>
<?php		
				}
?>
			</div>
<?php

			echo $page_links;	
		}
		else {
?>
			<b>Haussa ei löytynyt yhtään NFC tagia</b>
<?php			
		} 
	}
?>
