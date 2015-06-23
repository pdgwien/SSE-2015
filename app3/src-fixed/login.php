<?php
session_start();
?>
<html>
<head>
<title>Login</title>
</head>
<body>


<?php
$username = $_POST["username"];
$passwort = $_POST["password"];
$log=0;

$userdatei = fopen ("user.txt","r");
while (!feof($userdatei))
   {
   $zeile = fgets($userdatei,500);
   $userdata = explode("|", $zeile);

   if ($userdata[0]==$username and $passwort==trim($userdata[1]))
      {
      $_SESSION['username'] = $username;
		echo "Login war erfolgreich. <a href=\"loggedin.php\">Weiter</a>";
      $log = 1;
      }
   }
fclose($userdatei);

if ($log==0)
   {
   echo "Zugriff verweigert <a href=\"login.html\" >Zurück</a>";
   }
?>






</body>
</html>