<?php

session_start();
//If the session is already set, redirect the user to index.php
if (isset($_SESSION['username']) && isset($_SESSION['password']))
{
    header('Location: index.php');
} else
{
    require 'connecting to database.php';
    require 'login.html';
    //The main query function
    function sendQuery()
    {
        if (isset($_POST['username']) && isset($_POST['password']))
        {
            $username = htmlentities($_POST['username']); //Using htmlentities() function to render HTML as text.
            $password = htmlentities($_POST['password']);
        }
        $query_login = 'SELECT `username` , `password` FROM `users` WHERE `username`="' .
            mysql_real_escape_string($username).'"'; //Using mysql_real_escape_string function to prevent SQL Injection attacks.
        if ($query = mysql_query($query_login))
        {
            if(mysql_num_rows($query)==0)            //If the query returns 0 rows, i.e, no data, then print error message.
            {
                echo 'Invalid username/password';
            }
            while ($query_run = mysql_fetch_assoc($query))  
            {
                if (password_verify($password, $query_run['password'])) //Checks the hash for the password
                {
                    $_SESSION['username'] = $username;
                    $_SESSION['password'] = $password;
                    header('Location: index.php');
                    exit;
                } else
                {
                    echo 'Invalid username/password.';
                    session_destroy();
                }
            }
        }
    }
    if (isset($_POST['submit']))
    {
        sendQuery();
    }
}

?>