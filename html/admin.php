<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php
    session_start();
    if ($_SESSION['authtoken']==null)
	    header('Location: loginForm.php');
?>

<html>
<head>
	<meta charset="utf-8" />
	<title>ERG4210 Shop45 Admin Page</title>
	<link href="incl/admin.css" rel="stylesheet" type="text/css"/>
</head>

<body>
<h1><a href="shop.php">IERG4210 Shop45 Admin Page</a></h1>



<div id="mainMenu">
    <ul>
    <li><form id="content" method="POST" action="admin.php">
        <input type="submit" value="<?php $email=$_SESSION['authtoken']; $email=$email['em']; echo $email; ?>"></form></li>
    <li><input type="submit" value="Change-Password" onclick="adminLib.changePw()"></li>
    <li><input type="submit" value="Change-Password(email)" onclick="adminLib.changePwE()"></li>
    <li><form id="logoutForm" method="POST" action="process-user.php?action=Logout">
        <input type="submit" value="Logout" />
    </form></li>
    </ul>
</div>

<article id="mainBody">
<fieldset>
	<legend>New Category</legend>
	<form id="cat_insert" method="POST" action="process.php?action=categories_insert" onsubmit="return false;">
		<label for="cat_insert_name">Name</label>
		<div><input id="cat_insert_name" type="text" name="name" required="true" pattern="^\w+$" /></div>

		<input type="submit" value="Submit" />
	</form>
</fieldset>

<fieldset>
	<legend>Delete Category</legend>
	<form id="cat_delete" method="POST" action="process.php?action=categories_delete" onsubmit="return false;">
		<label for="cat_delete_catid">Category</label>
		<div><select id="cat_delete_catid" name="catid"></select></div>
       <input type="submit" value="Delete" />
	</form>
</fieldset>

<fieldset>
	<legend>Update Category</legend>
	<form id="cat_update" method="POST" action="process.php?action=categories_update" onsubmit="return false;">
		<label for="cat_update_catid">Category</label>
		<div><select id="cat_update_catid" name="catid"></select></div>
		<label for="cat_update_name">Name*</label>
		<div><input id="cat_update_name" type="text" name="name" required="true" pattern="^[\w\s]+$" /></div>
        <input type="submit" value="Submit" />
	</form>
</fieldset>

<fieldset>
	<legend>New Product</legend>
	<form id="prod_insert" method="POST" action="process.php?action=products_insert" enctype="multipart/form-data">
		<label for="prod_insert_catid">Category *</label>
		<div><select id="prod_insert_catid" name="catid"></select></div>

		<label for="prod_insert_name">Name *</label>
		<div><input id="prod_insert_name" type="text" name="name" required="true" pattern="^\w+$" /></div>

		<label for="prod_insert_price">Price *</label>
		<div><input id="prod_insert_price" type="number" name="price" required="true" pattern="^[\d\.]+$" /></div>

		<label for="prod_insert_description">Description</label>
		<div><textarea id="prod_insert_description" name="description" pattern="^\w+$"></textarea></div>

		<label for="prod_insert_name">Image *</label>
		<div><input type="file" name="file" required="true" accept="image/jpeg" /></div>

		<input type="submit" value="Submit" />
	</form>
</fieldset>

<fieldset>
	<legend>Delete Product</legend>
	<form id="prod_delete" method="POST" action="process.php?action=products_delete" onsubmit="return false;">
		<label for="prod_delete_pid">Products</label>
		<div><select id="prod_delete_pid" name="pid"></select></div>
		<input type="submit" value="Submit" />
	</form>
</fieldset>


</article>

<script type="text/javascript" src="incl/myLib.js"></script>
<script type="text/javascript" src="incl/adminLib.js"></script>
<script type="text/javascript">
(function(){

var el = function(A){return document.getElementById(A)};

function updateUI() {
	myLib.process({action:'categories_fetchall'}, function(categories){

		for (var html = [], i = 0, cat; cat = categories[i]; i++)
			html.push('<option value="' + parseInt(cat.catid) + '">' + cat.name.escapeHTML() + '</option>');
		el('prod_insert_catid').innerHTML = html.join('');
        el('cat_delete_catid').innerHTML = html.join('');
		el('cat_update_catid').innerHTML = html.join('');
	});
}
updateUI();

function updateUI_2() {
	myLib.process({action:'products_fetchall'}, function(products){

		for (var html = [], i = 0, pro; pro = products[i]; i++)
			html.push('<option value="' + parseInt(pro.pid) + '">' + pro.name.escapeHTML() + '</option>');
		el('prod_delete_pid').innerHTML = html.join('');
		//el('prod_update_pid').innerHTML = html.join('');		

	});
}
updateUI_2(); 

el('cat_insert').onsubmit = function() {
	myLib.validate(this) && myLib.processForm(this, function(){
		updateUI();
	});
	return false;
}

el('cat_delete').onsubmit = function() {
	myLib.validate(this) && myLib.processForm(this, function(){
		updateUI();
	});
	return false;
}

el('cat_update').onsubmit = function() {
	myLib.validate(this) && myLib.processForm(this, function(){
		updateUI();
	});
	return false;
}

el('prod_delete').onsubmit = function() {
	myLib.validate(this) && myLib.processForm(this, function(){
		updateUI_2();
	});
	return false;
}
/*
el('logoutForm').onsubmit = function(){
	myLib.validate(this);
	return false;
}
*/
})();
</script>



</body>
</html>