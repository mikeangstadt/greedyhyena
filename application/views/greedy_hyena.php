<script type="text/javascript" charset="utf-8">
//Greedy_Hyena API Libraries v1.3 - same domain inclusion (self-hosted Greedy Hyena)
//written by: Michael Angstadt
//creation date: 12/19/11
//description: replaces any Greedy Hyena testing elements
//included on the page 
jQuery(document).ready(function(){

  $("[class^=gh_]").each(function(intIndex){
    var test_id = $(this).attr("class");
         test_id = test_id.split("gh_");
        test_id = test_id[1].split(" ")[0];
    
    $(this).html(getVariant(test_id));
    
  });

});

//get a variant using the decision logic
//based on the test ID and applicable $apiKey and $userID
function getVariant(tID)
{
var api_key = <?php echo $apiKey; ?>;
var user_id = <?php echo $userID; ?>;
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
}/* ]]> */
</script>