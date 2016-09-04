<?php
session_start();
unset($_SESSION["vacupdatesessionid"]);
header("Location: index.php");
die();
?>