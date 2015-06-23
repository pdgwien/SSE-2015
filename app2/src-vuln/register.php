<html>
<head>
<title>Neuen Benutzernamen Registieren></title>
</head>
<body>


<?php
$username = $_POST["username"];
$password = $_POST["passwort"];
$password2 = $_POST["passwort2"];



if ($password == $password2)
   {
   $user_vorhanden = array();

   $userdatei = fopen ("user.txt","r");
   while (!feof($userdatei))
      {
      $zeile = fgets($userdatei,500);
      $userdata = explode("|", $zeile);
      array_push ($user_vorhanden,$userdata[0]);
      }
   fclose($userdatei);



   if (in_array($username,$user_vorhanden))
      {
      echo "Username schon vorhanden <br> <a href=\"eintragen.html\">Zurück zur Registrierung</a>";
      }

   else
      {
      $userdatei = fopen ("user.txt","a");
      fwrite($userdatei, $username);
      fwrite($userdatei, "|");
      fwrite($userdatei, $password);
      fwrite($userdatei, "\n");
      fclose($userdatei);
      echo "$username, deine Anmeldung war erfolgreich<br><a href=\"login.html\">zum Login</a>";
      }
   }

else
  {
  echo "Die Passwörter sind nicht identisch<br> <a href=\"eintragen.html\">Zurück zur Registrierung</a> ";
  }

?>





</body>
</html>












