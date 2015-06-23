<?php
require_once "userhandling.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
{
  require_once "main.php";
  die();
}
$errmsg = "";
if (isset($_POST["username"]) && isset($_POST["password"]))
{
    $usr = new User();
    $usr->username = $_POST["username"];
    $usr->password = md5($_POST["password"]);
    $userHandler = UserHandler::getInstance();
    $reg = $userHandler->tryRegister($usr);
    if ($reg)
    {
      header("Location: /");
      die();
    }
    else
    {
      $errmsg = "User already exists";
    }
}

include "header.php";
?>
<form id="login" method="post" action="/register.php">
    <h1>Register</h1>
    <span id="error"><?php print $errmsg;?></span>
    <fieldset id="inputs">
        <input id="username" name="username" type="text" placeholder="Username" autofocus required>   
        <input id="password" name="password" type="password" placeholder="Password" required>
    </fieldset>
    <fieldset id="actions">
        <input type="submit" id="submit" value="Register">
        <a href="/">Cancel</a>
    </fieldset>
</form>
<?php
include "footer.php";