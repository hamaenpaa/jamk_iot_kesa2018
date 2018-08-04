<?php
	function getText($conn, $identifier, $lang) {
		$sql = "SELECT text FROM ca_text WHERE 
				ca_text.identifier = ? AND ca_text.lang = ?";
		$q = $conn->prepare($sql);		
		$q->bind_param("ss", $identifier, $lang);
		$q->execute();
		$q->store_result(); 
		$text = "";
		$q->bind_result($text);
		$q->fetch();
		return $text;
	}
?>