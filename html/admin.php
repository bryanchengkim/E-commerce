<?php
include_once('lib/csrf.php');
include_once('lib/auth.php');

session_start();
auth();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>IERG4210 Shop - Admin Panel</title>
	<link href="css/admin.css" rel="stylesheet" type="text/css"/>
</head>

<body>
<h1>Food Online Shop - Admin Panel </h1>
<article id="main">

<!-- start my code -->
	<form id="logoutForm" method="POST" action="auth-process.php?action=<?php echo ($action = 'logout'); ?>">
	<input type="hidden" name="nonce" value="<?php echo csrf_getNonce($action); ?>" />
	<input type="submit" value="Logout" />
	</form>
	<form id ="pwForm">
	<input type="button" id="user_change_pw" value="Change Password"/>
	</form>
	
<!-- end my code -->
<section id="passwordPanel" class="hide">
<fieldset>
	<legend>Change Password</legend>
	<form id="pwForm" method="POST" action="auth-process.php?action=<?php echo ($action = 'ChangePassword'); ?>">
	<label for="oldPw">Old :</label>
	<div><input type="password" name="oldPw" required="true" pattern="^[\w@#$%\^\&\*\-]+$" /></div>
	<label for="newPw">New :</label>
	<div><input type="password" name="newPw" required="true" pattern="^[\w@#$%\^\&\*\-]+$" /></div>
	<label for="newPw2">Confirm :</label>
	<div><input type="password" name="newPw2" required="true" pattern="^[\w@#$%\^\&\*\-]+$" /></div>
	<input type="hidden" name="nonce" value="<?php echo csrf_getNonce($action); ?>" />
	<input type="submit" value="Change Password"/>
	</form>
</fieldset>
</section>

<section id="categoryPanel">
	<fieldset>
		<legend>New Category</legend>
		<form id="cat_insert" method="POST" action="admin-process.php?action=<?php echo ($action = 'cat_insert'); ?>">
			<label for="cat_insert_name">Name</label>
			<div><input id="cat_insert_name" type="text" name="name" required="true" pattern="^[\w\- ]+$" /></div>
			<input type="hidden" name="nonce" value="<?php echo csrf_getNonce($action); ?>" />
			<input type="submit" value="Submit" />
		</form>
	</fieldset>
	
	<!-- Generate the existing categories here -->
	<ul id="categoryList"></ul>
</section>

<section id="categoryEditPanel" class="hide">
	<fieldset>
		<legend>Editing Category</legend>
		<form id="cat_edit" method="POST" action="admin-process.php?action=<?php echo ($action = 'cat_edit'); ?>">
			<label for="cat_edit_name">Name</label>
			<div><input id="cat_edit_name" type="text" name="name" required="true" pattern="^[\w\- ]+$" /></div>
			<input type="hidden" id="cat_edit_catid" name="catid" />
			<input type="hidden" name="nonce" value="<?php echo csrf_getNonce($action); ?>" />
			<input type="submit" value="Submit" /> <input type="button" id="cat_edit_cancel" value="Cancel" />
		</form>
	</fieldset>
</section>

<section id="productPanel">
	<fieldset>
		<legend>New Product</legend>
		<form id="prod_insert" method="POST" action="admin-process.php?action=<?php echo ($action = 'prod_insert'); ?>" enctype="multipart/form-data">
			<label for="prod_insert_catid">Category *</label>
			<div><select id="prod_insert_catid" name="catid"></select></div>

			<label for="prod_insert_name">Name *</label>
			<div><input id="prod_insert_name" type="text" name="name" required="true" pattern="^[\w\- ]+$" /></div>

			<label for="prod_insert_price">Price *</label>
			<div><input id="prod_insert_price" type="number" name="price" required="true" pattern="^[\d\.]+$" /></div>

			<label for="prod_insert_description">Description</label>
			<div><textarea id="prod_insert_description" name="description" pattern="^[\w\-, ]$"></textarea></div>

			<label for="prod_insert_image">Image *</label>
			<div><input type="file" name="file" required="true" accept="image/*" /></div>

			<input type="hidden" name="nonce" value="<?php echo csrf_getNonce($action); ?>" />
			<input type="submit" value="Submit" />
		</form>
	</fieldset>
	
	<ul id="productList"></ul>

</section>
	
<section id="productEditPanel" class="hide">

		<!--Start of Editing panel-->
	<fieldset>
		<legend>Editing Product</legend>
		<form id="prod_edit" method="POST" action="admin-process.php?action=<?php echo ($action = 'prod_edit'); ?>" enctype="multipart/form-data">
			<label for="prod_edit_catid">Original Product</label>
			<div><input id="prod_edit_pid" type="hidden"  name="pid" /></div>
			<div><select id="prod_edit_catid" name="catid" ></select></div>

			<label for="prod_edit_name">Name *</label>
			<div><input id="prod_edit_name" type="text" name="name" required="true" pattern="^\w+$" /></div>

			<label for="prod_edit_price">Price *</label>
			<div><input id="prod_edit_price" type="number" name="price" required="true" pattern="^[\d\.]+$" /></div>

			<label for="prod_edit_description">Description *</label>
			<div><textarea id="prod_edit_description" type="text" name="description" required="true" pattern="^\w+$"></textarea></div>

			<label for="prod_edit_image">Image *</label>
			<div><input type="file" name="file" accept="image/*" /></div>

			<input type="hidden" name="nonce" value="<?php echo csrf_getNonce($action); ?>" />
			<input type="submit" value="Submit" /> <input type="button" id="prod_edit_cancel" value="Cancel" />

		</form>
	</fieldset>
	 	<!-- End of Editng panel-->

</section>
	
<div class="clear"></div>
</article>
<script type="text/javascript" src="incl/myLib.js"></script>
<script type="text/javascript">
(function(){

	function updateUI() {
		myLib.get({action:'cat_fetchall'}, function(json){
			// loop over the server response json
			//   the expected format (as shown in Firebug): 
			for (var options = [], listItems = [],
					i = 0, cat; cat = json[i]; i++) {
				options.push('<option value="' , parseInt(cat.catid) , '">' , cat.name.escapeHTML() , '</option>');
				listItems.push('<li id="cat' , parseInt(cat.catid) , '"><span class="name">' , cat.name.escapeHTML() , '</span> <span class="delete">[Delete]</span> <span class="edit">[Edit]</span></li>');
			}
			el('prod_insert_catid').innerHTML = '<option></option>' + options.join('');
			el('categoryList').innerHTML = listItems.join('');

			//start my code

			el('prod_edit_catid').innerHTML = '<option></option>' + options.join('');

			//end my code	
		});
		el('productList').innerHTML = '';
	}
	updateUI();
	
	el('categoryList').onclick = function(e) {
		if (e.target.tagName != 'SPAN')
			return false;
		
		var target = e.target,
			parent = target.parentNode,
			id = target.parentNode.id.replace(/^cat/, ''),
			name = target.parentNode.querySelector('.name').innerHTML;
		
		// handle the delete click
		if ('delete' === target.className) {
			confirm('Sure?') && myLib.post({action: 'cat_delete', catid: id}, function(json){
				alert('"' + name + '" is deleted successfully!');
				updateUI();
			});
		
		// handle the edit click
		} else if ('edit' === target.className) {
			// toggle the edit/view display
			el('categoryEditPanel').show();
			el('categoryPanel').hide();
			
			// fill in the editing form with existing values
			el('cat_edit_name').value = name;
			el('cat_edit_catid').value = id;
		
		//handle the click on the category name
		} else {
			el('prod_insert_catid').value = id;
			// populate the product list or navigate to admin.php?catid=<id>

			//Modify here
			//el('productList').innerHTML = '<li> Product 1 of "' + name + '" [Edit] [Delete]</li><li> Product 2 of "' + name + '" [Edit] [Delete]</li>';

			//start my code

			myLib.post({action:'prod_fetchall', catid:id}, function(json){
			// loop over the server response json
			// the expected format (as shown in Firebug): 
			for (var listItems = [],
					i = 0, cat; cat = json[i]; i++) {
				listItems.push('<li id="prod' , parseInt(cat.pid) , '" > <span class="catid">' , parseInt(cat.catid) , '</span><span class="name">' , cat.name.escapeHTML() , '</span> <span class="price">' , parseInt(cat.price) , '</span> <span class="description">' , String(cat.description) , '</span> <span class="delete">[Delete]</span> <span class="edit">[Edit]</span></li>');
				}
				el('productList').innerHTML = listItems.join('');
			});
			
//end my code
		}
	}
	
	
	el('cat_insert').onsubmit = function() {
		return myLib.submit(this, updateUI);
	}
	el('cat_edit').onsubmit = function() {
		return myLib.submit(this, function() {
			// toggle the edit/view display
			el('categoryEditPanel').hide();
			el('categoryPanel').show();
			updateUI();
		});
	}
	el('cat_edit_cancel').onclick = function() {
		// toggle the edit/view display
		el('categoryEditPanel').hide();
		el('categoryPanel').show();
	}

	//start my code

	el('productList').onclick = function(e) 
	{
		if (e.target.tagName != 'SPAN')
			return false;
		
		var target = e.target,
			parent = target.parentNode,
			id = target.parentNode.id.replace(/^prod/, ''),
			name = target.parentNode.querySelector('.name').innerHTML;
			catid = target.parentNode.querySelector('.catid').innerHTML;
			price = target.parentNode.querySelector('.price').innerHTML;
			description = target.parentNode.querySelector('.description').innerHTML;
			
		
		// handle the delete click
		if ('delete' === target.className) {
			confirm('Sure? '+id+'') && myLib.post({action: 'prod_delete', pid: id}, function(json){
				alert('"' + name + '" is deleted successfully!');
				updateUI();
			});
		
		// handle the edit click
		} else if ('edit' === target.className) {
			// toggle the edit/view display
			el('productEditPanel').show();
			el('productPanel').hide();
			
			// fill in the editing form with existing values
			el('prod_edit_pid').value = id;
			el('prod_edit_catid').value = catid;
			el('prod_edit_name').value = name;
			el('prod_edit_price').value = price;
			el('prod_edit_description').value= description;
			
		}
		updateUI();
	}
	el('prod_edit_cancel').onclick = function() {
		// toggle the edit/view display
		el('productEditPanel').hide();
		el('productPanel').show();
	}
	el('user_change_pw').onclick = function() {
		// toggle the edit/view display
		el('productEditPanel').hide();
		el('productPanel').hide();
		el('categoryEditPanel').hide();
		el('categoryPanel').hide();
		el('pwForm').hide();
		el('passwordPanel').show();
	}

//end my code	

})();
</script>
</body>
</html>
