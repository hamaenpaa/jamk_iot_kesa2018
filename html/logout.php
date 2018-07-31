<?php
session_start();
//Poor man's logout
if (isset($_SESSION)) {
session_destroy(); //DESTROY THEM ALL
}
echo "<meta http-equiv=\"refresh\" content=\"1;url=index.php\" />";
echo "<p>You have logged out!</p>";
?>