<?php  
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 * written by: Michael Angstadt
 * date: 12/12/11
 * VariantFetch is the controller that 
 * exposes the ability for users to fetch
 * a variant for display on their user-side
 * website based on the design testing logic
 */
class VariantFetch extends CI_Controller
{
public function index(){
 header("content-type: application/json");

  if(isset($_GET["apiKey"]))
    $apiKey = $_GET["apiKey"];
  
  if(isset($_GET["userID"]))
    $userID = $_GET["userID"];
  
  if(!isset($apiKey) || !isset($userID) || !$this->checkAPIKey($userID, $apiKey))
    echo $_GET['callback']. '('. json_encode('Error, invalid API Key or Username') . ')';
  
  // throw error if test ID is not supplied
  if(isset($_GET["tID"]))
    $testID = $_GET["tID"];
  else
    echo $_GET['callback']. '('. json_encode('Error, please provide a valid Test ID') . ')';
 

  //get all the variants for potential selection
  $testResults = $this->test_model->GetAllBy(array("id"=>$testID));
  $results = $this->variant_model->GetAllBy(array("test_id"=>$testID, "active"=>1));

  //first, let's see if we can use actual revenue
  //to select using greedy hyena
  $revAverage = $this->getAverage($results, "revenue");
  $revStDev = $this->getStandardDeviation($results, "revenue");

  if($revAverage > 0)
  {
	  $errRevenueCoef = $testResults[0]->stError * revAverage;
	  $revSigThresh = ($revStDev / $errrRevenueCoef) * 2;
	  
	  if($this->totalVariantField($results, "conversions") >= $revSigThresh)
		echo $this->greedyHyenaSelect($results, $testResults[0]->randFactor);
  }
  else 
  {
  
	//figure out if we can greed epsilon conversions
	// *4/3/12 moved inside else after $revAverage to optimize performance
	$conAverage = $this->getAverage($results, "conversions");
	$conStDev = $this->getStandardDeviation($results, "conversions");
 
	if($conAverage > 0)
	{  
	  $errConversionCoef = $testResults[0]->stError * conAverage;
	  $conSigThresh = ($conStDev / $errConversionCoef) * 2;
  
  
		if($this->totalVariantField($results, "views") >= $conSigThresh)
			echo $this->epsilonGreedySelect($results, "conversions");
	}
	// if we can't use revenue to select with greedy hyena
	// and we can't use conversion to decide with epsilon greedy
	// - just randomly display a variant and keep it moving
	else
	  {
		$randIndex = mt_rand(0, sizeof($results)-1);  
		$this->variant_model->addView($results[$randIndex]);
		
		//render out the ajax callback
		echo $_GET['callback']. '('. json_encode($results[$randIndex]). ')';
	  } 
  } 
 }
 
 //a helper method to sum all of the $field
 //for the $variants array passed
 //PRE: $field is a valid database property of
 //variant
  function totalVariantField($variants, $field)
  {
    $sum = 0;
    foreach($variants as $variant)
      $sum += $variant->$field;
      
    return $sum;
  }
  
  //a valid compare method for variants
  //COMPARE SCHEMA: using the greedy hyena selection heuristics
  //to compare $a and $b's greedy hyena 'values'
  function greedyHyenaSort($a,$b)
  {
    $scoreA = ($a->conversions / $a->views) * $a->revenue;
    $scoreB = ($b->conversions / $b->views) * $b->revenue;
    
    if($scoreA == $scoreB)
      return 0;
    else 
      return $scoreA < $scoreB ? $scoreA : $scoreB;
  }
  
  //a valid compare method for variants
  //COMPARE SCHEMA: using the epsilon greedy (multi-arm bandit) selection heuristics
  //to compare $a and $b's epsilon greedy 'values'
  function epsilonGreedySort($a,$b)
  {
    $scoreA = ($a->conversions / $a->views);
    $scoreB = ($b->conversions / $b->views);
    
    if($scoreA == $scoreB)
      return 0;
    else
      return $scoreA < $scoreB ? $scoreA : $scoreB;
  }
  
  //returns a JavaScript string which is the compiled
  //callback function to be rendered user-side based
  //on selecting from $variants given the random factor $randFactor
  //a single variant using the greedy hyena selection method
  function greedyHyenaSelect($variants, $randFactor)
  {
    usort($variants, "greedyHyenaSort");
    return $this->selectionMethodSelect($variants, $randFactor);
  }
  
  //returns a JavaScript string which is the compiled
  //callback function to be rendered user-side based
  //on selecting from $variants given the random factor $randFactor
  //a single variant using the epsilon greedy selection method
  function epsilonGreedySelect($variants, $randFactor)
  {
    usort($variants, array($this, "epsilonGreedySort"));
    return $this->selectionMethodSelect($variants, $randFactor);
  }
  
  //helper method added 1/04/13 to DRY up selection calls
  function selectionMethodSelect($variants, $randFactor)
  {
	$rand = mt_rand(0,100);
    
    //sometimes of the time, randomize
    if($rand >= $randFactor)
    {
      $randIndex = mt_rand(0, sizeof($randFactor));
       $this->variant_model->addView($variants[$randIndex]);
      return $_GET['callback']. '('. json_encode($variants[$randIndex]) . ')';
    }
    //otherwise - pick the best 
    else
	{
	  $this->variant_model->addView($variants[0]);	
      return $_GET['callback']. '('. json_encode($variants[0]) . ')';
	}
  }
  
  //helper method to get the average of a specific
  //field and given $variant
  //PRE: $field is a valid $variant database field
  function getAverage($variants, $field)
  {
    if(sizeof($variants) <= 0)
      return 0;
      
    $sum = 0;
    foreach($variants as $variant)
    {
      $sum += $variant->$field;
    }
    
    return $sum / sizeof($variants);
  }
  
  //helper method to get the standard deviation of a
  //specific field and given $variant
  //PRE: $field is a valid $variant database field
  function getStandardDeviation($variants, $field)
  {
    if(sizeof($variants) <= 0)
      return 0;
      
    $average = $this->getAverage($variants, $field);
    
    $sqSum = 0;
    foreach($variants as $variant)
    {
      $avgDiff = $average - $variant->$field;
      $sqSum += $avgDiff * $avgDiff;
    }
    
    return sqrt($sqSum / sizeof($variants));
  }
  
 //verify the $userID passed matches the $apiKey
 //supplied
 function checkAPIKey($userID, $apiKey)
 {
    $results = $this->user_model->GetAllBy(array('api_key'=>$apiKey));
	
    if(sizeof($results) > 0)
      return $results[0]->id == $userID;
    else
      return false;
 }
 }
?>