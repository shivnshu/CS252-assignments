<!DOCTYPE html>
<html>
<head>
</head>
<body>
<?php
include_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $username = $_GET["user_name"];
    $secret = $_GET["secret"];
    if ($username == "" || $secret == "") {
        echo "<script>alert('Both username and secret value has to be supplied as GET parameters.');</script>";
        exit();
    }
    $sq = 'SELECT salt FROM members WHERE username="'.$username.'";';
    $result = $mysqli->query($sq);
    if ($row = $result->fetch_assoc()) {
        if ($row["salt"] != $secret) {
            echo "<script>alert('Invalid secret value.');</script>";
            exit();
        }
    }
}
/*
else if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username= $_GET['user_name'];
    $secret = $_GET['secret'];
    $sq = 'SELECT salt FROM members WHERE username="'.$username.'";';
    $result = $mysqli->query($sq);
    if ($row = $result->fetch_assoc()) {
        if ($row["salt"] != $secret) {
            echo "<script>alert('Invalid secret value.');</script>";
            exit();
        }
    }

    echo $username;
}
*/



?>
<h2>WELCOME TO PASSWORD RETREIVAL SERVICE</h2>
<form action="" method="post" name="login_form">
            Enter New Password: <input type="text" name="pass" />
            Confirm new password: <input type="password"
                             name="password"
                             id="password"/>
            <input type="hidden" name="username" value=$username />
            <input type="hidden" name="secret" value=$secret />

            <button type='submit'>Change Password</button>
        </form>
</body>
</html>
