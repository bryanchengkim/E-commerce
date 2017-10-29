(function(){
    var el = function(A){return document.getElementById(A)};
	var myLib2 = window.myLib2= (window.myLib2 || {});
	var buyList = new Array();
	var pIndex=-1;

    myLib2.setCookie =function(c_name,value,exdays){
	    var exdate=new Date();
        exdate.setDate(exdate.getDate() + exdays);
        var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
        document.cookie=c_name + "=" + c_value;
    }
	
	myLib2.getCookie=function(){	    
	    var i,x,y,ARRcookies=document.cookie.split(";");
		var cookies=new Array();
        for (i=0;i<ARRcookies.length;i++)
        {
            x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
			x=x.replace(/^\s+|\s+$/g,"");
			cookies[i*2]=x;
            y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
			cookies[i*2+1]=y;            
        }		
        return cookies;
    }
	
	myLib2.updateCart=function() {
	    myLib.process({action:'products_fetchall'}, function(products){	
        var cookies=myLib2.getCookie();	
		var html=new Array();
		var total=0;
		
		for (i=0; i<(cookies.length/2); i++){
		    for (var j=0, pro; pro=products[j]; j++){
			    if (cookies[i*2]==parseInt(pro.pid)){
				    html.push('<li>'+pro.name.escapeHTML()+'<input id="quantity" type="number" value="'+cookies[i*2+1]+'"  pattern="^[\d\.]+$" onblur="myLib2.updateQuan('+cookies[i*2]+',this.value)" /><button type="button" onclick="myLib2.deleteProb('+cookies[i*2]+')">Delete</button></li>');
					total=total+cookies[i*2+1]*parseInt(pro.price);
				}
			}		    
		}
		html.push('<li>Total $HK: '+total+'</li>');		
		el('productList').innerHTML = html.join('');
		});	    	
	}
	
	myLib2.addToCart=function(pid){
	    myLib2.setCookie(pid, 1, 365);
		myLib2.updateCart();		
	}
	
	myLib2.deleteProb=function(pid){
	    myLib2.setCookie(pid,1,-1);
		myLib2.updateCart();
	}
	
    myLib2.updateQuan=function(pid,value){
	    myLib2.setCookie(pid,value,365);
		myLib2.updateCart();	    
	}
		
})();

    

