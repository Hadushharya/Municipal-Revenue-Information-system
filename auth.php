<?php

//session_start();

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['SESS_USER_NAME']) || (trim($_SESSION['SESS_USER_NAME']) == ''))
{
 header("location: ../login?categ=all");
exit();
}

?>