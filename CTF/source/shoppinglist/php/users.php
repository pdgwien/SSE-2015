<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "userhandling.php";
$userHandler = UserHandler::getInstance();
$users = $userHandler->getUsers();
include "header.php";
?>
<form id="login"  method="post" action="/">
    <h1>Users</h1>
    <p><a href="/">« Return</a></p>
    <?php
    $i = 1;
    foreach ($users as $user) {
        ?>
        <?php print "$i. - ".htmlspecialchars($user->username);?></br>
    <?php
        $i++;
    }
        ?>
</form>
<?php
include "footer.php";