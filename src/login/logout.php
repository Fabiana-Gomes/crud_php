<?php
session_start();
session_destroy();
header('Location: login.php');
exit;
$_SESSION = array();
session_destroy();
header('Location: login.php');
exit;
?>