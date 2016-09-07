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
$connect_db=new mysqli($server,$loginusername,$loginpassword,$database);
if(@!$connect_db)
{
    die($error);    
}
?>
</body>
</html>
