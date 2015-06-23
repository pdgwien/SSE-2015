<?php

	if(!defined("INC_BY_MAIN")) die("no!");

	@include($inc);
	$keepmesecret = isset($mysecret) ? secret_decrypt($user_key, $mysecret) : "";

?>
