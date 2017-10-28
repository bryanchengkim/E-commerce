<?php

function ChangePasswordEmail() 
{
	$clientEmail=$_POST['email'];
	$db = new PDO('sqlite:/var/www/cart.db');
	$db->query('PRAGMA foreign_keys = ON;');
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$q = $db->prepare("SELECT password FROM user WHERE email= ?");
	$q->execute(array($clientEmail));
	if ($r=$q->fetch())
	{
		require_once('lib/class.phpmailer.php');
		// optional, gets called from within class.phpmailer.php if not already loaded
        $mail = new PHPMailer();

        $mail->IsSMTP(); // telling the class to use SMTP
        //$mail->Host = "mail.yourdomain.com"; // SMTP server
        // enables SMTP debug information (for testing) // 1 = errors and messages // 2 = messages only
        $mail->SMTPDebug = 2;
        $mail->SMTPAuth = true;	 // enable SMTP authentication
        $mail->SMTPSecure = "tls";	 // sets the prefix to the servier
        $mail->Host = "smtp.gmail.com";	// sets GMAIL as the SMTP server
        $mail->Port = 587;	 // set the SMTP port for the GMAIL server
        $mail->Username = "ierg4210test@gmail.com"; 
        $mail->Password = "35123512";	
        //$mail->SetFrom("testtest4210@gmail.com", "First Last");
        //$mail->AddReplyTo("donotreplytothisemail@yourdomain.com","First Last");
        $mail->SetFrom('ierg4210test@gmail.com', 'Shop22');
        $mail->AddReplyTo("donotreplytothisemail@yourdomain.com", "Shop45");
        $mail->Subject = "PHPMailer Test Subject via smtp (Gmail), basic";
        // optional, comment out and test
        $mail->IsHTML(true); // send as HTML
        $mail->Subject = "This is the subject";
		
		//================nonce part===========
		$rand=rand(100000, 999999);
		$nonce=md5($rand. $clientEmail);
		$exp=1;
		
		$db1 = new PDO('sqlite:/var/www/db/changepw.db');
     	$db1->query('PRAGMA foreign_keys = ON;');
	    $db1->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	    $q1=$db1->prepare('insert into changepw values(?,?,?)');
	    if (!($q1->execute(array($clientEmail,$nonce,$exp)))) throw new Exception("nothing");
        $mail->Body ='<a href="www.grp22.ierg4210.ie.cuhk.edu.hk/lib/changePwForm.php?nonce='.$nonce.'&ac='.$clientEmail.'">www.grp22.ierg4210.ie.cuhk.edu.hk/lib/changePwForm.php?nonce='.$nonce.'&ac='.$clientEmail.'</a>'; //HTML Body
        $mail->AltBody = "To view the message, please use an HTML compatible email viewer!";
        $mail->AddAddress($clientEmail, "First Last");
        //$mail->AddAttachment("images/phpmailer.gif"); // attachment
        //$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

        if(!$mail->Send()) {echo "Mailer Error: " . $mail->ErrorInfo;}
        else {echo "Message sent!";}
	}
	
	
	return true;
}

function invalid($e){
	header('Refresh: 10; url=admin.php');
	echo $e.'<br>';
	echo 'Redirecting to the admin Page';
}


header("Content-type: text/html; charset=utf-8");

if(empty($_REQUEST['action']) || !preg_match('/^\w+$/', $_REQUEST['action'])){
	invalid('invalid-request');
}
try{
	if(!call_user_func($_REQUEST['action'])){
		invalid('server unavailable');
	}
} catch(PDOException $e){
	error_log($e->getMessage());
	invalid('error in db'.$e->getMessage());
} catch(Exception $e){
	invalid($e->getMessage());
}
?>
