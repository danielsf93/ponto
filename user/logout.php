<?php
// ponto/logout.php
session_start();
session_destroy();
header("Location: /ponto/index.php");
exit();
?>
