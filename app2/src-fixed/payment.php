<?php
session_start();
if(!isset($_SESSION['username']))
   {
   exit;
   }
   
$user = $_SESSION['username'];
$value = 0;
$user_payment = $_POST["payment"];
$user_bankAccountNr = getBankAccountNr($user);
$userdata[0] = "";
$newValue = 0;

$userdatei = fopen ("user_bankaccount.txt","r+");
$dokument[0] = "";

# Angegebene Kontonummer und Kontostand (=value) suchen
# Geld dazubuchen
for ($i = 0; $i < count(file("user_bankaccount.txt")); $i++)
{
   $zeile = fgets($userdatei,500);
   $userdata = explode("|", $zeile);
   $dokument[$i] = $userdata;
   if ($userdata[0]==$user)
   {	
	   # Username stimmt überein UND Banknummer
	   if ($userdata[1]==$user_bankAccountNr)
		{
			$value=trim($userdata[2]);
			$newValue = $value + $user_payment;
			$_SESSION["Kontostand"] = $newValue;
			
			$count = count(file("user_bankaccount.txt"))-1;
			if( $i == $count){
				$userdata[2] = $newValue;
			}else{
				$userdata[2] = $newValue . "\n";
			}
			
			$string = implode("|", $userdata);

			$dokument[$i] = $string;
		}
		else # Username stimmt ABER Banknummer stimmt nicht überein
		{
			$value=trim($userdata[2]);
			$newValue = $value - $user_payment;
			$_SESSION["Kontostand"] = $newValue;
			
			$count = count(file("user_bankaccount.txt"))-1;
			if( $i == $count){
				$userdata[2] = $newValue;
			}else{
				$userdata[2] = $newValue . "\n";
			}
			
			$string = implode("|", $userdata);

			$dokument[$i] = $string;
		}
		
   }
   # Username stimmt nicht überein ABER Banknummer
   else if ($userdata[1]==$user_bankAccountNr)
   {
		$value=trim($userdata[2]);
		$newValue = $value + $user_payment;
		
		$count = count(file("user_bankaccount.txt"))-1;
		if( $i == $count){
			$userdata[2] = $newValue;
		}else{
			$userdata[2] = $newValue . "\n";
		}
		$string = implode("|", $userdata);

		$dokument[$i] = $string;
    }
	# Nichts stimmt überein - nur kopieren
    $dokument[$i] = $userdata;
}

$ausgabe = "";
$userdatei = fopen ("user_bankaccount.txt","w+");
for ($i = 0; $i < count($dokument); $i++)
{	
	$ausgabe = implode("|", $dokument[$i]);
	fwrite($userdatei, $ausgabe);
}
fclose($userdatei);


function getBankAccountNr ($user)
{
	$userdatei = fopen ("user_banknumber.txt","r");
	$bankNumber = 0;
	
	while (!feof($userdatei))
	{
	  $zeile = fgets($userdatei,500);
	  $userdata = explode("|", $zeile);
	  if($userdata[0] == $user){
		  $bankNumber = trim($userdata[1]);;
	  }
	}
	fclose($userdatei);
    return $bankNumber;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Zahlung</title>
</head>
<body>

<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
<tr valign="middle" bgcolor="#FFCC66">
<td height="56" colspan="3"> <h1 align="center"><strong>Safe-Bank</strong> <img src="money-bag.png" alt="MoneyBagImage" width="110" height="150" hspace="10" align="center"></td>
</h1></tr>
<tr>
<td width="12" height="330" valign="top" bgcolor="#FFCC66">&nbsp;</td>
<td width="49" align="left" valign="top" bgcolor="#FFCC66"> <p><a href="login.html"><h3>Home</h3></a></p>
<p><a href="register.html"><h3>Register</h3></a></p>
<p><a href="account.php"><h3>Account</h3></a></p>
<p><a href="logout.php"><h3>Logout</h3></a></p>
<td width="380" valign="top"><p>&nbsp;</p>

<center><font size="4">
 <strong>Ihre Zahlung wurde getätigt!</strong><br> <br>
 Wechseln sie nun wieder zurück zu ihrem Konto: <a href="account.php"><h3>Mein Account</h3></a>

</form>
</font></p>

</td>
</tr>
</table>


</body>
</html> 