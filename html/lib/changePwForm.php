<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
	<meta charset="utf-8" />
	<title>IERG4210 Admin Demo</title>
	<link href="incl/admin.css" rel="stylesheet" type="text/css"/>
</head>

<body>

<?php 
echo $_GET['nonce'];
echo $_GET['ac'];
        $db = new PDO('sqlite:/var/www/db/changePw.db');
	    $db->query('PRAGMA foreign_keys = ON;');
     	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    	$q=$db->prepare('Select * From changepw Where email = ?');
	    $q->execute(array($_GET['ac']));
	    $r=$q->fetch();
		if($r['salt']!=$_GET['nonce']) {header('Refresh: 10; url=../loginForm.php?status=invalidLogin');echo 'hacking!!!'.'<br>';echo 'Redirecting to the login Page';}
		else echo'<article id="mainBody">
<fieldset>
	<legend>Change Password</legend>
	<form id="changePw" method="POST" action="../process-user.php?action=changePasswordEmail">
		<label for="newPw">New Password</label>
		<div><input id="newPw" name="newPw" type="text" required="true"/></div>
		<div><input type="hidden" id="ac" name="ac" value="'.$_GET['ac'].'"></div>
		<div><input type="hidden" id="nonce" name="nonce" value="'.$_GET['nonce'].'"></div>
		<input type="submit" value="Submit" />
	</form>
</fieldset>

</article>';
?>



</body>
</html>