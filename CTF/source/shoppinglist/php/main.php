<?php
if (session_status() == PHP_SESSION_NONE) {
   session_start();
}
require_once "userhandling.php";
$userHandler = UserHandler::getInstance();
if (!isset($_SESSION["user"]) || !$_SESSION["loggedin"] || !($user = $userHandler->getUser($_SESSION["user"])))
{
  require_once "index.php";
  die();
}

if (isset($_POST["secret"]))
{
  $user->note = $_POST["secret"];
  $userHandler->saveUser($user);
}
if(isset($_GET["logout"]))
{
  $_SESSION["loggedin"] = false;
  $_SESSION["user"] = false;
  session_destroy();
  require_once "index.php";
  die();
}
include "header.php";
?>
<form id="login" method="post" action="/main.php">
    <h1>List</h1>
    <fieldset id="inputs">
        <textarea name="secret" style="width:400px; height:100px;"><?php print htmlspecialchars(@$user->note);?></textarea>
    </fieldset>
    <fieldset id="actions">
        <input type="submit" id="submit" value="Save list">
        <a href="/main.php?logout">Logout</a>
    </fieldset>
</form>
<?php
include "footer.php";