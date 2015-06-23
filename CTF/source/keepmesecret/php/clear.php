<?php

	define("INC_BY_MAIN", true);

	require_once("./helper.inc.php");

	setcookie("s", "", time()+60*60*7);
	setcookie("k", "", time()+60*60*7);

	require_once("./h_head.inc.php");

?>
<div id="ud">
your note was deleted.
</div>
<span class="bb"><a href="home.php"><img src="./images/back.png" /> go back</a></span>

<?php
	require_once("./h_tail.inc.php");
?>

