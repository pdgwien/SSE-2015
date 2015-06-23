<?php

	if(!defined("INC_BY_MAIN")) die("no!");

	if(!function_exists("mcrypt_get_iv_size"))
		die("apt-get install php5-mcrypt ;-)<br />and maybe need to add \"extension=mcrypt.so\" in php.ini");

	function secret_encrypt($key, $data)
	{
		$key_bin = base64_decode($key);

		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

		$ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key_bin, $data, MCRYPT_MODE_CBC, $iv);

		return base64_encode($iv.$ciphertext);
	}

	function secret_decrypt($key, $data)
	{
		$key_bin = base64_decode($key);
		$data_bin = base64_decode($data);

		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);

		$iv = substr($data_bin, 0, $iv_size);
		$ciphertext = substr($data_bin, $iv_size);

		$plain = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key_bin, $ciphertext, MCRYPT_MODE_CBC, $iv);

		return trim($plain);
	}

?>
