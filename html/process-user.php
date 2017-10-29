<?php
include_once('lib/nonce.php');
include_once('lib/auth.php');
//handling the user request. Related to loginForm.php
function handleUser_Login(){
	if(empty($_POST['_nonce']) || !checkNonce($_REQUEST['action'],$_POST['_nonce']))
		throw new Exception('potential CSRF attack');
	if(empty($_POST['email'])||empty($_POST['pw'])||!preg_match("/^[\w=+\-\/][\w='+\-\/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$/",$_POST['email']))
		throw new Exception('wrong input');
	$result=userLogin($_POST['email'],$_POST['pw']);
	if($result){
		session_regenerate_id();
		header('Location: admin.php');
		exit();
	}		
	return false;
}
function handleUser_Logout(){
    setcookie('authtoken', '', -1);
    session_destroy();
	header('Location: loginForm.php');
    return true;	
}

function handleUser_ChangePassword(){
	
	$email=$_SESSION['authtoken'];
	$email=$email['em'];
	$result=userLogin($email,$_POST['oldPw']);
	if($result){
	    $db = new PDO('sqlite:/var/www/account.db');
		$db->query('PRAGMA foreign_keys = ON;');
	    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		/*
		$q = $db->query('SELECT * FROM account WHERE email='.$email);
		
		echo $q['email'];
		
		*/
		echo'fuckk';
		$q=$db->prepare('Select * From account Where email = ?');
		$q->execute(array($email));
		$r=$q->fetch();
		$saltPassword=sha1($r['salt'].$_POST['newPw']);
		$q=$db->prepare("UPDATE account SET password = ? WHERE email =?");
		
		if($q->execute(array($saltPassword, $email))) echo "hello3";
		
		handleUser_Logout();
		/*
		$db->query("UPDATE account SET password = ".$saltPassword." WHERE email =".$email);
        echo $saltPassword;
		*/
	}
	
	return true;
}

function handleUser_ChangePasswordEmail(){
	echo $_POST['ac'];
	echo $_POST['newPw'];
	$db = new PDO('sqlite:/var/www/account.db');
	$db->query('PRAGMA foreign_keys = ON;');
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$q=$db->prepare('Select * From account Where email = ?');
	$q->execute(array($_POST['ac']));
	$r=$q->fetch();
	$saltPassword=sha1($r['salt'].$_POST['newPw']);
	$q=$db->prepare("UPDATE account SET password = ? WHERE email =?");
	if ($q->execute(array($saltPassword, $_POST['ac']))) echo 'ok'; else echo'no';
	
	$db1 = new PDO('sqlite:/var/www/db/changePw.db');
     	$db1->query('PRAGMA foreign_keys = ON;');
	    $db1->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	    $q1=$db1->prepare('delete from changepw where email=?');
	    $q1->execute(array($_POST['ac']));
    handleUser_Logout();
	return true;
}


//You should output different error messages under the following cases: invalid email/pw, service unavailable
function invalidLogin($e){
	header('Refresh: 10; url=loginForm.php?status=invalidLogin');
	echo $e.'<br>';
	echo 'Redirecting to the login Page';
}


header("Content-type: text/html; charset=utf-8");

if(empty($_REQUEST['action']) || !preg_match('/^\w+$/', $_REQUEST['action'])){
	invalidLogin('invalid-request');
}
try{
	if(!call_user_func('handleUser_' . $_REQUEST['action'])){
		invalidLogin('server unavailable or invalid login');
	}
} catch(PDOException $e){
	error_log($e->getMessage());
	invalidLogin('error in db'.$e->getMessage());
} catch(Exception $e){
	invalidLogin($e->getMessage());
}
?>
