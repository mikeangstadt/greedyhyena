$(document).ready(function(){

  $("[class^=gh_]").each(function(intIndex){
    var test_id = $(this).attr('class');
         test_id = test_id.split("gh_");
        test_id = trim(test_id[1].split(" ")[0]);
    
    $(this).html(getVariant(test_id));
    
  });

});

function getVariant(tID)
{
var api_key = getParameterByName("apiKey");
var user_id = getParameterByName("userID");
var test_id = tID;

$.post("/getVariant", { apiKey : api_key, userID : user_id, tID : test_id }, function(){
  return eval(data);
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
}