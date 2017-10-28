<?php
include_once('lib/db.inc.php');
session_start();


function auth(){
if(!empty($_SESSION['auth']))
	return $_SESSION['auth']['em'];
	if(!empty($_COOKIE['auth'])){
		if($t = json_decode(stripslashes($_COOKIE['auth']),true)){
			if (time() > $t['exp']) return false;
			global $db;
			$db = ierg4210_DB();
			$q = $db->prepare('SELECT * FROM user WHERE email = ?');
			$q->execute(array($t['em']));
			if($r = $q->fetch()){
				$realk =hash_hmac('sha1',$t['exp'].$r['password'], $r['salt']);
				if($realk == $t['k']){
					$_SESSION['auth'] = $t;
					return $t['em'];
				}
			}
		}
	}
	header('Location:login.php'); 
	exit();
}
?>