<?php

	define("INC_BY_MAIN", true);

	require_once("./helper.inc.php");
	require_once("./session.inc.php");
	require_once("./crypt.inc.php");
	require_once("./store.inc.php");

	require_once("./h_head.inc.php");

?>
<div id="ud">
your note was saved.
</div>
<span class="bb"><a href="home.php"><img src="./images/back.png" /> go back</a></span>

<?php
	require_once("./h_tail.inc.php");
?>

