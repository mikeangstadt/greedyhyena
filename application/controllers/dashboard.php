<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 * written by: Michael Angstadt
 * date: 04/12/12
 * Dashboard is a frontend webpage for authenticated users
 * to view data about created tests
 */
class Dashboard extends CI_Controller {

	public function index()
	{
    $userID;
    $currentUser;
    $data;
    
    $data["pageTitle"] = "User Dashboard | Greedy Hyena";
    
    if(isset($_COOKIE['user']))
      $userID = $_COOKIE['user'];

    if(isset($userID))
    {
      $loginResults = $this->user_model->GetAllBy(array("id"=>$userID));
      $currentUser;
	  
      if(sizeof($loginResults) > 0)
      {
        $currentUser = new User();
        $currentUser = $loginResults[0];
      }
    }
    
		if(isset($currentUser))
		{
		  $data['currentUser'] = $currentUser;

		 $data['currentTests'] = $this->GetTestsByUserID($currentUser->id);
		}

		$this->load->view('dashboard', $data);
	}
	
	public function GetTestsByUserID($userID=null)
	{
      if($userID==null)
        $userID = $_POST["userID"];
        
			$this->test_model->GetAllBy(array("user_id"=>$userID));
	}
	public function AddNewTest()
	{
     if(isset($_COOKIE['user']))
     $newTest['user_id'] = $_COOKIE['user'];

    $newTest = array();
    
    if(isset($_POST['stError']))
      $newTest['stError']  = $_POST['stError'];
    else
      $newTest['stError'] = "5";
    
     if(isset($_POST['randFactor']))
      $newTest['randFactor']  = $_POST['randFactor'];
     else
      $newTest['randFactor'] = "20";
      
    if(isset($_POST['testName']))
      $newTest['display_name'] = $_POST['testName'];
      
     $newTest['active'] = true;
     
     $newTest['date_created'] = date("Y-m-d H:i:s");
     
     $this->test_model->InsertOrUpdate($newTest);
 
     echo json_encode($newTest);    
 }
	public function GetVariantsByTestID()
	{
		$testID;
		
		if(isset($_POST["test_id"]))
			$testID = $_POST["test_id"];
		else
			echo "Please use a valid test ID";
		
		echo json_encode($this->variant_model->GetAllBy(array("test_id"=>$testID)));
	}
}
/* End of file home.php */
/* Location: ./application/controllers/home.php */
?>
