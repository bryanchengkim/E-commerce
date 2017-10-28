<?php
include_once('lib/db.inc.php');
include_once('lib/csrf.php');
include_once('lib/auth.php');

session_start();
auth();

function ierg4210_cat_fetchall() {
	// DB manipulation
	global $db;
	$db = ierg4210_DB();
	$q = $db->prepare("SELECT * FROM categories LIMIT 100;");
	if ($q->execute())
		return $q->fetchAll();
}

//start my code

function ierg4210_prod_fetchall() {
	// DB manipulation
	global $db;
	$db = ierg4210_DB();
	$q = $db->prepare("SELECT * FROM products WHERE catid = ? LIMIT 100;");
	if ($q->execute(array($_POST['catid'])))
		return $q->fetchAll();
}

//end my code

function ierg4210_cat_insert() {
	// input validation or sanitization
	if (!preg_match('/^[\w\-, ]+$/', $_POST['name']))
		throw new Exception("invalid-name");
	if (empty($_POST['nonce'])) 
		throw new Exception("empty nonce");
	if (!csrf_verifyNonce('cat_insert',$_POST['nonce']))
		throw new Exception("ur:".$_POST['nonce']."mine:".$_SESSION[$fu]."not equal attack");

	// DB manipulation
	global $db;
	$db = ierg4210_DB();
	$q = $db->prepare("INSERT INTO categories (name) VALUES (?)");
	return $q->execute(array($_POST['name']));
}

function ierg4210_cat_edit() {
	// TODO: complete the rest of this function; it's now always says "successful" without doing anything
	//return true
	
        if (!preg_match('/^[\w\-, ]+$/', $_POST['name']))
                throw new Exception("invalid-name");
        if (empty($_POST['nonce']) || !csrf_verifyNonce('cat_edit',$_POST['nonce']))
		throw new Exception("potential attack");

	// DB manipulation
	global $db;
	$db = ierg4210_DB();
	$id=$_POST['catid'];
	$q = $db->prepare("UPDATE categories SET name=? WHERE catid =$id");
	return $q->execute(array($_POST['name']));
}

function ierg4210_cat_delete() {

	// input validation or sanitization
	$_POST['catid'] = (int) $_POST['catid'];

	// DB manipulation
	global $db;
	$db = ierg4210_DB();
	$q = $db->prepare("DELETE FROM categories WHERE catid = ?");
	return $q->execute(array($_POST['catid']));
}

// Since this form will take file upload, we use the tranditional (simpler) rather than AJAX form submission.
// Therefore, after handling the request (DB insert and file copy), this function then redirects back to admin.php
function ierg4210_prod_insert() {

	//start my code

	// input validation or sanitization
	if (!preg_match('/^[\w\-, ]+$/', $_POST['name']))
		throw new Exception("invalid-name");
	if (!preg_match('/^[\d\.]+$/', $_POST['price']))
		throw new Exception("invalid-price");
	if (!preg_match('/^[\w\!. ]+$/', $_POST['description']))
		throw new Exception("invalid-description");
	if (empty($_POST['nonce']) || !csrf_verifyNonce('prod_insert',$_POST['nonce']))
		throw new Exception("potential attack");
		
//end my code

	// DB manipulation
	global $db;
	$db = ierg4210_DB();
	// TODO: complete the rest of the INSERT command

//start my code

	$q = $db->prepare("INSERT INTO products (catid, name, price, description) VALUES (?, ?, ?, ?)");
	$q->execute(array($_POST['catid'],$_POST['name'],$_POST['price'],$_POST['description']));
	
//end my code

	// The lastInsertId() function returns the pid (primary key) resulted by the last INSERT command
	$lastId = $db->lastInsertId();
	$catId = $_POST['catid'];

	// Copy the uploaded file to a folder which can be publicly accessible at img/[pid].jpg
	if ($_FILES["file"]["error"] == 0

//start my code

		&& ( $_FILES["file"]["type"] == "image/jpeg" || $_FILES["file"]["type"] == "image/jpg" || $_FILES["file"]["type"] == "image/png" || $_FILES["file"]["type"] == "image/gif")
		&& ( mime_content_type($_FILES["file"]["tmp_name"])=="image/jpeg"  || mime_content_type($_FILES["file"]["tmp_name"])=="image/jpg" || mime_content_type($_FILES["file"]["tmp_name"])=="image/png" || mime_content_type($_FILES["file"]["tmp_name"])=="image/gif")
		&& $_FILES["file"]["size"] < 10000000) {

		// Note: Take care of the permission of destination folder (hints: current user is apache)
		if ( $_FILES["file"]["type"] == "image/jpeg"){
			if (move_uploaded_file($_FILES["file"]["tmp_name"], "img/" . $lastId . ".jpeg")) {
			// redirect back to original page; you may comment it during debug
				$q = $db->prepare("UPDATE products SET filename=? WHERE pid=?;");
				$q->execute(array($lastId.".jpeg", $lastId));
			header('Location: admin.php');
			exit();
			}
		}	
		else if ( $_FILES["file"]["type"] == "image/jpg"){
			if (move_uploaded_file($_FILES["file"]["tmp_name"], "img/" . $lastId . ".jpeg")) {
			// redirect back to original page; you may comment it during debug
				$q = $db->prepare("UPDATE products SET filename=? WHERE pid=?;");
				$q->execute(array($lastId.".jpeg", $lastId));
				header('Location: admin.php');
				exit();
			}
		}		
		else if ( $_FILES["file"]["type"] == "image/png") {
			if (move_uploaded_file($_FILES["file"]["tmp_name"], "img/" . $lastId . ".png")) {
				$q = $db->prepare("UPDATE products SET filename=? WHERE pid=?;");
				$q->execute(array($lastId.".png", $lastId));
				header('Location: admin.php');
				exit();
			}
		}
		else if ( $_FILES["file"]["type"] == "image/gif") {
			if (move_uploaded_file($_FILES["file"]["tmp_name"], "img/" . $lastId . ".gif")) {
				$q = $db->prepare("UPDATE products SET filename=? WHERE pid=?;");
				$q->execute(array($lastId.".gif", $lastId));
				header('Location: admin.php');
				exit();
			}
		}
		throw new Exception('invalid-file');
	}

//end my code

	// Only an invalid file will result in the execution below
	
	// TODO: remove the SQL record that was just inserted
	
	
	// To replace the content-type header which was json and output an error message
	header('Content-Type: text/html; charset=utf-8');
	echo 'Invalid file detected. <br/><a href="javascript:history.back();">Back to admin panel.</a>';
	exit();
}

// TODO: add other functions here to make the whole application complete

//start my code

function ierg4210_prod_delete() {
	// input validation or santization
	$_POST['pid'] = (int) $_POST['pid'];

	// DB manipulation
	global $db;
	$db = ierg4210_DB();
	$q = $db->prepare("DELETE FROM products WHERE pid = ?");
	return $q->execute(array($_POST['pid']));
}

function ierg4210_prod_edit() {
	if (!preg_match('/^[\w\-, ]+$/', $_POST['name']))
		throw new Exception("invalid-name");
	if (!preg_match('/^[\d\.]+$/', $_POST['price']))
		throw new Exception("invalid-price");
	if (!preg_match('/^[\w\!. ]+$/', $_POST['description']))
		throw new Exception("invalid");
	if (empty($_POST['nonce']) || !csrf_verifyNonce('prod_edit',$_POST['nonce']))
		throw new Exception("potential attack");
			
	global $db;
	$db = ierg4210_DB();
	
	$q = $db->prepare("UPDATE products SET catid=?, name=?, price=?, description=?  WHERE pid=?");
	$q->execute(array($_POST['catid'],$_POST['name'],$_POST['price'],$_POST['description'],$_POST['pid']));
		
	$lastId = $_POST['pid'];

	// Copy the uploaded file to a folder which can be publicly accessible at img/[pid].jpg
	if ($_FILES["file"]["error"] == 0
		&& ( $_FILES["file"]["type"] == "image/jpeg" ||  $_FILES["file"]["type"] == "image/jpg" || $_FILES["file"]["type"] == "image/png" || $_FILES["file"]["type"] == "image/gif")
		&& ( mime_content_type($_FILES["file"]["tmp_name"])=="image/jpeg"  || mime_content_type($_FILES["file"]["tmp_name"])=="image/jpg" || mime_content_type($_FILES["file"]["tmp_name"])=="image/png" || mime_content_type($_FILES["file"]["tmp_name"])=="image/gif")
		&& $_FILES["file"]["size"] < 10000000) {
		// Note: Take care of the permission of destination folder (hints: current user is apache)
		
		if ( $_FILES["file"]["type"] == "image/jpeg"){
			echo 'hit';
			if (move_uploaded_file($_FILES["file"]["tmp_name"], "img/" . $lastId . ".jpeg")) {
			// redirect back to original page; you may comment it during debug
				$q = $db->prepare("UPDATE products SET filename=? WHERE pid=?;");
				$q->execute(array("" . $lastId.".jpeg", $lastId));
			header('Location: admin.php');
			exit();
			}
		}		
		else if ( $_FILES["file"]["type"] == "image/jpg" ){
		
			if (move_uploaded_file($_FILES["file"]["tmp_name"], "img/" . $lastId . ".jpg")) {
			// redirect back to original page; you may comment it during debug
				$q = $db->prepare("UPDATE products SET filename=? WHERE pid=?;");
				$q->execute(array("" . $lastId.".jpg", $lastId));
			header('Location: admin.php');
			exit();
			}
		}
		else if ( $_FILES["file"]["type"] == "image/png") 
		{
			if (move_uploaded_file($_FILES["file"]["tmp_name"], "img/" . $lastId . ".png")) 
			{

				$q = $db->prepare("UPDATE products SET filename=? WHERE pid=?;");
				$q->execute(array($lastId.".png", $lastId));
				header('Location: admin.php');
				exit();
			}
		}
		else if ( $_FILES["file"]["type"] == "image/gif") 
		{
			if (move_uploaded_file($_FILES["file"]["tmp_name"], "img/" . $lastId . ".gif")) 
			{
				$q = $db->prepare("UPDATE products SET filename=? WHERE pid=?;");
				$q->execute(array($lastId.".gif", $lastId));
				header('Location: admin.php');
				exit();
			}
		}
		throw new Exception('invalid-file');
	}
	else if ($_FILES["file"]["error"] != 0)
	{
		header('Location: admin.php');
		exit();
	}

	// Only an invalid file will result in the execution below
	// To replace the content-type header which was json and output an error message
	header('Content-Type: text/html; charset=utf-8');
	echo 'Invalid file detected. <br/><a href="javascript:history.back();">Back to admin panel.</a>';
	exit();
}

//end my code

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

		// check if the form request can present a valid nonce
		include_once('lib/csrf.php');
		csrf_verifyNonce($_REQUEST['action'], $_POST['nonce']);
	}
	echo 'while(1);' . json_encode(array('success' => $returnVal));
} catch(PDOException $e) {
	error_log($e->getMessage());
	echo json_encode(array('failed'=>'error-db'));
} catch(Exception $e) {
	echo 'while(1);' . json_encode(array('failed' => $e->getMessage()));
}

?>
