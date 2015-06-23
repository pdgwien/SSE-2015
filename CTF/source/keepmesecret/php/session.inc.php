<?php

	if(!defined("INC_BY_MAIN")) die("no!");

	$user_session = FALSE;
	$user_key = FALSE;

	if(empty($_COOKIE["s"]) || empty($_COOKIE["k"]))
		unset($_COOKIE);

	if(!empty($_COOKIE["s"]))
		$user_session = $_COOKIE["s"];
	else
		$user_session = r_user();

	if(!empty($_COOKIE["k"]))
		$user_key = $_COOKIE["k"];
	else
		$user_key = r_key();

	setcookie("s", $user_session, time()+60*60*7);
	setcookie("k", $user_key, time()+60*60*7);

	$inc = $user_session;
	$fnam = DATASTORE_LOC."/".$user_session;

?>
