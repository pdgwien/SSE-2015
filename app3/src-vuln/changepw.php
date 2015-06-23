<html>
<head>
<title>Change Password></title>
</head>
<body>


<?php
$oldpassword = $_GET["oldpassword"];
$password = $_GET["passwort"];
$password2 = $_GET["passwort2"];




if ($password == $password2)
   {
   $old_password = array();

   $passworddata = fopen ("user.txt","r");
   while (!feof($passworddata))
      {
      $zeile = fgets($passworddata,500);
      $data = explode("|", $zeile);
      array_push ($old_password,$data[1]);
      }
   fclose($passworddata);



   if (in_array($oldpassword,$old_password))
      {
	  $file = "user.txt";
	  $content = file_get_contents($file);
	  $content = str_replace($oldpassword, $password, $content);
	  file_put_contents($file, $content);
      echo "Password successfully changed<br><a href=\"login.html\">zum Login</a>";
      }

   else
      {
      echo "Old password was wrong <br> <a href=\"changepw.html\">Try again!</a>";
      }
   }

else
  {
  echo "Use identical passwords<br> <a href=\"changepw.html\">Try again!</a> ";
  }

?>





</body>
</html>












