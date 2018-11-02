<!DOCTYPE html>
<html>
<head>
    <title>Generate Link for new tab</title>
    </head>
<body>

<h2>This is Your VIRTUAL INBOX</h2>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username=$_REQUEST["username"];
    echo 'Hi '.$username.', somebody requested the password reset for your account.<br>';
    echo 'Click on this link to generate a new password:<a href="ret.php?user_name='.$username.'"> retrieve_password.</a>';
}
?>

</body>
</html>
