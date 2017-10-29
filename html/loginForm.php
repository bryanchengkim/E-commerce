<?php
include_once('lib/nonce.php');
include_once('lib/auth.php');

$form_id='Login';
?>
<html>
<head>
	<meta charset="utf-8" />
	<title>IERG4210 Shop45 Admin</title>
    <link href="incl/login.css" rel="stylesheet" type="text/css">
</head>
<body>
<fieldset>
    <h3>IERG4210 Shop45 Admin</h3>
	<form id="loginForm" method="POST" action="process-user.php?action=<?php echo $form_id; ?>">
		<label for="email">Email:</label>
		<div><input id="email" type="text" name="email" required placeholder="abcd01@abcd.com" pattern="^[\w=+\-\/][\w=\'+\-\/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$" /></div>
		<label for="pw">Password:</label>
        <div><input id="pw" type="password" name="pw" required placeholder="abcd01"/></div>
        <input type="hidden" id="_nonce" name="_nonce" value="<?php echo generateNonce($form_id); ?>"/>
        <input type="submit" value="Login" />
	</form>
</fieldset>

<script type="text/javascript">
(function(){
var el = function(A){return document.getElementById(A)};
el('loginForm').onsubmit = function(){
	myLib.validate(this);
	return false;
}
})();
</script>
</body>
</html>