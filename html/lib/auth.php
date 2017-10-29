<?php

session_start();

//login related functions here
//Handle the account related session and cookies here
function newDB(){
	// connect to the database
	$db = new PDO('sqlite:/var/www/account.db');
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	return $db;
}

function userLogin($email, $password){
	//check that the information is correct then create related cookie and session
	//Parts of the code is copied from the Lecture notes 5 Session Management  
		$db=newDB();
		$q=$db->prepare('Select * From account Where email = ?');
		$q->execute(array($email));
		if($r=$q->fetch()){
			//expected format: $pw=sha1($salt.$plainPW);
			$saltPassword=sha1($r['salt'].$password);
			if($saltPassword == $r['password']){ 
				$exp = time() + 3600 * 24 * 3; // 3days 
				$token = array( 'em'=>$r['email'], 'exp'=>$exp,'k'=>sha1($exp . $r['salt'] . $r['password'])); 
				// create the cookie, make it HTTP only 
				setcookie('authtoken', json_encode($token), $exp,'','',false,true); 
				// put it also in the session
				$_SESSION['authtoken'] = $token;
				//$_SESSION['email']=$email;
				return true;
			}
			echo "incorrect password<br>";
			
		return false;
		}
		echo "incorrect username<br>";
	return false;
}

// authorization check
// returns email if success, otherwise, redirect to loginForm.php
function auth(){
	//check if the user is logined here
	//Parts of the code is copied from the Lecture notes 5 Session Management
	if(!empty($_SESSION['authtoken']))
		return $_SESSION['authtoken']['em'];
	if(!empty($_COOKIE['authtoken'])){
		//stripslashes() Returns a string with backslashes stripped off. (\' becomes ' and so on.)
		if($t = json_decode(stripslashes($_COOKIE['authtoken']),true)){ 
			if (time() > $t['exp']) {
				header("Location: loginForm.php");
				exit();
			}// to expire the user
			$db=newDB();
        		$q=$db->prepare('Select * From account Where email = ?');
        		$q->execute(array($t['em']));
			if($r=$q->fetch()){
                		//expected format: $pw=sha1($exp.$salt.$password);
                		$realk=sha1($t['exp'].$r['salt'].$r['password']);
				if($realk == $t['k']){
					$_SESSION['authtoken'] = $t; 
					return $t['em'];
				}
			}
		}
	}
	header("Location: loginForm.php");
	exit();
}
?>
