<?php


function ierg4210_buildOrder() {
	// if(empty($_POST['_nonce']) || !checkNonce('checkOut',$_POST['_nonce']))
	// throw new Exception('potential attack');
	
	$list = $_REQUEST['list'];
	error_log(print_r($list,true));
	$salt = mt_rand().time();
	$current ='HKD';
	$business = 'ierg4210test-facitator@gmail.com';
	$digest = sha1($salt.@$business.@$current.@$list);

	$db = new PDO ('sqlite:/var/www/order.db');
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$q = $db->prepare("INSERT INTO invoice (salt, digest,list) VALUES (?,?,?)");
	if (!($q->execute(array($salt,$digest,$list)))) 
		throw new Exception ("I will get you");
	$iid=$db->lastInsertId();
	$q = $db->prepare("UPDATE invoice SET check ='0' where id = $iid");
	return (implode(',',array($digest,$iid)));

}


header('Content-Type: application/json');

// input validation
if (empty($_REQUEST['action']) || !preg_match('/^\w+$/', $_REQUEST['action'])) {
	echo json_encode(array('failed'=>'undefined'));
	exit();
}

// The following calls the appropriate function based to the request parameter $_REQUEST['action'],
//   (e.g. When $_REQUEST['action'] is 'cat_insert', the function ierg4210_cat_insert() is called)
// the return values of the functions are then encoded in JSON format and used as output
try {
	if (($returnVal = call_user_func('ierg4210_' . $_REQUEST['action'])) === false) {
		if ($db && $db->errorCode()) 
			error_log(print_r($db->errorInfo(), true));
		echo json_encode(array('failed'=>'1'));
	}
	echo 'while(1);' . json_encode(array('success' => $returnVal));
} catch(PDOException $e) {
	error_log($e->getMessage());
	echo json_encode(array('failed'=>'error-db'));
} catch(Exception $e) {
	echo 'while(1);' . json_encode(array('failed' => $e->getMessage()));
}



?>

