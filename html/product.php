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
	<div id="page-container">
		<header>
			<div class="top-bar">
				FREE SHIPPING ABOVE HK$150 
				&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
				15 DAY RETURN 
				&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
				<img src="./img/info.jpg"/>+852-100|FAQ
			</div>	

			<div class="banner">
				<img src="./img/food.jpg">&nbsp&nbsp&nbsp  Food On Line
				<section class="shopping" id= "ShopCart">
				<img src="./img/shopping_cart.jpg" onmouseover="ui.cart.updateHTML()">
				<nav>
				<form method="POST" action="https://www.sandbox.paypal.com/cgi-bin/webscr" onsubmit="return ui.cart.submit(this)">
				<ul class="cartsize" id="Cart" ></ul>
				<input type="hidden" name="cmd" value="_cart">
				<input type="hidden" name="upload" value="1">
				<input type="hidden" name="business" value="ierg4210test-facilitator@gmail.com">
				<input type="hidden" name="currency_code" value="HKD">
				<input type="hidden" name="charset" value="utf-8">
				<input type="hidden" name="custom" value="0">
				<input type="hidden" name="invoice" value="0">
				
				<h2><input type="submit" value="Checkout"></h2>
				</form>
				</nav>
				</section>
			</div>

			<nav>
				<ul>
				<?php
				$db = ierg4210_DB();
				$query = $db->prepare("SELECT * FROM categories");
				$query->execute();
				$cats = $query->fetchALL();
				?>
				<a href="index.php"><img src="./img/home.jpg"></a>
				<?php
				for($i = 0; $i < sizeof($cats); $i++){
				?>
					<li><a href="product.php?catid=<?php echo $cats[$i]["catid"]; ?>"><?php echo $cats[$i]['name']; ?></a></li>
					<?php } 
					echo "\n";
					?>
				</ul>
			</nav>

			
		</header>


		<?php
		$pagenum = (int)$_GET["pagenum"];
		//echo 'pagenum'. $pagenum .PHP_EOL;
		$page_rows = 6;
	    $start = (int)$_GET["catid"];
	    $db = ierg4210_DB();
		$query0 = $db->prepare("SELECT * FROM categories WHERE catid = '$start'");
		$query0->execute();
		$catname = $query0->fetchALL();
	    ?>
	    
	    <span><a href ="index.php">Home</a>&nbsp; > &nbsp;<a href="#"><?php echo $catname[0]['name']; ?></a></span>

	    <?php
		$db = ierg4210_DB();
		$query = $db->prepare("SELECT * FROM products WHERE catid = '$start'");
		$query->execute();
		$prods = $query->fetchALL();
		
		$rows = sizeof($prods);
		//echo 'rows'. $rows .PHP_EOL;
		$last = ceil($rows/$page_rows);
		//echo 'last'. $last .PHP_EOL;
		if ($pagenum < 1)
		{
			$pagenum = 1;
		}
		elseif ($pagenum > $last)
		{
		 $pagenum = $last;
		}
		//echo 'pagenum_'. $pagenum .PHP_EOL;
		$max = 'limit ' . $page_rows .' offset ' .($pagenum-1) * $page_rows;
		//echo 'max'. $max .PHP_EOL;
		$query_p = $db->prepare("SELECT * FROM products WHERE catid = '$start' $max");
		$query_p->execute();
		$prods_p = $query_p->fetchALL();

		?>
			<ul class="product-list">
				<?php
				$j =0;
				while($j<sizeof($prods_p)){

				?>
					<li><a class='iframe' href="detail.php?pid=<?php echo $prods_p[$j]['pid']; ?>">
						<p><img class='mini' src="img/<?php echo $prods_p[$j]['filename']?>" alt="<?php echo $prods_p[$j]['name']; ?>">
							<br><br><?php echo $prods_p[$j]['name']; ?>
						</a>
						HK$<?php echo $prods_p[$j]['price']; ?>
						<input class="addtocart" type="submit" onclick="ui.cart.add(<?php echo $prods_p[$j]['pid'] ?>)" value="Add to cart">
					</li>

				<?php
					$j++;
					}
				?>
		</ul>
		<div class = "pagination">
			<?php
				echo " --Page $pagenum of $last-- <p>";
				if ($pagenum == 1) {	}
				else
				{
					$previous = $pagenum-1;
					echo " <a href='product.php?catid=$start&pagenum=$previous'> <-Previous</a> ";
				}
				if ($pagenum == $last){		}
				else {
					$next = $pagenum+1;
					echo " <a href='product.php?catid=$start&pagenum=$next'>Next -></a> ";
				}
			?>
		</div>

		<div class="bottom-nav">
			<table>
				<tr>	
					<td>
						<h6>Shop By Product</h6>
					</td>
					<td>
						<h6>Shop By Price</h6>
					</td>
					<td>
						<h6>Support</h6>
						<li><a href="#">Become a Dealer</a></li>
						<li><a href="#">Find a Dealer</a></li>
						<li><a href="#">Get a Catalog</a></li>
						<li><a href="#">Returns</a></li>
					</td>
					<td>
						<h6>News and Events</h6>
						<li><a href="#">Latest News</a></li>
						<li><a href="#">Current Events</a></li>
					</td>
				</tr>
				<tr>
					<img src="./img/credit_card.jpg">
				</tr>
			</table>
		</div>	

		<div id="footer">
			<p>Made by Amy Chow & Billy Cheng&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspIERG4210 2013-2014 Fall</p>
		</div>
	</div>


</div>
<script type="text/javascript" src="incl/myLib.js"></script>
<script type="text/javascript" src="incl/ui.js"></script>
</body>
</html>
