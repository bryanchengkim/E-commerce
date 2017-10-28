<?php
//Nonce related functions here

function generateNonce($formUsed){
	//Generate nonce
	//$time=ceil(time()/43200);	
	$rand=rand(100000, 999999);
	$_SESSION['nonce']=md5($rand. $formUsed);
	return $_SESSION['nonce'];
}
function checkNonce($formUsed,$nonce){
	//Checking nonce. Hint: you should save the nonce on the server
	//$time=ceil(time()/43200);	
    //$current_nonce=md5($time . $formUsed);	
	if ($nonce != $_SESSION['nonce']) return false;
	return true;
}

?>
