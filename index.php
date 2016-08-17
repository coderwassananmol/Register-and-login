<!DOCTYPE HTML>
<html lang="en">
<head>
<style>
.input {
    background-color: #4CAF50;
    border: none;
    color: white;
    padding: 8px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
}
</style>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body>
<?php

session_start();
//If the session is set, welcome the user.
if (isset($_SESSION['username']) && isset($_SESSION['password']))
{
    require 'connecting to database.php';
    echo 'Welcome, ' . $_SESSION['username'] . '<br />';
    echo '<form action="logout.php" method="post"><input type="submit" class="input" name="submit" value="Log me out!"></form>';
}
//If the session is unset, and user tries to access the file, redirect him to index.php
else
{
    session_destroy();
    header('Location: userlogin.php');
}

?>
</body>
</html>