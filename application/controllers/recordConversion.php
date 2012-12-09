<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class RecordConversion extends CI_Controller
{
public function index(){
header("content-type: application/json");

  if(isset($_GET["apiKey"]))
    $apiKey = $_GET["apiKey"];
  
  if(isset($_GET["userID"]))
    $userID = $_GET["userID"];
  
  if(!isset($apiKey) || !isset($userID) || !$this->checkAPIKey($userID, $apiKey))
    echo $_GET['callback']. '('. json_encode('Error, invalid API Key or Username') . ')';
  
  // throw error if variant ID is not supplied
  if(isset($_GET["vID"]))
    $variantID = $_GET["vID"];
  else
    echo $_GET['callback']. '('. json_encode('Error, please provide a valid Variant ID') . ')';
 

  //get all the variants for potential selection
  $results = $this->variant_model->GetAllBy(array("id"=>$variantID, "active"=>1));

  $this->variant_model->addConversion($results[0]);
  
  if(isset($_GET["rev"]) && $_GET["rev"] != "undefined" && $_GET["rev"] != null)
	$this->variant_model->addRevenue($results[0], floatval($_GET["rev"])); 
	
	echo $_GET['callback']. '('. json_encode('successful') . ')';
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