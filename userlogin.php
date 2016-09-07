<?php

session_start();
//If the session is already set, redirect the user to index.php
if (isset($_SESSION['username']) && isset($_SESSION['password']))
{
    header('Location: index.php');
    exit;
} else
{
    $pass = '';
    require 'connecting to database.php';
    require 'login.html';
    //The main query function
    function sendQuery()
    {
        global $connect_db;
        if (isset($_POST['username']) && isset($_POST['password']))
        {
            $username = htmlentities($_POST['username']); //Using htmlentities() function to render HTML as text.
            $password = htmlentities($_POST['password']);
        }
        if ($query_login = $connect_db->prepare('SELECT `username` , `password` FROM `users` WHERE `username`=?'))
        {
            $query_login->bind_param('s', $username);
            if ($query_login->execute())
            {
                $numrows=$query_login->num_rows;
                if($numrows==0)
                    {
                        echo 'Invalid username/password. <br />';
                    }
                $query_login->bind_result($username, $pass);
                while ($query_login->fetch())
                {
                    if(password_verify($password, $pass))
                    {
                        $_SESSION['username']=$username;
                        $_SESSION['password']=$password;
                        header('Location: index.php');
                        exit;
                    } else if($numrows!=0 && !password_verify($password, $pass))
                    {
                        echo 'Invalid username/password. <br />';
                        session_destroy();
                    }
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
