<html>
<head>
<title>Login</title>
</head>
<body>
<?php

require 'connecting to database.php';
require 'form.html';
$empty_error = 'Login failed. One or more fields were empty.';
$database_error = 'No such username found.';
function SetEverything($submit, $username, $password)
{
    global $empty_error;
    if (isset($submit))
    {
        if (isset($username) && isset($password))
        {
            if (empty($username) || empty($password))
            {
                die($empty_error);
            }
        }
    }
}

function SendQuery()
{
    global $database_error;
    $query = 'SELECT * FROM `user_info` WHERE `username`="' . $_POST['username'] .
        '" AND `password`="' . $_POST['password'] . '"';
    if ($query_run = mysql_query($query))
    {
        while ($mysql_query = mysql_fetch_assoc($query_run))
        {
            echo 'First name: <b>' . $mysql_query['fname'] . '</b><br />';
            echo '<br />';
            echo 'Last name: <b>' . $mysql_query['lname'] . '</b><br />';
            echo '<br />';
            echo 'Age: <b>' . $mysql_query['age'] . '</b><br />';
            echo '<br />';
            echo 'Sex: <b>' . $mysql_query['sex'] . '</b><br />';
            echo '<br />';
            echo 'Email: <b>' . $mysql_query['email'] . '</b><br />';
            echo '<br />';
            echo 'Mother\'s name: <b>' . $mysql_query['mname'] . '</b><br />';
            echo '<br />';
            echo 'Father\'s name: <b>' . $mysql_query['faname'] . '</b><br />';
            echo '<br />';
            echo 'Mother\'s occupation : <b>' . $mysql_query['moccu'] . '</b><br />';
            echo '<br />';
            echo 'Father\'s occupation : <b>' . $mysql_query['foccu'] . '</b><br />';
            echo '<br />';
            echo 'Phone No.: <b>' . $mysql_query['phone'] . '</b><br />';
            echo '<br />';
            echo 'Whether Married?: <b>' . $mysql_query['married'] . '</b><br />';
            echo '<br />';
        }
    } else
    {
        die($database_error);
    }
}
if (isset($_POST['submit']) && isset($_POST['username']) && isset($_POST['password']))
{
    SetEverything($_POST['submit'], $_POST['username'], $_POST['password']);
    SendQuery();
}

?>
</body>
</html>