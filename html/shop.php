<!DOCTYPE html>

<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>shop.html</title>
	<meta name="viewport" content="initial-scale=1.0,width=device-width,user-scalable=no" />
	<link rel="stylesheet" media="screen and (max-width:700px)" href="incl/tinyscreen.css" />
    <link rel="stylesheet" media="screen and (min-width:700px)" href="incl/style.css" />
    
</head>

<body>
       <header>
            <img src="incl/img/logo.png">
       </header>
       <iframe class="fblike" src="http://www.facebook.com/plugins/like.php?href=www.shop45.ierg4210.org"
        scrolling="no" frameborder="0"></iframe>
       <div id="cart_container">
          <ul id="mainMenu">
              <li><a href="https://www.shop45.ierg4210.org/admin.php">admin</a></li>
          </ul>
            
          </div>
          <div id="cart">
            <div id="cartLabel">Shopping Cart (No item)</div>
            <form id="submitForm" method="POST" action="https://www.sandbox.paypal.com/cgi-bin/webscr" onsubmit="return myLib3.cart.submit(this)">		  
                <ul id="productList"></ul>			
				<input type="hidden" name="cmd" value="_cart" />
		        <input type="hidden" name="upload" value="1" />
		        <input type="hidden" name="business" value="chm110_1332605307_biz@gmail.com" />
	        	<input type="hidden" name="currency_code" value="HKD" />
         		<input type="hidden" name="charset" value="utf-8" />
        		<input type="hidden" name="custom" value="0" />
        		<input type="hidden" name="invoice" value="0" />
        		<input type="submit" value="Checkout" />
        	</form>
		</div>


<div class="main">      
    <div id="main_left">
	<?php
	    include_once('lib/db.inc.php');
		$id = $_GET['catid'];
		$db = myLib_DB();
		$q = $db->query('SELECT * FROM categories');
		print"<ul>";
		foreach($q as $row){
		print"<li><a href=shop.php?catid=".$row["catid"].">".$row["name"]."</a></li>";}
		print"</ul>";
	?>
	</div>    
	
     <div id="main_right">

       <div id="table_container">
             <ul class="table">

             <?php 
			    $proid=NULL;
			    $proid = $_GET['pid'];			  
				$q=$db->prepare('SELECT * FROM products WHERE catid= ?');
	        	$q->execute(array($id));
	        	$q=$q->fetchAll();
				
	            foreach($q as $row){
	            print"<li>";
		        print"<div>";
	            print'<div><a href=shop.php?pid='.$row["pid"].'>'.'<img src="incl/prod/'.$row["pid"].'.jpg"/></a></div>';
	            print"<div>".$row["name"]."</div>";
	            print"<div>$".$row["price"]."</div>";				
				$temp=$row["name"];   
				echo '<div><button type="button" value="button" onclick="myLib3.cart.add('.$row["pid"].')">addtocart</button></div>';
	            print"</div>";
	            print"</li>";}
				
				
				if ($proid==NULL) {}
				else {

					$q2=$db->prepare('SELECT * FROM products WHERE pid= ?');
	        	    $q2->execute(array($proid));
	        	    $q2=$q2->fetchAll();
					foreach($q2 as $row){
					print'<div><img src="incl/prod/'.$row["pid"].'.jpg"/></div>';
					print"<div>".$row["name"]."</div>";
					print"<div>$".$row["price"]."</div>";
					print'<div><button type="button" value="bbutton" onclick="myLib3.cart.add('.$row["pid"].')">addtocart</button></div>';	
					print'<div>description: '.$row["Description"].'</div>';
					}
				}
             ?>
             </ul>
       </div>
       
   </div>      
</div>
       
</div>
<div id="fb"><?php 
			              if(!$_COOKIE['fbau'])
						      echo '<button type="button" onClick="myLib3.fbAu()">Facebook</button>';
						  else
						      {if ($_COOKIE['fbn']==null) {include_once('lib/fbAuth.php');}
					  echo("hello, ".$_COOKIE['fbn']);}
						    ?>
			</div>

<div class="foot">
  <footer>Copyright c 2012 MING. All rights reserved.</footer>
</div>
<script type="text/javascript" src="incl/myLib.js"></script>

<script type="text/javascript" src="incl/myLib3.js"></script>



</body>
</html>