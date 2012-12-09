<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Greedy Hyena: Epsilon Greedy Strategy Evolved</title>
	<link rel='stylesheet' type='text/css' href='http://www.epsilongreedy.com/css/styles.css' />
</head>
<body>
<div id='outsideWrapper'>
  <div id='pageWrapper'>
    <div class='navContainer'>
      <a class='navButton' href='/logout'>Logout</a>
      <h1 id='mainH1' style='float:right; margin-right:10px; width:490px;' >Greedy Hyena Dashboard</h1>
    </div>
    <div id='rightSide'>
      <div class='bigYellowContainer'>
          <ul>
            <li><a href='javascript:addNewTest();'>Add New Test</a></li>
          </ul>
      </div>
    </div>
    <div id='leftSide'>
      <header id='header' class='bigYellowContainer'>
	  <?php if(isset($currentTests) && sizeof($currentTests) > 0):
	   echo "<h3>Existing Tests</h3><p>select an existing test below, or click 'Add Test' on the right.</p>";
		echo "<table class='gridTable'><tr class='gridHeader'>";
		$objVars = get_object_vars($currentTests[0]);
		foreach($objVars as $name => $value )
		{
			if($name != "id" && $name != "user_id")
				echo "<td>".$name."</td>";
		}
		
		echo "</tr><tr>";
		
		foreach($currentTests as $test)
		{
			$objVars = get_object_vars($test);
			
			foreach( $objVars as $name => $value )
				if($name != "id" && $name != "user_id")
					echo "<td><a href='javascript:loadVariants(".$test->id.");'>".$value."</a></td>";
		}
		
		echo "</tr></table>";

		else: ?>
       <h3>Hi!, Welcome to Greedy Hyena</h3><p>To get started, click 'Add New Test' on the right. <a href='javascript:moreInfo("testInfo");'>More Info...</a></p><div class='moreInfo' id='testInfo'><p>You should Each design element you'd like to test will be organized into a Test->Variant structure.</p></div>
	 <?php endif; ?>
      </header>
    </div>
  </div>
</div> <!-- end outsideWrapper -->
<div id='modalOverlay'>
  <div id='modalWindow'>
    <div class='modalClose' style='padding:5px; background:#fff; color:#ff0000; font-weight:bold;'>X</div>
    <div id='modalInterior'>
    </div>
  </div>
</div>
<?php $this->load->view('footer'); ?>
<script type='text/javascript'>
$(document).ready(function(){ 
  $(".modalClose").click(function(){ 
     $("#modalOverlay").animate({"opacity":"0"}, 500, function(){ $(this).css("display", ""); });
  });
});
function loadVariants(testID)
{
	$.post("<?=base_url();?>Dashboard/GetVariantsByTestID", { test_id : testID }, function(data){
		var testVariants = eval(data);
		var markUp ="<tr class='gridHeader'><td>Active</td><td>name</td><td>conversions</td><td>views</td><td>conversion rate</td><td>revenue</td><td>Edit Mark-Up</td></tr>";
		
		
		for(var i=0;i<testVariants.length;i++)
		{
			markUp += "<tr>";
			markUp += "<td><input type='checkbox' name='active' value='" + testVariants[i].active + "'></input></td>";
			markUp += "<td>" + testVariants[i].display_name + "</td>";
			markUp += "<td>" + testVariants[i].conversions + "</td>";
			markUp += "<td>" + testVariants[i].views + "</td>";
			if(testVariants[i].views > 0)
			{
				var pumpedNum = (parseFloat(testVariants[i].conversions / testVariants[i].views) * 100).toString();
				markUp += "<td>" + pumpedNum.substring(0, pumpedNum.indexOf('.') + 3)  + "%</td>";
			}
			else
				markUp += "<td>N/A</td>";
			markUp += "<td>$" + testVariants[i].revenue + "</td>";
			markUp += "<td><a href='javascript:editMarkUp(" + testVariants[i].id + ")'>Edit</a></td>";
			markUp += "</tr>";
		}
		
		$(".gridTable").html(markUp);
	});
}
function addNewTest()
{
  $("#modalInterior").html("<form id='addTestForm' onsubmit='addNewTestClick(); return false;' method='POST' name='addNewTestForm'><span class='inputLabel'>Test Name</span><input type='text' name='testName'></input><br/><span class='formLabel'>Confidence Interval (0-100%)</span><input type='text' name='stError'></input><br/><span class='formLabel'>How Greedy Are You? (0-100%)</span><input type='text' name='randFactor'></input><br/><input type='submit'  value='Save'></input></form>");
  $("#modalOverlay").css("display", "block").animate({"opacity":"1"}, 500);
}
function addNewTestClick()
{
  var sender = document.getElementById('addTestForm');
  
  $.post("<?=base_url()?>dashboard/addNewTest", { test : sender.elements }, function(data){
    var dataObject = eval(data);
    
    loadTests(data.user_id);
    
  });
}
function moreInfo(expandingItemId)
{
  var exItem = $("#" + expandingItemId);
  if(exItem.hasClass("expanded"))
  {
    exItem.stop().animate({"height":"0px"}, 300);
    exItem.removeClass("expanded");
  }
  else
    {
      exItem.stop().animate({"height":"150px"}, 300);
      exItem.addClass("expanded");
    }
}
function loadTests(userID)
{
$.post("<?=base_url();?>Dashboard/GetTestsByUserID", { user_id : userID }, function(data){
		var tests = eval(data);
		var markUp ="<tr class='gridHeader'><td>Active</td><td>Name</td><td>Greediness</td><td>Confidence</td><td>Edit Mark-Up</td></tr>";
		
		
		for(var i=0;i<tests.length;i++)
		{
			markUp += "<tr>";
			markUp += "<td><input type='checkbox' name='active' value='" + tests[i].active + "'></input></td>";
			markUp += "<td>" + tests[i].display_name + "</td>";
			markUp += "<td>" + tests[i].randFactor + "</td>";
			markUp += "<td>" + tests[i].stError + "</td>";
			
			markUp += "<td><a href='javascript:editTestMarkUp(" + tests[i].id + ")'>Edit</a></td>";
			markUp += "</tr>";
		}
		
		$(".gridTable").html(markUp);
	});
}
function addNewElement()
{
  $("#modalInterior").html("<form action='javascript:addNewElementClick();' method='POST' name='addNewTestForm' /><span class='inputLabel'>Element Name</span><input type='text' name='elementName'></input><span class='inputLabel'>Replace HTML or CSS Class?</span><input type='radio' value='html' name='HTMLcss[]'>HTML</input><input type='radio' name='HTMLcss[]' value='css'>CSS Class</input><input type='submit' value='Save'></input></form>");
  $("#modalOverlay").css("display", "block").animate({"opacity":"1"}, 500);
}
function addNewVariant()
{
  $("#modalInterior").html("<form action='javascript:addNewElementClick();' method='POST' name='addNewTestForm' /><span class='inputLabel'>Variant Name</span><input type='text' name='variantName'></input><span class='inputLabel'>Variant</span><input type='submit' value='Save'></input></form>");
  $("#modalOverlay").css("display", "block").animate({"opacity":"1"}, 500);
}
function addNewTestClick()
{
 $.post("<?=base_url();?>dashboard/addNewTest", {} , function(){
  });
}
</script>