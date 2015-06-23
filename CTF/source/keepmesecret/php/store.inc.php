<?php

	if(!defined("INC_BY_MAIN")) die("no!");

	if(!empty($_POST["keepmesecret"]))
		$keepmesecret = $_POST["keepmesecret"];
	else
		$keepmesecret = "";
	
	if(strlen($keepmesecret) > 1000)
		die("uhh, that was much content.");

	if($fp = @fopen($fnam, "w"))
	{
	    @fwrite($fp, '<?php $mysecret = "'.(secret_encrypt($user_key, $keepmesecret)).'"; ?>');
	    @fclose($fp);
	}

?>
