<html>
<head>
</head>
<body>
<?php

$loginusername = 'root';
$loginpassword = '';
$server = 'localhost';
$error = 'Could not connect';
$database='users';
if(!(@mysql_connect($server,$loginusername,$loginpassword)) || !(@mysql_select_db($database)))
{
    die($error);
}

?>
</body>
</html>