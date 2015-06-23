<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "userhandling.php";
$userHandler = UserHandler::getInstance();
if (!isset($_SESSION["user"])  || !$_SESSION["loggedin"] || !($user = $userHandler->getUser($_SESSION["user"])))
  $_SESSION["loggedin"] = false;
if ($_SESSION["loggedin"] === true)
{
  require_once "main.php";
  die();
}
$errmsg = "";
if (isset($_POST["username"]) && isset($_POST["password"]))
{
  $user = $userHandler->login($_POST["username"], $_POST["password"]);
  if ($user)
  {
      $_SESSION["loggedin"] = true;
      $_SESSION["user"] = $user->username;
      require_once "main.php";
      die();
  }
  else {
    $errmsg = "Wrong username or password!";
  }
}

include "header.php";
?>
<form id="login"  method="post" action="/">
    <h1>Log In</h1>
    <span id="error"><?php print $errmsg;?></span>
    <fieldset id="inputs">
        <input id="username" name="username" type="text" placeholder="Username" autofocus required>   
        <input id="password" name="password" type="password" placeholder="Password" required>
    </fieldset>
    <fieldset id="actions">
        <input type="submit" id="submit" value="Log in">
        <p><a href="/register.php">Register</a></p>
        <p><a href="/users.php">Users</a></p>
    </fieldset>
</form>
<?php
include "footer.php";