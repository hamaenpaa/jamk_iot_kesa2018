<?php 
	define("PAGE_SIZE", 20);
	$seek_params_hidden_inputs = hidden_input("seek_student_id", $seek_student_id).
	                             hidden_input("seek_first_name", $seek_first_name).
	                             hidden_input("seek_last_name", $seek_last_name).
								 hidden_input("page", $page);
   	if ($seek_first_name != "" || $seek_last_name != "" || $seek_student_id != "") {
?>
    	<h2>Etsityt oppilaat</h2>
<?php
		$sql_count_seek = "SELECT COUNT(*) AS c FROM ca_student WHERE removed=0 ";
		$sql_count_seek = add_further_seek_param($conn, $sql_count_seek, "firstName", $seek_first_name);
   		$sql_count_seek = add_further_seek_param($conn, $sql_count_seek, "lastName", $seek_last_name);
   		$sql_count_seek = add_further_seek_param($conn, $sql_count_seek, "Student_ID", $seek_student_id);

   		$sql_seek = "SELECT * FROM ca_student WHERE removed = 0 ";
   		$sql_seek = add_further_seek_param($conn, $sql_seek, "firstName", $seek_first_name);
   		$sql_seek = add_further_seek_param($conn, $sql_seek, "lastName", $seek_last_name);
   		$sql_seek = add_further_seek_param($conn, $sql_seek, "Student_ID", $seek_student_id);
		$sql_seek .= " LIMIT " . (($page - 1) * PAGE_SIZE) . "," . PAGE_SIZE;
	
		$result_count = $conn->query($sql_count_seek);
		$res_count = $result_count->fetch_assoc();
		$count = $res_count['c'];
		$page_count = intdiv($count, PAGE_SIZE);
		if ($page_count * PAGE_SIZE < $count) { $page_count++; }	
		$page_links = generate_page_list("list_students.php".$seek_params_get, $page_count, $page,
						"","","curr_page","other_page");	
	
		$student_text = "oppilas";
		if ($count > 1) { $student_text .= "ta"; }		
	
   		if ($result = $conn->query($sql_seek)) {
   			if ($count > 0) {   
?>
				<div id="count_of_results">Haussa löytyi 
					<?php echo $count." ".$student_text ?>.
				</div>
				<div id="student_table">
					<div class="row">
						<div class="col-sm-2"><b>Etunimi</b></div>
						<div class="col-sm-2"><b>Sukunimi</b></div>
						<div class="col-sm-2"><b>Sähköposti</b></div>
						<div class="col-sm-2"><b>Puhelin</b></div>
						<div class="col-sm-1"><b>Oppilas ID</b></div>
						<div class="col-sm-1"><b>NFC ID</b></div>
						<div class="col-sm-1"><b>Muokkaa</b></div>
						<div class="col-sm-1"><b>Poista</b></div>
					</div>
<?php   	
					while($res = $result->fetch_assoc()) {
?>
						<div class="row">
							<div class="col-sm-2"><?php echo $res['FirstName']; ?></div>
							<div class="col-sm-2"><?php echo $res['LastName']; ?></div>
							<div class="col-sm-2"><?php echo $res['Email']; ?></div>
							<div class="col-sm-2"><?php echo $res['PhoneNumber']; ?></div>
							<div class="col-sm-1"><?php echo $res['Student_ID']; ?></div>
							<div class="col-sm-1"><?php echo $res['NFC_ID']; ?></div>
							<div class="col-sm-1">
								<form method="post" action="list_students.php">
									<input type="hidden" name="id" value="<?php echo $res['ID']; ?>"/>
<?php echo $seek_params_hidden_inputs; ?>					
									<input type="submit" value="Muokkaa" />
								</form>
							</div>	
							<div class="col-sm-1">
								<form method="post" action="inc/sub/student/remove_student.php">
									<input type="hidden" name="id" value="<?php echo $res['ID']; ?>"/>
<?php echo $seek_params_hidden_inputs; ?>					
									<input type="submit" value="Poista" />
								</form>
							</div>										
						</div>
<?php		
				}
				echo $page_links;
			}
			else {
?>
				<b>Haulla ei löytynyt oppilaita</b>
<?php				
			} 
?>
			</div>
<?php
		}
	} else {
?>
		<h2>Etsityt oppilaat</h2>
		<b>Oppilaiden haussa täytyy olla joku hakuehto; kaikkia opiskelijoita ei ole mielekästä hakea</b>
<?php		
	}
?>