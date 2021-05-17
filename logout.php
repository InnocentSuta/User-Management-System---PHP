<?php
 require_once 'core/init.php';
 
 unset($_SESSION['SBUser']);
//  session_destroy();
 header("Location: login.php");

?>
