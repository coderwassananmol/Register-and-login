<?php

session_start();
//If the session is already set, redirect the user to the index.php page.
if (isset($_SESSION['username']) && isset($_POST['password']))
{
    header('Location: index.php');
    exit;
} else
{
    ob_start();
    require 'connecting to database.php';
    require 'register.html';
    if (isset($_POST['username'], $_POST['password1'], $_POST['password2'], $_POST['firstname'],
        $_POST['lastname'], $_POST['age'], $_POST['sex'], $_POST['email'], $_POST['fathername'],
        $_POST['mothername'], $_POST['fatheroccupation'], $_POST['motheroccupation'], $_POST['phone'],
        $_POST['g-recaptcha-response']))
    {
        $username = htmlentities($_POST['username']);
        $password1 = htmlentities($_POST['password1']);
        $password2 = htmlentities($_POST['password2']);
        $firstname = htmlentities($_POST['firstname']);
        $lastname = htmlentities($_POST['lastname']);
        $age = htmlentities($_POST['age']);
        $sex = htmlentities($_POST['sex']);
        $email = htmlentities($_POST['email']);
        $fathername = htmlentities($_POST['fathername']);
        $mothername = htmlentities($_POST['mothername']);
        $fatheroccupation = htmlentities($_POST['fatheroccupation']);
        $motheroccupation = htmlentities($_POST['motheroccupation']);
        $phone = htmlentities($_POST['phone']);
        $captcha = htmlentities($_POST['g-recaptcha-response']);
        $hash = password_hash($password1, PASSWORD_DEFAULT);
    }
    //Declare the "flag" variable. Being used in various functions.
    $flag = 0;
    //Checks if any of the fields are empty or not.
    function ifEmpty()
    {
        global $username, $password1, $password2, $firstname, $lastname, $age, $sex, $email,
            $fathername, $mothername, $fatheroccupation, $motheroccupation, $phone, $flag;
        if (empty($username) || empty($password1) || empty($password2) || empty($firstname) ||
            empty($lastname) || empty($age) || empty($sex) || empty($email) || empty($fathername) ||
            empty($mothername) || empty($fatheroccupation) || empty($motheroccupation) ||
            empty($phone))
        {
            echo '<font color="red">*</font> <strong>Marked fields are mandatory.</strong> <br />';
            $flag = 1;
        }
    }
    //Checks both the password fields for equality.
    function checkPwd($pwd1, $pwd2)
    {
        global $flag;
        if (strcmp($pwd1, $pwd2) != 0)
        {
            echo '<strong>Passwords</strong> do not match. <br />';
            $flag = 1;
        }
    }
    //Checks whether the username , email or phone already exists in the database.
    function sendCheckQuery()
    {
        global $flag, $username, $phone, $email;
        $query_check_username = 'SELECT `username` FROM `users` WHERE `username`="' . $_POST['username'] .
            '"';
        if ($query_send_check_username = mysql_query($query_check_username))
        {
            if (mysql_num_rows($query_send_check_username) == 1)
            {
                echo '<strong>Username</strong> already exists. <br />';
                $flag = 1;
            }
        }
        $query_check_email = 'SELECT `email` FROM `users` WHERE `email`="' . $_POST['email'] .
            '"';
        if ($query_send_check_email = mysql_query($query_check_email))
        {
            if (mysql_num_rows($query_send_check_email) == 1)
            {
                echo '<strong>E-Mail</strong> already exists. <br />';
                $flag = 1;
            }
        }
        $query_check_phone = 'SELECT `phone` FROM `users` WHERE `phone`=' . $_POST['phone'] .
            '"';
        if ($query_send_check_phone = mysql_query($query_check_phone))
        {
            if (mysql_num_rows($query_send_check_phone) == 1)
            {
                echo '<strong>Phone no.</strong> already exists. <br />';
                $flag = 1;
            }
        }
    }
    //Print out statement if the corresponding fields are invalid.
    function isInvalid($regex)
    {
        echo '<strong>Invalid: </strong><b><font color="red">' . $regex .
            '</font></b><br />';
    }
    //Gets the IP address of the user.
    function getIP()
    {
        @$ip1 = $_SERVER['REMOTE_ADDR'];
        @$ip2 = $_SERVER['HTTP_CLIENT_IP'];
        @$ip3 = $_SERVER['HTTP_X_FORWARDED_FOR'];
        if (!empty($ip2))
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } else
            if (!empty($ip3))
            {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else
            {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
            return $ip;
    }
    //Sends the query to update the database (only if everything is correct)
    function sendQuery()
    {
        global $username, $password1, $firstname, $lastname, $age, $sex, $email, $fathername,
            $mothername, $fatheroccupation, $motheroccupation, $phone, $hash;
        $query = 'INSERT INTO `users` (`username`, `password`, `firstname`, `lastname`, `age`, `sex`, `email`, `fathername`, `mothername`, `fatheroccupation`, `motheroccupation`, `phone`, `id`) 
    VALUES ("' . mysql_real_escape_string($username) . '", "' .
            mysql_real_escape_string($hash) . '", "' . mysql_real_escape_string($firstname) .
            '", "' . mysql_real_escape_string($lastname) . '", "' . mysql_real_escape_string($age) .
            '", "' . mysql_real_escape_string($sex) . '", "' . mysql_real_escape_string($email) .
            '", "' . mysql_real_escape_string($fathername) . '", "' .
            mysql_real_escape_string($mothername) . '", "' . mysql_real_escape_string($fatheroccupation) .
            '", "' . mysql_real_escape_string($motheroccupation) . '", "' .
            mysql_real_escape_string($phone) . '", NULL)';
        if (mysql_query($query))
        {
            header('Location: regsuccess.html');
        } else
        {
            header('Location: regfailure.html');
        }
    }
    //Checks and verifies the captcha
    function checkCaptcha()
    {
        global $captcha, $flag;
        $secret = '6LefoigTAAAAAHfmwiJ2CLnMUaOmDzuO51M8bEvd';
        $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' .
            $secret . '&response=' . $captcha . '&remoteip=' . getIP());
        if (@!$response)
        {
            echo '<strong>Please verify yourself.</strong> <br>';
            $flag = 1;
        } else
        {
            $json = json_decode($response, true);
            if ($json['success'])
            {
                $flag = 0;
            } else
            {
                $flag = 1;
            }
        }
    }
    /*
    -User submits form.
    -It checks for the empty condition.
    -It checks the password equality.
    -It checks whether the values already exist in the database.
    -Checks for the invalid conditions and calls the isInvalid() function.
    -If everything is true (i.e $flag=0), it sends the query to database. 
    */
    if (isset($_POST['submit']))
    {
        ifEmpty();
        checkPwd($password1, $password2);
        sendCheckQuery();
        checkCaptcha();
        if (!preg_match('/^\d{10}$/', $phone) && !empty($phone))
        {
            isInvalid('Phone');
            $flag = 1;
        }
        if (!preg_match('/^[a-zA-Z]{1,30}$/', $firstname) && !empty($firstname))
        {
            isInvalid('First Name');
            $flag = 1;
        }
        if (!preg_match('/^[a-zA-Z]{1,30}$/', $lastname) && !empty($lastname))
        {
            isInvalid('Last Name');
            $flag = 1;
        }
        if (!preg_match('/^[a-zA-Z]{1,30}$/', $fathername) && !empty($fathername))
        {
            isInvalid('Father Name');
            $flag = 1;
        }
        if (!preg_match('/^[a-zA-Z]{1,30}$/', $mothername) && !empty($mothername))
        {
            isInvalid('Mother Name');
            $flag = 1;
        }
        if (!preg_match('/^[a-zA-Z]{1,20}$/', $fatheroccupation) && !empty($fatheroccupation))
        {
            isInvalid('Father Occupation');
            $flag = 1;
        }
        if (!preg_match('/^[a-zA-Z]{1,20}$/', $motheroccupation) && !empty($motheroccupation))
        {
            isInvalid('Mother Occuption');
            $flag = 1;
        }
        if ($flag == 0)
        {
            sendQuery();
        }
    }
    ob_end_flush();
}

?>
