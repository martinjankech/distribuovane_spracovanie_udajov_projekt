<?php
/* Inicializacia Session */
session_start();
 
/* Zrusenie nastaveny*/
$_SESSION = array();
 
/* Znicenie relacie */
session_destroy();
 
/* Presmerovanie na prihlasovaciu stranku */
header("location: login.php");
exit;
?>