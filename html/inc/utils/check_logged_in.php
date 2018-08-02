<?php
if (!isset($_SESSION)) {
	session_start();	
}
if (!isset($_SESSION['staff_permlevel']) ||
	($_SESSION['staff_permlevel'] != 0 &&
	 $_SESSION ['staff_permlevel'] != 1) ||
	 (!isset($_SESSION['staff_id']) ||
	  $_SESSION['staff_id'] == 0)) {
	die("Et ole kirjautunut sisään ja yritit aukoa sivua, joka vaatii sitä.");
} 
/*else {
  echo "staff_id " .  $_SESSION['staff_id'] . "\n";	
} */
?>