(function(){
    var el = function(A){return document.getElementById(A)};
	var adminLib = window.adminLib= (window.adminLib || {});
	
	adminLib.changePw = function()
	{
	
	    var html=new Array();
		html.push('<fieldset><legend>Change Password</legend>');
		html.push('<form id="changePwForm" method="POST" action="auth-process.php?action=<?php echo ($action = 'ChangePassword'); ?>">');
		html.push('<label for="oldPw">New password</label>');
		html.push('<div><input id="oldPw" name="oldPw" required="true" /></div>');
		html.push('<label for="newPw">New password</label>');
		html.push('<div><input id="newPw" name="newPw" required="true" /></div>');
		html.push('<label for="newPw2">New password</label>');
		html.push('<div><input id="newPw2" name="newPw2" required="true" /></div>');
		html.push('<input type="submit" value="Submit" />');
		html.push('</form></fieldset>');
		el('mainBody').innerHTML = html.join('');
	}
	
	adminLib.changePwE=function()
	{
	    var html=new Array();
		html.push('<fieldset><legend>Change Password(email)</legend>');
		html.push('<form id="changePwEForm" method="POST" action="process-gmail.php?action=ChangePasswordEmail">');
		html.push('<label for="email">Email: </label>');
		html.push('<div><input id="email" name="email" required="true" /></div>');
		html.push('<input type="submit" value="Submit" />');
		html.push('</form></fieldset>');
		el('mainBody').innerHTML = html.join('');
	}
	
})();