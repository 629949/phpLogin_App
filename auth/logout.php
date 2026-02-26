<?php
declare(strict_types=1);
session_start();

$_SESSION = [];
session_destroy();

setcookie("remember_user", "", time() - 3600, "/");
setcookie("remember_token", "", time() - 3600, "/");

header("Location: ../index.php");
exit;
