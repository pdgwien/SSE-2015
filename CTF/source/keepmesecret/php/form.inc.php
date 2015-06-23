<?php 

	if(!defined("INC_BY_MAIN")) die("no!");

	if(empty($keepmesecret))
		$keepmesecret = "";
?>
<form method="post" action="save.php" id="textform">
<div id="ud">
  <strong>keepmesecret</strong><br />
  <em>stores your notes super secretly</em>
  <textarea name="keepmesecret" cols="30" rows="8" placeholder="you may enter a note here..."><?php echo $keepmesecret; ?></textarea><br />
</div>
  <span class="bb"><a onclick="document.getElementById('textform').submit();" href="#"><img src="./images/save.png" /> save</a></span><br />
  <span class="bb"><a onclick="if(confirm('Do you really want to delete the content of your note? There is no way to recover your note!')) document.location.href='clear.php';" href="#"><img src="./images/delete.png" /> delete</a></span><br />
  <span class="bb"><a href="help.php"><img src="./images/help.png" /> help</a></span>
</form>

