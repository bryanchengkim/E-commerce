<!DOCTYPE html>

<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>shop.html</title>
	<meta name="viewport" content="initial-scale=1.0,width=device-width,user-scalable=no" />
	<link rel="stylesheet" media="screen and (max-width:700px)" href="incl/tinyscreen.css" />
    <link rel="stylesheet" media="screen and (min-width:700px)" href="incl/style.css" />
    
</head>
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
<script type="text/javascript" src="incl/myLib.js"></script>
<script type="text/javascript" src="incl/myLib3.js"></script>
<body>
</body>
</html>
