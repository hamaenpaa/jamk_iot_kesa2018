<?php
if (isset($_SESSION)) {
	session_destroy(); //DESTROY THEM ALL
}
echo "<meta http-equiv=\"refresh\" content=\"1;url=index.php\" />";
echo "<p>Olet uloskirjattu!</p>";
?>