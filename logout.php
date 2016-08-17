<?php

session_start();
//If the session is set, log him out.
if(isset($_SESSION['username']) && isset($_SESSION['password']))
{
    session_destroy();
    header('Location: userlogin.php');
    exit;
}
//If the session is unset, redirect him to index.php which will further redirect the user to userlogin.php
else
{
    header('Location: index.php');
    exit;
}
?>