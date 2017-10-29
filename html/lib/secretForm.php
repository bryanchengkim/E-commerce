<?php
include_once('auth.php');

// Always enforce proper authorization check for every priviledged page
//auth();

$form_id=Logout;

?>
Hello, admin!!
<html>
<head>
	<meta charset="utf-8" />
	<title>IERG4210 Admin page</title>
</head>
<body><h1>IERG4210 Admin page</h1>
<fieldset>
	<legend>Admin</legend>
	<form id="logoutForm" method="POST" action="../process-user.php?action=<?php echo $form_id; ?>">
		
<input type="submit" value="Logout" />
	</form>
</fieldset>
<script type="text/javascript">
(function(){
var el = function(A){return document.getElementById(A)};
el('logoutForm').onsubmit = function(){
	myLib.validate(this);
	return false;
}
})();
</script>
</body>
</html>