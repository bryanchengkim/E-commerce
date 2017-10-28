<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">

	<title>Food On Line</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/colorbox.css">
	<link rel="stylesheet" href="css/global.css">

	<script src="js/jquery.min.js"></script>
	<script src="js/jquery.colorbox-min.js"></script>
	<script src="js/slides.min.jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/ready.js"></script>

	<script>
			$(function(){
			$('#slides').slides({
				preload: true,
				preloadImage: 'img/loading.gif',
				play: 5000,
				pause: 2500,
				hoverPause: true,
				animationStart: function(current){
					$('.caption').animate({
						bottom:-35
					},100);
					if (window.console && console.log) {
						// example return of current slide number
						console.log('animationStart on slide: ', current);
					};
				},
				animationComplete: function(current){
					$('.caption').animate({
						bottom:0
					},200);
					if (window.console && console.log) {
						// example return of current slide number
						console.log('animationComplete on slide: ', current);
					};
				},
				slidesLoaded: function() {
					$('.caption').animate({
						bottom:0
					},200);
				}
			});
		});
	</script>

</head>
<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

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
	&nbsp&nbsp&nbsp&nbsp
	<section class="shopping" id= "ShopCart">
		<img src="./img/shopping_cart.jpg">
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
<div class="fb-like" href="https://secure.grp22.ierg4210.ie.cuhk.edu.hk" layout="button_count" action="like" show_faces="true" share="true"></div>

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
			?><li><a href="product.php?catid=<?php echo $cats[$i]['catid']; ?>"><?php echo $cats[$i]['name']; ?></a></li>
			<?php } echo "\n";?>
		</ul>
	</nav>
	<span>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<a href ="">Home</a></span>
	</header>

	<body>
	<div id="container">
		<div id="example">
			<div id="slides">
				<div class="slides_container">
					<div class="slide">
						<img src="img/slide-1.jpg" width="570" height="270" alt="Slide 1"></a>
					</div>
					<div class="slide">
						<img src="img/slide-2.jpg" width="570" height="270" alt="Slide 2"></a>
					</div>
					<div class="slide">
						<img src="img/slide-3.jpg" width="570" height="270" alt="Slide 3"></a>
					</div>
					<div class="slide">
						<img src="img/slide-4.jpg" width="570" height="270" alt="Slide 4"></a>
					</div>
					<div class="slide">
						<img src="img/slide-5.jpg" width="570" height="270" alt="Slide 5"></a>
					</div>
					<div class="slide">
						<img src="img/slide-6.jpg" width="570" height="270" alt="Slide 6"></a>
					</div>
					<div class="slide">
						<img src="img/slide-7.jpg" width="570" height="270" alt="Slide 7"></a>
					</div>
				</div>
				<a href="#" class="prev"><img src="img/arrow-prev.png" width="24" height="43" alt="Arrow Prev"></a>
				<a href="#" class="next"><img src="img/arrow-next.png" width="24" height="43" alt="Arrow Next"></a>
			</div>
			<img src="img/example-frame.png" width="739" height="341" alt="Example Frame" id="frame">
		</div>
	</div>

			
		<div class="homepage-display">
	<table>
		<tr>
			<td>
				<h4>Best Sell</h4>
				<img src="./img/sushi 001.jpg">
				<img src="./img/sushi 002.jpg">
				<img src="./img/sushi 003.jpg">
			</td>
			<td>
				<h4>Business Time</h4>
				<p>
				Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna 				aliquam erat volutpat. 
				</p>
			</td>
			<td>
				<h4>Special Favour</h4>
				<img src="./img/baked 001.jpg">
				<img src="./img/baked 002.jpg">
				<img src="./img/baked 003.jpg">
			</td>
		</tr>
	</table>
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
