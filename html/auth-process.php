<?php
//init $_SESSION
session_start();

//start my code
include_once('lib/db.inc.php');
include_once('lib/checkuser.php');
include_once('lib/changepw.php');
//end my code

function ierg4210_login(){
	if (empty($_POST['email']) || empty($_POST['pw']) 
		|| !preg_match("/^[\w=+\-\/][\w='+\-\/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$/", $_POST['email'])
		|| !preg_match("/^[\w@#$%\^\&\*\-]+$/", $_POST['pw']))
		throw new Exception("Wrong Credentials");
	
	// Implement the login logic here
	//start my code
	global $db;
	$db = ierg4210_DB();
	$q = $db->prepare('SELECT * FROM user WHERE email = ?');
	
	if ($q->execute(array($_POST['email']))
		&& ($r = $q->fetch())
		&& $r['password'] == hash_hmac('sha1', $_POST['pw'], $r['salt'])){ 
		//check if the hash of the password is same as saved in database
		//if yes, create authentication information in cookies and session
			echo'reach';
			$exp = time() + 3600*24*3;
			$token = array(
				'em'=>$r['email'],
				'exp'=>$exp,
				'k'=>hash_hmac('sha1',$exp.$r['password'],$r['salt']));
		setcookie('auth', json_encode($token),$exp,'','',true,true);
		$_SESSION['auth'] = $token;
		session_regenerate_id();
		header('Location:admin.php', true, 302);
		exit();
		}
	return false;
	}
	//end my code

function ierg4210_logout(){
	// clear the cookies and session
	
	//start my code
	$exp = time()-3600;
	setcookie('auth'," ",$exp);
	$_SESSION = array();
	session_destroy();
	//end my code
	
	// redirect to login page after logout
	header('Location: login.php', true, 302);
	exit();
}
function ierg4210_ChangePassword(){
	$email=$_SESSION['auth'];
	$email=$email['em'];
	global $db;
	$db = ierg4210_DB();
	$q=$db->prepare('Select * From user Where email = ?');
	$q->execute(array($email));
	if($r=$q->fetch()){
			$saltPassword=hash_hmac('sha1', $_POST['oldPw'], $r['salt']);
			//echo "i make it!!!!<br>";
			if($saltPassword == $r['password']){ 
				//echo 'bingo';
				//$db = ierg4210_DB();
				//$db->query('PRAGMA foreign_keys = ON;');
			    //$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
				$q=$db->prepare('Select * From user Where email = ?');
				$q->execute(array($email));
				$r=$q->fetch();
				if($_POST['newPw']==$_POST['newPw2'])
				{
					$newPassword=hash_hmac('sha1', $_POST['newPw'], $r['salt']);
					$q=$db->prepare("UPDATE user SET password = ? WHERE email =?");
					if($q->execute(array($newPassword, $email)))
						echo "done!!!!<br>";
					ierg4210_Logout();
					return true;
				}
				echo "new password not match<br>";
				return false;
			}
			echo "incorrect password<br>";
			return false;
	}
	echo "incorrect username<br>";
	return false;
}


header("Content-type: text/html; charset=utf-8");

try {
	// input validation
	if (empty($_REQUEST['action']) || !preg_match('/^\w+$/', $_REQUEST['action']))
		throw new Exception('Undefined Action');
	
	// check if the form request can present a valid nonce
	include_once('lib/csrf.php');
	csrf_verifyNonce($_REQUEST['action'], $_POST['nonce']);
	
	// run the corresponding function according to action
	if (($returnVal = call_user_func('ierg4210_' . $_REQUEST['action'])) === false) {
		if ($db && $db->errorCode()) 
			error_log(print_r($db->errorInfo(), true));
		throw new Exception('Failed');
	} else {
		// no functions are supposed to return anything
		// echo $returnVal;
	}

} catch(PDOException $e) {
	error_log($e->getMessage());
	header('Refresh: 10; url=login.php?error=db');
	echo '<strong>Error Occurred:</strong> DB <br/>Redirecting to login page in 10 seconds...';
} catch(Exception $e) {
	header('Refresh: 10; url=login.php?error=' . $e->getMessage());
	echo '<strong>Error Occurred:</strong> ' . $e->getMessage() . '<br/>Redirecting to login page in 10 seconds...';
}
?>