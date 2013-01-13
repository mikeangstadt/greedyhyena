<?php
/* 
 * written by: Michael Angstadt
 * date: 12/12/11
 * test_model to organize groups of variants
 * one to one mapping to a end-site elements
 */
require_once 'base_model.php';

class test_model extends base_model
{
    public function test_model()
    {
        $this->table_name = "test_table";     
    }
}

//container class to fetch into 
//from database
class Test
  {
    var $id;
    var $user_id;
    var $display_name;
    var $date_created;
    var $active;
    var $stError;
    var $randFactor;

	//encapsulates a variant model call
	//to get all by this test's id
	//returns an array of all the variants associated with this test
	public function GetVariants()
	{
		return $this->variant_model->GetAllBy(array("test_id"=>$this->id));
	}
	
	//compute the average conversion rate for
	//this test
	public function AverageConversionRate()
	{
		$sum;
		$variants = $this->GetVariants();
		
		if(sizeof($variants) <= 0)
			return "N/A";
		
		foreach($variants as $variant)
		{
			$sum += $variant->ConversionRate();
		}
		
		return $sum / sizeof($variants);
	}
	
  }	
?>
