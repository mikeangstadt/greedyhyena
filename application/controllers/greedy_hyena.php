<?php
//header("content-type: application/x-javascript");
echo 'jQuery(document).ready(function($){

  $("[class^=gh_]").each(function(intIndex){
    var test_id = $(this).attr("class");
         test_id = test_id.split("gh_");
        test_id = test_id[1].split(" ")[0];
    
	var _trigger = $(this);
	
    $(this).html(getVariant(test_id, _trigger));
    
  });
  
 

});
function setupConversions()
{
jQuery(function($){
 $("[class^=ghc_]").each(function(intIndex){
   $(this).click(function(){
	var test_id = $(this).attr("class");
         test_id = test_id.split("ghc_");
        test_id = test_id[1].split(" ")[0];
    
	var _ctrigger = $(this);
	
	var revenueInput = $("[class^=ghcr_]");
	var rev = null;
	
	if(revenueInput.length > 0)
	{
		rev = $("[class^=ghcr_]")[0].value;
	}
	var variant = $(".gh_" + test_id);
	var vID = variant.attr("v_id");
	
	recordConversion(vID, _ctrigger, rev);
  });
  });
  });
}
function recordConversion(vID, trigger, rev)
{
jQuery(function($){
	var api_key = "'.$_GET["apiKey"].'";
	var user_id = '.$_GET["userID"].';
	var variant_id = vID;

	 var surl =  "http://www.epsilongreedy.com/RecordConversion?apiKey="+api_key+"&userID="+user_id+"&vID="+variant_id+"&rev="+rev+"&callback=?";
	 trigger.append($("<script>", { 
		src : surl, 
		type : "text/javascript"
	}));
	
});
}
function pausecomp(millis)
 {
  var date = new Date();
  var curDate = null;
  do { curDate = new Date(); }
  while(curDate-date < millis);
}

function getVariant(tID, trigger)
{
jQuery(function($){
	var api_key = "'.$_GET["apiKey"].'";
	var user_id = '.$_GET["userID"].';
	var test_id = tID;

	 var surl =  "http://www.epsilongreedy.com/FetchVariant?apiKey="+api_key+"&userID="+user_id+"&tID="+test_id+"&callback=?";
		var me = $(this);
		jQuery.ajax(surl, {
		crossDomain:true, 
		dataType: "jsonp", 
		success:function(data,text,xhqr){
		  trigger.html(data.value);
		  trigger.attr("v_id", data.id);
		  setupConversions();
		}
	});
	});
}
function getParameterByName(name)
{
  name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
  var regexS = "[\\?&]" + name + "=([^&#]*)";
  var regex = new RegExp(regexS);
  var results = regex.exec(window.location.search);
  if(results == null)
    return "";
  else
    return decodeURIComponent(results[1].replace(/\+/g, " "));
}';
?>