<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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

  //figure out if we can greed epsilon conversions
  $conAverage = $this->getAverage($results, "conversions");
  $conStDev = $this->getStandardDeviation($results, "conversions");
  
  if($revAverage > 0)
  {
	  $errRevenueCoef = $testResults[0]->stError * revAverage;
	  $revSigThresh = ($revStDev / $errrRevenueCoef) * 2;
	  
	  if($this->totalVariantField($results, "conversions") >= $revSigThresh)
		echo $this->greedyHyenaSelect($results, $testResults[0]->randFactor);
  }
  else if($conAverage > 0)
	{  
	  $errConversionCoef = $testResults[0]->stError * conAverage;
	  $conSigThresh = ($conStDev / $errConversionCoef) * 2;
  
  
		if($this->totalVariantField($results, "views") >= $conSigThresh)
			echo $this->epsilonGreedySelect($results, "conversions");
  }
  else
  {
    $randIndex = mt_rand(0, sizeof($results)-1);  
	$this->variant_model->addView($results[$randIndex]);
    echo $_GET['callback']. '('. json_encode($results[$randIndex]). ')';
  }  
 }
  function totalVariantField($variants, $field)
  {
    $sum = 0;
    foreach($variants as $variant)
      $sum += $variant->$field;
      
    return $sum;
  }
  function greedyHyenaSort($a,$b)
  {
    $scoreA = ($a->conversions / $a->views) * $a->revenue;
    $scoreB = ($b->conversions / $b->views) * $b->revenue;
    
    if($scoreA == $scoreB)
      return 0;
    else 
      return $scoreA < $scoreB ? $scoreA : $scoreB;
  }
  function epsilonGreedySort($a,$b)
  {
    $scoreA = ($a->conversions / $a->views);
    $scoreB = ($b->conversions / $b->views);
    
    if($scoreA == $scoreB)
      return 0;
    else
      return $scoreA < $scoreB ? $scoreA : $scoreB;
  }
  function greedyHyenaSelect($variants, $randFactor)
  {
    usort($variants, "greedyHyenaSort");
    
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
  function epsilonGreedySelect($variants, $randFactor)
  {
    usort($variants, array($this, "epsilonGreedySort"));
    
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