<?php
include_once('lib/db.inc.php');

function myLib_categories_fetchall() {
	global $db;
	$db = myLib_DB();
	$q = $db->prepare("SELECT * FROM categories LIMIT 100;");
	if ($q->execute())
		return $q->fetchAll();
}

 function myLib_products_fetchall() {
	global $db;
	$db = myLib_DB();
	$q = $db->prepare("SELECT * FROM products LIMIT 100;");
	return ($q->execute())
		? $q->fetchAll()
		: array();
} 

function myLib_categories_insert() {
	if (!preg_match('/^\w+$/', $_POST['name']))
		throw new Exception("invalid-name");

	global $db;
	$db = myLib_DB();
	$q = $db->prepare("INSERT INTO categories (name) VALUES (?)");
	return $q->execute(array($_POST['name']));

}

function myLib_categories_update() {
	if (!preg_match('/^[\w\s]+$/', $_POST['name']))
		throw new Exception("invalid-name");

	global $db;
	$db = myLib_DB();
	$temp=$_POST['catid'];
	$q = $db->prepare("UPDATE categories SET name = ? WHERE catid =$temp");
	return $q->execute(array($_POST['name']));
}

function myLib_categories_delete() {
	global $db;
	$db = myLib_DB();
	$temp=$_POST['catid'];
	$q = $db->prepare("DELETE FROM categories WHERE catid =$temp");
	return $q->execute();
}



function myLib_products_insert() {
    
	global $db;
	$db = myLib_DB();

	// input validations
    if (!preg_match('/^\w+$/', $_POST['name']))
	throw new Exception("invalid-name");
	// db insert command here
    $q = $db->prepare("INSERT INTO products (catid, name, price, description) VALUES (?, ?, ?, ?)");
	$q->execute(array($_POST['catid'], $_POST['name'], $_POST['price'], $_POST['description']));
	// Instead of the line below, should use: $lastId = $db->lastInsertId();
	$lastId = $db->lastInsertId();
	//$q = $db->prepare("UPDATE products SET picnumber = $lastId WHERE proid = $lastId");
	//$q->execute();


	if ($_FILES["file"]["error"] == 0
		&& ($_FILES["file"]["type"] == "image/jpeg" || $_FILES["file"]["type"] == "image/gif" || $_FILES["file"]["type"] == "image/png")
		&& $_FILES["file"]["size"] < 5000000) {
		// you need to take care of the permission of destination folder
		if (move_uploaded_file($_FILES["file"]["tmp_name"], "incl/prod/" . $lastId . ".jpg")) {
			// get back to original page; comment it during debug
			header('Location: admin.html');
			exit();
		}

	}

	throw new Exception('invalid-file');
}

function myLib_products_delete() {
	global $db;
	$db = myLib_DB();
	$temp=$_POST['pid'];
	$q = $db->prepare("DELETE FROM products WHERE pid =$temp");
	return $q->execute();
}


header('Content-Type: application/json');

if (empty($_REQUEST['action']) || !preg_match('/^\w+$/', $_REQUEST['action'])) {
	echo json_encode(array('failed'=>'undefined'));
	exit();
}

try {
	if (($returnVal = call_user_func('myLib_' . $_REQUEST['action'])) === false) {
		if ($db && $db->errorCode()) error_log(print_r($db->errorInfo(), true));
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
