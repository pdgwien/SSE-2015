<?php
session_start();
if(!isset($_SESSION['username']))
   {
   echo "Bitte erst <a href=\"login.html\">einloggen</a>";
   exit;
   }
$user = $_SESSION['username'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Account</title>
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
 <strong> <?php echo "Account von : $user "?></strong><br> <br>
 
 <form action="payment.php" method="GET">
Betrag der auf dein Konto transferiert werden soll:<br><br>
<input type="text" size="10" maxlength="15" name="payment">  EURO <br><br>

<input type="hidden" size="20" maxlength="25" name="bankAccountNr" value="5000">

<input type="submit" value="Einzahlen">
<br><br>

<?php 
if(isset($_SESSION["Kontostand"]))
{
	$user_value = $_SESSION["Kontostand"];
	echo "Derzeitiger Kontostant beträgt:  $user_value Euro!";
}
?>
</form>
 
</font></center>

</td>
</tr>
</table>


</body>
</html> 