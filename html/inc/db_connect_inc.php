<?php
   define("DB_HOST", "localhost");
   define("DB_USER", "root");
   define("DB_PASS", "");
   define("DB_DATABASE", "ca");
   
   $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
   mysqli_select_db($conn, DB_DATABASE);
   
  
   if ($result = $conn->query("SELECT * FROM ca_staff")) {
      while($res = $result->fetch_assoc()) {
      	 echo $res['Password'];
	  }
   }

?>