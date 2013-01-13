<?php
//Greedy_Hyena API Libraries v1.3 - for cross-domain inclusion
//written by: Michael Angstadt
//creation date: 12/19/11
//description: replaces any Greedy Hyena testing elements
//included on the page 
echo '$(document).ready(function(){

  $("[class^=gh_]").each(function(intIndex){
    var test_id = $(this).attr('class');
         test_id = test_id.split("gh_");
        test_id = test_id[1].split(" ")[0];
    
    $(this).html(getVariant(test_id));
    
  });

});

function getVariant(tID)
{
var api_key = '.$_GET["apiKey"].';
var user_id = '.$_GET["userID"]';
var test_id = tID;

 var surl =  "http://www.epsilongreedy.com/getVariant?apiKey="+api_key+"&userID="+user_id+"&tID="+test_id+"&callback=?";
    var me = $(this);
    $.ajax(surl, {
    crossDomain:true, 
    dataType: "jsonp", 
    success:function(data,text,xhqr){
      me.html(data);
    }
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