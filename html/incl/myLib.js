(function(){
	String.prototype.escapeHTML = function(){
		return this.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
	}
		  
		  
  var myLib = window.myLib = (window.myLib || {});
  
  // To generate POST parameters based on the control values
  myLib.formData = function(form) {
    // private variable for storing parameters
    this.data = [];
    for (var i = 0, j = 0, name, el, els = form.elements; el = els[i]; i++) {
      // skip those useless elements
      if (el.disabled || el.name == '' 
         || ((el.type == 'radio' || el.type == 'checkbox') && !el.checked))
        continue;
      // add those useful to the data array
      this.append(el.name, el.value);
    }
  };
  // public methods of myLib.formData
  myLib.formData.prototype = {
    // output the required final POST parameters, e.g. a=1&b=2&c=3
    toString: function(){
      return this.data.join('&');
    },
    // encode the data with the built-in function encodeURIComponent
    append: function(key, val){
      this.data.push(encodeURIComponent(key) + '=' + encodeURIComponent(val));
    }
  };

  myLib.encodeParam = function(obj) {
	var data = [];
	for (var key in obj)
		data.push(encodeURIComponent(key) + '=' + encodeURIComponent(obj[key]));
	return data.join('&');
  }
  
  myLib.ajax = function(opt) {
    opt = opt || {};
    var xhr = (window.XMLHttpRequest) 
            ? new XMLHttpRequest()                     // IE7+, Firefox1+, Chrome1+, etc
            : new ActiveXObject("Microsoft.XMLHTTP"),  // IE 6
        async = opt.async || true,
        success = opt.success || null, error = opt.error || function(){alert('AJAX Error: ' + this.status)};
    // pass three parameters, otherwise the default ones, to xhr.open()
    xhr.open(opt.method || 'GET', opt.url || '', async);
    if (opt.method == 'POST') 
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    // Asyhronous Call requires a callback function listening on readystatechange
    if (async)  xhr.onreadystatechange = function(){
        if (xhr.readyState == 4) {
          var status = xhr.status, response = xhr.responseText;
          if ((status >= 200 && status < 300) || status == 304 || status == 1223) {
			success && success.call(xhr, (response.substr(0,9) == 'while(1);') ? response.substring(9) : response);
		  }
          else if (status >= 500)
            error.call(xhr);
        }
      };
    xhr.onerror = function(){error.call(xhr)};
    // POST parameters encoded as opt.data is passed here to xhr.send()
    xhr.send(opt.data || null);
    // Synchronous Call blocks UI and returns result immediately after xhr.send()
    !async && callback && callback.call(xhr, xhr.responseText);
  };
  
  // a private function for displayError
  function displayErr(el,msg){alert('FieldError: ' + msg);el.focus();return false}
  
  myLib.validate = function(form) {
    // Looping over every form control incl <input>, <textarea>, and <select>
    for (var i = 0, p, el, els = form.elements; el = els[i]; i++) {
      // bypass any disabled controls
      if (el.disabled) continue;
	  // validate empty field, radio and checkboxes
	  if (el.hasAttribute('required')) {
		if (el.type == 'radio') {
		  if (lastEl && lastEl == el.name) continue;
		  for (var j = 0, chk = false, lastEl = el.name, choices = form[lastEl],
			   choice; choice = choices[j]; j++)
			if (choice.checked) {chk = true; break;}
		  if (!chk) return displayErr(el, 'choose a ' + el.title);
		  continue;
		} else if ((el.type == 'checkbox' && !el.checked) || el.value == '') 
		  return displayErr(el, el.title + ' is required');
	  }
	  if ((p = el.getAttribute('pattern')) && !new RegExp(p).test(el.value))
		return displayErr(el, 'in' + el.title);
	}
	return true;
  };
  
  myLib.getJSON = function(url, param, successCallback, opt) {
	opt = opt || {};
	opt.url = url || 'process.php';
	opt.method = opt.method || 'GET';
	if (param)
		opt.data = myLib.encodeParam(param);
	opt.success = function(json){
		json = JSON.parse(json);
		if (json.success)
			successCallback && successCallback.call(this, json.success);
		else 
			alert(json.failed);
	};
	myLib.ajax(opt);
  };
  myLib.process = function(param, successCallback) {
	param = param || {};
	param.rnd =  new Date().getTime();
	myLib.getJSON('process.php?' + myLib.encodeParam(param), null, successCallback);
  } 
  
  myLib.submitOverAJAX = function(form, opt) {
    var formData = new myLib.formData(form);
    //formData.append('rnd', new Date().getTime());

	opt = opt || {};
	opt.url = opt.url || form.getAttribute('action');
	opt.method = opt.method || 'POST';
	opt.data = formData.toString();
	opt.success = opt.success || function(msg){alert(msg)};
    myLib.ajax(opt);
  };
  
  myLib.processForm = function(form, successCallback) {
    myLib.submitOverAJAX(form, {
      url:form.getAttribute('action'),
	  success:function(json){
		json = JSON.parse(json);
	    if (json.success) {
		  successCallback && successCallback.call(this, json.success);
		} else 
		  alert(json.failed);
	  }});
  }

})();
