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
<title>LoggedIn</title>
</head>
<body>

<p><a href="login.html"><h3>Home</h3></a></p>
<p><a href="changepw.html"><h3>Change Password</h3></a></p>
<p><a href="logout.php"><h3>Logout</h3></a></p>
<td width="380" valign="top"><p>&nbsp;</p>

<center><font size="4">
 <strong> <?php echo "$user erfolgreich eingeloggt!"?></strong><br> <br>
 

<br><br>

</form>
 
</font></center>

</td>
</tr>
</table>


</body>
</html> 