(function(){
    var el = function(A){return document.getElementById(A)};
	var myLib3 = window.myLib3= (window.myLib3 || {});
	
	myLib3.cart=myLib3.cart || {};
	var buyList=new Array();
	
	myLib3.fbAu=function(){
		document.cookie="fbau=true";
		window.location.reload();
	}
	
	myLib3.cart.add = function(pid)
	{
	    var newPro=1;
	    for (var i=0; i<buyList.length; i+=2)
		{
		    if(buyList[i]==pid){
			    buyList[i+1]++;
                newPro=0;	
            }				
		}
		if (newPro)
		    buyList=buyList.concat(pid, 1);
	    
	    window.localStorage.setItem("cartList", JSON.stringify(buyList)); 
		myLib3.cart.display();
	}
	
	myLib3.getLocalStorage=function(){	
	    buyList =(buyList=window.localStorage.getItem("cartList"))?JSON.parse(buyList): new Array();
    }

	myLib3.cart.display = function()
	{
	    var k=0;
	    myLib3.getLocalStorage();
		var list=buyList;
		var total=0;
	    myLib.process({action:'products_fetchall'}, function(products){
			for(var html=[], i=0; i<list.length/2; i++)
			{
			    for(var j=0, pro; pro=products[j]; j++)
				{
				    if (list[i*2]==parseInt(pro.pid)){
					    html.push('<li>');
						html.push('<input type="hidden" name="item_number_'+(++k)+'" value="'+pro.pid+'">'+pro.name.escapeHTML());
						html.push('<input type="hidden" name="item_name_'+k+'" value="'+pro.name.escapeHTML()+'">');
						html.push('<input type="number" name="quantity_'+k+'" min="0" max="99" maxlength="2" class="qty" value="'+list[i*2+1]+'" onblur="myLib3.cart.update('+pro.pid+',this.value)">');
						//html.push('<input type="number" name="quantity_'+k+'" min="0" max="99" maxlength="2" class="qty" value="'+list[i*2+1]+'" onblur="myLib3.cart.update('+pro.pid+','+'this.value)">');
						html.push('<input type="hidden" name="amount_'+k+'" value="'+parseFloat(pro.price)+'">');
						html.push('<span>$'+parseFloat(pro.price)*list[i*2+1]+'  </span>');
						html.push('<img src="/incl/img/closs.png" a href="" onclick="myLib3.cart.update('+pro.pid+', 0)"></img>');
						html.push('</li>');
			
						
				        //html.push('<li>'+pro.name.escapeHTML()+'<input id="quantity" type="number" value="'+list[i*2+1]+'"  pattern="^[\d\.]+$" onblur="myLib2.updateQuan('+list[i*2]+',this.value)" /><button type="button" onclick="myLib2.deleteProb('+list[i*2]+')">Delete</button></li>');
					    total=total+list[i*2+1]*parseInt(pro.price);
				    }
				}
			}
			el('productList').innerHTML = html.join('');
			el('cartLabel').innerHTML='Shopping Cart Total($ '+total+' )';
		});
	};
	
	myLib3.cart.update=function(pid, value)
	{
		//alert(pid+"  "+value);
	    for (var i=0; i<buyList.length; i+=2)
		{
		    if(buyList[i]==pid)
			    if (value==0)
				    buyList.splice(i, 2);
				else
			        buyList[i+1]=parseInt(value);			
		}
		window.localStorage.setItem("cartList", JSON.stringify(buyList));
		myLib3.cart.display();
	};
		
	myLib3.cart.submit=function(form)
	{
	    
	    myLib3.getLocalStorage();
		/*
		myLib.process({
		{action: "cartHandle", list:JSON.stringify(buyList)},
		function(retval){
		    //form.custom.value=retval.digest;
			//form.invoice.value=retval.invoice;
			form.submit()},
		    {method:"POST"}});
			*/
			
		
		
	    myLib.getJSON(
		    "process-checkout.php", 
		    {action: "cartHandle", list:JSON.stringify(buyList)}, 
		    function(retval){
			form.custom.value=retval.digest;
			form.invoice.value=retval.invoice;
			form.submit();
			buyList= new Array();
		    window.localStorage.setItem("cartList", JSON.stringify(buyList)); 
		    myLib3.cart.display();
			},
		    {method:"POST"});
			
		return false;
	}
	
	myLib3.cart.display();
	
})();

    

