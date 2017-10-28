
$(document).ready(function(){
	$(".iframe").colorbox({iframe:true, opacity:"0.7",width:"700px", height:"500px", fixed:"false"});
	$("#click").click(function(){ 
	$('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
		return false;
	});	
});