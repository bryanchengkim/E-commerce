(function (){
window.ui = window.ui || {
        cart: {}
    };
var storage={};
    
ui.cart.add = function (pid) {
	
	if (storage[pid] >=1)
		storage[pid] = (parseInt(storage[pid]) +1).toString();
	else
		storage[pid] = "1";
	window.localStorage.setItem("cart_storage", JSON.stringify(storage));
	window.alert("Product added!");
	ui.cart.updateHTML();
};

ui.cart.setQty = function(pid, qty){
	storage[pid] = qty;
	if (parseInt(qty) <= 0 )
		delete storage[pid];
	window.localStorage.setItem("cart_storage", JSON.stringify(storage));
	ui.cart.updateHTML();
};

ui.cart.updateHTML = function() {
//window.alert("Updating");
	storage = (storage = window.localStorage.getItem("cart_storage")) ;
	storage = storage ? JSON.parse(storage) : {};
	el('Cart').innerHTML = 'Shopping Cart';
	//window.alert("Updating 2");
	//we don't have cart
	el('Cart').innerHTML = ('');
	//window.alert("Updating --");
	var total_price = 0;

	myLib.post2({action:'prod_getcart'}, function(json) {
	//window.alert("Updating 3");
		
		
		for (var listItems = [],  i = 0,j=0 ; prod = json[i]; i++){
			if (storage[prod.pid]>=1){
				j++;
			listItems.push('<li>' , prod.name , ' </li><input type ="hidden" name="item_number_',j,'" value="',parseInt(prod.pid),'"><input type ="hidden" name="item_name_',j,'" value="',prod.name,'"><input type ="hidden" name="amount_',j,'" value="',prod.price,'"><input type="number" name = "quantity_',j,'" pattern="^[\d]+$" required="true" min="0" max ="99" maxlength="2" value="' , storage[prod.pid] ,
			'" onblur="ui.cart.setQty(' , parseInt(prod.pid) , ',this.value)"> @ $' , prod.price , '</li>');
			total_price = total_price + storage[prod.pid] * prod.price;
		}}
	var cart_items = listItems.join('');
	//window.alert(cart_items);
	//window.alert(el('Cart').innerHTML);
	//window.alert(total_price);
	var show_cart_value = ["Shopping Cart (Total: $" + total_price + ")" + cart_items];
	el('Cart').innerHTML = show_cart_value.join();
	//window.alert(el('Cart').innerHTML);
	//window.alert("Update complete");
	});
};
ui.cart.updateHTML();

ui.cart.clear = function () {
	window.localStorage.removeItem("cart_storage");	
		ui.cart.updateHTML();
	}
ui.cart.submit = function(shopping_cart){
	var storage={};
	
	//retriveing an object from web storage
	storage = window.localStorage.getItem('cart_storage');
	if (!storage || storage == '{}')
		return false;
	storage = storage ? JSON.parse(storage) : {};
	
	myLib.processJSON("process-ui.php",{action: 'buildOrder', list: JSON.stringify(storage)}, function(json)  {
	if (!json) return alert("Fail");
	json = json.split(',');
	shopping_cart.custom.value = json.digest;
	shopping_cart.invoice.value = json.lastInsertId;

	
	storage = {};

	// encodes an object to a string with JSON.stringify()
	// then save it as 'cart_storage' in the localStorage
	window.localStorage.setItem('cart_storage', JSON.stringify(storage));
	
	
	shopping_cart.submit();			
	
	
	}
	,{method:"POST"});
	return false;
	ui.cart.clear();
};
})();
