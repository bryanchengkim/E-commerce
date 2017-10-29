<?php

include_once('auth.php');
echo 'hello0';
$db1=newDB();
echo 'hello1';
$email='sysschm@gmail.com';
$pastword='fuckyou';
$salt=mt_rand();
$saltPassword=sha1($salt.$pastword);
echo 'hello2';
$q=$db1->prepare("INSERT INTO account (email, salt, password) VALUES (?,?,?)");
echo 'hello2.0';
if ($q->execute(array($email, $salt, $saltPassword)))
    echo 'hello3';
?>
<html>
<head>
	<meta charset="utf-8" />
	<title>Register</title>
</head>
<body><h1>Register</h1>

</body>
</html>