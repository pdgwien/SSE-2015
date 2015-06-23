<?php

require_once("../model/IpModel.class.php");
require_once("../model/CardModel.class.php");

function validate() {
	if (validIp()) {
		if (!empty($_POST['number'])) {
			$number = $_POST['number'];
			if (!preg_match("/[0-9]{16}/", $number)) return "Number must consist of exactly 16 digits!";
		} else {
			return "Credit card number must not be empty!";
		}
		if (!empty($_POST['owner'])) {
			$owner = $_POST['owner'];
			if (!preg_match("/.{1,40}/", $owner)) return "Owner must consist of at least 1 and a maximum of 40 characters!";
		} else {
			return "Owner must not be empty!";
		}
		if (!empty($_POST['validMonth'])) {
			$validMonth = $_POST['validMonth'];
			if (!preg_match("/[0-9]{1,2}/", $validMonth)) return "Month must consist of 1 to 2 digits!";
		} else {
			return "Valid month must not be empty!";
		}
		if (!empty($_POST['validYear'])) {
			$validYear = $_POST['validYear'];
			if (!preg_match("/[0-9]{4}/", $validYear)) return "Year must consist of exactly 4 digits!";
		} else {
			return "Valid year must not be empty!";
		}
		if (!empty($_POST['cvv'])) {
			$cvv = $_POST['cvv'];
			if (!preg_match("/[0-9]{3}/", $cvv)) return "CVV must consist of exactly 3 digits!";
		} else {
			return "CVV must not be empty!";
		}
		$card = new CardModel();
		if ($card->validate($number, $owner, $validMonth, $validYear, $cvv)) {
			return "Credit card is valid!";
		} else {
			return "Credit card is invalid!";
		}
	} else {
		return "You are only allowed to validate one credit card per minute!";
	}
}

function register() {
    if (!empty($_POST['number'])) {
	    $number = $_POST['number'];
		if (!preg_match("/[0-9]{16}/", $number)) return "Number must consist of exactly 16 digits!";
	} else {
		return "Credit card number must not be empty!";
	}
	if (!empty($_POST['owner'])) {
		$owner = $_POST['owner'];
		if (!preg_match("/.{1,255}/", $owner)) return "Owner must consist of at least 1 and a maximum of 40 characters!";
	} else {
		return "Owner must not be empty!";
	}
	if (!empty($_POST['validMonth'])) {
		$validMonth = $_POST['validMonth'];
		if (!preg_match("/[0-9]{1,2}/", $validMonth)) return "Month must consist of 1 to 2 digits!";
	} else {
		return "Valid month must not be empty!";
	}
	if (!empty($_POST['validYear'])) {
		$validYear = $_POST['validYear'];
		if (!preg_match("/[0-9]{4}/", $validYear)) return "Year must consist of exactly 4 digits!";
	} else {
		return "Valid year must not be empty!";
	}
	if (!empty($_POST['cvv'])) {
		$cvv = $_POST['cvv'];
		if (!preg_match("/[0-9]{3}/", $cvv)) return "CVV must consist of exactly 3 digits!";
	} else {
		return "CVV must not be empty!";
	}
	$card = new CardModel();
    $card->setNum($number);
    $card->setValidMonth($validMonth);
    $card->setValidYear($validYear);
    $card->setCvv($cvv);
    $card->setOwner($owner);
    $card->persist();
    return "Registration successful!";
}

function validIp() {
	date_default_timezone_set('Europe/Vienna');
	$ip = "";
  $ip = $_SERVER['REMOTE_ADDR'];
	$ipModel = new IpModel($ip);
	if ($ipModel->getLastAccess() == "") {
		$ipModel->setLastAccess(date('Y-m-d H:i:s'));
		$ipModel->persist();
		return true;
	} else {
		if (((strtotime("now") - strtotime($ipModel->getLastAccess())) / 60) > 0) {
			$ipModel->touch();
			return true;
		} else {
			return false;
		}
	}
}
?>
