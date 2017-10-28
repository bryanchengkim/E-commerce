<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Product</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/colorbox.css">
	<link rel="stylesheet" href="css/global.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script src="js/jquery.colorbox-min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/ready.js"></script>

</head>
<body>
	<?php include_once("lib/db.inc.php"); ?>
	<?php $num = $_GET['pid']; ?>
	<?php 
		$db = ierg4210_DB();
		$query = $db->prepare("SELECT * FROM products WHERE pid = " . $num);
		$query->execute();
		$prods = $query->fetchALL();
	?>
<div>
	<div id='coconut'>
		<img class='detailphoto' src="img/<?php echo $prods[0]['filename']?>">
		<div class='detailtext'>
			<p class='title'>
			Name:  <?php echo $prods[0]['name']; ?>
			</p>
			<ul class='content'>
			<li>Description: <?php echo $prods[0]['description']; ?></li>
			<li>Price: HK$ <?php echo $prods[0]['price']; ?></li>
			&nbsp&nbsp&nbsp&nbsp
			<input class="addtocart" type="submit" onclick="ui.cart.add(<?php echo $prods[0]['pid'] ?>)" value="Add to cart">
			</ul>
		</div>
	</div>
</div>
	<script type="text/javascript" src="incl/myLib.js"></script>
	<script type="text/javascript" src="incl/ui.js"></script>
</body>
</html>
