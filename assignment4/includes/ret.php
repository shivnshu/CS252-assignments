<!DOCTYPE html>
<html>
<head>
</head>
<body>
<?php
include_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {

$username=$_GET["user_name"];
$sq = 'SELECT * FROM members WHERE username="'.$username.'";';
$result = $mysqli->query($sq);
while($row = $result->fetch_assoc()) {
               
}
}
else if($_SERVER["REQUEST_METHOD"] == "POST"){
$username= $_GET['user_name'];
echo $username;
}



?>
<h2>WELCOME TO PASSWORD RETREIVAL SERVICE</h2>
<form action="" method="post" name="login_form"> 			
            Enter New Password: <input type="text" name="pass" />
            Confirm new password: <input type="password" 
                             name="password" 
                             id="password"/>
            <button type='submit'>Change Password</button> 
        </form>
</body>
</html>