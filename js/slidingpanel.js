$(document).ready(function() {
	$("div.panel_button").click(function(){
		$("div#panel").slideDown();
		
		$("div.panel_button").toggle();
		$("img#signup").toggle();
	});	
   $("div#hide_button").click(function(){
		$("div#panel").slideUp();
   });	
});