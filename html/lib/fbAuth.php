<?php 
   $app_id = "350488878321608";
   $app_secret = "d5b7c1bf546b1dcd7c0fbf1e1b4357ba";
   $my_url = "http://www.shop45.ierg4210.org/shop.php";

   session_start();
   $code = $_REQUEST["code"];

   if(empty($code)) {
     $_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
     $dialog_url = "https://www.facebook.com/dialog/oauth?client_id=" 
       . $app_id . "&redirect_uri=" . urlencode($my_url) . "&state="
       . $_SESSION['state'];

     echo("<script> top.location.href='" . $dialog_url . "'</script>");
   }

   if($_REQUEST['state'] == $_SESSION['state']) {
     $token_url = "https://graph.facebook.com/oauth/access_token?"
       . "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
       . "&client_secret=" . $app_secret . "&code=" . $code;

     $response = file_get_contents($token_url);
     $params = null;
     parse_str($response, $params);

     $graph_url = "https://graph.facebook.com/me?access_token=" 
       . $params['access_token'];

     $user = json_decode(file_get_contents($graph_url));
	 //foreach($user as $temp)
     //echo("Hello " . $temp."</br>");
	 
	 $exp = time() + 3600 * 24 * 3; // 3days
	 setcookie('fbn', json_encode($user->name), $exp);
	 header('Location: ../shop.php');
   }
   else {
     echo("The state does not match. You may be a victim of CSRF.");
   }
?>