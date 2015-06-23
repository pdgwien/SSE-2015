<?php

	if(!defined("INC_BY_MAIN")) die("no!");

	ini_set("display_errors", 1);
	error_reporting(E_ALL | E_STRICT);

	define("DATASTORE_LOC", "/home/keepmesecret/datastore");
	set_include_path(get_include_path().PATH_SEPARATOR.DATASTORE_LOC);

	function r_user()
	{
		$a = mt_rand(0, 0xFFFFFF);
		$o = sprintf("%06x", $a);
		$e = sprintf("%012x", intval(microtime(true)*1e4));
		return trim($o.$e);
	}

	function r_key()
	{
		$r = exec("dd bs=1 count=32 if=/dev/urandom 2> /dev/null | base64");
		return trim($r);
	}

?>
